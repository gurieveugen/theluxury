<?php 
/*
Template Name: G2P Error Page
*/

$home = get_home_url();
if(isset($_REQUEST['orderid']) && $_REQUEST['orderid'] !='')
{

	wp_redirect($home.'?orderNow=3&error=g2p_error');
}
else {
	wp_redirect( home_url() ); 
}
exit;
?>