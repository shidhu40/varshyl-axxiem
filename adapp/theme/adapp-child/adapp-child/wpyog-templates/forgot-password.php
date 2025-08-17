<style>
#preloader {
    left: 50%;
    top: 10%;
    position: absolute;
    z-index: 9;
    display: none;
}
</style>
<div id="lostPassword" class="col-lg-6 col-md-6 col-sm-6 leftForm">
	<div id="message"></div>
	<h6 class="pm-primary">Forgot Password</h6>
	<form id="lostPasswordForm" method="post">
		<?php
			// this prevent automated script for unwanted spam
			if ( function_exists( 'wp_nonce_field' ) ) 
				wp_nonce_field( 'rs_user_lost_password_action', 'rs_user_lost_password_nonce' );
		?>
		<div class="pm-input-container wpyog-form">				
			<input type="text" name="user_login" id="user_login" placeholder="Username or Email Address" required>
			<span class="asterisk_input">
		</div>
		<?php
		/**
		 * Fires inside the lostpassword <form> tags, before the hidden fields.
		 *
		 * @since 2.1.0
		 */
		do_action( 'lostpassword_form' ); ?>
		
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e('Get New Password'); ?>" />
			<img src="<?php echo get_stylesheet_directory_uri(); ?>/Load-RH.gif" id="preloader" alt="Preloader" />
		</p>
		<a href="<?php echo home_url('/login');?>">Back to Log in</a>	
	</form>
</div>
<script>
jQuery(document).ready(function($) {	
	var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' );?>';
	$("form#lostPasswordForm").submit(function(){
		var submit = $("div#lostPassword #submit"),
			preloader = $("div#lostPassword #preloader"),
			message	= $("div#lostPassword #message"),
			contents = {
				action: 	'wpyog_lost_pass',
				nonce: 		this.rs_user_lost_password_nonce.value,
				user_login:	this.user_login.value
			};
		
		// disable button onsubmit to avoid double submision
		submit.attr("disabled", "disabled").addClass('disabled');
		
		// Display our pre-loading
		preloader.css({'visibility':'visible'});
		
		$.post( ajaxurl, contents, function( data ){
			submit.removeAttr("disabled").removeClass('disabled');
			
			// hide pre-loader
			preloader.css({'visibility':'hidden'});
			
			// display return data
			message.html( data );
		});
		
		return false;
	});
});
</script>