<?php
include '../../../wp-load.php';

include('../../../wp-admin/includes/post.php');
include('../../../wp-admin/includes/image.php');
include('../../../wp-admin/includes/file.php');
include('../../../wp-admin/includes/media.php');

$file = wp_handle_upload($_FILES['uploadfile'], array('test_form' => false), current_time('mysql'));
if ($file['url']) {
	echo $file['url'].';'.get_post_thumb($file['url'], 61, 61, true);
} else {
	echo 'error';
}
?>