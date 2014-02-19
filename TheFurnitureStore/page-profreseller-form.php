<?php
/*
Template Name: Professional Seller Form
*/
global $OPTION;
?>
<?php get_header(); ?>
<?php if (isset($_GET['success'])) { ?>
<div class="prof-seller-success">
	<p><strong>Your information was successfully sent. Thank You.</strong></p>
</div>
<?php } else { ?>
<form method="POST" class="prof-seller-form content-cabin-font" onsubmit="return profseller_form_submit();">
	<input type="hidden" name="SellersAction" value="profseller_form_submit">
	<div class="form-row" id="psf-name">
		<label class="tlt">Name:</label>
		<input type="text" name="psf_name" value="<?php echo $_POST['psf_name']; ?>">
	</div>
	<div class="form-row" id="psf-company">
		<label class="tlt">Company Name:</label>
		<input type="text" name="psf_company" value="<?php echo $_POST['psf_company']; ?>">
	</div>
	<div class="form-row" id="psf-email">
		<label class="tlt">E-mail:</label>
		<input type="email" name="psf_email" value="<?php echo $_POST['psf_email']; ?>">
	</div>
	<div class="form-row" id="psf-contact-number">
		<label class="tlt">Contact number:</label>
		<input type="text" name="psf_contact_number" value="<?php echo $_POST['psf_contact_number']; ?>">
	</div>
	<div class="form-row form-row-1" id="psf-category">
		<label class="tlt">Product category:</label>
		<div class="col">
			<label class="label">
				<input type="checkbox" name="psf_category[]" value="Handbags">
				Handbags
			</label>
			<label class="label">
				<input type="checkbox" name="psf_category[]" value="Shoes">
				Shoes
			</label>
			<label class="label">
				<input type="checkbox" name="psf_category[]" value="Watches">
				Watches
			</label>
		</div>
		<div class="col">
			<label class="label">
				<input type="checkbox" name="psf_category[]" value="Jewelry">
				Jewelry
			</label>
			<label class="label">
				<input type="checkbox" name="psf_category[]" value="Accessories">
				Accessories
			</label>
		</div>
	</div>
	<input type="submit" class="btn-orange" value="SUBMIT">
</form>
<?php } ?>
<?php get_footer(); ?>