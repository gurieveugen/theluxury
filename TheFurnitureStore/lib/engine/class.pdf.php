<?php
	//this class is only called by file class.invoice.php at line 17

	class PDF extends FPDF {
	
		/*
			Colours can be expressed in RGB components or gray scale
			Dimmensions are set in mm
		*/
		
		//Page header
		function Header(){				
			
			global $OPTION;
			
			// the logo 
			$path 	= WP_CONTENT_DIR.'/themes/' . WPSHOP_THEME_NAME . '/images/logo/'.$OPTION['wps_pdf_logo'];			
			
		
			clearstatcache();
			if(file_exists($path) === TRUE){
				$w 	= $OPTION['wps_pdf_logoWidth'];
				$this->Image($path,NULL,NULL,$w); 
			}
			else{
				$this->SetFont('Arial','B',8);
				$this->SetTextColor(225,0,0);
				$this->Cell(75,10,__('Double check your logo image file name under your Shop > PDF settings!','wpShop'),1,0);
			}
											
			$this->SetXY(90,7);	
			
			//print the shop's address 
			if ($OPTION['wps_pdf_header_addr_disable'] == FALSE){
				//the shop's address as saved in the theme settings
				$ad 			= array();
				$ad['f_name']	= stripslashes($OPTION['wps_shop_name']);	
				if ($OPTION['wps_pdf_shop_name_only'] == FALSE) {
					$ad[street]	= $OPTION['wps_shop_street'];
					$ad[zip]	= $OPTION['wps_shop_zip'];
					$ad[town]	= $OPTION['wps_shop_town'];
					$ad[state]	= $OPTION['wps_shop_province'];
					$ad[country]= get_countries(2,$OPTION['wps_shop_country']);
					
					//create shop's address
					$biz_ad = address_format($ad,'bill_header');	
					$biz 	= str_replace("<br/>","\n",$biz_ad);
					$biz	= pdf_encode($biz);
				}
				
				// set font size
				$font_size = $OPTION['wps_pdf_header_fontSize'];
				$this->SetFont('Arial','B',$font_size);
			
				//get the text colour
				$txt_colour = $OPTION['wps_pdf_header_txtColour'];
				$this->SetTextColor($txt_colour);
				
				//do we want a border?
				if ($OPTION['wps_pdf_header_addrBorder']){$border = 1;} else {$border = 0;}
				
				// get the width - default is 0.2mm
				$borderWidth 	= $OPTION['wps_pdf_header_addrBorderWidth'];
				$this->SetLineWidth($borderWidth);
				
				// do we want a background colour?
				if ($OPTION['wps_pdf_header_bgdColour_enable']) {
					//get the bgd colour 
					$bgd_colour = $OPTION['wps_pdf_header_bgdColour'];
					$this->SetFillColor($bgd_colour);
					if ($OPTION['wps_pdf_shop_name_only'] == FALSE) {
						$this->MultiCell(0,7,"$biz",$border,'L',true);//width, height, string to print, no border, align left, fill true
					} else {
						$this->Cell(0,7,"$ad[f_name]",$border,2,'L',true); //for just the Shop's name
					}
					// custom header text
					if ($OPTION['wps_pdf_header_custom_text'] !='') {
						$font_size2 = $font_size - 2;
						$this->SetFont('Arial','',$font_size2);
						$this->Cell(0,7,$OPTION['wps_pdf_header_custom_text'],$border,'L',true);
					}
				} else {
					if ($OPTION['wps_pdf_shop_name_only'] == FALSE) {
						$this->MultiCell(0,7,"$biz",$border,'L'); //width, height, string to print, no border, align left, fill false
					} else {
						$this->Cell(0,7,"$ad[f_name]",$border,2,'L'); //for just the Shop's name
					}
					// custom header text
					if ($OPTION['wps_pdf_header_custom_text'] !='') {
						$font_size2 = $font_size - 2;
						$this->SetFont('Arial','',$font_size2);
						$this->Cell(0,7,$OPTION['wps_pdf_header_custom_text'],$border,'L');
					}
				}
			}
			
			// for the happy event that customers order so much that additional pages are necessary...
			$pageNo	= $this->PageNo();
	
			if($pageNo > 1){

				$this->Ln(25);
				
				$w1 = $OPTION['wps_pdf_colWidth1']; //20;
				$w2 = $OPTION['wps_pdf_colWidth2']; //115 
				$w3 = $OPTION['wps_pdf_colWidth3']; //15 	
				$w4 = $OPTION['wps_pdf_colWidth4']; //20 	
				$w5 = $OPTION['wps_pdf_colWidth5']; //20 	
				$h2	= 3;
				
				$continuation = __('- invoice page:','wpShop') . ' ' . $pageNo . ' -';
				$this->SetFont('Arial','',8);
				$this->Cell(0,6,pdf_encode($continuation),0,1,'R');
				$this->Ln(3);
				
				$this->SetFont('Arial','B',9);
				$this->Cell($w1,6,pdf_encode( __('Item-No:','wpShop')),1,0);
				$this->Cell($w2,6,pdf_encode( __('Item','wpShop')),1,0);
				$this->Cell($w3,6,pdf_encode( __('Qty','wpShop')),1,0);
				$this->Cell($w4,6,pdf_encode( __('Item Price','wpShop')),1,0);
				$this->Cell($w5,6,pdf_encode( __('Item Total','wpShop')),1,1);	
				$this->SetFont('Arial','',9);	
			}
		}

		
		//Page footer
		function Footer()
		{
			global $OPTION;
		
			//Position at xy mm from bottom
			$this->SetY(-20);
			
			// set font size
			$font_size = $OPTION['wps_pdf_footer_fontSize'];
			$this->SetFont('Arial','',$font_size);
			
			if(strlen($OPTION['wps_vat_id']) > 1)
			{
				$vat_id = ' - ' . $OPTION['wps_vat_id_label'].': '.$OPTION['wps_vat_id'];
			}
			else{$vat_id = NULL;}
			
			// the footer text
			//custom text?
			if ($OPTION['wps_pdf_footer_custom_text'] !='') {
				$footer_text = utf8_decode($OPTION['wps_pdf_footer_custom_text']);	
			} else {
				$footer_text = utf8_decode($OPTION['wps_shop_name'] . $vat_id);		
			}
			
			//get the text colour
			$txt_colour = $OPTION['wps_pdf_footer_txtColour'];
			$this->SetTextColor($txt_colour);
			
			//do we want a border?
			if ($OPTION['wps_pdf_footer_Border']){$border = 1;} else {$border = 0;}
			
			// get the width - default is 0.2mm
			$borderWidth 	= $OPTION['wps_pdf_footer_BorderWidth'];
			$this->SetLineWidth($borderWidth);
			
			// do we want a background colour?
			if ($OPTION['wps_pdf_footer_bgdColour_enable']) {
				//get the bgd colour 
				$bgd_colour = $OPTION['wps_pdf_footer_bgdColour'];
				$this->SetFillColor($bgd_colour);
				$this->Cell(0,10,"$footer_text",$border,0,'C',true);
				
			} else {
				$this->Cell(0,10,"$footer_text",$border,0,'C');
			}
			
		}
	}