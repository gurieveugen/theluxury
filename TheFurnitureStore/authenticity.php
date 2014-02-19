<?php /*template name:authenticity */ ?>
<?php get_header();
$DEFAULT = show_default_view();

 include (TEMPLATEPATH . '/lib/pages/index_body.php'); 

 if($DEFAULT){
	$WPS_sidebar		= $OPTION['wps_sidebar_option'];
	switch($WPS_sidebar){
		case 'alignRight':
			$the_float_class 	= 'alignleft';
		break;
		case 'alignLeft':
			$the_float_class 	= 'alignright';
		break;
	}?>
	
	<?php /* if (have_posts()) : while (have_posts()) : the_post(); 

			if($post->post_parent) {
				$children = wp_list_pages("title_li=&child_of=".$post->post_parent."&parent=".$post->post_parent."&echo=0"); 
			} else {
				$children = wp_list_pages("title_li=&child_of=".$post->ID."&echo=0");
			}
			if (($children) || is_sidebar_active('about_page_widget_area') || is_sidebar_active('contact_page_widget_area') || is_sidebar_active('page_widget_area')) {$the_div_class = 'narrow '. $the_float_class;} else {$the_div_class = 'wide';}
	?>

		<div <?php post_class('page_post '.$the_div_class); ?> id="post-<?php the_ID(); ?>">
			<?php the_content('<p class="serif">'. __( 'Read the rest of this page &raquo;', 'wpShop' ) . '</p>'); 
			wp_link_pages(array('before' => '<p><strong>' . __( 'Pages:', 'wpShop' ) . '</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
		</div><!-- page_post -->
	<?php endwhile; endif;

	include (TEMPLATEPATH . '/widget_ready_areas.php'); */
} ?>
<div id="contentWrap">
                    <div id="breadcrumb"> </div>
                    <div class="smallPadding box" id="contentBox">
                        <div class="bigPadding" id="mainContentWrap">
                        <div id="mainContent">
                            <!-- CONTENTS START -->
                            <!-- READ FROM DB --><table width="" cellpadding="20" align="" style="border: medium none; border-collapse: collapse; width: 100%;">
    <tbody>
        <tr>
            <td style="border: medium none;">
            <table width="" align="" style="border: medium none; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <td style="border: medium none;">
                        <img border="0" align="" style="margin-top: 10px;" src="<?php bloginfo('template_directory'); ?>/images/guarauthtag.jpg" alt="Guaranteed Authentic Pre-owned Luxury">
                        </td>
                        <td style="border: medium none; text-align: left; vertical-align: middle; letter-spacing: 0pt; word-spacing: 0pt;">
						<img alt="" src="<?php bloginfo('template_directory'); ?>/images/bagearntagtext.gif" align="left" border="0"><br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        We know that shopping for pre-owned luxury items isn't easy and nothing
                        will ruin your day faster than finding out that the handbag you bought
                        on an auction or classifieds website turns out to be a replica.
                        <br>
                        <br>
                        <table width="" align="" style="border: medium none; border-collapse: collapse; height: 114px;">
                            <tbody>
                                <tr>
                                </tr>
                                <tr>
                                    <td style="border: medium none;">
                                    <img border="0" align="" src="<?php bloginfo('template_directory'); ?>/images/quotebar.gif" alt="" style="margin-left: 10px; margin-right: 10px; width: 6px; height: 128px;">
                                    </td>
                                    <td style="border: medium none; color: rgb(105, 105, 105); font-style: italic;"><span style="font-size: 10pt;"><span style="font-size: 10pt;"><span style="font-size: 10pt;"><br>
                                    <br>
                                    We guarantee the authenticity of every item we sell or 100% of
                                    your money back including original and return shipping costs.</span></span><br>
                                    <br>
                                    Only 100% authentic bags earn the tag!</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        </td>
                    </tr>
                </tbody>
            </table>
            We're experts in distinguishing the "wanna-bes" from the "real deal." Whether it's a handbag or necklace, determining the authenticity of an item is a mix of art and science.
            Items such as stampings, symmetry, stitching, hardware, authenticity stamps, as well as
            overall workmanship and materials are inspected and determined to be
            consistent with the manufacturer's established standards of quality. If
            applicable, manufacturer <a style="font-family: Verdana;" href="/t-dateauthcodes.aspx">date codes and serial numbers</a> are verified for consistency. Lastly, the item is compared to on-hand authentic reference items. <br>
            </td>
        </tr>
    </tbody>
</table>
<br>
<br><!-- END OF DB -->


                            <!-- CONTENTS END -->
                            </div>
                        </div>
                    <div style="clear:both;"></div>
                    </div>
                </div>

<?php get_footer(); ?>