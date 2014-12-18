<?php
// alerts functions
add_action('init', 'alerts_init');
function alerts_init() {
	global $wpdb, $OPTION, $current_user;
	if ($_GET['notify'] == 'send') {
		echo 'Notify notifications:<br><br>';
		alerts_send_notifications();
		exit;
	}
	if (strlen($_POST['AlertsAction'])) {
		$user_email = $_POST['follow_brands_email'];
		if ($current_user->ID > 0) {
			$user_email = $current_user->user_email;
		}
		switch ($_POST['AlertsAction']) {
			case "create_alert":
				$ca_type = $_POST['ca_type'];
				$ca_value = trim($_POST['ca_value']);
				$ca_ajax = $_POST['ca_ajax'];

				$act = 'none';
				if ($ca_type == 1) { // create alert button
					$ca_category = $_POST['ca_category'];
					$ca_brand = $_POST['ca_brand'];
					$ca_colour = $_POST['ca_colour'];
					if (strlen($ca_category)) {
						$ca_value = '{ct:'.$ca_category.'}';
					}
					if (strlen($ca_brand)) {
						if (strlen($ca_value)) { $ca_value .= ';'; }
						$ca_value .= '{br:'.$ca_brand.'}';
					}
					if (strlen($ca_colour)) {
						if (strlen($ca_value)) { $ca_value .= ';'; }
						$ca_value .= '{cl:'.$ca_colour.'}';
					}
					if (strlen($ca_value)) {
						$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s AND value = '%s'", $wpdb->prefix, $current_user->ID, $ca_type, $ca_value));
						if (!$alert_id) {
							$act = 'insert';
						}
					}
				} else if ($ca_type == 2) { // it bags
					if (strlen($ca_value)) {
						$cavalues = explode(";", $ca_value);
						$ca_value = '';
						foreach($cavalues as $cavalue) {
							if (strlen($ca_value)) { $ca_value .= ';'; }
							$ca_value .= '{'.$cavalue.'}';
						}
					}
					$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s", $wpdb->prefix, $current_user->ID, $ca_type));
					if ($alert_id) {
						$act = 'update';
					} else {
						$act = 'insert';
					}
				} else if ($ca_type == 3) { // top brands
					$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_email = '%s' AND type = %s", $wpdb->prefix, $user_email, $ca_type));
					if (strlen($ca_value)) {
						$ca_value = '{'.str_replace(',', '};{', $ca_value).'}';
						if ($alert_id) {
							if ($_POST['ca_follow'] == 'true') {
								$alert_value = $wpdb->get_var(sprintf("SELECT value FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
								if (strpos($alert_value, $ca_value) !== false) {
									$ca_value = $alert_value;
								} else {
									$ca_value = $alert_value.';'.$ca_value;
								}
							}
							$act = 'update';
						} else {
							$act = 'insert';
						}
					} else {
						$act = 'delete';
					}
				} else if ($ca_type == 4) { // search term
					if (strlen($ca_value)) {
						$alert_id = $wpdb->get_var(sprintf("SELECT alert_id FROM %swps_user_alerts WHERE user_id = %s AND type = %s AND value = '%s'", $wpdb->prefix, $current_user->ID, $ca_type, $ca_value));
						if (!$alert_id) {
							$act = 'insert';
						}
					}
				}

				if ($act == 'insert') {
					$insert = array();
					$insert['user_id'] = $current_user->ID;
					$insert['user_email'] = $user_email;
					$insert['type'] = $ca_type;
					$insert['value'] = $ca_value;
					$wpdb->insert($wpdb->prefix."wps_user_alerts", $insert);
				} else if ($act == 'update' && $alert_id) {
					$update = array();
					$update['value'] = $ca_value;
					$wpdb->update($wpdb->prefix."wps_user_alerts", $update, array('alert_id' => $alert_id));
				} else if ($act == 'delete' && $alert_id) {
					$wpdb->query(sprintf("DELETE FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
				}
				if ($ca_ajax == 'true') { exit; }
			break;
			case "remove_my_searches_alert":
				$alert_id = $_POST['alert_id'];
				$wpdb->query(sprintf("DELETE FROM %swps_user_alerts WHERE alert_id = %s", $wpdb->prefix, $alert_id));
				exit;
			break;
			case "get_login_encodedurl":
				$url = $_POST['url'];
				echo get_permalink($OPTION['wps_account_login_page']).'?redirect_to='.urlencode($url);
				exit;
			break;
		}
	}
}

add_action('wp', 'alerts_notifications_cron_job');
add_action('alerts_notifications_cron', 'alerts_send_notifications');
function alerts_notifications_cron_job() {
	if (!wp_next_scheduled('alerts_notifications_cron')) {
		wp_schedule_event(mktime(6, 0, 0, date("m"), date("d"), date("Y")), 'daily', 'alerts_notifications_cron');
	}
}

function alerts_send_notifications() { // Alerts Notifications
	global $wpdb, $OPTION;

	$anotifications = array();
	$ancd = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
	$alerts_notifications_cron_date = get_option("alerts_notifications_cron_date");

	$hivista_message = 'Date: '.$ancd.'; Last Cron Date: '.$alerts_notifications_cron_date.'<br>';

	if ($alerts_notifications_cron_date != $ancd || $_GET['notify'] == 'send') {
		$subject = stripcslashes($OPTION['wps_alerts_notification_subject']);

		$wpdb->query(sprintf("DELETE FROM %swps_user_alerts_temp", $wpdb->prefix)); // clear temp alert posts table
		$added_posts = $wpdb->get_results(sprintf("SELECT p.* FROM %sposts p LEFT JOIN %spostmeta pm ON pm.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND pm.meta_key = 'alert_send' AND pm.meta_value = '1' ORDER BY ID", $wpdb->prefix, $wpdb->prefix));
		echo '- found '.count($added_posts).' new items<br>';
		$hivista_message .= 'Found: '.count($added_posts).'<br>';
		if ($added_posts) {
			$users_search_keys_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 4 ORDER BY alert_id DESC", $wpdb->prefix, $wpdb->prefix));
			$aposts = array();
			foreach($added_posts as $added_post) {
				$post_categories = array($added_post->tax_cat_1, $added_post->tax_cat_2, $added_post->tax_cat_3, $added_post->tax_cat_4, $added_post->tax_cat_5);
				$post_brand = $added_post->tax_brands;
				$post_tags = $added_post->tag;
				if ($post_tags) { $post_tags = unserialize($post_tags); }
				if (is_array($post_tags)) { $post_tags = array(); }

				// it bags (type = 2)
				if ($post_brand) {
					$users_it_bags_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 2 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand."-".$added_post->post_title."}%"));
					if ($users_it_bags_alerts) {
						foreach($users_it_bags_alerts as $users_it_bags_alert) {
							$user_email = $users_it_bags_alert->user_email;
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}

				// top brands (type = 3)
				if ($post_brand) {
					$users_top_brands_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 3 AND value LIKE '%s' ORDER BY alert_id DESC", $wpdb->prefix, "%{".$post_brand."}%"));
					if ($users_top_brands_alerts) {
						foreach($users_top_brands_alerts as $users_top_brands_alert) {
							$user_email = $users_top_brands_alert->user_email;
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}

				// search terms (type = 4)
				if ($users_search_keys_alerts) {
					foreach($users_search_keys_alerts as $users_search_keys_alert) {
						$sval = strtolower($users_search_keys_alert->value);
						$user_email = $users_search_keys_alert->user_email;
						if (strpos(strtolower($added_post->post_title), $sval) !== false || strpos(strtolower($added_post->post_content), $sval) !== false) {
							$anotifications[$user_email][$added_post->ID] = $added_post;
						}
					}
				}
				$insert = array(
						'br' => $added_post->tax_brands,
						'cl' => $added_post->tax_colours,
						'pr' => $added_post->tax_prices,
						'sl' => $added_post->tax_selections,
						'sz' => $added_post->tax_sizes,
						'rs' => $added_post->tax_ring_sizes,
						'cs' => $added_post->tax_clothes_sizes,
						'tg' => $post_tags[0],
						'post_id' => $added_post->ID,
						'post' => serialize($added_post)
					);
				if ($post_categories) {
					$cnmb = 1;
					foreach($post_categories as $pcategory) {
						if ($pcategory) {
							$insert['ct'.$cnmb] = $pcategory;
						}
						$cnmb++;
					}
				}
				$wpdb->insert($wpdb->prefix.'wps_user_alerts_temp', $insert);
				delete_post_meta($added_post->ID, 'alert_send');
			}
			// created requests (type = 1)
			$users_search_filter_alerts = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts WHERE type = 1 ORDER BY alert_id DESC", $wpdb->prefix));
			if ($users_search_filter_alerts) {
				foreach ($users_search_filter_alerts as $users_search_filter_alert) {
					$user_email = $users_search_filter_alert->user_email;
					$values = explode(';', $users_search_filter_alert->value);
					$where  = alerts_get_notification_where($values);
					if (strlen($where)) {
						$tdatas = $wpdb->get_results(sprintf("SELECT * FROM %swps_user_alerts_temp WHERE %s", $wpdb->prefix, $where));
						if ($tdatas) {
							foreach($tdatas as $tdata) {
								$anotifications[$user_email][$tdata->post_id] = unserialize($tdata->post);
							}
						}
					}
				}
			}
			$wpdb->query(sprintf("DELETE FROM %swps_user_alerts_temp", $wpdb->prefix)); // clear temp alert posts table

			$hivista_message .= 'Notifications count: '.count($anotifications).'<br>';
			$notifications_html = alerts_notifications_html();
			// send alert emails
			if (count($anotifications)) {
				foreach($anotifications as $user_email => $post_items) {
					$hivista_message .= 'Sent to: '.$user_email.' - posts: '.count($post_items).'<br>';
					echo '- sent '.count($post_items).' new items to user: '.$user_email.'<br>';

					$items_list = alerts_notifications_get_items_list($post_items);
					$body = str_replace('{USER_NAME}', $user_email, $notifications_html);
					$body = str_replace('{ITEMS_LIST}', $items_list, $body);
					NWS_send_email($user_email, $subject, $body);
				}
			}
		}
		update_option("alerts_notifications_cron_date", $ancd);
	}
}

function alerts_get_notification_where($params) {
	$where = "";
	$grouped = array();
	foreach($params as $param) {
		$param = str_replace(array('{','}'), '', $param);
		$flks = explode(':', $param);
		$grouped[$flks[0]][] = $flks[1];
	}
	foreach($grouped as $gk => $gvals) {
		if (strlen($where)) { $where .= " AND "; }
		if ($gk == 'ct') {
			$where .= "(";
			for($c=1; $c<=5; $c++) {
				$where .= $or."ct".$c." IN (".implode(',', $gvals).")";
				$or = " OR ";
			}
			$where .= ")";
		} else {
			$where .= $gk." IN (".implode(',', $gvals).")";
		}
	}
	return $where;
}

function alerts_notifications_html() {
	global $OPTION, $wpdb;
	ob_start();
?>
	<table align="center" width="700" style="font-family:Arial,Tahoma,Verdana;font-size:14px;" border="0">
	  <tr>
		<td align="center"><a href="<?php bloginfo('url'); ?>/?utm_source=The_Luxury_Closet&utm_medium=email&utm_campaign=notify_me" title="<?php bloginfo('name'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" border="0"></a></td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center"><strong>Hi {USER_NAME},</strong></td>
	  </tr>
	  <tr>
		<td align="center">New items matching your personalised alerts<br>To manage your alerts go to <a href="<?php echo get_permalink($OPTION['wps_account_my_alerts_page']); ?>/?utm_source=The_Luxury_Closet&utm_medium=email&utm_campaign=notify_me">My Notifications</a>.</td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center">
			<table cellpadding="0" cellspacing="10" style="font-family:Arial,Tahoma,Verdana;font-size:13px;" border="0">
			  <tr>
				{ITEMS_LIST}
			  </tr>
			</table>
		</td>
	  </tr>
	  <tr>
		<td align="center"><hr></td>
	  </tr>
	  <tr>
		<td align="center">&copy; <?php echo date('Y'); ?>. <a href="<?php bloginfo('url'); ?>/?utm_source=The_Luxury_Closet&utm_medium=email&utm_campaign=notify_me" style="text-decoration:none;color:#000;"><?php bloginfo('name'); ?></a> | <?php _e('All Rights Reserved','wpShop'); ?></td>
	  </tr>
	</table>
<?php
	$notifications_html = ob_get_contents();
	ob_end_clean();
	return $notifications_html;
}

function alerts_notifications_get_items_list($post_items) {
	global $OPTION, $wpdb;
	$total_items = count($post_items);
	$items_list = '';
	$tr_nmb = 1;
	foreach($post_items as $pid => $postdata) { $total_items--;
		$permalink = get_permalink($pid);
		$post_thumb = $postdata->thumbnail;
		$price = $postdata->price;
		$new_price = $postdata->new_price;
		if ($new_price > 0) { $price = $new_price; }
		$items_list .= '<td align="center" valign="top">';
		if ($post_thumb) {
		  $items_list .= '<div style="width:156px;height:160px;"><a href="'.$permalink.'/?utm_source=The_Luxury_Closet&utm_medium=email&utm_campaign=notify_me" title="'.$postdata->post_title.'"><img src="'.$post_thumb.'" alt="'.$postdata->post_title.'" border="0" width="156"></a></div>';
		}
		$items_list .= '<a href="'.$permalink.'" style="text-decoration:none;color:#000;"><strong>'.$postdata->post_title.'</strong><br>$'.format_price($price).'</a>';
		$items_list .= '</td>';
		if ($tr_nmb == 4 && $total_items > 0) {
		  $tr_nmb = 0;
		  $items_list .= '</tr><tr>';
		}
		$tr_nmb++;
	}
	return $items_list;
}
?>