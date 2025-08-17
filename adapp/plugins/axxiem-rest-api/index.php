<?php
/*
	Plugin Name: Axxiem Rest Api
	Description: Axxiem Rest Api.
	Author: Axxiem
	Version: 1.0
 */

// Register REST API endpoints
class Axxiem_REST_API_Endpoints {
	
    /**
     * Register the routes for the objects of the controller.
     */
    public static function register_endpoints() {
        // endpoints will be registered here
        register_rest_route( 'axxiem', '/login', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'login' ),
        ) );
		
		register_rest_route( 'axxiem', '/get-token', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'getToken' ),
        ) );
		
		register_rest_route( 'axxiem', '/logout', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'logout' ),
        ) );
		
		register_rest_route( 'axxiem', '/forgot-password', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'forgotPassword' ),
        ) );
		
		register_rest_route( 'axxiem', '/update-profile', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'updateProfile' ),
        ) );
		
		register_rest_route( 'axxiem', '/update-profile-pic', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'uploadProfilePic' ),
        ) );
		
		register_rest_route( 'axxiem', '/create-post', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'createPost' ),
        ) );
		
		register_rest_route( 'axxiem', '/notices', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'getNotices' ),
        ) );
		
		register_rest_route( 'axxiem', '/delete-notice', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'deleteNotices' ),
        ) );
		
		register_rest_route( 'axxiem', '/file-manager', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'fileManager' ),
        ) );
		
		register_rest_route( 'axxiem', '/create-directory', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'createDirectory' ),
        ) );
		
		
		register_rest_route( 'axxiem', '/remove-directory', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'removeDirectory' ),
        ) );
		
		register_rest_route( 'axxiem', '/upload-files', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'uploadFiles' ),
        ) );
		
		register_rest_route( 'axxiem', '/search', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'search' ),
        ) );
		
		register_rest_route( 'axxiem', '/directory-search', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'directorySearch' ),
        ) );
		
		register_rest_route( 'axxiem', '/changeSetting', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'changeSetting' ),
        ) );
		register_rest_route( 'axxiem', '/refreshSetting', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'refreshSetting' ),
        ) );
		
		register_rest_route( 'axxiem', '/all-category', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'axxiemApiCategoryLists' ),
        ) );
		
		register_rest_route( 'axxiem', '/dashboard', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'axxiemDashboardLists' ),
        ) );
		
		register_rest_route( 'axxiem', '/posts', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'axxiemPosts' ),
        ) );
		
		register_rest_route( 'axxiem', '/create-shared-document', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'axxiemCreateSharedDocument' ),
        ) );
		register_rest_route( 'axxiem', '/mark-recent-document', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'axxiemPostRecentDocument' ),
        ) );
		
		register_rest_route( 'axxiem', '/weekly-notes', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'weeklyNotes' ),
        ) );
		
		register_rest_route( 'axxiem', '/get-roles', array(
            'methods' => 'GET',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'getRoles' ),
        ) );
		
		register_rest_route( 'axxiem', '/get-users', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'getUsers' ),
        ) );
		
		register_rest_route( 'axxiem', '/get-chat-users', array(
            'methods' => 'POST',
            'callback' => array( 'Axxiem_REST_API_Endpoints', 'getChatUsersList' ),
        ) );
    }

    /**
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public static function login( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
        $data = $response = array();
        $data['user_login'] = strtolower($request["username"]);
        $data['user_password'] =  $request["password"];
        $data['remember'] = true;
		
		if(empty($data['user_login']) || empty($data['user_password'])) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		$user = wp_authenticate( $data['user_login'], $data['user_password'] );
		if ( is_wp_error( $user ) ) {
			$response['status'] = 0;
			$response['message'] = "Invalid Credentials";
        } else {		
			$allowerdRoleLogin = ['staff', 'hr', 'supervisor', 'admins', 'administrator', 'coalition_staff'];
			$role = isset($user->roles[0]) ? $user->roles[0] : '';
			if($role) {
				if (in_array($role, $allowerdRoleLogin)) {
					$userInfo['user_id'] = $user->ID;
					$userInfo['display_name'] = $user->data->display_name;
					$userInfo['user_email'] = $user->data->user_email;
					$uID = get_user_meta($user->ID, 'uID', true);
					$userInfo['uid'] = ($uID) ? $uID : '';
					$userInfo['roles'] = $role;

					$axxiem_file_notification = get_option('axxiem_file_notification');
					$axxiem_notice_notification = get_option('axxiem_notice_notification' );

					$userInfo['axxiem_file_notification'] = ($axxiem_file_notification == 1) ? 1 : 0;
					$userInfo['axxiem_notice_notification'] = ($axxiem_notice_notification == 1) ? 1 : 0; 

					$simple_local_avatar = get_field('image_avatar', $user);

					$userInfo['avatar'] = !empty($simple_local_avatar) ? $simple_local_avatar : '';
					$userInfo['create_post'] = true;
					$userInfo['create_event'] = true;
					$userInfo['create_document'] = true;
					$userInfo['chat_enabled'] = true;
					$new_public_key     = (new Axxiem_REST_API_Endpoints())->generate_public_key( $user->user_email );
					$new_secret_key     = (new Axxiem_REST_API_Endpoints())->generate_private_key( $user->ID );

					update_user_meta( $user->ID, 'rest_api_token_auth_public_key', $new_public_key );
					update_user_meta( $user->ID, 'rest_api_token_auth_secret_key', $new_secret_key );   

					$token              = (new Axxiem_REST_API_Endpoints())->get_token( $user->ID );			
					$response['status'] = 1;
					$response['message'] = "Logged Successfully";
					$response['token']      = $token;
					$response['user'] = $userInfo;

					(new Axxiem_REST_API_Endpoints())->getuserInfo( $user->ID, $request);
				} else {
					$response['status'] = 0;
					$response['message'] = "You are not allowed to access this app.";
				}
			} else {
				$response['status'] = 0;
				$response['message'] = "You are not allowed to access this app.";
			}
			
		}
		
		return $response;
    }
	
	/**
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public static function logout( $request ) {
		
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
			
		if ($response['status'] === 0) {
			return $response;
		}	
					
		$public_key = (new Axxiem_REST_API_Endpoints())->get_user_public_key( $user_id );
		$secret_key = (new Axxiem_REST_API_Endpoints())->get_user_secret_key( $user_id );
		if ( ! empty( $public_key ) ) {
			delete_transient( md5( 'rest_api_token_auth_cache_user_' . $public_key ) );
			delete_transient( md5( 'rest_api_token_auth_cache_user_public_key' . $user_id ) );
			delete_transient( md5( 'rest_api_token_auth_cache_user_secret_key' . $user_id ) );
			delete_user_meta( $user_id, 'rest_api_token_auth_public_key' );
			delete_user_meta( $user_id, 'rest_api_token_auth_secret_key' );
			wp_logout();
			
		}
		
		$response['status'] = 1;
		$response['message'] = 'Logout successfully';
		return $response;
    }
	
	/**
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public static function forgotPassword( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_login = $request['user_login'];
		
		$response = array();
		if (empty($user_login)) {
			
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if ( strpos( $user_login, '@' ) ) {
			$user_data = get_user_by( 'email', trim( $user_login ) );
			if ( empty( $user_data ) ) {
				$response['status'] = 0;
				$response['message'] = "There is no user registered with that email address.";
			}
		} else {
			$login = trim( $user_login );
			$user_data = get_user_by('login', $login);
			if ( empty( $user_data ) ){
				$response['status'] = 0;
				$response['message'] = " There is no user registered with that email address.";
			}
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

			$response['status'] = 1;
			$response['message'] = 'Reset request has been processed, kindly check your email for instruction to reset your password.';
		}
		
		return $response;
    }
	
	
	/**
     * @param WP_REST_Request $request Full data about the request.
     * @return WP_Error|WP_REST_Request
     */
    public static function updateProfile( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$display_name = $request['display_name'];
		$password = $request['password'];
		
		$user_id = $request["user_id"];
		$token = $request["token"];
		
		$response = array();
		if (empty($user_id) || empty($token)) {
			
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		/* $validateToken = (new Axxiem_REST_API_Endpoints())->validate_token ( $user_id, $token );
		if (!$validateToken) {
			return new WP_Error(
                'token_auth_failed',
                __('Invalid Tokem', 'wp-api-token-auth'),
                array(
                    'status' => 403,
                )
            );
		} */
			
		if ($response['status'] === 0) {
			return $response;
		}	
		
		if (!empty($display_name ) || !empty($password )) {
			if (!empty($password)) {
				wp_set_password($password,$user_id);
			}
			
			if (!empty($display_name)) {
				wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );
			}
			
			$response['status'] = 1;
			$response['message'] = 'Profile updated successfully.';
		}
		
		return $response;
    }
	
	public static function refreshSetting( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_id = $request["user_id"];
		$token = $request["token"];	
		if (empty($user_id) || empty($token)) {
			return new WP_Error(
                'token_auth_failed',
                __('Invalid Tokem', 'wp-api-token-auth'),
                array(
                    'status' => 403,
                )
            );
		}
		
		$axxiem_file_notification = get_option('axxiem_file_notification');
		$axxiem_notice_notification = get_option('axxiem_notice_notification' );
			
		$response['axxiem_file_notification'] = ($axxiem_file_notification == 1) ? 1 : 0;
		$response['axxiem_notice_notification'] = ($axxiem_notice_notification == 1) ? 1 : 0;
		$response['status'] = 1;
		$response['message'] = 'Setting listed successfully';
		return $response;
	}
	
	
	public static function changeSetting( $request ){
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_id = $request["user_id"];
		$token = $request["token"];	
		if (empty($user_id) || empty($token)) {
			return new WP_Error(
                'token_auth_failed',
                __('Invalid Tokem', 'wp-api-token-auth'),
                array(
                    'status' => 403,
                )
            );
		}

		if( isset($request['axxiem_file_notification']) ||  isset($request['axxiem_notice_notification'])) {
			$axxiem_file_val = ($request['axxiem_file_notification'] == 1) ? 1 : 0;
			$axxiem_notice_val = ($request['axxiem_notice_notification'] == 1) ? 1 : 0;
			update_option( 'axxiem_file_notification', $axxiem_file_val );
			update_option( 'axxiem_notice_notification', $axxiem_notice_val );
		}
	
		if ($response['status'] === 0) {
			return $response;
		}

		$response['status'] = 1;
		$response['message'] = 'Setting has been modified successfully';
		return $response;
	}	
	
	public static function uploadProfilePic( $request ) {
		ini_set('max_execution_time', 999999);
		ini_set('memory_limit','999999M');
		ini_set('upload_max_filesize', '500M');
		ini_set('max_input_time', '-1');
		ini_set('max_execution_time', '-1');
		
		header("Acess-Control-Allow-Origin: *");
		header("Acess-Control-Allow-Methods: POST");
		
		
		require_once( ABSPATH . 'wp-admin/includes/admin.php' );
		$user_id = $_POST["user_id"];
		$token = $_POST["token"];
		$uploadFile = !empty($_FILES) ?  $_FILES['profile_pic'] : '';
		
		$response = array();
		if (empty($uploadFile) || empty($user_id)) {
			
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		/* $validateToken = (new Axxiem_REST_API_Endpoints())->validate_token ( $user_id, $token );
		if (!$validateToken) {
			return new WP_Error(
                'token_auth_failed',
                __('Invalid Tokem', 'wp-api-token-auth'),
                array(
                    'status' => 403,
                )
            );
		} */
			
		if ($response['status'] === 0) {
			return $response;
		}
		
		if (!empty($uploadFile)) {			
			$upload_path = wp_upload_dir();
			$file = array(
			  'name'     => $uploadFile['name'],
			  'type'     => $uploadFile['type'],
			  'tmp_name' => $uploadFile['tmp_name'],
			  'error'    => $uploadFile['error'],
			  'size'     => $uploadFile['size']
			);
			
			
			$file_return = wp_handle_upload( $file, array('test_form' => false ) );

			$avatar_full_path = $file_return['file'];

			$size = 96;
			// generate the new size
			$editor = wp_get_image_editor( $avatar_full_path );
			if ( ! is_wp_error( $editor ) ) {
				$resized = $editor->resize( $size, $size, true );
				if ( ! is_wp_error( $resized ) ) {
					$dest_file = $editor->generate_filename();
					$saved     = $editor->save( $dest_file );
					if ( ! is_wp_error( $saved ) ) {
						$local_avatars[ $size ] = str_replace( $upload_path['basedir'], $upload_path['baseurl'], $dest_file );
					}
				}
			}
			// save updated avatar sizes
			update_user_meta( $user_id, 'simple_local_avatar', $local_avatars );
			
			$response['status'] = 1;
			$response['avatar'] = $local_avatars[ $size ];
			$response['message'] = 'Profile updated successfully.';	
		}
		
		return $response;
	}
	
	public static function axxiemApiCategoryLists($request) {
		global $wp_roles;
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;

		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		$role = '';
		$user = get_user_by('id', $user_id);
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$role = $wp_roles->roles[$role]['name'];
		$cat_args = array(
			'taxonomy' => 'shared-document-category',
			'orderby'  => 'name',
			'hide_empty' => false,
			'order'    => 'ASC'
		);
		$cats = get_categories($cat_args);
		$category_lists = [];
		$files = [];
		if(!empty($cats)) { 
			foreach($cats as $key=>$shared_cat) {
				$category_lists[$key]['cat_id'] = $shared_cat->term_id;
				$category_lists[$key]['cat_icon'] = 'https://demo11.axxiem.com/wp-content/plugins/axxiem-shared-document/images/Folder-Secure-icon.svg';
				$category_lists[$key]['name'] = $shared_cat->name;
				$category_lists[$key]['category_parent'] = $shared_cat->category_parent;
			} 
		}
		$response['results'] = $category_lists;
		$response['status'] = 1;
		$response['message'] = 'Post listed successfully';
		return $response;
	}
	
	public static function axxiemDashboardLists($request) {
		global $wp_roles, $wpdb;
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		$role = '';
		$user = get_user_by('id', $user_id);
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$role = $wp_roles->roles[$role]['name'];
		$args = array(
			'taxonomy' => 'shared-document-category',
			'hide_empty' => false,
			'orderby'  => 'name',
			'order'   => 'ASC'
		);
		
		$all_cats_list = get_categories($args);
		$filtered_categories = array();
		$support_role = array('FM Admin','Administrator','Keymaster');
		if ($all_cats_list) {
			foreach ($all_cats_list as $category) {
				// Replace 'your_acf_field' with the name of your ACF field
				$acf_value = get_field('featured_on_homepage', $category);
				$category_role =  get_field('choose_role', $category);
				// Add your condition here, for example, check if ACF field equals a certain value
				if ($acf_value == 'Yes') {
					if (in_array($role, $support_role)) {
						$filtered_categories[] = $category;
					}elseif(in_array($role, $category_role)) {
						$filtered_categories[] = $category;
					}
				}
			}
		}
		
		$args = array(
			'post_type' => 'adapp_postings',
			'order' => 'desc',
			'orderby' => 'date',
			'posts_per_page' => 5, // Retrieve all posts
		);
		$posts_query = new WP_Query( $args );
		$response = $postArray = $category_lists = $recent_document = $featured_document = array();
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$post_id = $post->ID;
				$author_id = get_post_field('post_author', $post_id);
				$postArray[$key]['id'] = $post_id;
				$postArray[$key]['title'] = $post->post_title;
				$postArray[$key]['description'] = $post->post_content;
				$postArray[$key]['author_name'] = get_the_author_meta('display_name', $author_id);
				$postArray[$key]['publish_date'] = get_the_date('F j, Y' , $post_id);
			}
		}
		if(!empty($filtered_categories)) { 
			$latest_categories = array_slice($filtered_categories, 0, 6);
			foreach($latest_categories as $key=>$cat) { 
				$cat_icon = get_field('thumbnail', $cat);
				$category_lists[$key]['cat_id'] = $cat->term_id;
				$category_lists[$key]['cat_url'] = esc_url(add_query_arg( 'cat_id', $cat->term_id, site_url( '/documents/' )));
				$category_lists[$key]['cat_icon'] = ($cat_icon) ? $cat_icon : 'https://demo11.axxiem.com/wp-content/plugins/axxiem-shared-document/images/Folder-Secure-icon.png';
				$category_lists[$key]['cat_name'] = $cat->name;
			}
		}
		
		$query = '';
		if (!empty($role) && !in_array($role, array('FM Admin','Administrator','Keymaster'))) {
			$query .= " where role_type = '$role' ";
		}
		$sql = "SELECT post_id from {$wpdb->prefix}axxiem_shared_document_views {$query}  order by id desc limit 5";
		$resultRows = $wpdb->get_results($sql);
		$post_ids = [];
		if (!empty($resultRows)) {
			foreach($resultRows as $result) {
				$post_ids[] = $result->post_id;
			}
		}
		$args = [];
		if($post_ids) {
			$args = array(
				'post_type' => 'shared-documents',
				'post__in' => $post_ids,
				'orderby' => 'post__in'
			);
		}
		$posts_query = new WP_Query( $args );
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$recent_document[$key]['id'] = $post->ID;
				$recent_document[$key]['title'] = $post->post_title;
				$upload_file_array = get_field( 'upload_file',$post->ID);
				$recent_document[$key]['mediaObject'] = array('url'=>$upload_file_array['url'], 'mime_type'=>$upload_file_array['mime_type'],'filename'=>$upload_file_array['filename'],'filesize'=>(new Axxiem_REST_API_Endpoints())->sizeFormat($upload_file_array['filesize']));
			}
		}
		
		$args = array(
			'post_type' => 'shared-documents',
			'order' => 'DESC',
			'orderby' => 'date',
			'posts_per_page' => 5, // Retrieve all posts
		);
		if (!empty($role) && !in_array($role, array('FM Admin','Administrator','Keymaster'))) {
			$meta_query['meta_query'] = array(
				'relation' => 'AND',
				array(
					 'relation' => 'AND',
					   array(
							'key'     => 'assign_to',
							'compare' => 'EXISTS', // Ensure the custom field exists
					   ),
					   array(
							'key'     => 'assign_to',
							'value'   =>  $role,
							'compare' => 'LIKE',
					   )
				),
				array(
					'relation' => 'AND',
					 array(
							'key' => 'expiry_date_of_featured_document',
							'value' => date("Ymd"),
							'compare' => '>=',
							'type' => 'DATE',
					  ),
					  array(
							'key'=>'featured_document',
							'value'=>'Yes',
							'compare' => '='
						),
				),
			);
			$args = array_merge($args, $meta_query);
		}
		$posts_query = new WP_Query( $args );
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$featured_document[$key]['id'] = $post->ID;
				$featured_document[$key]['title'] = $post->post_title;
				$upload_file_array = get_field( 'upload_file',$post->ID);
				$featured_document[$key]['mediaObject'] = array('url'=>$upload_file_array['url'], 'mime_type'=>$upload_file_array['mime_type'],'filename'=>$upload_file_array['filename'],'filesize'=>(new Axxiem_REST_API_Endpoints())->sizeFormat($upload_file_array['filesize']));
			}
		}
		
		$weeklyNotes = array();
		$tax_query = array(
			array(
				'taxonomy' => 'adapp_posting_category',
				'field'    => 'slug',
				'terms'    => 'weekly-notes', // or use term ID
			),
		);
		$args = array(
			'post_type' => 'adapp_postings',
			'tax_query'      => $tax_query,
			'order' => 'desc',
			'orderby' => 'date',
			'posts_per_page' => 5
		);
		$posts_query = new WP_Query( $args );
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$post_id = $post->ID;
				$weeklyNotes[$key]['id'] = $post_id;
				$weeklyNotes[$key]['title'] = $post->post_title;
				$weeklyNotes[$key]['author_name'] = get_the_author_meta('display_name', $post->post_author);
				$weeklyNotes[$key]['description'] = $post->post_content;
				$weeklyNotes[$key]['publish_date'] = get_the_date('F j, Y' , $post_id);
				
				$attachments = [];
				$attachment_ids = get_post_meta( $post->ID, 'wp_custom_attachment', true );
				if (!empty($attachment_ids)) {
					foreach ($attachment_ids as $key=>$id) {
						$url = wp_get_attachment_url( $id );
						if ($url) {
							$attachments[$key] = $url;
						}
					}
				}
				$weeklyNotes[$key]['attachments'] = $attachments;
			}
		}
		
		$response['results'] = array('categories' => $category_lists, 'posts' => $postArray, 'recent_document' => $recent_document, 'featured_document' => $featured_document, 'weekly-notes'=>$weeklyNotes);
		$response['status'] = 1;
		$response['message'] = 'Post listed successfully';
		return $response;	
	}
	
	public static function axxiemPostRecentDocument($request) {
		global $wp_roles, $wpdb;
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		$post_id = $request["file_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		$role = '';
		$user = get_user_by('id', $user_id);
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$role = $wp_roles->roles[$role]['name'];
		
		$table_name = $wpdb->prefix . "axxiem_shared_document_views";
		$insert_data = array(
			'post_id' =>$post_id,
			'role_type' => $role,
			'created_date' => date('Y-m-d h:i:s'),
			'user_id' => $user_id
		);
		$wpdb->insert($table_name,$insert_data);
		$response['status'] = 1;
		$response['message'] = 'Post saved successfully';
		return $response;
	}
	public static function axxiemPosts($request) {
		global $wp_roles;
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;

		$user_id = $request["user_id"];
		$token = $request["token"];
		$paged = !empty($request["page"]) ? $request["page"] : 0;
		$limit = !empty($request["limit"]) ? $request["limit"] : 10;
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		
		$args = array(
			'post_type'      => 'adapp_postings',
			'posts_per_page' => $limit,
			'paged'          => $paged,
			'meta_query'     => array(
				array(
					'key'     => '_is_pinned',
					'value'   => '1',
					'compare' => '='
				)
				// 👉 Add other filters like Choose_Role or dynamic_key here if needed
			),
			'orderby' => array(
				'menu_order' 	   => 'DESC',
				'date'             => 'DESC'   // Then by date
			)
		);
		
		$posts_query = new WP_Query( $args );
		$response = $postArray = array();
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$post_id = $post->ID;
				$postArray[$key]['id'] = $post_id;
				$postArray[$key]['title'] = $post->post_title;
				$postArray[$key]['author_name'] = get_the_author_meta('display_name', $post->post_author);
				$postArray[$key]['description'] = $post->post_content;
				$postArray[$key]['_is_pinned'] = get_post_meta( $post->ID, '_is_pinned', true );
				$postArray[$key]['publish_date'] = get_the_date('F j, Y' , $post_id);
				
				$attachments = [];
				$attachment_ids = get_post_meta( $post->ID, 'wp_custom_attachment', true );
				if (!empty($attachment_ids)) {
					foreach ($attachment_ids as $key=>$id) {
						$url = wp_get_attachment_url( $id );
						if ($url) {
							$attachments[$key] = $url;
						}
					}
				}
				$postArray[$key]['attachments'] = $attachments;
			}
		}
		
		$non_pinned_args = array(
			'post_type'      => 'adapp_postings',
			'posts_per_page' => $limit - count($postArray),
			'paged'          => $paged,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					'key'     => '_is_pinned',
					'value'   => '1',
					'compare' => '!='
				),
				array(
					'key'     => '_is_pinned',
					'compare' => 'NOT EXISTS'
				)
			),
			'orderby'        => array(
				'menu_order' => 'DESC',
				'date'       => 'DESC'
			)
		);
		$non_pinned_posts = new WP_Query($non_pinned_args);

		if (!empty($non_pinned_posts->have_posts())) { 
			$posts = $non_pinned_posts->posts;
			$keyCount = count($postArray);
			foreach($posts as $key => $post) {
				$post_id = $post->ID;
				$postArray[$keyCount]['id'] = $post_id;
				$postArray[$keyCount]['title'] = $post->post_title;
				$postArray[$keyCount]['author_name'] = get_the_author_meta('display_name', $post->post_author);
				$postArray[$keyCount]['description'] = $post->post_content;
				$postArray[$keyCount]['_is_pinned'] = get_post_meta( $post->ID, '_is_pinned', true );
				$postArray[$keyCount]['publish_date'] = get_the_date('F j, Y' , $post_id);
				
				$attachments = [];
				$attachment_ids = get_post_meta( $post->ID, 'wp_custom_attachment', true );
				if (!empty($attachment_ids)) {
					foreach ($attachment_ids as $key=>$id) {
						$url = wp_get_attachment_url( $id );
						if ($url) {
							$attachments[$key] = $url;
						}
					}
				}
				$postArray[$keyCount]['attachments'] = $attachments;
				$keyCount++;
			}
		}
		
		$combined_posts = $posts_query->found_posts + $non_pinned_posts->found_posts;
		
		$response['results'] = array('posts' => $postArray,'total_post' => $combined_posts , 'limit'=>$limit);
		$response['status'] = 1;
		$response['message'] = 'Post listed successfully';
		return $response;
	}
	
	public static function axxiemCreateSharedDocument($request) {
		ini_set('max_execution_time', 999999);
		ini_set('memory_limit','999999M');
		ini_set('upload_max_filesize', '500M');
		ini_set('max_input_time', '-1');
		ini_set('max_execution_time', '-1');
		ini_set('file_uploads', 'On');
		ini_set('post_max_size', '25M');
		ini_set('upload_max_size', '20M');
		
		header("Acess-Control-Allow-Origin: *");
		header("Acess-Control-Allow-Methods: POST");

		$user_id = $_POST["user_id"];
		$token = $_POST["token"];
		$title = $_POST["title"];
		$sent_to = !empty($_POST['choose_role']) ? $_POST['choose_role'] : '';
		$is_pinned = !empty($_POST['featured_document']) ? $_POST['featured_document'] : 'No';
		$expiry_date = !empty($_POST['expiry_date']) ? $_POST['expiry_date'] : '';
		$category = !empty($_POST['category']) ? array_map('intval', array_map('trim', explode(',', $_POST['category']))) : [];
		$files = !empty($_FILES) ?  $_FILES['files'] : '';
		$response = array();
		if (empty($user_id) || empty($token) || empty($title)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		if ($title) {	
			$post_id = wp_insert_post(array (
				'post_type' => 'shared-documents',
				'post_title' => $title,
				'post_author' => $user_id,
				'post_status' => 'publish'
			));
			if ($post_id) {
				update_post_meta($post_id, 'featured_document', $is_pinned);
				if ($expiry_date) {
					update_post_meta($post_id, 'expiry_date_of_featured_document', $expiry_date);
				}
				if ($sent_to) {
					update_post_meta($post_id, 'assign_to',  array_map('trim', explode(',', $sent_to)));
				}
				if ($category) {
					wp_set_object_terms($post_id, $category, 'shared-document-category');
				}
				
				if ($files) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';
					
					if(!empty($files['tmp_name'])){
						$file = array(
						  'name'     => $files['name'],
						  'type'     => $files['type'],
						  'tmp_name' => $files['tmp_name'],
						  'error'    => $files['error'],
						  'size'     => $files['size']
						);

						$file_return = wp_handle_upload( $file, array('test_form' => false ) );
						$filename = $file_return['file'];

						$attachment = array(
							'post_mime_type' => $file_return['type'],
							'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
							'post_content' => '',
							'post_status' => 'inherit',
							'guid' => $file_return['url']
						);
						$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
						update_post_meta( $post_id, 'upload_file', $attachment_id );
						update_post_meta( $post_id, '_upload_file', 'field_6699373cd8b17' );
					}	
				}
				$response['status'] = 1;
				$response['message'] = "Adapp Post created successfully";
				}
		} else {
			$response['status'] = 0;
			$response['message'] = "Title is required field";
		}
		return $response;
	}
	public static function getRoles(){
		
		$response['roles'] = array('staff'=>'Staff', 'supervisor'=>'Supervisor', 'admins'=>'FM Admin', 'hr'=>'Human Resources', 'coalition_staff'=>'Coalition Staff');
		$response['status'] = 1;
		$response['message'] = 'File listed successfully';
		
		return $response;
	}
	
	public static function getUsers( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
			return $response;
		}
		$role = $request["role"];
		$users = array();
		$all_users = get_users( [ 'role__in' => [$role] ] );
		if($all_users) {
			foreach($all_users as $k=>$user) {
				$users[$k]['id'] = $user->data->ID;
				$users[$k]['name'] = $user->data->display_name;
			}
		}
		$response['user_list'] = $users;
		$response['status'] = 1;
		$response['message'] = "User listed Successfully";
		return $response;
	}
	
	public static function getChatUsersList( $request ){
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
			return $response;
		}
		
		$args = array(
			'meta_query' => array(
				array(
					'key'     => 'uID',
					'compare' => 'EXISTS'
				),
			),
		);
		$all_users = get_users( $args );
		if($all_users) {
			foreach($all_users as $k=>$user) {
				$users[$k]['id'] = $user->data->ID;
				$users[$k]['name'] = $user->data->display_name;
				$uID = get_user_meta($user->ID, 'uID', true);
				$users[$k]['uid'] = ($uID) ? $uID : '';
				$simple_local_avatar = get_field('image_avatar', $user);
				$users[$k]['avatar'] = !empty($simple_local_avatar) ? $simple_local_avatar : '';
			}
		}
		$response['user_list'] = $users;
		$response['status'] = 1;
		$response['message'] = "User listed Successfully";
		
		return $response;
	}
	public static function fileManager( $request ){
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		$token = $request["token"];
		$directory_path = !empty($request["directory_path"]) ? $request["directory_path"]: '';
		$user_type = !empty($request["user_type"]) ? $request["user_type"] : 0;
		$dir_user_id = !empty($request["dir_user_id"]) ? $request["dir_user_id"] : 0;
		
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		/*
		$validateToken = (new Axxiem_REST_API_Endpoints())->validate_token ( $user_id, $token );
		if (!$validateToken) {
			return new WP_Error(
                'token_auth_failed',
                __('Invalid Tokem', 'wp-api-token-auth'),
                array(
                    'status' => 403,
                )
            );
		} */
			
		if ($response['status'] === 0) {
			return $response;
		}	
		
		$user = get_user_by('id', $user_id);
		// Get all the user roles as an array.
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$user_name = $user->data->user_login;
		$arrFiles = $folder_info = [];
		
		if (empty($directory_path)) {
			if ($role === 'admins') {
				
				$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro';
				$fileInfo     = array_diff(scandir($file_path_scan), array('..', '.', '.quarantine', '.tmb','fm_backup'));
				
				$i = $j = 0;
				foreach ($fileInfo as $folder) {			
					if (is_dir($file_path_scan . DIRECTORY_SEPARATOR . $folder) === true ) {					
						$dir_path = $file_path_scan . DIRECTORY_SEPARATOR . $folder;
						$path_parts = pathinfo($dir_path);
						$user_type = ($path_parts['filename']== 'Shared_Documents') ? 2 : 1; 
						$folder_info['directory'][$i]['path'] = $fetch_dir_path. '/' .$path_parts['filename'];
						$folder_info['directory'][$i]['filename'] = $path_parts['filename'];
						$folder_info['directory'][$i]['user_type'] = $user_type;
						$folder_info['directory'][$i]['modified'] = date("F d Y H:i:s",filemtime($dir_path. '/.'));
						$folder_info['directory'][$i]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($dir_path));
						$i++;
						
					} else {
						$file_path = $file_path_scan .'/' . $folder;
						$path_parts = pathinfo($file_path);
						
						$folder_info['files'][$j]['path'] = str_replace('/var/www/vhosts/demo11.axxiem.com/httpdocs',site_url(),$file_path_scan .'/' . $folder);
						$folder_info['files'][$j]['filename'] = $path_parts['filename'];
						$folder_info['files'][$j]['modified'] = date("F d Y H:i:s",filemtime($file_path));
						$folder_info['files'][$j]['user_type'] = 1;
						$folder_info['files'][$j]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat(filesize($file_path));
						$j++;
					}	
				}
				
			    $k = key( array_slice( $folder_info['directory'], -1, 1, TRUE ) ) + 1;

				$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/' . $user_name;
				$path_parts = pathinfo($file_path_scan);
				
				$folder_info['directory'][$k]['path'] = 'users/' . $path_parts['filename'];
				$folder_info['directory'][$k]['filename'] = $path_parts['filename'];
				$folder_info['directory'][$k]['user_type'] = 0;
				$folder_info['directory'][$k]['modified'] = date("F d Y H:i:s",filemtime($file_path_scan. '/.'));
				$folder_info['directory'][$k]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($file_path_scan)); $folder_info['directory'][$k]['dir_user_id'] = $user_id; 
				
			} else {
				$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/' . $user_name;
				$path_parts = pathinfo($file_path_scan);
				
				$folder_info['directory'][0]['path'] = 'users/' . $path_parts['filename'];
				$folder_info['directory'][0]['filename'] = $path_parts['filename'];
				$folder_info['directory'][0]['user_type'] = 0;
				$folder_info['directory'][0]['modified'] = date("F d Y H:i:s",filemtime($file_path_scan. '/.'));
				$folder_info['directory'][0]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($file_path_scan));$folder_info['directory'][0]['dir_user_id'] = $user_id;
				$k = count($folder_info['directory']);
				
			}
			
			if ($role === 'supervisor') {
				$args  = array(
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'reporting_to',
							'value'   => $user_id,
							'compare' => '='
						),
						array(
							'relation' => 'AND',
							array(
								'key'     => 'wp_capabilities',
								'value'   => 'a:0:{}',
								'compare' => '!='
							),
							array(
								'key'     => 'wp_capabilities',
								'value'   => '""', // users with no role at all (optional, if needed)
								'compare' => '!='
							),
						)
					)
				);
				$reporting_staff_users = new WP_User_Query( $args );
				
				if ( ! empty( $reporting_staff_users->results ) ) {
					$staffFiles = [];
					foreach($reporting_staff_users->results as $st_user){
						$user_name = $st_user->data->user_login;					
						$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/' . $user_name;
						
						$path_parts = pathinfo($file_path_scan);
						
						$folder_info['directory'][$k]['path'] = 'users/' . $path_parts['filename'];
						$folder_info['directory'][$k]['filename'] = $path_parts['filename'];
						$folder_info['directory'][$k]['user_type'] = 1;
						$folder_info['directory'][$k]['modified'] = date("F d Y H:i:s",filemtime($file_path_scan. '/.'));
						$folder_info['directory'][$k]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($file_path_scan));
						$folder_info['directory'][$k]['dir_user_id'] = $st_user->ID;
						
						$k++;
					}
				}
			}elseif ($role === 'hr') {
				$args  = array(
					'meta_query' => array(
						'relation' => 'AND',
						array(
							'key'     => 'reporting_to',
							'value'   => $user_id,
							'compare' => '='
						),
						array(
							'relation' => 'AND',
							array(
								'key'     => 'wp_capabilities',
								'value'   => 'a:0:{}',
								'compare' => '!='
							),
							array(
								'key'     => 'wp_capabilities',
								'value'   => '""', // users with no role at all (optional, if needed)
								'compare' => '!='
							),
						)
					)
				);
				$reporting_hr_staff_users = new WP_User_Query( $args );
				if ( ! empty( $reporting_hr_staff_users->results ) ) {
					$reporting_staff_users_arr = [];
					foreach($reporting_hr_staff_users->results as $reporting_hr_user){
						$user_name = $reporting_hr_user->data->user_login;					
						$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/' . $user_name;
						
						$path_parts = pathinfo($file_path_scan);
						
						$folder_info['directory'][$k]['path'] = 'users/' . $path_parts['filename'];
						$folder_info['directory'][$k]['filename'] = $path_parts['filename'];
						$folder_info['directory'][$k]['user_type'] = 1;
						$folder_info['directory'][$k]['modified'] = date("F d Y H:i:s",filemtime($file_path_scan. '/.'));
						$folder_info['directory'][$k]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($file_path_scan));
						$folder_info['directory'][$k]['dir_user_id'] = $reporting_hr_user->ID;
						$k++;
					}
				}
				$all_users = get_users( [ 'role__in' => ['staff','supervisor','hr','admins', 'coalition_staff'] ] );
				
				$k = count($folder_info['directory']);
				if ( ! empty( $all_users ) ) {
					foreach($all_users as $st_user){							
						$user_name = $st_user->data->user_login;					
						$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/' . $user_name;
						
						$path_parts = pathinfo($file_path_scan);
						
						$folder_info['directory'][$k]['path'] = 'users/' . $path_parts['filename'];
						$folder_info['directory'][$k]['filename'] = $path_parts['filename'];
						$folder_info['directory'][$k]['user_type'] = 1;
						$folder_info['directory'][$k]['modified'] = date("F d Y H:i:s",filemtime($file_path_scan. '/.'));
						$folder_info['directory'][$k]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($file_path_scan));
						$folder_info['directory'][$k]['dir_user_id'] = $st_user->ID;
						$k++;							
					}
				}
				
			}
			
			$arrFiles = array_merge($arrFiles,$folder_info);
		} else {
			if ($dir_user_id === 0) {
				if (strpos($directory_path, 'users') !== false){
					
					$dir_user_array = explode("users/", $directory_path);
					if(isset($dir_user_array[1])) {
						$dir_user_array1 = explode("/", $dir_user_array[1]);
						$user = get_user_by('login', $dir_user_array1[0]);
						$dir_user_id = $user->ID;
					}
				}
			}
			
			$fetch_dir_path = $directory_path;
			$directory_path = ABSPATH .'wp-content/uploads/wp-file-manager-pro/'. $directory_path;
			$fileInfo     = array_diff(scandir($directory_path), array('..', '.', '.quarantine', '.tmb'));
			$restrict_dir = $allFileLists = [];
			
			if( !empty($user_type) && ($user_type == 1 || $user_type == 2)) {
				if ($role === 'supervisor') {
					$restrict_dir = array('hr');
				}elseif ($role === 'hr') {
					$restrict_dir = array('adapp_supervisor_information');
				}elseif ($role === 'admins') {
					$restrict_dir = array('hr');
				}else{
					$restrict_dir = array('adapp_supervisor_information');
				}			
			}
			
			$args = [
				'meta_query' => [
					'relation' => 'OR',
					[
						'key'     => 'wp_capabilities',
						'value'   => 'a:0:{}',
						'compare' => '='
					],
					[
						'key'     => 'wp_capabilities',
						'value'   => '""',
						'compare' => '='
					]
				]
			];
			$user_query = new WP_User_Query($args);
			if (!empty($user_query->get_results())) {
				foreach ($user_query->get_results() as $user) {							
					$user_name = $user->user_login;
					array_push($restrict_dir, $user_name);
				}
			}		
			
			$i = $j = 0;
			if ($fileInfo ) {
				foreach ($fileInfo as $folder) {			
					if (is_dir($directory_path . DIRECTORY_SEPARATOR . $folder) === true ) {					
						if (is_array($restrict_dir) && !in_array($folder,$restrict_dir)){
							$dir_path = $directory_path . DIRECTORY_SEPARATOR . $folder;
							$path_parts = pathinfo($dir_path);
							$allFileLists['directory'][$i]['path'] = $fetch_dir_path. '/' .$path_parts['filename'];
							$allFileLists['directory'][$i]['filename'] = $path_parts['filename'];
							$allFileLists['directory'][$i]['user_type'] = $user_type;
							$allFileLists['directory'][$i]['modified'] = date("F d Y H:i:s",filemtime($dir_path. '/.'));
							$allFileLists['directory'][$i]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($dir_path));
							$allFileLists['directory'][$i]['dir_user_id'] = $dir_user_id;
							$i++;
						}
					} else {
						$file_path = $directory_path .'/' . $folder;
						$path_parts = pathinfo($file_path);

						$allFileLists['files'][$j]['path'] = str_replace('/var/www/vhosts/demo11.axxiem.com/httpdocs',site_url(),$directory_path .'/' . $folder);
						$allFileLists['files'][$j]['filename'] = $path_parts['filename'];
						$allFileLists['files'][$j]['modified'] = date("F d Y H:i:s",filemtime($file_path));
						$allFileLists['files'][$j]['user_type'] = $user_type;
						$allFileLists['files'][$j]['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat(filesize($file_path));
						$allFileLists['files'][$j]['dir_user_id'] = $dir_user_id;
						$j++;
					}	
				}
				$arrFiles = array_merge($arrFiles,$allFileLists);
			}
						
		}
		if ($role === 'hr' || $role === 'supervisor' || $role === 'staff' || $role === 'coalition_staff') {
			$rules = array (0 => 'upload');
		}else{
			$rules = array (0 => 'upload', 1 => 'delete', 2 => 'create', 3=>'rename');
		}
		
		
		$response['rules'] = $rules;
		$response['file_manager'] = !empty($arrFiles) ? $arrFiles : (object) [];
		$response['status'] = 1;
		$response['message'] = 'File listed successfully';
		
		return $response;
		
	}
	
	public static function directorySearch($request) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_id = $request["user_id"];
		$token = $request["token"];
		$keywords = !empty($request["keywords"]) ? $request["keywords"] : '';
		
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if ($response['status'] === 0) {
			return $response;
		}
		
		$user = get_user_by('id', $user_id);
		// Get all the user roles as an array.
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$restrict_dir = [];			
		if ($role === 'supervisor') {
			$restrict_dir = array('hr');
		}elseif ($role === 'hr') {
			$restrict_dir = array('adapp_supervisor_information');
		}elseif ($role === 'fm_admin') {
			$restrict_dir = array('hr');
		}else{
			$restrict_dir = array('adapp_supervisor_information');
		}	
		
		
		//$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/'. $directory_path;
		$file_path_scan = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users';
		$search_file = (new Axxiem_REST_API_Endpoints())->listFolderFiles($file_path_scan, $keywords, $restrict_dir);	
		$search_dir = (new Axxiem_REST_API_Endpoints())->list_folders($file_path_scan, $keywords, $restrict_dir);
		$arrFiles = array('directory'=>$search_dir,'files'=>$search_file);
		$response['file_manager'] = !empty($arrFiles) ? $arrFiles : (object) [];
		$response['status'] = 1;
		$response['message'] = 'File listed successfully';
		
		return $response;
	}
	public static function search($request) {
		global $wp_roles;
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;

		$user_id = $request["user_id"];
		$token = $request["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		
		$role = '';
		$user = get_user_by('id', $user_id);
		$user_roles = $user->roles;
		$role = $user_roles[0];
		$role = $wp_roles->roles[$role]['name'];
		
		$cat 		= ! empty(  $request['filterByCat'] ) 	? $request['filterByCat'] 	: '';
		$keywords 	= ! empty(  $request['keywords'] ) 	? $request['keywords'] 	: '';
    	$sortBy 	= ! empty( $request['sortBy'] ) 		? $request['sortBy'] 		: 'title';
	
		$post_type = 'shared-documents';
		$args = array ( 
			'post_type'			=> $post_type,
			'post_status'		=> 'publish',
			'posts_per_page'	=> -1
		);
	
		if ($sortBy == 'date') {
			$sortBYFilter = array('orderby' => 'date', 'order' => 'DESC');
		} else {
			$sortBYFilter = array('order' => 'ASC', 'orderby' => 'title');
		}
		$args = array_merge($args, $sortBYFilter);

		if( $keywords != "") {
			$args = array_merge($args,array('s' => $keywords));
		}	
	
		if( $cat != "" ) {
			$cat_args['tax_query'] = array(
				array(
					'taxonomy'  => 'shared-document-category',
					'field'     => 'term_id',
					'terms'     => $cat,
					'include_children' => false, 
				)
			);
			$args = array_merge($args, $cat_args);
			$term = get_term( $cat, 'shared-document-category' );
		}

		if (!empty($role) && !in_array($role, array('FM Admin','Administrator','Keymaster'))) {
			$meta_query = array('meta_query' => array(
					array(
						'key'     => 'assign_to',
						'value'   => sprintf(':"%s";', $role),
						'compare' => 'LIKE',
					),
				),
			);
			$args = array_merge($args, $meta_query);
		}
	
		if( !empty($keywords) && !empty($cat)) {
			$all_cats_list = get_terms(array_merge($sortBYFilter,array(
				'taxonomy' => 'shared-document-category', // Adjust the taxonomy if necessary
				'name__like' => $keywords,
				'include' => array($cat),
				'hide_empty' => false,
			)));
		} else if ($keywords) {
			$all_cats_list = get_terms(array_merge($sortBYFilter,array(
				'taxonomy' => 'shared-document-category', // Adjust the taxonomy if necessary
				'name__like' => $keywords,
				'hide_empty' => false,
			)));
		} else if ($cat) {
			$all_cats_list = get_terms(array_merge($sortBYFilter,array(
				'taxonomy' => 'shared-document-category', // Adjust the taxonomy if necessary
				'parent' => $cat,
				'hide_empty' => false,
			)));
		}

		if (empty($keywords) && empty($cat)) {
			$all_cats_list = get_terms(array_merge($sortBYFilter,array(
				'taxonomy' => 'shared-document-category',
				'parent' => 0,
				'hide_empty' => false,
			)));
			$args = array_merge($args , array('tax_query' => array(
				array(
					'taxonomy' => 'shared-document-category',
					'operator' => 'NOT EXISTS',
				),
			)));
			if(!in_array($role, array('FM Admin','Administrator','Keymaster'))) {
				$meta_query = array('meta_query' => array(
					array(
						'key'     => 'assign_to',
						'value'   => sprintf(':"%s";', $role),
						'compare' => 'LIKE',
						),
					),
				);
				$args = array_merge($args, $meta_query);
			}
		}
	
		$categories = [];
		$support_role = array('FM Admin','Administrator','Keymaster');
		if (in_array($role, $support_role)) {
			$categories = $all_cats_list;
		} else {
			if ($all_cats_list) {
				foreach ($all_cats_list as $category) {
					$category_role =  get_field('choose_role', $category);
					if (!empty($category_role) && in_array($role, $category_role)) {
						$categories[] = $category;
					}
				}
			}
		}
		$posts_query = new WP_Query( $args );
		$category_lists = [];
		$files = [];
		if(!empty($categories)) { 
			foreach($categories as $key=>$shared_cat) {
				$category_lists[$key]['cat_id'] = $shared_cat->term_id;
				$category_lists[$key]['cat_icon'] = 'https://demo11.axxiem.com/wp-content/plugins/axxiem-shared-document/images/Folder-Secure-icon.svg';
				$category_lists[$key]['name'] = $shared_cat->name;
			} 
		}
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key=>$post) {
				$post_id = $post->ID;
				$upload_file_array = get_field( 'upload_file',$post_id);
				$files[$key]['file_id'] = $post_id;
				$files[$key]['file_name'] = $post->post_title;
				$files[$key]['file_path'] = $upload_file_array['url'];
			}
		}
		$trail_breadcrumb = [];
		
		if($term){
			if ($term->parent == 0) {
				$trail_breadcrumb['name'] = $term->name;
				$trail_breadcrumb['cat_id'] = $term->term_id;
			} else {
				//$trail_breadcrumb['name'] = $term->name;
				//$trail_breadcrumb['cat_id'] = $term->term_id;
				$trail_breadcrumb = (new Axxiem_REST_API_Endpoints())->get_category_breadcrumb($term->term_id);
			}
		}
		$response['results'] = array('categories' => $category_lists, 'files' => $files, 'breadcrumb'=> $trail_breadcrumb);
		$response['status'] = 1;
		$response['message'] = 'Post listed successfully';
		return $response;
	}
	
	
	public static function createPost($request) {
		ini_set('max_execution_time', 999999);
		ini_set('memory_limit','999999M');
		ini_set('upload_max_filesize', '500M');
		ini_set('max_input_time', '-1');
		ini_set('max_execution_time', '-1');
		ini_set('file_uploads', 'On');
		ini_set('post_max_size', '25M');
		ini_set('upload_max_size', '20M');
		
		header("Acess-Control-Allow-Origin: *");
		header("Acess-Control-Allow-Methods: POST");

		$user_id = $_POST["user_id"];
		$token = $_POST["token"];
		$response = array();
		if (empty($user_id) || empty($token)) {
			$response['status'] = 0;
			$response['message'] = "Required field missing";
		}
		
		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
		
		$role = '';
		$user = get_user_by('id', $user_id);
		$user_roles = $user->roles;
		$role = $user_roles[0];
		
		$title = $_POST["title"];
		$description = !empty($_POST['content']) ? $_POST['content'] : '';
		$sent_to = !empty($_POST['choose_role']) ? $_POST['choose_role'] : '';
		$sent_to_staff = !empty($_POST['sent_to_staff']) ? $_POST['sent_to_staff'] : '';
		$sent_to_supervisior = !empty($_POST['sent_to_staff']) ? $_POST['sent_to_supervisior'] : '';
		$sent_to_hr = !empty($_POST['sent_to_hr']) ? $_POST['sent_to_hr'] : '';
		$fm_admin = !empty($_POST['fm_admin']) ? $_POST['fm_admin'] : '';
		$coalition_staff = !empty($_POST['coalition_staff']) ? $_POST['coalition_staff'] : '';
		$is_pinned = !empty($_POST['_is_pinned']) ? $_POST['_is_pinned'] : 0;
		$weekly_notes = !empty($_POST['weekly_notes']) ? $_POST['weekly_notes'] : '';
		
		if ($title) {	
			$post_id = wp_insert_post(array (
				'post_type' => 'adapp_postings',
				'post_title' => $title,
				'post_content' => $description,
				'post_author' => $user_id,
				'post_status' => 'publish',
				'comment_status' => 'closed',   // if you prefer
				'ping_status' => 'closed',      // if you prefer
			));
			
			if ($post_id) {
				update_post_meta($post_id, '_is_pinned', $is_pinned);
				if ($weekly_notes) {
					wp_set_object_terms($post_id, 'weekly-notes', 'adapp_posting_category');
				}
				if ($sent_to) {
					update_post_meta($post_id, 'Choose_Role',  array_map('trim', explode(',', $sent_to)));
				}
				if ($sent_to_staff) {
					update_post_meta($post_id, 'staff',  array_map('trim', explode(',', $sent_to_staff)));
				}
				if ($sent_to_supervisior) {
					update_post_meta($post_id, 'supervisor',  array_map('trim', explode(',', $sent_to_supervisior)));
				}
				if ($fm_admin) {
					update_post_meta($post_id, 'fm_admin',  array_map('trim', explode(',', $fm_admin)));
				}
				if ($sent_to_hr) {
					update_post_meta($post_id, 'human_resources',  array_map('trim', explode(',', $sent_to_hr)));
				}
				if ($coalition_staff) {
					update_post_meta($post_id, 'coalition_staff',  array_map('trim', explode(',', $coalition_staff)));
				}
				
				$files = !empty($_FILES) ?  $_FILES['files'] : '';
				if ($files) {
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';
					require_once ABSPATH . 'wp-admin/includes/image.php';
					
					$all_uploads = [];
					for($i=0; $i< count($files['name']); $i++){
						if(!empty($files['tmp_name'][$i])){
							$file = array(
							  'name'     => $files['name'][$i],
							  'type'     => $files['type'][$i],
							  'tmp_name' => $files['tmp_name'][$i],
							  'error'    => $files['error'][$i],
							  'size'     => $files['size'][$i]
							);

							$file_return = wp_handle_upload( $file, array('test_form' => false ) );
							$filename = $file_return['file'];

							$attachment = array(
								'post_mime_type' => $file_return['type'],
								'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
								'post_content' => '',
								'post_status' => 'inherit',
								'guid' => $file_return['url']
							);
							$attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
							$all_uploads[] = $attachment_id;
						}
					}
					update_post_meta($post_id, 'wp_custom_attachment', $all_uploads);
				}
				$response['status'] = 1;
				$response['message'] = "Adapp Post created successfully";
			}
		} else {
			$response['status'] = 0;
			$response['message'] = "Title is required field";
		}
		return $response;
	}
	/**
     * Retrieve the user's token
     *
     * @access private
     * @since  1.0.0
     * @param  int $user_id
     * @return string
     */
    public function get_token( $user_id = 0 ) {
        return hash( 'md5', $this->get_user_secret_key( $user_id ) . $this->get_user_public_key( $user_id ) );
    }
	
	public static function getToken( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		$user_id = $request["user_id"];
		
		if (empty($user_id)) {			
			$response['status'] = 0;
			$response['message'] = "Directory Required";
		}

		if ($response['status'] === 0) {
			return $response;
		}
		
		$token              = (new Axxiem_REST_API_Endpoints())->get_token( $user_id );			
		$response['status'] = 1;
		$response['message'] = "Token List Successfully";
		$response['token']      = $token;
		return $response;
	}
	
	public static function weeklyNotes( $request ) {
		parse_str(file_get_contents("php://input"), $request_data);
		$request = !empty($request_data['data']) ? $request_data['data'] : $request_data;
		
		$user_id = $request["user_id"];
		$paged = !empty($request["page"]) ? $request["page"] : 0;
		$limit = !empty($request["limit"]) ? $request["limit"] : 10;
		
		$postArray = $response = array();
		
		if (empty($user_id)) {			
			$response['status'] = 0;
			$response['message'] = "User_id is Required";
		}

		if (isset($response['status']) && $response['status'] === 0) {
			return $response;
		}
			
		$tax_query = array(
			array(
				'taxonomy' => 'adapp_posting_category',
				'field'    => 'slug',
				'terms'    => 'weekly-notes', // or use term ID
			),
		);
		$args = array(
			'post_type' => 'adapp_postings',
			'tax_query'      => $tax_query,
			'order' => 'desc',
			'orderby' => 'date',
			'posts_per_page' => $limit, // Retrieve all posts
			'paged'	=> $paged
		);
		$posts_query = new WP_Query( $args );
		if (!empty($posts_query->have_posts())) { 
			$posts = $posts_query->posts;
			foreach($posts as $key => $post) {
				$post_id = $post->ID;
				$postArray[$key]['id'] = $post_id;
				$postArray[$key]['title'] = $post->post_title;
				$postArray[$key]['author_name'] = get_the_author_meta('display_name', $post->post_author);
				$postArray[$key]['description'] = $post->post_content;
				$postArray[$key]['publish_date'] = get_the_date('F j, Y' , $post_id);
				
				$attachments = [];
				$attachment_ids = get_post_meta( $post->ID, 'wp_custom_attachment', true );
				if (!empty($attachment_ids)) {
					foreach ($attachment_ids as $key=>$id) {
						$url = wp_get_attachment_url( $id );
						if ($url) {
							$attachments[$key] = $url;
						}
					}
				}
				$postArray[$key]['attachments'] = $attachments;
			}
		}
		$response['results'] = array('posts' => $postArray,'total_post' => $posts_query->found_posts, 'limit'=>$limit);
		$response['status'] = 1;
		$response['message'] = "Weekly Notes List Successfully";
		return $response;
	}
	
	public function get_user_public_key( $user_id = 0 ) {
        global $wpdb;

        if ( empty( $user_id ) ) {
            return '';
        }

        $cache_key       = md5( 'rest_api_token_auth_cache_user_public_key' . $user_id );
        $user_public_key = get_transient( $cache_key );

        if ( empty( $user_public_key ) ) {
            $user_public_key = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'rest_api_token_auth_public_key' AND user_id = %d", $user_id ) );
            set_transient( $cache_key, $user_public_key, DAY_IN_SECONDS * 15 );
        }

        return $user_public_key;
    }

    /**
     * 
     *
     * @param [type] $user_id [description]
     *
     * @return [type] [description]
     */
    public function get_user_secret_key( $user_id = 0 ) {
        global $wpdb;

        if ( empty( $user_id ) ) {
            return '';
        }

        $cache_key       = md5( 'rest_api_token_auth_cache_user_secret_key' . $user_id );
        $user_secret_key = get_transient( $cache_key );

        if ( empty( $user_secret_key ) ) {
            $user_secret_key = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM $wpdb->usermeta WHERE meta_key = 'rest_api_token_auth_secret_key' AND user_id = %d", $user_id ) );
            set_transient( $cache_key, $user_secret_key, DAY_IN_SECONDS * 15 );
        }

        return $user_secret_key;
    }
	
	private function validate_token ( $user_id, $token ) {
		$user_public_key = get_user_meta( $user_id, 'rest_api_token_auth_public_key',true);
		if (empty($user_public_key)) {
			return false;
		}

		$public_key = $this->get_user_public_key( $user_id );
		if ( ! ( $user = $this->get_user( $public_key ) ) ) {
			return false;
        } else {
			$token  = $token;
            $secret = $this->get_user_secret_key( $user_id );
            $public = $public_key;

            if ( hash_equals( md5( $secret . $public ), $token ) ) {
                return true;
            } else {
                return false;
            }
        }
	}
	
	private function generate_public_key( $user_email = '' ) {
        $auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
        $public   = hash( 'md5', $user_email . $auth_key . date( 'U' ) );
        return $public;
    }
	
	private function generate_private_key( $user_id = 0 ) {
        $auth_key = defined( 'AUTH_KEY' ) ? AUTH_KEY : '';
        $secret   = hash( 'md5', $user_id . $auth_key . date( 'U' ) );
        return $secret;
    }
	
	/**
     * Retrieve the user ID based on the public key provided
     *
     * @access public
     * @since 1.0.0
     * @global object $wpdb Used to query the database using the WordPress
     * Database API
     *
     * @param string $key Public Key
     *
     * @return bool if user ID is found, false otherwise
     */
    public function get_user( $key = '' ) {
        global $wpdb;

        if ( empty( $key ) ) {
            return false;
        }

        $user = get_transient( md5( 'rest_api_token_auth_cache_user_' . $key ) );

        if ( false === $user ) {
            $user = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'rest_api_token_auth_public_key' AND meta_value = %s LIMIT 1", $key ) );
            set_transient( md5( 'rest_api_token_auth_cache_user_' . $key ) , $user, DAY_IN_SECONDS * 15 );
        }

        if ( $user != NULL ) {
            $this->user_id = $user;
            return $user;
        }

        return false;
    }
	
	public function getuserInfo($user_id, $user = array()) {
		global $wpdb;
		
		$check = true;
		$table_name = $wpdb->prefix . "axxiem_user_devices";
		$userdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}axxiem_user_devices WHERE user_id = %d", $user_id ) );
		
		if ($userdata) {
			foreach ($userdata as $user_devices) {
				if (isset($user['device_id']) && ($user_devices->device_id == $user['device_id'])) {
					$dvice_status = $user_devices->force_status;
					$app_version = $user_devices->app_version;
					$version_code = $user_devices->version_code;
					$userdevice_id = $user_devices->id;
					$check = false;
					break;
				} else {
					$check = true;
				}
			}
		}
		
		if ($check) {
			$device_name = !empty($user['device_name']) ? $user['device_name'] : '';
			$os_version = !empty($user['os_version']) ? $user['os_version'] : '';
			
			$userdevice = [
				'user_id' => $user_id,
				'device_id' => $user['device_id'],
				'device_token' => $user['device_token'],
				'device_type' => $user['device_type'],
				'app_version' => $user['app_version'],
				'version_code' => $user['version_code'],
				'device_name' => $device_name,
				'os_version' => $os_version,
				'created' => date('Y-m-d H:i:s'),
				'updated' => date('Y-m-d H:i:s'),
				'force_status' => 0
			];
	
			$wpdb->insert($table_name,$userdevice);	
			
		}else{
			$device_name = !empty($user['device_name']) ? $user['device_name'] : '';
			$os_version = !empty($user['os_version']) ? $user['os_version'] : '';

			$device_data = [
				'device_token' => $user['device_token'],
				'device_type' => $user['device_type'],
				'app_version' => $user['app_version'],
				'version_code' => $user['version_code'],
				'device_name' => $device_name,
				'os_version' => $os_version,
				'force_status' => 0,
				'updated' => date('Y-m-d H:i:s'),
				'status' => 1
			];
			
			$wpdb->update($table_name,$device_data,array('id' => $userdevice_id));
		}		
	}
	
	public function folderSize($dir){
		$count_size = 0;
		$count = 0;
		$dir_array = scandir($dir);
		foreach($dir_array as $key=>$filename){
			if($filename!=".." && $filename!="."){
				if(is_dir($dir."/".$filename)){
					$new_foldersize = $this->foldersize($dir."/".$filename);
					$count_size = $count_size+ $new_foldersize;
				}else if(is_file($dir."/".$filename)){
					$count_size = $count_size + filesize($dir."/".$filename);
					$count++;
				}
			}
		}
		return $count_size;
	}

	public function sizeFormat($bytes){ 
		$kb = 1024;
		$mb = $kb * 1024;
		$gb = $mb * 1024;
		$tb = $gb * 1024;

		if (($bytes >= 0) && ($bytes < $kb)) {
			return $bytes . ' B';

		} elseif (($bytes >= $kb) && ($bytes < $mb)) {
			return ceil($bytes / $kb) . ' KB';

		} elseif (($bytes >= $mb) && ($bytes < $gb)) {
			return ceil($bytes / $mb) . ' MB';

		} elseif (($bytes >= $gb) && ($bytes < $tb)) {
			return ceil($bytes / $gb) . ' GB';

		} elseif ($bytes >= $tb) {
			return ceil($bytes / $tb) . ' TB';
		} else {
			return $bytes . ' B';
		}
	}
	
	private function listFolderFiles($dir, $keywords, $restrict_dir)
	{
		$fileInfo     = array_diff(scandir($dir), array('..', '.', '.quarantine', '.tmb'));
		$files = array();
		foreach ($fileInfo as $folder) {			
			if (!in_array($folder, $restrict_dir)) {
				if (is_dir($dir . DIRECTORY_SEPARATOR . $folder) === true ) {				
					$files2 = (new Axxiem_REST_API_Endpoints())->listFolderFiles($dir . DIRECTORY_SEPARATOR . $folder, $keywords, $restrict_dir);
					if ( $files2 )
						$files = array_merge($files, $files2 );
				} else {
					if (strpos($folder,$keywords)!==false) {
						$file_path = $dir . '/' . $folder;
						$path_parts = pathinfo($file_path);
						
						$allFileLists['path'] = str_replace('/var/www/vhosts/demo11.axxiem.com/httpdocs',site_url(),$dir . '/' . $folder);
						$allFileLists['filename'] = $path_parts['filename'];
						$allFileLists['modified'] = date("F d Y H:i:s",filemtime($file_path));
						$files[] = $allFileLists;
					}
				}				
			}
		}
		return $files;
	}
	
	function list_folders( $folder = '', $keywords, $restrict_dir, $levels = 100 ) {
		if ( empty($folder) )
			return false;

		if ( ! $levels )
			return false;

		$files = array();
		if ( $dir = @opendir( $folder ) ) {
			while (($file = readdir( $dir ) ) !== false ) {
				if ( in_array($file, array_merge($restrict_dir,array('..', '.', '.quarantine', '.tmb') ) ) )
					continue;
				if ( is_dir( $folder . '/' . $file ) ) {
					$files2 = (new Axxiem_REST_API_Endpoints())->list_folders( $folder . '/' . $file, $keywords, $restrict_dir,$levels - 1);
					if ( $files2 )
						$files = array_merge($files, $files2 );
					else {
						if (strpos($file,$keywords)!==false) {
							//$files[] = $folder . '/' . $file . '/';
							$path_dir = explode('wp-file-manager-pro/', $folder . '/' . $file);
							$path_parts = pathinfo($folder . '/' . $file);
							$dir_path['path'] = $path_dir[1];
							$dir_path['filename'] = $path_parts['filename'];
							$dir_path['user_type'] = 0;
							$dir_path['modified'] = date("F d Y H:i:s",filemtime($folder . '/' . $file. '/.'));
							$dir_path['size'] = (new Axxiem_REST_API_Endpoints())->sizeFormat((new Axxiem_REST_API_Endpoints())->folderSize($folder . '/' . $file));
							
							$files[] = $dir_path;
						}
					}
				}  
			}
		}
		@closedir( $dir );
		return $files;
	}
	
	function get_category_breadcrumb($category_id) {
		$breadcrumbArray = [];
		$taxonomy = 'shared-document-category';
		$parent = get_term( $category_id, $taxonomy );

		if (is_wp_error($parent)) {
			return '';
		}
		$breadcrumbArray['name'] = $parent->name;
		$breadcrumbArray['cat_id'] = $parent->term_id;
		if ($parent->parent) {
			$breadcrumbArray['parent'] = (new Axxiem_REST_API_Endpoints())->get_category_breadcrumb($parent->parent);
		}
		return $breadcrumbArray;
	}
}
add_action( 'rest_api_init', array( 'Axxiem_REST_API_Endpoints', 'register_endpoints' ) );
?>