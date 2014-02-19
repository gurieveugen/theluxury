<?php
	define('FPDF_FONTPATH','font/');
	require(WP_CONTENT_DIR . '/themes/' . WPSHOP_THEME_NAME . '/lib/fpdf16/fpdf.php');
	require(WP_CONTENT_DIR . '/themes/' . WPSHOP_THEME_NAME . '/lib/engine/class.pdf.php');			

	class Invoice {

		function make_pdf($order,$option='bill',$test=0){

			global $OPTION, $order_payment_methods;									
			$VOUCHER = load_what_is_needed('voucher');		

			if (is_array($order)) {
				foreach($order as $key => $val){
					$order[$key] = stripslashes($val);
				}
			}

			if($option == 'bill'){
		
				//Instanciation of inherited class
				$format = $OPTION['wps_pdf_invoiceFormat'];
				$pdf 	= new PDF('P','mm',$format);
				// set the margins
				$leftMargin 	= $OPTION['wps_pdf_leftMargin']; 
				$rightMargin 	= $OPTION['wps_pdf_rightMargin']; 
				$topMargin 		= $OPTION['wps_pdf_topMargin'];
				
				$pdf->SetLeftMargin($leftMargin);
				$pdf->SetRightMargin($rightMargin);
				$pdf->SetTopMargin($topMargin);
				
				// set the widths of columns 	
				$w1 = $OPTION['wps_pdf_colWidth1']; //20;
				$w2 = $OPTION['wps_pdf_colWidth2']; //115 
				$w3 = $OPTION['wps_pdf_colWidth3']; //15 	
				$w4 = $OPTION['wps_pdf_colWidth4']; //20 	
				$w5 = $OPTION['wps_pdf_colWidth5']; //20 	

				$h2	= 3;

				$pdf->AddPage();
				$pdf->Ln(40);
				$pdf->SetFont('Arial','',10);
					
				// if different delivery address and enable - we display it
				if($order['d_addr'] == '1' && $OPTION['wps_PDF_delAddr_enable']){				
					address_format($order,'pdf_delivery_address',$pdf);		
				}
				else {
					address_format($order,'pdf_cust_address',$pdf);		
				}

				$pdf->SetFont('Arial','',10);
				
				$date_order = date("F d, Y", $order['order_time'] + $OPTION['wps_time_addition']); 
				$pdf->Cell(0,6,$date_order,0,1,'R');
				
				$invoice_no = $this->produce_invoice_no($order['oid']);
				
				$pdf->Ln(5);
				
				if($OPTION['wps_PDF_invoiceNum_enable'] == 1){
					$pdf->SetFont('Arial','B',10);
					$label 		= $OPTION['wps_PDF_invoiceLabel'];
					$bill_no 	= pdf_encode( $label.' '.$OPTION['wps_invoice_no_prefix'].$invoice_no);
					$align 		= $OPTION['wps_PDF_invoiceLabel_align'];
					$pdf->Cell(0,6,$bill_no,0,1,"$align");	
				}
				
				if($OPTION['wps_PDF_orderNum_enable'] == 1){
					$oid = $order[oid];
					if ($order['layaway_order'] > 0) {
						$oid = $order['layaway_order'];
					}
					$pdf->SetFont('Arial','B',10);
					$label 		= $OPTION['wps_PDF_orderLabel'];
					$bill_no 	= pdf_encode( $label.' '.$OPTION['wps_order_no_prefix'].$oid);
					$align 		= $OPTION['wps_PDF_orderLabel_align'];
					$pdf->Cell(0,6,$bill_no,0,1,"$align");	
				}
				
				if($OPTION['wps_PDF_trackID_enable'] == 1){
					$pdf->SetFont('Arial','B',10);
					$label 		= $OPTION['wps_PDF_trackLabel'];
					$bill_no 	= pdf_encode( $label.' '.$order[tracking_id]);
					$align 		= $OPTION['wps_PDF_trackLabel_align'];
					$pdf->Cell(0,6,$bill_no,0,1,"$align");
				}

				if($OPTION['wps_PDF_payment_method_enable'] == 1){
					$pdf->SetFont('Arial','B',10);
					$label 		= $OPTION['wps_PDF_payment_method_label'];
					$bill_no 	= pdf_encode( $label.' '.$order_payment_methods[$order[p_option]]);
					$align 		= $OPTION['wps_PDF_payment_method_label_align'];
					$pdf->Cell(0,6,$bill_no,0,1,"$align");	
				}

				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell($w1,6,pdf_encode( __('Item-No:','wpShop')),1,0);
				$pdf->Cell($w2,6,pdf_encode( __('Item','wpShop')),1,0);
				$pdf->Cell($w3,6,pdf_encode( __('Qty','wpShop')),1,0);
				$pdf->Cell($w4,6,pdf_encode( __('Item Price','wpShop')),1,0);
				$pdf->Cell($w5,6,pdf_encode( __('Item Total','wpShop')),1,1);	
				$pdf->SetFont('Arial','',9);
				
				// get the cart content again 
				$CART = show_cart($order);
						
				foreach($CART[content] as $v){
					$details = explode("|",$v);
					if ($details[1] > 0) {
						$pdf->SetFont('Arial','B',9);
						
						if(WPLANG == 'de_DE'){$details[2] = utf8_decode($details[2]);}
						$details[3] = '$'.format_price($details[3]);
						$details[4] = '$'.format_price($details[4]);
						
						$h1 = 6;
						$itit = pdf_encode($details[2]);
						$itwd = $pdf->GetStringWidth($itit);
						if ($itwd > $w2) { $h1 = 7; }

						$pdf->Cell($w1,$h1,pdf_encode("$details[5]"),'LTR',0); // Item-no
						if ($itwd > $w2) {
							$cx = $pdf->GetX();
							$cy = $pdf->GetY();
							$pdf->MultiCell($w2,4,$itit,'LTR',''); // Item
							$pdf->SetXY($cx + $w2, $cy);
						} else {
							$pdf->Cell($w2,$h1,pdf_encode("$details[2]"),'LTR',0); // Item
						}
						$pdf->Cell($w3,$h1,pdf_encode("$details[1]"),'LTR',0); // Quantity
						$pdf->Cell($w4,$h1,pdf_encode("$details[3]"),'LTR',0); // Item Price
						$pdf->Cell($w5,$h1,pdf_encode("$details[4]"),'LTR',1); // Item Total 
						
						// any attributes?
						$attributes = display_attributes($details[7],'pdf');
						if($attributes != 'none'){
							
							$pdf->SetFont('Arial','',7);
							
							foreach($attributes as $v){
							
								#if(WPLANG == 'de_DE'){$v = utf8_decode($v);}
								pdf_encode($v);
							
								$pdf->Cell($w1,$h2,"",'LR',0); // Item-no
								$pdf->Cell($w2,$h2," $v",'LR',0); // Item
								$pdf->Cell($w3,$h2,"",'LR',0); // Quantity
								$pdf->Cell($w4,$h2,"",'LR',0); // Item Price
								$pdf->Cell($w5,$h2,"",'LR',1); // Item Total 				
							}
						}
						
						// any personalizations?
						$personalize =  retrieve_personalization($details[0],'pdf'); // Personalization 
						
						if($personalize !== 'none'){
							$pdf->SetFont('Arial','',7);	
							
							foreach($personalize as $personal){

								pdf_encode($personal);
							
								$pdf->Cell($w1,$h2,"",'LR',0); // Item-no
								$pdf->Cell($w2,$h2," $personal",'LR',0); // Item
								$pdf->Cell($w3,$h2,"",'LR',0); // Quantity
								$pdf->Cell($w4,$h2,"",'LR',0); // Item Price
								$pdf->Cell($w5,$h2,"",'LR',1); // Item Total 				
							}	
						}
						
						// ending line of article row 
						$pdf->Cell($w1,1,"",'LBR',0); // Item-No
						$pdf->Cell($w2,1,"",'LBR',0); // Item
						$pdf->Cell($w3,1,"",'LBR',0); // Quantity
						$pdf->Cell($w4,1,"",'LBR',0); // Item Price
						$pdf->Cell($w5,1,"",'LBR',1); // Item Total 		
					}
				}
				$pdf->SetFont('Arial','',9);
				
				// cart net sum
				$sum_net	= '$'.format_price($CART['total_price']);
				$netsum_str	= __('Subtotal:','wpShop');
				$netsum_str .= $sum_net; 
				$pdf->Cell(0,6,pdf_encode($netsum_str),0,1,'R');
					
				// voucher 
				if(($order[voucher] != 'non')&&($VOUCHER->code_exist($order['voucher']))){
				
					$TOTAL_AM 	= $CART[total_price];
					$vdata 		= $VOUCHER->subtract_voucher($TOTAL_AM,$order);
					
					$voucher_str	= pdf_encode( __('- Voucher:','wpShop'));
					$voucher_str .= '$'.format_price($vdata['subtr_am']); 
					$pdf->Cell(0,6,"$voucher_str",0,1,'R');
				}

				//tax
				$tax_data = tax_invoice_pdf_addition($CART,$order);

				//taxes without shipping costs
				if(($tax_data['display'] == 'yes')&&($tax_data['without_ship'] == 'yes')&&($tax_data['tax_info'] != 'no_show')){
					$pdf->Cell(0,6,$tax_data['tax_info'],0,1,'R');
				}
				
				// shipping fee
				$shipf_str	= pdf_encode( __('Shipping Fee:','wpShop'));
				$shipf_str .= '$'.format_price($order['shipping_fee']); 
				$pdf->Cell(0,6,"$shipf_str",0,1,'R');
					
				//taxes with shipping costs
				if(($tax_data['display'] == 'yes')&&($tax_data['with_ship'] == 'yes')&&($tax_data['tax_info'] != 'no_show')){
					$pdf->Cell(0,6,$tax_data['tax_info'],0,1,'R');
				}
				
				//$TOTAL_AM 	= ($CART[total_price] - $vdata[subtr_am]) + $order['shipping_fee'] + $tax_data['amount'];
				$TOTAL_AM 	= $order['amount'];

				if ($order['layaway_order'] > 0) {
					$oamounts = layaway_get_process_amounts($order['layaway_order']);
					$pdf->SetFont('Arial','B',9);
					$shipf_str	= pdf_encode( __('Total Amount:','wpShop'));
					$shipf_str .= '$'.format_price($oamounts['total']); 
					$pdf->Cell(00,6,$shipf_str,0,1,'R');

					$shipf_str	= pdf_encode( __('Paid Amount:','wpShop'));
					$shipf_str .= '$'.format_price($TOTAL_AM); 
					$pdf->Cell(00,6,$shipf_str,0,1,'R');

					$shipf_str	= pdf_encode( __('Balance Amount:','wpShop'));
					$shipf_str .= '$'.format_price($oamounts['balance']); 
					$pdf->Cell(00,6,$shipf_str,0,1,'R');
				} else {
					$pdf->SetFont('Arial','B',9);
					$shipf_str	= pdf_encode( __('Order Total:','wpShop'));
					$shipf_str .= '$'.format_price($TOTAL_AM); 
					$pdf->Cell(00,6,$shipf_str,0,1,'R');
				}
			}

			$parts			= explode("-",$order[who]);
			$invoice_name	= $parts[0];
			$file_name 		= $OPTION[wps_invoice_prefix].'_' . $invoice_name . '.pdf'; 
			
			$pdf->SetDisplayMode(100);
			$output_path 		= WP_CONTENT_DIR . '/themes/'. WPSHOP_THEME_NAME . '/pdf/bills/'. $file_name;
			$stamp				= time();																				
			$output_path_test	= WP_CONTENT_DIR . '/themes/'. WPSHOP_THEME_NAME . '/pdf/tests/test_'.$stamp.'.pdf';
			
			if($test == 0){
				$pdf->Output($output_path,'F');
			}
			else{
				$pdf->Output($output_path_test,'F');
				$path = get_option('siteurl').'/wp-content/themes/'. WPSHOP_THEME_NAME . '/pdf/tests/test_'.$stamp.'.pdf';
				return $path;
			}
		}

		
		function make_html($order,$option='bill'){
		 
			global $OPTION;                                  
			$VOUCHER = load_what_is_needed('voucher');       
		 
			if($option == 'bill'){
		 
			// Language variables are set here
			$invoice_no        = __('Invoice-No:','wpShop').' '.$OPTION['wps_invoice_no_prefix'] . $this->produce_invoice_no($order['oid']);  
			$order_no        = __('Order-No:','wpShop').' '.$OPTION['wps_order_no_prefix'].$order[oid];  
			$tracking_id    = __('Tracking-ID:','wpShop').' '.$order[tracking_id];  
			$article_no      = __('Item-No:','wpShop');
			$article_name      = __('Item','wpShop');
			$amount           = __('Quantity','wpShop');
			$unit_price      = __('Item Price','wpShop');
			$total          = __('Item Total','wpShop');
			$subtotal          = __('Subtotal:','wpShop');
			$ship_fee          = __('Shipping Fee:','wpShop'); 
			$minus_voucher     = __('- Voucher:','wpShop');
			$incl           = __('incl.','wpShop');
		 
			// logo path
			$url        = get_bloginfo('template_directory');
			$logopath      = $url .'/images/logo/'. $OPTION['wps_pdf_logo'];
		 
			// date
			$date         = NWS_translate_date();
		 
			// sums + currency
			$sum_net     = $order[amount] - $order[shipping_fee];
			$sum_net     = sprintf("%01.2f",$sum_net);
			$vat_abbr    = $OPTION['wps_tax_abbr'];
			$vat_perc     = $OPTION['wps_tax_percentage'];
		 
		 
			// shop's address data
			$shopname          = $OPTION['wps_shop_name'];
			$shopstreet     = $OPTION['wps_shop_street'];
			$shoptown          = $OPTION['wps_shop_town'].', '.$OPTION['wps_shop_province'].' '.$OPTION['wps_shop_zip'];
			$shopcountry    = $OPTION['wps_shop_country'];
		 

			// get the cart content again
			$cart_content      = NULL; 
			$CART            = show_cart($order);
		   
			foreach($CART[content] as $v){
		   
				$details = explode("|",$v);
		  
				// any attributes?
				// ...maybe some <br/> needed
				$attributes     = display_attributes($details[7],'html');
				if($attributes != 'none'){
					$attr = "<br/>".$attributes;                               
				}
				else {NULL;}
				// Personalization                                                
				$personalize     =  retrieve_personalization($details[0]);       
				if(strlen($personalize) > 0){
					$personalize = "<br/>".$personalize;
				}
				else {NULL;}
			 
					$cart_content .= "<tr>";  
					$cart_content .= "<td>$details[5]</td>";
					$cart_content .= "<td>$details[2] $attr</td>";
					$cart_content .= "<td>$details[1]</td>";
					$cart_content .= "<td>$".format_price($details[3])."</td>";
					$cart_content .= "<td>$".format_price($details[4])."</td>";
					$cart_content .= "</tr>";
			  
			} 
		 
			// voucher?
			if(($order[voucher] != 'non')&&($VOUCHER->code_exist($order[voucher]))){
		 
				$cart_am          = $CART[total_price];
				$vdata            = $VOUCHER->subtract_voucher($cart_am,$order);
				$voucher_row     = "<tr><td colspan='4' align='right'>$minus_voucher</td><td>$".format_price($vdata['subtr_am'])."</td></tr>";
			}
			else {
				$voucher_row  = NULL;
			}
		 
		 
			// the html
			$stringData ="
				<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
				<head>
					<meta http-equiv='content-type' content='text/html; charset=UTF-8' />
					<style type='text/css'>
						html,body{
							font-size:100.01%;
							font-family: verdana,arial,sans-serif;
						}
						body {
							width:205mm;
						}
						table {
							width:205mm;
						}
					 
						#address{
							margin-top: 40px;
						}
					 
						#reno{
							margin-top: 55px;
						}
				 
					</style>
				</head>
				<body>
					<div id='header'>
						<table>
							<tr> 
								<td valign='top'><img src='$logopath' border='0'/></td>
								<td valign='top'>
									<h4>$shopname</h4>
									<h4>$shopstreet</h4>
									<h4>$shoptown</h4>
									<h4>$shopcountry</h4>
								</td
							</tr>
						</table>
					</div>
					<div id='address'>
						$order[f_name] $order[l_name]<br/>
						$order[street]<br/>
						$order[town], $order[state] $order[zip]<br/>
						$order[country]
					</div>
					<div id='reno'>";
		 
				if($OPTION['wps_PDF_invoiceNum_enable'] == 1){
					$stringData .= "<b>$invoice_no</b><br/>";
				} 
				if($OPTION['wps_PDF_invoiceNum_enable'] == 1){
					$stringData .= "$order_no<br/>";
				} 
				if($OPTION['wps_PDF_invoiceNum_enable'] == 1){
					$stringData .= "$tracking_id";
				}
		   
				//$TOTAL_AM     = ($CART['total_price'] - $vdata['subtr_am']) + $order['shipping_fee'] + $tax_data['amount'];
				$TOTAL_AM 	= $order['amount'];

				$stringData .= "
						</div>
						<div align='right' id='date'>$date</div>
						<br/><br/>
						<table border='1' spacing='0'>
							<tr>
								<th>$article_no</th><th>$amount</th><th>$article_name</th><th>$unit_price</th><th>$total</th>
							</tr>
							$cart_content
							<tr><td colspan='4' align='right'><b>{$subtotal}:</b></td><td>$".format_price($CART['total_price'])."</td></tr>   
							$voucher_row
							<tr><td colspan='4' align='right'>$ship_fee</td><td>$".format_price($order['shipping_fee'])."</td></tr>
							<tr><td colspan='4' align='right'><b>{$total}:</b><br/>$incl {$vat_perc}% $vat_abbr</td><td>$".format_price($TOTAL_AM)."</td></tr>                
						</table>
						<br/><br/>
					</body>
				</html>
				";
				// the html file is produced
		 
				$parts            = explode("-",$order[who]);
				$invoice_name    = $parts[0];
		   
				$myFile      = WP_CONTENT_DIR.'/themes/'. WPSHOP_THEME_NAME .'/pdf/bills/' . $OPTION[wps_invoice_prefix].'_' . $invoice_name . '.html';
				$fh           = fopen($myFile, 'w');
				if($fh == FALSE){echo error_explanation('0006','yes');}
				fwrite($fh, $stringData);
				fclose($fh);		 
			}
		}
	
	
		function produce_invoice_no($oid){
			global $wpdb;
			$table 	= is_dbtable_there('invoices');
			// check oid
			$no = $wpdb->get_var(sprintf("SELECT no FROM %s WHERE oid = %s", $table, $oid));
			if (!$no) {
				$no = $wpdb->get_var(sprintf("SELECT MAX(no) FROM %s", $table));
				$no++;
				
				// create new invoice
				$new_invoice = array('oid' => $oid, 'no' => $no);
				$wpdb->insert($table, $new_invoice);
			}

			return $no;
		}

		
		function retrieve_invoice_no($oid){

			global $OPTION;

			$table	= is_dbtable_there('invoices');
			$qStr 	= "SELECT no FROM $table WHERE oid = $oid LIMIT 0,1";
			$res	= mysql_query($qStr);

			if(mysql_num_rows($res) < 1){
				$invoice_no	= __('bill','wpShop');
			} else {
				$row		= mysql_fetch_assoc($res);
				$invoice_no	= $OPTION['wps_invoice_no_prefix'].$row['no'];
			}
			

		return $invoice_no;
		}
	
	
		function retrieve_invoice_pdf(){

			global $OPTION;

			if(!isset($_GET['invoice']) && ($_GET['display_invoice'] != '1')){exit("A mistake has occurred.");}

			$invoice 	= mysql_real_escape_string(trim($_GET['invoice']));
			$invoice 	= NWS_decode($invoice);
			$parts 		= explode("_",$invoice);
			$a 			= count($parts)-1;				
			$parts		= explode(".",$parts[$a]);	
			$ending		= '.' . $parts[1];
			$track_id 	= $parts[0];

			// does this exist?
			// if not, we tell user
			$table 	= is_dbtable_there('orders');
			$qStr 	= "SELECT * FROM $table WHERE tracking_id = '$track_id' LIMIT 0,1";
			echo $qStr;
			$res 	= mysql_query($qStr);
			if($res !== FALSE){
				$row 	= mysql_fetch_assoc($res);
				$num	= mysql_num_rows($res);
			}
			else {
				$num = 0;
			}


			if($num < 1){
				echo 'This is not a valid invoice link.';
				mail($OPTION['wps_shop_email'],'Invoice link error','Somebody tried to call an invoice with an invalid link.');
				exit();
			}

			// Create path based on WP_CONTENT_DIR
			$path	= WP_CONTENT_DIR . '/themes/'.WPSHOP_THEME_NAME.'/pdf/bills/';
			$file 	= $path.$OPTION[wps_invoice_prefix].'_'.$track_id.$ending;


			// everything ok - we get the file 
			if(file_exists($file)){
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename='.basename($file));
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($file));
				ob_clean();
				flush();
				readfile($file);
				exit();
			}
		}	
	}
?>