<?php ob_start();
/**
 * Template Name: WPYog Login Page New
 *
 * Login Page Template.
 *
 * @author WPYog
 * @since 1.0.0
 */
if(!session_id())
{
	session_start();
} 
if (is_user_logged_in()){	
	if(current_user_can('hr') || current_user_can('fm_admin') || current_user_can('staff') || current_user_can('staff_pro') || current_user_can('supervisor')){
		wp_safe_redirect(home_url('/documents/'));
	}else{
		wp_safe_redirect(admin_url('index.php'));
	}
}
get_header(); ?>
<div class="search-banner" style="display:none!important">
<div class="lsow-hero-header lsow-section-bg-cover" style="padddd-top:100px; paddimg-bottom:100px; background-image: url(https://demo11.axxiem.com/wp-content/uploads/2018/03/inner-banner-1.jpg);">	 	<div class="lsow-overlay" style="background-color: rgba(51, 51, 51, 0.7);"></div>
    <div class="lsow-header-content">
		<div class="lsow-standard-header">
			<div class="container"> <header class="page-header">
				<h3>Staff Login</h3>
			</header><!-- .page-header -->  </div>       
        </div>		
	</div>
</div></div>
<?php 
function logout_page() {
  $login_page  = home_url( '/' );
  wp_redirect($login_page);
  exit;
}

add_action('wp_logout','logout_page');
$err = '';
if(isset($_POST['submitLogin']) && $_POST['submitLogin'] == 'sign in' ){
	global $wpdb;

	//We shall SQL escape all inputs
	$username = $wpdb->escape($_REQUEST['username']);
	$password = $wpdb->escape($_REQUEST['password']);
	$remember = $wpdb->escape($_REQUEST['rememberme']);
	
	
	if($remember) $remember = "true";
	else $remember = "false";

	if( $username == "" || $password == "" ) {
		$err = 'Please fill required field.';
	} else {
		$user_data = array();
		$user = get_user_by('email', $username);
		if(empty($user)){
			$user = get_userdatabylogin($username);
		}
		if(!$user){
            $err = '<strong>ERROR</strong>: Either the email or password you entered is invalid.';
        }else{
			 if(!wp_check_password($password, $user->data->user_pass, $user->ID)){
				$err = '<strong>ERROR</strong>: Either the email or password you entered is invalid.';
			 }else{
				$role = isset($user->roles[0]) ? $user->roles[0] : '';
				$restricted_role = array('staff', 'hr', 'admins', 'supervisor', 'customer', 'coalition_staff', 'administrator', 'bbp_keymaster' );
				if ($role && in_array($role, $restricted_role)) {
					$curr = wp_set_current_user( $user->ID, $user->user_login );
					error_log(print_r($curr,true));
					wp_set_auth_cookie( $user->ID);
					do_action( 'wp_login', $user->user_login , $user, false);			 
					do_action('set_current_user');
					 if (is_user_logged_in()){	
						 if(current_user_can('hr') || current_user_can('admins') || current_user_can('staff') || current_user_can('staff_pro') || current_user_can('supervisor')){
							ob_start();
							$url = home_url('/documents/');
							header("Location: $url");
							exit();
						}elseif(current_user_can('customer')){
							ob_start();
							$url = home_url('/documents/');
							header("Location: $url");
							exit();
						 }else{
						   wp_safe_redirect(admin_url('index.php'));
						   exit();
						}
					}
				} else {
					$err = '<strong>ERROR</strong>: You are not allowed to access this site.';
				}
            }
		}
	}
}
?>

<div class="container">
	<div class="row bg-login">		
		<div class="loginContainer">
		<div class="col-lg-6 col-md-6 col-sm-6 leftForm">
			<?php if(!empty($err)) { ?> 
				<div class="alert alert-warning alert-dismissable page-alert" id="wpyog_alert_msg">
				
					<?php echo $err;?>
				</div>
			<?php } ?>
			<?php if(!empty($_SESSION['WPyog_reset_success'])) { ?>
				<div class="alert alert-success alert-dismissable page-alert" id="wpyog_alert_msg">
					<?php echo $_SESSION['WPyog_reset_success']; ?>
				</div>
			<?php unset($_SESSION['WPyog_reset_success']); } ?>
			<h6 class="pm-primary">Login using your credentials</h6>
			<form id="loginACFA" method="post">
				<div class="pm-input-container wpyog-form">
				
					<input id="username" type="text" name="username" type="text" placeholder="Username or Email" required>
					<span class="asterisk_input">
				</div>
				<div class="pm-input-container wpyog-form">
					
					<input class="pm-form-textfield-with-icon" id="password" type="password" placeholder="Password" name="password" required>
					<span class="asterisk_input">
				</div>

				<div class="pm-checkbox-input">
					<label><input name="rememberme" type="checkbox" value="" id="rememberme" class="pm-remember-checkbox"> Remember me</label>
				</div>

				<input name="submitLogin" type="submit" class="pm-rounded-submit-btn pm-no-margin" value="sign in" id="pm-login-btn">
			</form>			
			<br>
					<a href="javascript:void(0);" id="forgotPasswordLink">Forgot Password</a>		
		</div>
		</div>
		<div class="forgotPasswordContainer" style="display:none;">
				<?php echo do_shortcode('[wpyog_forgot_password]'); ?>
		</div>
	</div>
</div>
<script>
	jQuery(document).ready(function($) {
		$(document).on('click','#forgotPasswordLink',function(){
			$('.loginContainer').hide();
			$('.forgotPasswordContainer').show();
		});
	});
</script>
<?php get_footer(); ?>