<?php
/*
Template Name: Sell item step 3
*/
global $OPTION, $current_user;

get_header();
?>
<div class="user-info-row">
	<div class="right">
		<a href="#">Questions? Take the Tour.</a>
	</div>
	<h1 class="main-title">graphicartist.sunil@gmail.com</h1>
</div>
<div class="add-item-steps">
	<div class="step">
		<a href="#" class="ico">1</a>
		<strong>Submit an item</strong>
	</div>
	<div class="step">
		<a href="#" class="ico">2</a>
		<strong>Manage items</strong>
	</div>
	<div class="step active last">
		<a href="#" class="ico">3</a>
		<strong>Payment</strong>
	</div>
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
						<input name="seller_first_name" value="Ruslan" type="text">
					</div>
					<div class="column" id="seller-last-name">
						<label>Last Name *</label>
						<input name="seller_last_name" value="Hunter" type="text">
					</div>
				</div>
				<div class="row" id="seller-address">
					<label>Address</label>
					<textarea name="seller_address">USA
		32 avenu street, CO, 80001
		test line</textarea>
				</div>
				<div class="row">
					<div class="column" id="seller-email">
						<label>E-mail *</label>
						<input name="seller_email" value="gavrilenko.ruslan@gmail.com" type="text">
					</div>
					<div class="column" id="seller-phone">
						<label>Telephone *</label>
						<input name="seller_phone" value="333-444-5555" type="text">
					</div>
				</div>
				<div class="row">
					<strong class="title">Preferred payment method</strong>
				</div>
				<div class="row" id="seller-bank-type">
					<label class="check-row">
						<input name="seller_bank_type" value="Cheque" type="radio">
						<span class="label">Cheque</span> </label>
					<label class="check-row">
						<input name="seller_bank_type" value="Bank transfer" checked="checked" type="radio">
						<span class="label">Bank transfer</span> </label>
					<label class="check-row">
						<input name="seller_bank_type" value="Paypal" type="radio">
						<span class="label">Paypal</span> </label>
				</div>
				<div class="row" id="seller-bank-details">
					<label>Payment Details</label>
					<textarea name="seller_bank_details">International Bank
		45 avenu street
		cart number: 123456789</textarea>
				</div>
				<div class="row">
					<input value="Submit" type="submit" class="btn-submit">
					<div class="seller-submitting">
						Updating...
					</div>
					<div class="seller-message">
						Your Info was successfully updated.
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<?php
get_footer(); ?>
?>