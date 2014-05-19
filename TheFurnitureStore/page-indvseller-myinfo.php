<?php
/*
Template Name: Indvidual Seller My Info Page
*/
global $OPTION, $current_user;

get_header();

if (is_user_logged_in() && !in_array('profseller', $current_user->roles)) {

$seller_first_name = get_user_meta($current_user->ID, 'first_name', true);
$seller_last_name = get_user_meta($current_user->ID, 'last_name', true);
$seller_address = get_user_meta($current_user->ID, 'seller_address', true);
$seller_phone = get_user_meta($current_user->ID, 'phone', true);
$seller_bank_type = get_user_meta($current_user->ID, 'seller_bank_type', true);
$seller_bank_details = get_user_meta($current_user->ID, 'seller_bank_details', true);
$seller_email = $current_user->data->user_email;
if (!strlen($seller_bank_type)) { $seller_bank_type = 'Bank transfer'; }
?>
<div class="user-info-row">
	<div class="right">
		<a href="<?php echo get_permalink($OPTION['wps_what_happens_next_page']); ?>">Find out what happens next</a>
	</div>
	<h1 class="main-title"><?php echo $current_user->data->user_login; ?></h1>
</div>
<div class="indvseller-my-items">
	<div id="my-info">
		<div class="sellers-other-tabs">
			<form id="indivseller-my-info" method="POST" class="form-add">
				<div class="row">
					<strong class="title">Your details</strong>
				</div>
				<div class="row">
					<div class="column" id="seller-first-name">
						<label>First Name *</label>
						<input type="text" name="seller_first_name" value="<?php echo $seller_first_name; ?>">
					</div>
					<div class="column" id="seller-last-name">
						<label>Last Name *</label>
						<input type="text" name="seller_last_name" value="<?php echo $seller_last_name; ?>">
					</div>
				</div>
				<div class="row" id="seller-address">
					<label>Address</label>
					<textarea name="seller_address"><?php echo $seller_address; ?></textarea>
				</div>
				<div class="row">
					<div class="column" id="seller-email">
						<label>E-mail *</label>
						<input type="text" name="seller_email" value="<?php echo $seller_email; ?>">
					</div>
					<div class="column" id="seller-phone">
						<label>Telephone *</label>
						<input type="text" name="seller_phone" value="<?php echo $seller_phone; ?>">
					</div>
				</div>
				<div class="row">
					<strong class="title">Preferred payment method</strong>
				</div>
				<div class="row" id="seller-bank-type">
					<label class="check-row">
						<input type="radio" name="seller_bank_type" value="Cheque"<?php if ($seller_bank_type == 'Cheque') { echo ' CHECKED'; } ?>>
						<span class="label">Cheque</span> 
					</label>
					<label class="check-row">
						<input type="radio" name="seller_bank_type" value="Bank transfer"<?php if ($seller_bank_type == 'Bank transfer') { echo ' CHECKED'; } ?>>
						<span class="label">Bank transfer</span> 
					</label>
					<label class="check-row">
						<input type="radio" name="seller_bank_type" value="Paypal"<?php if ($seller_bank_type == 'Paypal') { echo ' CHECKED'; } ?>>
						<span class="label">Paypal</span> 
					</label>
				</div>
				<div class="row" id="seller-bank-details">
					<label>Payment Details</label>
					<textarea name="seller_bank_details"><?php echo $seller_bank_details; ?></textarea>
				</div>
				<div class="row">
					<input value="Submit" type="submit" class="btn-orange">
					<div class="seller-submitting">Updating...</div>
					<div class="seller-message">Your Info was successfully updated.</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
} else {
	echo '<p>You are not allowed to view this page.</p>';
}
get_footer(); ?>
?>