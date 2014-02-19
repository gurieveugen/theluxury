<?php

	class Email{

		function email_header($option='text'){	
					
		global $OPTION;
					
				switch($option){
				
					case 'text':
						$message_head	= $OPTION['wps_email_txt_header'];
					break;
				}
						
		return $message_head;
		}	
		
		
		function send_mail($to, $subject, $message, $admin_email_address = '', $domain = '', $cc_email = ''){
			global $OPTION;

			// Subject encoding
			$subject = encode_email_subject($subject);					

			// Headerangaben
			$now 	= time();
			$eol 	= "\r\n";
			if (!strlen($admin_email_address)) {
				$admin_email_address = $OPTION['wps_shop_email'];
			}
			if (!strlen($domain)) {
				$domain = $OPTION['wps_shop_name'];
				$domain = str_replace(",", '', $domain);
			}

			$headers  = 'MIME-Version: 1.0'.$eol;
			$headers .= 'Content-type: text/plain; charset="UTF-8"'.$eol;
			$headers .= 'Content-Transfer-Encoding: 8bit'.$eol;
			$headers .= "From: $domain <" . $admin_email_address . '>' .$eol;
			$headers .= "Reply-To: $domain <" . $admin_email_address . '>' .$eol;
			$headers .= "Return-Path: $domain <" . $admin_email_address . '>' .$eol;
			$headers .= "X-Mailer: PHP v".phpversion().$eol;
			
			$mail_success = wp_mail($to,$subject,$message,$headers);	
			if (strlen($cc_email)) {
				wp_mail($cc_email,$subject,$message,$headers);	
			}
			
			return $mail_success;
		}

		
		function html_mail($to, $subject, $message, $admin_email_address = '', $domain = '', $cc_email = ''){
			global $OPTION;

			// Subject encoding
			$subject 	= encode_email_subject($subject);					

			// Headerangaben
			$now 	= time();
			$eol 	= "\r\n";
			if (!strlen($admin_email_address)) {
				$admin_email_address = $OPTION['wps_shop_email'];
			}
			if (!strlen($domain)) {
				$domain = $OPTION['wps_shop_name'];
				$domain = str_replace(",", '', $domain);
			}
			
			// für HTML-E-Mails muss der 'Content-type'-Header gesetzt werden
			$headers  = 'MIME-Version: 1.0' . $eol;
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . $eol;
			$headers .= "From: $domain" . '<' . $admin_email_address . '>' .$eol;
			$headers .= "Reply-To: $domain <" . $admin_email_address . '>' .$eol;
			$headers .= "Return-Path: $domain <" . $admin_email_address . '>' .$eol;
			$headers .= "X-Mailer: PHP v".phpversion().$eol;
		 

			$mail_success = mail($to, $subject, $message, $headers);
			if (strlen($cc_email)) {
				mail($cc_email, $subject, $message, $headers);
			}

			return $mail_success;
		}

		
		function mime_mail($to, $subject, $message_html, $message_txt, $admin_email_address = '', $domain = '', $module = 'native', $cc_email = ''){
			global $OPTION;

			if (!strlen($admin_email_address)) {
				$admin_email_address = $OPTION['wps_shop_email'];
			}
			if (!strlen($domain)) {
				$domain = $OPTION['wps_shop_name'];
				$domain = str_replace(",", '', $domain);
			}

			if($module == 'native') {
				$subject 	= encode_email_subject($subject);	
				
				// Generate a boundary string to demarcate different types
				$mime_boundary 	= "----=_NextPart_X[".md5(time())."]";

				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "To: <".$to."> \r\n";
				$headers .= "From: $domain <$admin_email_address>\r\n";
				$headers .= "Content-Type: multipart/alternative; ".
							"boundary=\"".$mime_boundary."\"\r\n";
				// Add a multipart boundary above the plain message
				$newline = "\r\n";
				$message = "This is a multi-part message in MIME format.\r\n\r\n".
						"--".$mime_boundary."\r\n".
						"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n".
						"Content-Disposition: inline\r\n".
						"Content-Transfer-Encoding: 8bit\r\n\r\n".
						$message_txt."\r\n\r\n".
						"--".$mime_boundary."\r\n".
						"Content-Type: text/html; charset=\"iso-8859-1\"\r\n".
						"Content-Disposition: inline\r\n".
						"Content-Transfer-Encoding: 8bit\r\n\r\n".
						$message_html."\r\n\r\n".
						"--".$mime_boundary."--\r\n";
				

				/* Sends the mail */
				$mail_success = mail($to,$subject,$message,$headers);
				if (strlen($cc_email)) {
					mail($cc_email,$subject,$message,$headers);
				}
			} else if($module == 'zend') {
				if(version_compare(PHP_VERSION, '5.0.0', '>=')){			
					$zf_path = WP_CONTENT_DIR . '/themes/' . WPSHOP_THEME_NAME . '/lib/';
					
					set_include_path('.' 
						. PATH_SEPARATOR . $zf_path
						. PATH_SEPARATOR . get_include_path() 
					);

					include_once 'Zend/Mail.php';		
									
					$mail 	= new Zend_Mail('UTF-8');	
					$mail->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
					$mail->setFrom($admin_email_address,$domain);
					$mail->addTo($to);
					$mail->setSubject($subject);
					$mail->setBodyText($message_txt);
					$mail->setBodyHtml($message_html);
					$mail->send();
					if (strlen($cc_email)) {
						$mail2 	= new Zend_Mail('UTF-8');	
						$mail2->setHeaderEncoding(Zend_Mime::ENCODING_BASE64);
						$mail2->setFrom($admin_email_address,$domain);
						$mail2->addTo($cc_email);
						$mail2->setSubject($subject);
						$mail2->setBodyText($message_txt);
						$mail2->setBodyHtml($message_html);
						$mail2->send();
					}
				}
				else {
					echo "<div class='error'>".__('Attention: Confirmation-Email was not sent.','wpShop').' '. 
								__('Reason: Zend Mime module requires at least PHP version 5, you are using a PHP version lower than that!','wpShop').' '.
								__('Please contact your host to upgrade to version 5.','wpShop').
						"</div>";
				}		
			}
			return $mail_success;
		}

		
		function email_confirmation($order,$PDT_DATA,$pm=''){
			global $wpdb, $OPTION, $order_payment_methods;
			$VOUCHER = load_what_is_needed('voucher');
			$INVOICE = load_what_is_needed('invoice');

			$order_id = $OPTION['wps_order_no_prefix'].$order['oid'];
			if ($order['layaway_order'] > 0) {
				$order_id = $OPTION['wps_order_no_prefix'].$order['layaway_order'];
			}

			$to 					= $order['email'];
			$subject 				= __('Thank you for your order : Order No. [##order-id##]','wpShop');
			$subject				= str_replace('[##order-id##]', $order_id, $subject);
			$admin_email_address	= $OPTION['wps_shop_email'];
			$domain 				= utf8_decode($OPTION['wps_shop_name']); 	

			if ($PDT_DATA['pay_m'] == 'paypal_ipn') {
				$CART = show_cart($order,$PDT_DATA['custom']);
			} else {
				$CART = show_cart($order);
			}
			$img_size = $OPTION['wps_ProdRelated_img_size'];
			$des_src  = $OPTION['upload_path'].'/cache';
			$img_url  = get_option('siteurl').'/'.$des_src.'/';

			$shipping_data = array();
			if ($order['d_addr'] == 1) {
				$shipp_res = $wpdb->get_row(sprintf("SELECT * FROM %swps_delivery_addr WHERE who = '%s'", $wpdb->prefix, $order['who']));
				if ($shipp_res) {
					$shipping_data['f_name'] = $shipp_res->f_name;
					$shipping_data['l_name'] = $shipp_res->l_name;
					$shipping_data['street'] = $shipp_res->street;
					$shipping_data['state'] = $shipp_res->state;
					$shipping_data['zip'] = $shipp_res->zip;
					$shipping_data['town'] = $shipp_res->town;
					$shipping_data['country'] = $shipp_res->country;
				}
			}

			ob_start();
			include(WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/email-confirmation.php');
			$message = ob_get_contents();
			ob_end_clean();

			$message_txt = strip_tags($message);

			// admin email
			$admin_subject = "New Order received : Order No. ".$order_id;
			$admin_message = "Order No.: ".$order_id." - ".date("d.m.Y - H:i:s", ($order['order_time'] + $OPTION['wps_time_addition']))."<br><br>";
			$admin_message .= "Billing:<br>".email_billing_address($order)."<br>";
			if ($order['d_addr'] === '1') {
				$admin_message .= "Delivery:<br>".email_delivery_address($order)."<br>";
			}
			$admin_message .= email_order_items_table($order['who'])."<br>";
			$admin_message .= "Total Value: ";
			if ($order['layaway_order'] > 0) {
				$admin_message .= format_price($lorder_total * $_SESSION["currency-rate"], true)." Paid: ".format_price($lorder_paid * $_SESSION["currency-rate"], true)."<br>";
			} else {
				$admin_message .= format_price($order['amount'] * $_SESSION["currency-rate"], true)."<br>";
			}
			$admin_message .= "Invoice: ".$INVOICE->retrieve_invoice_no($order['oid'])."<br>";
			$admin_message .= "P: ".$order_payment_methods[$order['p_option']]."<br>";
			$admin_message .= "D: ".$order['d_option']."<br>";

			$admin_message_txt = str_replace('<br>', '\n', $admin_message);
			$admin_message_txt = strip_tags($admin_message_txt);

			switch(WPSHOP_EMAIL_FORMAT_OPTION){
				case 'mime':
					$this->mime_mail($to,$subject,$message,$message_txt,$admin_email_address,$domain,'zend');
					if($OPTION['wps_email_confirmation_dbl'] == 'yes') {
						$this->mime_mail($admin_email_address,$admin_subject,$admin_message,$admin_message_txt,$admin_email_address,$domain,'zend');
					}
				break;
				case 'txt':
					$this->send_mail($to,$subject,$message_txt,$admin_email_address,$domain);
					if($OPTION['wps_email_confirmation_dbl'] == 'yes') {
						$this->send_mail($admin_email_address,$admin_subject,$admin_message_txt,$admin_email_address,$domain);
					}
				break;
			}
		}

		
		function email_owner_order_notification($PDT_DATA,$search,$replace){
						
			global $OPTION;
			
							$to 		= $OPTION['wps_shop_email'];
							$subject 	= __('You received - ','wpShop') . $PDT_DATA[itemname];					
							
							$filename	= 'email-owner-order-notification.txt';
							$path		= (strlen(WPLANG)< 1 ? WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/' : WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/' . WPLANG.'-'); 
							$message	= file_get_contents($path.$filename);			

							$message_shop_owner 	= str_replace($search,$replace,$message);			
							$admin_email_address	= $OPTION['wps_shop_email'];
							$domain					= get_option('home');
							$domain 				= substr($domain, 7); 
							
							$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);	
							
		return 'DONE';
		}

		
		function email_owner_pending_payment_notification($search,$replace,$option){
							
			global $OPTION;
							
					switch($option){
					
						case 'completed':
									
							$to 		= $OPTION['wps_shop_email'];
							$subject 	= __('A Pending PayPal Payment has been Completed','wpShop');
							$filename	= 'email-owner-completed-pending-payment.txt';
							
						break;	
						
						case 'new':
						
							global $PDT_DATA;
									
							$to 		= $OPTION['wps_shop_email'];
							$subject 	= __('PENDING! You received - ','wpShop') . $PDT_DATA[itemname];
							$filename	= 'email-owner-new-pending-payment.txt';
							
						break;	
					}	
						
							$path		= (strlen(WPLANG)< 1 ? 'wp-content/themes/'. WPSHOP_THEME_NAME .'/email/' : 'wp-content/themes/'. WPSHOP_THEME_NAME .'/email/' . WPLANG.'-'); 
							$message	= file_get_contents($path.$filename);			

							$message_shop_owner 	= str_replace($search,$replace,$message);			
							$admin_email_address	= $OPTION['wps_shop_email'];
							$domain					= get_option('home');
							$domain 				= substr($domain, 7); 
							
							$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);	
									
		return 'DONE';
		}

		
		function email_owner_lkey_warning($fname,$search,$replace){
						
			global $OPTION;
						
							$to 		= $OPTION['wps_shop_email'];
							$subject 	= __('Your license keys for %FNAME% are running low','wpShop');
							$subject 	= str_replace("%FNAME%",$fname,$subject);
							
							$filename	= 'email-owner-lkey-warning.txt';
							$path		= (strlen(WPLANG)< 1 ? 'wp-content/themes/'. WPSHOP_THEME_NAME .'/email/' : 'wp-content/themes/'. WPSHOP_THEME_NAME .'/email/' . WPLANG.'-'); 
							$message	= file_get_contents($path.$filename);			

							$message_shop_owner 	= str_replace($search,$replace,$message);			
							$admin_email_address	= $OPTION['wps_shop_email'];
							$domain					= get_option('home');
							$domain 				= substr($domain, 7); 
							
							$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);	
						
		return 'DONE';
		}
	
	
		function stock_low_email($ID_item,$search,$replace){
				/*
				$to 		= $OPTION['wps_stock_warn_email'];
				$subject 	= __('Warning: amount of article-id %ID_ITEM% is running low','wpShop');
				$subject	= str_replace("%ID_ITEM%",$ID_item,$subject);			// cid2item_id($ID_item)
				$filename	= 'email-owner-low-stock-warning.txt';
							
				$path		= (strlen(WPLANG)< 1 ? 'wp-content/themes/'.WPSHOP_THEME_NAME.'/email/' : 'wp-content/themes/'.WPSHOP_THEME_NAME.'/email/' . WPLANG.'-'); 		
				$message	= file_get_contents($path.$filename);			

				$message_shop_owner 	= str_replace($search,$replace,$message);			
				$admin_email_address	= $OPTION['wps_shop_email'];
				$domain					= $OPTION['home'];
				$domain 				= substr($domain, 7); 
							
				$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);	//change.9.10
				*/	
		}

	
		function email_new_user_welcome($to,$search,$replace){
					
			global $OPTION;		
			
			$EMAIL 	= load_what_is_needed('email');		//change.9.10
					
				$subject 	= __('You have succesfully registered as a member of','wpShop').' '.$OPTION['blogname'];										   
				$filename	= 'email-new-user-welcome.txt';
				$path		= (strlen(WPLANG)< 1 ? 'email/' : 'email/' . WPLANG.'-'); 
				$message	= file_get_contents($path.$filename);			

				$message_shop_owner 	= str_replace($search,$replace,$message);			
				$admin_email_address	= $OPTION['wps_shop_email'];
				$domain					= get_option('home');
				$domain 				= substr($domain, 7); 
				
				$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);		//change.9.10
						
		return 'DONE';
		}
			
			
		function email_new_user_owner_notify($email,$search,$replace){
				
				global $OPTION;

				$EMAIL 		= load_what_is_needed('email');		//change.9.10
				
				$subject 	= __('A new member has registered on','wpShop').' '.$OPTION['blogname'];
				$filename	= 'email-new-user-owner-notification.txt';
				$path		= (strlen(WPLANG)< 1 ? 'email/' : 'email/' . WPLANG.'-'); 
				$message	= file_get_contents($path.$filename);			

				$message_shop_owner 	= str_replace($search,$replace,$message);			
				$admin_email_address	= $OPTION['wps_shop_email'];
				$to						= $admin_email_address;
				$domain					= get_option('home');
				$domain 				= substr($domain, 7); 
				
				$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);		//change.9.10
		}
			
			
		function email_password_reset($to,$search,$replace){

			global $OPTION;
					
			$EMAIL 	= load_what_is_needed('email');		//change.9.10		
					
				$subject 	= __('Your password for your','wpShop').' '.$OPTION['blogname'].' '.__('account has been reset','wpShop');											   
				$filename	= 'email-password-reset.txt';
				$path		= (strlen(WPLANG)< 1 ? WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/' : WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/email/' . WPLANG.'-');
				
				$message	= file_get_contents($path.$filename);			

				$message_shop_owner 	= str_replace($search,$replace,$message);			
				$admin_email_address	= $OPTION['wps_shop_email'];
				$domain					= get_option('home');
				$domain 				= substr($domain, 7); 
				
				$this->send_mail($to,$subject,$message_shop_owner,$admin_email_address,$domain);		//change.9.10
						
		return 'DONE';
		}
	}
?>