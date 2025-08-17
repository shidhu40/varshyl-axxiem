<style>
#preloader {
    left: 50%;
    top: 10%;
    position: absolute;
    z-index: 9;
    display: none;
}
</style>
<div class="row bg-login">
	<div id="resetPassword" class="col-lg-6 col-md-6 col-sm-6 leftForm">
		<div id="message"></div>
		<!--this check on the link key and user login/username-->
		<?php
		$errors = new WP_Error();
		// display error message
		if ( $errors->get_error_code() )
			echo $errors->get_error_message( $errors->get_error_code() );
		?>
		<h6 class="pm-primary">Reset Password</h6>
		<form id="resetPasswordForm" method="post" autocomplete="off">
			<?php
				// this prevent automated script for unwanted spam
				if ( function_exists( 'wp_nonce_field' ) ) 
					wp_nonce_field( 'rs_user_reset_password_action', 'rs_user_reset_password_nonce' );
			?>
			
			<input type="hidden" name="user_key" id="user_key" value="<?php echo esc_attr( $_GET['key'] ); ?>" autocomplete="off" />
			<input type="hidden" name="user_login" id="user_login" value="<?php echo esc_attr($_GET['login']); ?>" autocomplete="off" />
 
			<div class="pm-input-container wpyog-form">
				<input class="pm-form-textfield-with-icon" name="pass1" id="pass1" type="password" placeholder="New password" required>
				<span class="asterisk_input">
			</div>
				
			<div class="pm-input-container wpyog-form">
				<input class="pm-form-textfield-with-icon" name="pass2" id="pass2" type="password" placeholder="Confirm new password" required>
				<span class="asterisk_input">
			</div>
			
			<br class="clear" />
			<p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Reset Password'); ?>" />
				<img src="<?php echo get_stylesheet_directory_uri(); ?>/Load-RH.gif" id="preloader" alt="Preloader" />
			</p>
		</form>
	</div>
</div>
<script>
jQuery(document).ready(function($) {	
	var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' );?>';
	$("form#resetPasswordForm").submit(function(){
		var submit = $("div#resetPassword #submit"),
			preloader = $("div#resetPassword #preloader"),
			message	= $("div#resetPassword #message"),
			contents = {
				action: 	'wpyog_reset_pass',
				nonce: 		this.rs_user_reset_password_nonce.value,
				pass1:		this.pass1.value,
				pass2:		this.pass2.value,
				user_key:	this.user_key.value,
				user_login:	this.user_login.value
			};
		
		// disable button onsubmit to avoid double submision
		submit.attr("disabled", "disabled").addClass('disabled');
		
		// Display our pre-loading
		preloader.css({'visibility':'visible'});
		
		$.post(ajaxurl, contents, function( data ){
			if(data =='success'){
				window.location.href='<?php echo home_url("/login/");?>'
			}else{
				submit.removeAttr("disabled").removeClass('disabled');			
				// hide pre-loader
				preloader.css({'visibility':'hidden'});			
				// display return data
				message.html( data );
			}			
		});
		return false;
	});
});
</script>