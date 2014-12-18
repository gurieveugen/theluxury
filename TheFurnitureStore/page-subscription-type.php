<?php
/*
Template Name: Change Subscription Type
*/
$email = $_POST['email'];
?>
<?php get_header(); ?>

	<div class="page subscription-type">
		<?php the_content(); ?>
		<form class="subscription-type-form" method="POST">
			<div class="form-row">
				<label>Email:</label>
				<input type="text" name="email" class="stf-email" value="<?php echo $email; ?>">
			</div>
			<div class="form-row">
				<label class="l-block">Weekly Newsletter:</label>
				<div class="row-check"><input type="radio" name="weekly" value="yes" checked> Yes</div>
				<div class="row-check"><input type="radio" name="weekly" value="no"> No</div>
			</div>
			<input type="submit" value="Subscribe" class="stf-submit">
		</form>
	</div>

<?php get_footer(); ?>