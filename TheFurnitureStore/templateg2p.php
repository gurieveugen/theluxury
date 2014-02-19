<?php 
/*
Template Name: G2P Result Page
*/

$home = get_home_url();
if(isset($_REQUEST['orderid']) && $_REQUEST['orderid'] !='')
{

	wp_redirect($home.'?confirm=30&orderid='.$_REQUEST['orderid']);
}
else {
	wp_redirect( home_url() ); 
}
exit;
?>