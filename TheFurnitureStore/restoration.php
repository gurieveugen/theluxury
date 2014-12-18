<?php
$WPS_sidebar		= $OPTION['wps_sidebar_option'];
switch($WPS_sidebar){
	case 'alignRight':
		$the_float_class 	= 'alignleft';
	break;
	case 'alignLeft':
		$the_float_class 	= 'alignright';
	break;
}

get_header();?>
	
	<div class="clear"></div>
	<div class="main">
	   <div class="row">
			<p>
				If you have a damaged, dirty, stained or worn out bag with you, send it to us. We professionally clean and restore your bag to near original condition, this means removing stains, marks, scratches, odours and even doing complete leather color restoration! See the before and after picture below. <br>
				<img src="<?php bloginfo('template_directory'); ?>/images/lvbefore.png" width="400" height="303" alt="Before Cleaning" align="center" border="100">
				<img src="<?php bloginfo('template_directory'); ?>/images/lvafter.png" width="400" height="303" alt="After Cleaning" align="center" border="100">
			<br><br>		
			</p>
			<h4>It takes just 3 easy step.</h4><br>	
			<ul>
				<li>Fill our cleaning and repair form or simply call 800 LUX to schedule a pick up. Pick up and delivery is free within the UAE.</li>
				<li>Hand over the bag to our representative</li>	
				<li>Receive your refurbished bag in 2 &ndash; 4 weeks. Pay through cash on delivery!</li>
			</ul>		
		</div>
		<div class="row">
			<h4>Restoration charges</h4>
			<p>The cleaning packages are detailed below. If you have any questions, dont hesitate to give us a call.</p>
		</div>
		<div class="row">
		<table width="55%" border="0" cellpadding="0" cellspacing="0" class="table_class">
			<tbody>
				<tr class="table_head">
					<td>Size</td>
					<td>Charges* (AED)</td>
					<td>Includes</td>
				</tr>
				<tr>
					<td>Express Cleaning</td>
					<td>299</td>
					<td> &nbsp;(Thorough cleaning of the interior and exterior plus all lining and hardware. Does not include stain removal) </td>
				</tr>
				<tr>
					<td>Complete Cleaning</td>
					<td>449</td>
					<td> (Express cleaning PLUS removal of small stains/scratches, Minor color correction on handles and exterior) </td>
				</tr>
				<tr>
					<td>VIP Cleaning</td>
					<td>599</td>
					<td> (Complete cleaning PLUS full color restoration including major stain removal inside/outside and repairs of handles/lining) </td>
				</tr>
			</tbody>
		</table>
		</div>
		<div class="row">
			<p>*Charges include free pick up and drop off of your bag within UAE</p>
		</div>
		<div class="clear"></div>
		<div class="sell">
			<div class="try-container2">
				<div class="btn-try">
					<a href="<?php bloginfo('siteurl'); ?>/item-restoration-form" class="normal"></a>
				</div>
				<div class="arrow-down-long-right">
					<a href="<?php bloginfo('siteurl'); ?>/prelaunch/restoration_form.html" class="normal">...don't wait</a>
				</div>
			</div>
		</div>     	  
	</div>
	<div class="clear"></div>    

<?php get_footer(); ?>