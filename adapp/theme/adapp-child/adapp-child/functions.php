<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_parent_css' ) ):
    function chld_thm_cfg_parent_css() {
        wp_enqueue_style( 'chld_thm_cfg_parent', trailingslashit( get_template_directory_uri() ) . 'style.css', array( 'sydney-bootstrap' ) );
    }
endif;
function wpb_custom_new_menu() {
  register_nav_menu('my-custom-menu',__( 'My Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );
// END ENQUEUE PARENT ACTION


function new_excerpt_more($more) {
    return '';
}
add_filter('excerpt_more', 'new_excerpt_more',200 );


function the_excerpt_more_link( $excerpt ){
    $post = get_post();
    $excerpt .= '<a href="'. get_permalink($post->ID) . '" class="more">Read More</a>';
    return $excerpt;
}
add_filter( 'the_excerpt', 'the_excerpt_more_link', 21 );


// Change Buy Now label to Register
add_filter( 'tribe_tickets_buy_button', function( $html ) {
    return str_replace( 'Buy Now!', 'test', $html );
} );


function wpyog_custom_post_status(){
     register_post_status( 'archive', array(
          'label'                     => _x( 'Archive', 'news' ),
          'public'                    => true,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Archive <span class="count">(%s)</span>', 'Archive <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'wpyog_custom_post_status' );


add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !current_user_can('admins') && !current_user_can('fm_admin') && !is_admin()) {
  show_admin_bar(false);
}
}

function wpb_widgets_init() {
    register_sidebar( array(
        'name' =>__( 'News Sidebar', 'wpb'),
        'id' => 'sidebar-2',
        'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
	 register_sidebar( array(
        'name' =>__( 'Blog details Sidebar', 'wpb'),
        'id' => 'sidebar-8',
        'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    }
 


add_shortcode( 'wpyog_forgot_password', 'wpyog_forgot_password' );
add_shortcode( 'wpyog_reset_password', 'wpyog_reset_password' );
function wpyog_forgot_password(){
	require_once( get_stylesheet_directory() . '/wpyog-templates/forgot-password.php');
}
add_action('wp_ajax_nopriv_wpyog_lost_pass', 'wpyog_lost_pass_callback');
add_action('wp_ajax_wpyog_lost_pass', 'wpyog_lost_pass_callback');
function wpyog_lost_pass_callback(){
	global $wpdb, $wp_hasher ,$my_error;
	$nonce = $_POST['nonce'];	
	if ( ! wp_verify_nonce( $nonce, 'rs_user_lost_password_action' ) )
        die ( 'Security checked!');	
	//We shall SQL escape all inputs to avoid sql injection.
	$user_login = $_POST['user_login'];
	$errors = new WP_Error();
	if ( empty( $user_login ) ) {
		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
	} else if ( strpos( $user_login, '@' ) ) {
		$user_data = get_user_by( 'email', trim( $user_login ) );
		if ( empty( $user_data ) )
			$errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
	} else {
		$login = trim( $user_login );
		$user_data = get_user_by('login', $login);
	}
	/**
	 * Fires before errors are returned from a password reset request.
	 *
	 * @since 2.1.0
	 * @since 4.4.0 Added the `$errors` parameter.
	 *
	 * @param WP_Error $errors A WP_Error object containing any errors generated
	 *                         by using invalid credentials.
	 */
	do_action( 'lostpassword_post', $errors );
	
	if (empty($user_data )) {
		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or email.'));
	}
	if (!empty($user_data )) {
		// Redefining user_login ensures we return the right case in the email.
		$user_login = $user_data->data->user_email;
		$user_email = $user_data->data->user_email;
		$userName = $user_data->data->display_name;
	
		$reset_link = esc_url(add_query_arg( array('login' =>base64_encode($user_login)),site_url( '/reset-password/')));
		$msg  = "Hello!, $userName  \r\n";
	
		$msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.'), $user_login ) . "\r\n";
  
		$msg .= 'To reset your password, visit the following address' . "\r\n";
		$msg .= $reset_link . "\r\n";
		$msg .= 'Thanks!' . "\r\n";
	
		$title = 'ADAPP - Password Reset';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		wp_mail($user_email, $title, $msg);
 
		$successMsg = 'Reset request has been processed, kindly check your email for instruction to reset your password.';
	}
	// display error message
	if ( $errors->get_error_code() ){
		echo '<div class="wpyog_error_message"><i class="fa fa-times-circle"></i>'. $errors->get_error_message( $errors->get_error_code() ).'</div>';
	}else{
		echo '<div class="wpyog_success_message"><i class="fa fa-check"></i>'. $successMsg.'</div>';
	}
	
	wp_die(); 
	exit;
}
function wpyog_reset_password($atts){ ?>
	
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

<?php }
add_action( 'wp_ajax_nopriv_wpyog_reset_pass', 'wpyog_reset_pass_callback' );
add_action( 'wp_ajax_wpyog_reset_pass', 'wpyog_reset_pass_callback' );
/*
 *	@desc	Process reset password
 */
function wpyog_reset_pass_callback() {
	global $wpdb;
	$errors = new WP_Error();
	$nonce = $_POST['nonce'];
	
	if ( ! wp_verify_nonce( $nonce, 'rs_user_reset_password_action' ) )
        die ( 'Security checked!');
	
	$pass1 	= $_POST['pass1'];
	$pass2 	= $_POST['pass2'];
	$key 	= $_POST['user_key'];
	$login 	= base64_decode($_POST['user_login']);
	$user = get_user_by('email',trim($login));
	// check to see if user added some string
	if( empty( $pass1 ) || empty( $pass2 ) )
		$errors->add( 'password_required', __( 'Password is required field' ) );
 
	// is pass1 and pass2 match?
	if (isset($pass1) && $pass1 != $pass2 )
		$errors->add( 'password_reset_mismatch', __( 'The passwords do not match.' ) );
 
	/**
	 * Fires before the password reset procedure is validated.
	 *
	 * @since 3.5.0
	 *
	 * @param object           $errors WP Error object.
	 * @param WP_User|WP_Error $user   WP_User object if the login and reset key match. WP_Error object otherwise.
	 */
	//do_action( 'validate_password_reset', $errors, $user );
    
	if ( ( ! $errors->get_error_code() ) && isset( $pass1 ) && !empty( $pass1 ) && !empty($user)) {
		wp_set_password($pass1,$user->ID);
		$dasaUsers = $wpdb->get_results($wpdb->prepare("Select count(id) as cid from {$wpdb->prefix}axxiem_api_logs where user_id = %d",$user->ID));
		if($dasaUsers[0]->cid >0 ){
			$meta = get_user_meta( $user->ID );
		 
			// Filter out empty meta data
			$user_meta = array_filter( array_map( function( $a ) {
				return $a[0];
			}, $meta ) );

			$insert_array = array(
				'user_login' => $user->data->user_login,
				'user_pass' => $pass1,
				'user_nicename' => $user->data->user_nicename,
				'display_name' => $user->data->display_name,
				'user_firstname' => $user_meta['first_name'],
				'user_lastname' => $user_meta['last_name'],
				'user_email' => $user->data->user_email
			);
			$insert_array = json_encode($insert_array);
			$axxiem_webhook_url = get_option( 'axxiem_webhook_url' );

			$response = wp_remote_post($axxiem_webhook_url, array(
				'body'    => $insert_array,
				'headers' => array(
					'Content-Type' => 'application/json'
				),
			));
			
			$responseBody = wp_remote_retrieve_body( $response );
			$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $responseBody, true ) : null;
			$table_name = $wpdb->prefix . "axxiem_api_logs";
			$logs_data = array(
				'user_id'=>$user->ID,
				'method_used'=>'Update User',
				'content'=>$insert_array,
				'status'=>0,
				'created'=>date('Y-m-d H:i:s')
			);
			if($responceData['status'] == 'success'){
				$logs_data['status'] = 1;
			}
			$wpdb->insert($table_name,$logs_data);
		}
		if(!session_id())
		{
			session_start();
		} 
		echo "success";
		$_SESSION['WPyog_reset_success'] = 'Your password has been reset.';
		//$errors->add( 'password_reset', __( 'Your password has been reset.' ) );
	}
	
	// display error message
	if ( $errors->get_error_code() ){
		echo '<div class="wpyog_error_message"><i class="fa fa-times-circle"></i>'. $errors->get_error_message( $errors->get_error_code() ).'</div>'; }
	
	// return proper result
	wp_die();
	exit;
}


add_action( 'pre_get_posts', 'tribe_exclude_events_category_month_list' );
function tribe_exclude_events_category_month_list( $query ) { 
	if ( isset( $query->query_vars['eventDisplay'] ) && ! is_singular( 'tribe_events' ) ) { 
        if ($query->query_vars['eventDisplay'] == 'day' && ! is_tax( Tribe__Events__Main::TAXONOMY ) || $query->query_vars['eventDisplay'] == 'list' && ! is_tax( Tribe__Events__Main::TAXONOMY ) || $query->query_vars['eventDisplay'] == 'month' && $query->query_vars['post_type'] == Tribe__Events__Main::POSTTYPE && ! is_tax( Tribe__Events__Main::TAXONOMY ) && empty( $query->query_vars['suppress_filters'] ) ) {
              if ( is_user_logged_in() ){
				  $query->set( 'tax_query', array(
						'relation' => 'AND',
						array(
							'taxonomy' => TribeEvents::TAXONOMY,
							'field' => 'slug',
							'terms'    => array( 'all-members', 'members-only' )
							)
						)
					);				
			  } else { 
			  		$query->set( 'tax_query', array(
						'relation' => 'OR',
						array(
							'taxonomy' => Tribe__Events__Main::TAXONOMY,
							'field'    => 'slug',
							'terms'    => array( 'members-only'),
							'operator' => 'NOT IN'
						)
					));			  
			  }	
        }
    }
	
    return $query;
}



add_action('user_register','axxiem_user_function'); 
function axxiem_user_function($user_id){
	$upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
	$wp_content_dir = $upload_dir;
	$users = get_user_by( 'id',$user_id);
	
	//chatBotAccount($users);
	
	$user_name = $users->user_login;
	$role = $users->roles[0];
	if(in_array($role,array('staff','hr','supervisor','admins','coalition_staff'))){
		$user_dir = $wp_content_dir .'/wp-file-manager-pro/users/'.$user_name;
		if (! is_dir($user_dir)) {
			mkdir($user_dir, 0777, true);
			$sub_hr_dir = $wp_content_dir .'/wp-file-manager-pro/users/'.$user_name.'/hr';
			mkdir($sub_hr_dir, 0777, true);
			$my_dir = $wp_content_dir .'/wp-file-manager-pro/users/'.$user_name.'/my';
			mkdir($my_dir, 0777, true);
			$personnel_dir = $wp_content_dir .'/wp-file-manager-pro/users/'.$user_name.'/personnel';
			mkdir($personnel_dir, 0777, true);
		}
	}
	axxiem_custom_wp_filemanager_roles();
}

add_action( 'set_user_role','axxiem_user_role_update', 10, 2);
function axxiem_user_role_update($user_id)
{
    $users = get_user_by( 'id',$user_id);
	$user_name = $users->user_login;
	$role = $users->roles[0];
	chatBotAccount($users);
	axxiem_custom_wp_filemanager_roles();
}


add_action( "password_reset", "axxi_password_reset", 10, 2 );
function axxi_password_reset( $current_user, $new_pass ) {
	global $wpdb;
	//$current_user = get_user_by('login', $user);
	$user_id = $current_user->ID;
	$dasaUsers = $wpdb->get_results($wpdb->prepare("Select count(id) as cid from {$wpdb->prefix}axxiem_api_logs where user_id = %d",$user_id));
	if($dasaUsers[0]->cid >0 ){
		$meta = get_user_meta( $user_id );
		 
		// Filter out empty meta data
		$user_meta = array_filter( array_map( function( $a ) {
			return $a[0];
		}, $meta ) );

		$insert_array = array(
			'user_login' => $current_user->data->user_login,
			'user_pass' => $new_pass,
			'user_nicename' => $current_user->data->user_nicename,
			'display_name' => $current_user->data->display_name,
			'user_firstname' => $user_meta['first_name'],
			'user_lastname' => $user_meta['last_name'],
			'user_email' => $current_user->data->user_email
		);
		
		$insert_array = json_encode($insert_array);
		$axxiem_webhook_url = get_option( 'axxiem_webhook_url' );
		$response = wp_remote_post($axxiem_webhook_url, array(
			'body'    => $insert_array,
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));
		
		$responseBody = wp_remote_retrieve_body( $response );
		$responceData = ( ! is_wp_error( $response ) ) ? json_decode( $responseBody, true ) : null;
		$table_name = $wpdb->prefix . "axxiem_api_logs";
		$logs_data = array(
			'user_id'=>$user_id,
			'method_used'=>'Update User',
			'content'=>$insert_array,
			'status'=>0,
			'created'=>date('Y-m-d H:i:s')
		);
		if($responceData['status'] == 'success'){
			$logs_data['status'] = 1;
		}
		$wpdb->insert($table_name,$logs_data);
	}
}

function axxiem_custom_wp_filemanager_roles(){
	$role_array['wp_filemanager_nonce_field'] = 'ff74dbb86b';
	$role_array['_wp_http_referer'] = '/wp-admin/admin.php?page=wp_file_manager_settings';
	$role_array['fm_user_roles'] = array(0 => 'staff', 1 => 'staff_pro', 2 => 'supervisor', 3 => 'hr', 4 => 'admins', 5 => 'coalition_staff');
	
	$role_array['private_folder_access'] = '';
	$role_array['fm_max_upload_size'] = '256';
	$role_array['lang'] = 'en';
	$role_array['theme'] = 'light';
	$role_array['wp_fm_view'] = 'list';
	$role_array['code_editor_theme'] = 'light';
	//$role_array['select_user_roles'] = array (1 => 'staff',2 => 'staff',3 => 'admins',4 => 'admins',5 => 'hr',6 => 'hr',7=> 'hr');
	$role_array['select_user_roles'] = array (1 => 'staff',6 => 'coalition_staff',2 => 'admins',3 => 'admins',4 => 'hr',5 => 'hr');
	$role_array['seprate_folder'] = array(
		0 => '',
		1 => '*',
		//2 => 'wp-content/uploads/wp-file-manager-pro/Shared_Documents',
		2 => '*',
		3 => 'wp-content/uploads/wp-file-manager-pro/',
		4 => '*',
		5 => 'wp-content/uploads/wp-file-manager-pro/users',
		6 => '*');
	
	
	//HR - restrict My and Personnel folders
	$hr_hidden_dir = [];
	//Code comment by sudhir 10 August, 2025
	/*$all_users = get_users( [ 'role__not_in' => ['staff','supervisor','hr','admins','coalition_staff'] ] );
	if ( ! empty( $all_users ) ) {
		$k=0;
		foreach($all_users as $st_user){
			$hr_hidden_dir[$k++] = $st_user->data->user_login.'/my|'.$st_user->data->user_login.'/personnel';
		}
	}
	
	$hr_hidden_dir_str = implode('|',$hr_hidden_dir);
	*/
	/*commented by Harish - 
	$all_users = get_users( [ 'role__in' => ['fm_admin','administrator'] ] );
	if ( ! empty( $all_users ) ) {
		$k = key( array_slice( $hr_hidden_dir, -1, 1, TRUE ) ) + 1;
		foreach($all_users as $st_user){
			$hr_hidden_dir[$k] = $st_user->data->user_login;
			$k++;
		}
	}*/

	//FM admin - restrict HR folder
	$fmadmin_hidden_dir = $non_adapp_users = $non_adapp_users_for_hr = [];
	$all_users = get_users( [ 'role__in' => ['staff', 'supervisor', 'hr', 'coalition_staff'] ] );
	if ( ! empty( $all_users ) ) {
		$k=0;
		foreach($all_users as $st_user){
			$fmadmin_hidden_dir[$k++] = 'users/'.$st_user->data->user_login.'/hr';
		}
	}
	$fmadmin_hidden_dir_str = implode('|',$fmadmin_hidden_dir);
	
	//Hide all user who are not Staff, Supervisor, HR or FM Admin - For FM 
	$all_users = get_users( [ 'role__not_in' => ['staff','supervisor','hr','admins', 'coalition_staff'] ] );
	if ( ! empty( $all_users ) ) {
		$k = key( array_slice( $non_adapp_users, -1, 1, TRUE ) ) + 1;
		$m = key( array_slice( $non_adapp_users_for_hr, -1, 1, TRUE ) ) + 1;
		foreach($all_users as $st_user){
			$non_adapp_users[$k] = 'users/'.$st_user->data->user_login;
			$non_adapp_users_for_hr[$m] = $st_user->data->user_login;
			$k++;
			$m++;
		}
	}
	//echo "<pre>"; print_r($non_adapp_users); exit;
	$non_adapp_users_str = implode('|',$non_adapp_users);
	$non_adapp_users_for_hr_str = implode('|',$non_adapp_users_for_hr);
	
	$role_array['restrict_folders'] = array (
		1 => '',
		//2 => 'adapp_supervisor_information',
		2 => '',
		3 => $fmadmin_hidden_dir_str.'|'.$non_adapp_users_str.'|fm_backup',
		4 => '',
		// Added by Harish - removing $hr_hidden_dir_str, as HR needs to see all user folders
		//5 => $hr_hidden_dir_str.'|'.$non_adapp_users_for_hr_str,
		5 => $non_adapp_users_for_hr_str,
	);
	$role_array[ 'restrict_files'] = array (
		1 => '',
		2 => '',
		3 => '',
		4 => '',
		5 => '',
		6 => '',
	);
	$role_array['userrole_fileoperations_1'] = array (
		0 => 'mkdir',
		1 => 'rename',
		2 => 'archive',
		3 => 'extract',
		4 => 'cut',
		5 => 'rm',
		6 => 'resize',
	);
	$role_array['userrole_fileoperations_2'] = array (
		0 => 'mkdir',
		1 => 'mkfile',
		2 => 'rename',
		3 => 'duplicate',
		4 => 'paste',
		5 => 'archive',
		6 => 'extract',
		7 => 'cut',
		8 => 'edit',
		9 => 'rm',
		10 => 'upload',
		11 => 'empty',
	);
	$role_array['userrole_fileoperations_5'] = array (
		0 => 'mkdir',
		1 => 'mkfile',
		2 => 'rename',
		3 => 'duplicate',
		4 => 'archive',
		5 => 'extract',
		6 => 'cut',
		7 => 'edit',
		8 => 'rm',
		9 => 'empty',
		10 => 'resize',
	);
	$role_array['userrole_fileoperations_6'] = array (
		0 => 'mkdir',
		1 => 'mkfile',
		2 => 'rename',
		3 => 'duplicate',
		4 => 'archive',
		5 => 'extract',
		6 => 'cut',
		7 => 'edit',
		8 => 'rm',
		9 => 'empty',
		10 => 'resize',
	);
	$role_array['userrole_fileoperations_7'] = array (
		0 => 'mkdir',
		1 => 'mkfile',
		2 => 'rename',
		3 => 'duplicate',
		4 => 'archive',
		5 => 'extract',
		6 => 'cut',
		7 => 'edit',
		8 => 'rm',
		9 => 'empty',
		10 => 'resize',
	);
	
	//Generate array for user restrictions - Supervisor
	$role_array['user_seprate_folder'][0] = '';
	$supervisor_users = get_users( [ 'role__in' => [ 'supervisor'] ] );
	if(!empty($supervisor_users)){
		$i =1;
		foreach($supervisor_users as $s_user){
			$j=0;
			$hide_staff_folder = $exclude_staff_user = [];
			$exclude_staff_user[0] = $s_user->user_login;
			$args  = array(
				'meta_key' => 'reporting_to',
				'meta_value' => $s_user->ID ,
				'meta_compare' => "=",
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => 'wp_capabilities',
						'value'   => 'bbp_blocked',
						'compare' => 'NOT LIKE'
					),
					array(
						'relation' => 'OR',
						array(
							'key'     => 'wp_capabilities',
							'value'   => 'a:0:{}',
							'compare' => '='
						),
						array(
							'key'     => 'wp_capabilities',
							'value'   => '""',
							'compare' => '='
						)
					)
				)
			); 
			$reporting_staff_users = new WP_User_Query( $args );
			
			if ( ! empty( $reporting_staff_users->results ) ) {
				foreach($reporting_staff_users->results as $st_user){
					$hide_staff_folder[$j++] = $st_user->data->user_login .'/hr';
					$exclude_staff_user[$j++] = $st_user->ID;
				}
			}
			$args  = array(
				'role__in ' => array('staff','supervisor','hr','admins','administrator','coalition_staff')
			);
	
			if(!empty($exclude_staff_user)){
				$args  = array_merge($args,array('exclude'=>$exclude_staff_user));
			}
			$staff_users = get_users( $args );
			if ( ! empty( $staff_users ) ) {
				foreach($staff_users as $st_user){
					$hide_staff_folder[$j++] = $st_user->data->user_login;
				}
			}
			$restrict_user_folders = !empty($hide_staff_folder)? implode('|',$hide_staff_folder) : '';
			//Add their own folder access
			$role_array['select_users'][$i] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$i] = '*';
			$role_array['restrict_user_folders'][$i] = '';
			$role_array['restrict_user_files'][$i] = '';
			$role_array['users_fileoperations_'.$i] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
			//Add shared documents access
			/* Hide as shared documents moved to separate section
			$k = $i+1;
			$role_array['select_users'][$k] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$k] = '';
			$role_array['restrict_user_folders'][$k] = '';
			$role_array['restrict_user_files'][$k] = '';
			$role_array['users_fileoperations_'.$k] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
			*/
			//Add their restricted users folder access
			$m = $i+2;
			$role_array['select_users'][$m] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$m] = 'wp-content/uploads/wp-file-manager-pro/users/';
			$role_array['restrict_user_folders'][$m] = $restrict_user_folders;
			$role_array['restrict_user_files'][$m] = '';
			$role_array['users_fileoperations_'.$m] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm', 8 => 'empty',9 => 'resize');
		
			$i = $i+3;
			//$i++;
		}
	}
	
	
	//Generate array for user restrictions - HR
	$counthr = $i;
	//$role_array['user_seprate_folder'][0] = '';
	$hr_users = get_users( [ 'role__in' => [ 'hr'] ] );
	if(!empty($hr_users)){
			$i =$counthr;
			foreach($hr_users as $s_user){
				$j=0;
				//Get staff reporting to this HR user
				$args  = array(
					'meta_key' => 'reporting_to',
					'meta_value' => $s_user->ID ,
					'meta_compare' => "=",
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'wp_capabilities',
							'value'   => 'bbp_blocked',
							'compare' => 'NOT LIKE'
						),
						array(
							'relation' => 'OR',
							array(
								'key'     => 'wp_capabilities',
								'value'   => 'a:0:{}',
								'compare' => '='
							),
							array(
								'key'     => 'wp_capabilities',
								'value'   => '""',
								'compare' => '='
							)
						)
					)
				); 
				$reporting_hr_staff_users = new WP_User_Query( $args );
				//Convert to array
				$reporting_staff_users_arr = [];
				 
				if ( ! empty( $reporting_hr_staff_users->results ) ) {
					foreach($reporting_hr_staff_users->results as $reporting_hr_user){
						$reporting_staff_users_arr[] = $reporting_hr_user->ID;
					}
				}
				//HR - restrict My and Personnel folders only for non-reporting staff
				$hr_hidden_dir = [];
				/*$all_users = get_users( [ 'role__in' => ['staff','supervisor','hr','admins'] ] );
				if ( ! empty( $all_users ) ) {
					$k=0;
					foreach($all_users as $st_user){
						//check if user is reporting to HR, then dont hide my and personnel
						if(!in_array($st_user->ID,$reporting_staff_users_arr))
							$hr_hidden_dir[$k++] = $st_user->data->user_login.'/my|'.$st_user->data->user_login.'/personnel';
					}
				}*/
				$hr_hidden_dir_str = implode('|',$hr_hidden_dir);


				//Add their own folder access
				$role_array['select_users'][$i] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$i] = '*';
				$role_array['restrict_user_folders'][$i] = '';
				$role_array['restrict_user_files'][$i] = '';
				$role_array['users_fileoperations_'.$i] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'empty',8 => 'resize');
				//Add shared documents access
				/* Hide as shared documents moved to separate section
				$k = $i+1;
				$role_array['select_users'][$k] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$k] = '';
				$role_array['restrict_user_folders'][$k] = '';
				$role_array['restrict_user_files'][$k] = '';
				$role_array['users_fileoperations_'.$k] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
				*/
				//Add restricted folders
				$m = $i+2;
				$role_array['select_users'][$m] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$m] = 'wp-content/uploads/wp-file-manager-pro/users/';
				//Added by Harish - HR now sees all folder, so removing $hr_hidden_dir_str
				//$role_array['restrict_user_folders'][$m] = $hr_hidden_dir_str.'|'.$non_adapp_users_for_hr_str;
				$role_array['restrict_user_folders'][$m] = $non_adapp_users_for_hr_str;
				$role_array['restrict_user_files'][$m] = '';
				$role_array['users_fileoperations_'.$m] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut',7 => 'empty',8 => 'resize');

				$i = $i+3;
			}
		}
	
	$role_array['display_fm_on_pages'] = 'after_content';
	$role_array['without_login_shortcode'] = '';
	update_option( 'wp_filemanager_options', $role_array );
}
add_filter('views_edit-tribe_events','axxiem_view_tribe_events');

function axxiem_view_tribe_events($views){
	if( ( is_admin() ) && ( $_GET['post_type'] == 'tribe_events' ) ) {
		global $wp_query;

		$query = array(
			'post_type'   => 'tribe_events',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => '_EventStartDate',
			'meta_value' => date( "Y-m-d H:i:s" ), // change to how "event date" is stored
			'meta_compare' => '>',
		);

		$result = new WP_Query($query);
		$class = (isset($wp_query->query_vars['event_status']) && ($wp_query->query_vars['event_status'] == "upcomming")) ? ' class="current"' : '';
		$views['upcoming'] = sprintf(__('<a href="%s"'. $class .'>'. "Upcoming" .' <span class="count">(%d)</span></a>', "Upcoming" ), admin_url('edit.php?post_status=publish&post_type=tribe_events&event_status=upcoming'), $result->found_posts);

		$query_completed = array(
			'post_type'   => 'tribe_events',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'meta_key' => '_EventStartDate',
			'meta_value' => date( "Y-m-d H:i:s" ), // change to how "event date" is stored
			'meta_compare' => '<',
		);

		$resultCompleted = new WP_Query($query_completed);
		$class = (isset($wp_query->query_vars['event_status']) && ($wp_query->query_vars['event_status'] == "completed")) ? ' class="current"' : '';
		$views['completed'] = sprintf(__('<a href="%s"'. $class .'>'. "Completed" .' <span class="count">(%d)</span></a>', "Completed" ), admin_url('edit.php?post_status=publish&post_type=tribe_events&event_status=completed'), $resultCompleted->found_posts);

		return $views;
	}
}

add_filter( 'pre_get_posts', 'axxiem_event_filter_status' );

function axxiem_event_filter_status( $query){
	global $pagenow;
	$type = 'tribe_events';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	
	if( 'tribe_events' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['event_status']) && $_GET['event_status'] != '' && $query->is_main_query()) {
		$query->query_vars['post_status'] = 'publish';
		$query->query_vars['meta_key'] = '_EventStartDate';
		$query->query_vars['meta_value'] = date( "Y-m-d H:i:s" );
		$query->query_vars['meta_compare'] = ($_GET['event_status'] =='completed') ? '<' : '>';
	}
}

add_filter('wp_authenticate_user', 'restrict_login_by_role', 10, 2);

function restrict_login_by_role($user, $password) {
    $restricted_roles = array('none', 'bbp_blocked'); // roles to block

    if (array_intersect($restricted_roles, $user->roles)) {
        return new WP_Error('access_denied', __('You are not allowed to log in.'));
    }

    return $user;
}

add_action('wp_login', 'logout_restricted_roles', 10, 2);

function logout_restricted_roles($user_login, $user) {
    $restricted_roles = array('none', 'bbp_blocked'); // roles to block
	
	if(empty($user->roles)) {
		wp_logout(); // log the user out

        // Redirect to home or custom page with message
        wp_redirect(home_url('/?access=denied'));
        exit;
	}
    if (array_intersect($restricted_roles, $user->roles)) {
        wp_logout(); // log the user out

        // Redirect to home or custom page with message
        wp_redirect(home_url('/?access=denied'));
        exit;
    }
}
function generateUnique16($id) {
    return substr(md5($id . uniqid('', true)), 0, 16);
}

function chatBotAccount($user) {
	$curl = curl_init();
	
	$username = generateUnique16($user->ID);
	$name = $user->data->display_name;
	$email = $user->data->user_email;

	$user_chat_bot_array = array(
		"uid" => $username,
		"name" => $name,
		'metadata' => [
			'@private' => [	
				'email' => $email
			]
		]
	);
	
	
	$user_icon = get_field('image_avatar', $user);
	$avatar = ($user_icon) ? $user_icon : '';
	
	if ($avatar) {
		$user_chat_bot_array = array_merge(array("avatar" => $avatar), $user_chat_bot_array);
	}
	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://". CHAT_APP_ID .".api-us.cometchat.io/v3/users",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "POST",
	  CURLOPT_POSTFIELDS => json_encode($user_chat_bot_array),
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"content-type: application/json",
		"apiKey : ". CHAT_API_KEY
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  	update_user_meta( $user->ID, 'uID', $username );
		echo $response;
	}

}

function listcometchat() {
	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://". CHAT_APP_ID .".api-us.cometchat.io/v3/users?perPage=100&page=1",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "GET",
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"apiKey : ". CHAT_API_KEY
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
		$arrayJson = json_decode($response, true);
		$arrayJson = $arrayJson['data'];
		if ($arrayJson) {
			foreach($arrayJson as $res) {
				echo $uid = $res['uid'];
				delete_chatbot_users($uid);
			}
		}
	  	
	}
}

function delete_chatbot_users($uid) {
	$curl = curl_init();

	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://". CHAT_APP_ID .".api-us.cometchat.io/v3/users/$uid",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "DELETE",
	  CURLOPT_POSTFIELDS => json_encode([
		'permanent' => true
	  ]),
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"content-type: application/json",
		"apiKey : ". CHAT_API_KEY
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}
}

function update_chatbot_users($uid) {
	$curl = curl_init();
	curl_setopt_array($curl, [
	  CURLOPT_URL => "https://". CHAT_APP_ID .".api-us.cometchat.io/v3/users/$uid",
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => "PUT",
	  CURLOPT_POSTFIELDS => json_encode([
		'metadata' => [
			'@private' => [
					'email' => 'user@email.com',
					'contactNumber' => '0123456789'
			]
		],
		'tags' => [
			'tag1'
		],
		'unset' => [
			'avatar'
		]
	  ]),
	  CURLOPT_HTTPHEADER => [
		"accept: application/json",
		"content-type: application/json",
		"apiKey : ". CHAT_API_KEY
	  ],
	]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
	  echo "cURL Error #:" . $err;
	} else {
	  echo $response;
	}
	
}
function wpb_create_chatbot_users() {
	$all_users = get_users( [ 'role__in' => ['staff', 'supervisor', 'hr', 'admins', 'coalition_staff'] ] );
	foreach($all_users as $user) {
		//echo "<pre>"; print_r($user);
		$uID = get_user_meta($user->ID, 'uID', true);
		if($uID) {
			chatBotAccount($user);
		} else {
			
		}
		
		
	}
}
//add_action( 'admin_init', 'axxiem_custom_wp_filemanager_roles' );


if (!empty($_GET['code'])) {
    $url = 'https://login.microsoftonline.com/' . MS_GRAPH_TENANT_ID . '/oauth2/v2.0/token';

    $response = wp_remote_post($url, [
        'body' => [
            'client_id'     => MS_GRAPH_CLIENT_ID,
            'scope'         => 'offline_access https://graph.microsoft.com/Mail.Read',
            'code'          => $_GET['code'],
            'redirect_uri'  => 'https://demo11.axxiem.com/?weblistner=weeklynotes',
            'grant_type'    => 'authorization_code',
            'client_secret' => MS_GRAPH_CLIENT_SECRET
        ]
    ]);

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!empty($data['refresh_token'])) {
        update_option('ms_graph_refresh_token', $data['refresh_token']);
        echo "Refresh token saved!";
    }
}