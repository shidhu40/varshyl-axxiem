<?php
/**
 * Plugin Name:       Axxiem Shared Document
 * Description:       Axxiem Shared Document
 * Version:           1.0.0
 * Author: 			  Axxiem
 * Author URI:        
 * License:           GPLv2 or later
 * License URI: 	  http://www.gnu.org/licenses/gpl-2.0.html
 */


if(!defined('Axxiem_Shared_Document_URL'))
	define('Axxiem_Shared_Document_URL', plugin_dir_url( __FILE__ ));
if(!defined('Axxiem_Shared_Document_PATH'))
	define('Axxiem_Shared_Document_PATH', plugin_dir_path( __FILE__ ));

global $firebase_admin_json;
$path = plugin_dir_path(__FILE__) . 'admin-fcm.json';
$firebase_admin_json = json_decode(file_get_contents($path), true);

function send_fcm_notification($topic, $title, $sub_title, $device_token='', $type="1") {
    global $firebase_admin_json;

    $now = time();
    $jwt_header = base64_encode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));

    $jwt_claim = base64_encode(json_encode([
        'iss' => $firebase_admin_json['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud' => 'https://oauth2.googleapis.com/token',
        'iat' => $now,
        'exp' => $now + 3600
    ]));

    $signature_input = $jwt_header . '.' . $jwt_claim;

    // Sign the JWT
    openssl_sign($signature_input, $jwt_signature, $firebase_admin_json['private_key'], 'sha256WithRSAEncryption');
    $jwt = $signature_input . '.' . base64_encode($jwt_signature);

    // Step 2: Exchange JWT for Access Token
    $token_request = wp_remote_post('https://oauth2.googleapis.com/token', [
        'body' => [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]
    ]);

    $token_response = json_decode(wp_remote_retrieve_body($token_request), true);

    if (!isset($token_response['access_token'])) {
        error_log('Failed to retrieve access token: ' . print_r($token_response, true));
        return;
    }

    $access_token = $token_response['access_token'];

    // Step 3: Prepare and send the FCM message
    $project_id = $firebase_admin_json['project_id'];
    $fcm_url = "https://fcm.googleapis.com/v1/projects/{$project_id}/messages:send";
	if($device_token){
		$notification_data = [
			'message' => [
				'token' => $device_token, // 🔁 Replace with your topic name
				'data'=>[
					'title' => $title,
					'sub_title' => $sub_title,
					'type' => $type
				],
				'notification' =>[
					'title' => $title,
					'body' => $sub_title
				],
				'android' => [
                	'priority' => 'high'
            	]
			]
		];
	}else{
		$notification_data = [
			'message' => [
				'topic' => $topic, // 🔁 Replace with your topic name
				'data'=>[
					'title' => $title,
					'sub_title' => $sub_title,
					'type' => $type
				],
				'notification' =>[
					'title' => $title,
					'body' => $sub_title
				],
				'android' => [
                	'priority' => 'high'
            	]
			]
		];
	}
	//echo "<pre>"; print_r($notification_data);
	$response = wp_remote_post($fcm_url, [
		'headers' => [
			'Authorization' => 'Bearer ' . $access_token,
			'Content-Type'  => 'application/json'
		],
		'body' => json_encode($notification_data)
	]);
	//echo "<pre>"; print_r($response); exit;
	// Check for WP_Error (network issues, etc.)
	if (is_wp_error($response)) {
		error_log('FCM Error: ' . $response->get_error_message());
		return;
	}
	// Get HTTP status code
	$status_code = wp_remote_retrieve_response_code($response);
	$body = wp_remote_retrieve_body($response);
	//Success: 200 or 201 (depending on the API)
	if ($status_code === 200 || $status_code === 201) {
		error_log('FCM Notification Sent Successfully!');
		error_log('FCM Response: ' . $body);
	} else {
		error_log('FCM Failed with status: ' . $status_code);
		error_log('FCM Response: ' . $body);
	}
}

add_action('init', 'Axxiem_Shared_Document_init',1);

function Axxiem_Shared_Document_init(){
	$document_labels = array(
		'name'                  => __('Shared Documents'),
		'singular_name'         => __('All File'),
		'all_items'             => 'All Files',
		'add_new'               => __('Add File'),
		'add_new_item'          => __('Add New File'),
		'edit_item'             => __('Edit File'),
		'new_item'              => __('New File'),
		'view_item'             => __('View File'),
		'search_items'          => __('Search  File'),
		'not_found'             =>  __('No File found'),
		'not_found_in_trash'    => __('No File found in Trash'),
		'parent_item_colon'     => '',
		'menu_name'             => __( 'Shared Documents')
	);
	
	$document_args = array(
		'labels'              => $document_labels,
		'public'              => true,
		'publicly_queryable'  => true,
    	'exclude_from_search' => false,
		'show_ui'             => true,
		'show_in_menu'        => true, 
		'query_var'           => true,
		'rewrite'             => array( 
			'slug'       => 'shared-documents',
			'with_front' => false
		),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 5,
		'supports'            => array('title'),
		'show_in_rest'		  => true,
		'taxonomies' => ['shared-documents-category'],
	);
	register_post_type( 'shared-documents', $document_args );
	
	register_taxonomy('shared-document-category', ['shared-documents'], [
		'label' => __('Category', 'txtdomain'),
		'rewrite' => ['slug' => 'shared-document-category'],
		'hierarchical' => true,
   		'show_ui' => true,
    	'show_in_rest' => true,
        'show_admin_column' => true,
        'query_var' => true,
		'labels' => [
			'singular_name' => __('Category', 'txtdomain'),
			'all_items' => __('All Category', 'txtdomain'),
			'edit_item' => __('Edit Category', 'txtdomain'),
			'view_item' => __('View Category', 'txtdomain'),
			'update_item' => __('Update Category', 'txtdomain'),
			'add_new_item' => __('Add New Category', 'txtdomain'),
			'new_item_name' => __('New Category Name', 'txtdomain'),
			'search_items' => __('Search Category', 'txtdomain'),
			'popular_items' => __('Popular Category', 'txtdomain'),
			'separate_items_with_commas' => __('Separate Category with comma', 'txtdomain'),
			'choose_from_most_used' => __('Choose from most used Category', 'txtdomain'),
			'not_found' => __('No Category found', 'txtdomain'),
		]
	]);
	register_taxonomy_for_object_type('shared-documents-category', 'shared-documents');
}

add_action( 'init', function() {
    remove_post_type_support( 'shared-documents', 'editor' );
	remove_post_type_support('shared-documents', 'thumbnail');
}, 99999);


/**
 * Display a custom taxonomy dropdown in admin
 * @author Axxiem
 */

add_action('restrict_manage_posts', 'axxiem_filter_shared_post_type_by_taxonomy');
function axxiem_filter_shared_post_type_by_taxonomy() {
	global $typenow;
	$post_type = 'shared-documents'; // change to your post type
	$taxonomy  = 'shared-document-category'; // change to your taxonomy
	if ($typenow == $post_type) {
		$selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
		$info_taxonomy = get_taxonomy($taxonomy);
		wp_dropdown_categories(array(
			'show_option_all' => sprintf( __( 'Show all %s', 'textdomain' ), $info_taxonomy->label ),
			'taxonomy'        => $taxonomy,
			'name'            => $taxonomy,
			'orderby'         => 'name',
			'selected'        => $selected,
			'show_count'      => true,
			'hide_empty'      => false,
		));
	};
}

/**
 * Filter posts by taxonomy in admin
 * @author  Axxiem
 */
/*
add_filter('parse_query', 'axxiem_shared_category_term_in_query');
function axxiem_shared_category_term_in_query($query) {
	global $pagenow;
	$post_type = 'shared-documents'; // change to your post type
	$taxonomy  = 'shared-document-category'; // change to your taxonomy
	$q_vars    = &$query->query_vars;
	if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
		$term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
		$q_vars[$taxonomy] = $term->slug;
	}
}

add_shortcode('axxiem-shared-document-list', 'axxiem_shared_document_list');
*/

add_filter('parse_query', 'axxiem_shared_category_term_in_query');
function axxiem_shared_category_term_in_query($query) {
    global $pagenow;
    $post_type = 'shared-documents'; // Change to your post type
    $taxonomy  = 'shared-document-category'; // Change to your taxonomy
    $q_vars    = &$query->query_vars;

    if ($pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0) {
        $term_id = intval($q_vars[$taxonomy]);
        $term = get_term_by('id', $term_id, $taxonomy);

        if ($term) {
            // Modify the query to filter by the selected term and exclude child terms
            $q_vars['tax_query'] = [
                [
                    'taxonomy' => $taxonomy,
                    'field' => 'id',
                    'terms' => $term_id,
                    'include_children' => false, // Exclude sub-categories
                ],
            ];

            // Unset the direct taxonomy query var to avoid conflicts
            unset($q_vars[$taxonomy]);
        }
    }
}

add_shortcode('axxiem-shared-document-list', 'axxiem_shared_document_list');

if ( ! function_exists( 'axxiem_shared_document_front_scripts' ) ) {
	function axxiem_shared_document_front_scripts(){	
		wp_register_style( 'axxiem_document_front_css', plugin_dir_url( __FILE__ ). 'css/shared-document.css', false, '1.0.0' );
		wp_enqueue_style( 'axxiem_document_front_css' );
		
		wp_register_script( 'axxiem_document_filter_js', plugin_dir_url( __FILE__ ). 'js/shared-filter-script.js', false, '1.0.0' );
		wp_enqueue_script( 'axxiem_document_filter_js' );
		
		wp_localize_script('axxiem_document_filter_js', 'ajax_var', array('url' => admin_url('admin-ajax.php'),'nonce' => 	wp_create_nonce('ajaxnonce')
 ));
	} 
}
add_action('wp_head', 'axxiem_shared_document_front_scripts');
function checkFileExtention($filename){
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	//images/doc-icon.svg
	$extIcon  = "images/others-icon.svg";
	switch($ext){
		case 'doc':
		case 'docx':
		    $extIcon = "images/doc-icon.svg";
		break;
		case 'pdf':
		    $extIcon = "images/pdf-icon.svg";
		break;
		case 'txt':
		    $extIcon = "images/others-icon.svg";
		break;
		case 'zip':
		case 'rar':
		    $extIcon = "images/others-icon.svg";
		break;
		case 'ppt':
		case 'pptx':
		case 'pptm':
		    $extIcon = "images/ppt-icon.svg";
		break;
		case 'xls':
		case 'csv':
		case 'xlsx':
		    $extIcon = "images/xls-icon.svg";
		break;
		case 'png':
			$extIcon = "images/png-icon.svg";
		break;
		case 'jpg':
		case 'jpeg':
			$extIcon = "images/jpeg-icon.svg";
		break;
		case 'gif':
		    $extIcon = "images/gif-icon.svg";
		break;
		case 'mp4':
		case 'm4a':
		    $extIcon = "images/mp4-icon.svg";
		break;
		case 'pub':
		    $extIcon = "images/pub-icon.svg";
		break;
		case 'com':
		    $extIcon = "images/others-icon.svg";
		break;
		default:
			$extIcon = "images/others-icon.svg";
	}
	return $extIcon;
}

function axxiem_shared_document_list($atts, $content=null){
	ob_start();
	global $wp_roles, $content, $wpdb;
    $user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $role )
                $role = $wp_roles->roles[$role]['name'];
        }
    }
	
	extract(shortcode_atts(array(
		'searchOption' => 'true',
		'limit'   => -1
	), $atts));
	
	$args_cat = array(
		'taxonomy' => 'shared-document-category',
		'hide_empty' => false,
		'order'   => 'ASC'
	);
   $all_cats_list = get_categories($args_cat);
	
   $cat_args = array(
		'taxonomy' => 'shared-document-category',
		'orderby'  => 'name',
        'parent' => 0,
		'hide_empty' => false,
		'order'    => 'ASC'
	);
    $cats = get_categories($cat_args);

	// Query posts not in any category (Uncategorized)
	$args = array(
		'post_type' => 'shared-documents',
		'tax_query' => array(
        	array(
            	'taxonomy' => 'shared-document-category',
            	'operator' => 'NOT EXISTS',
        	),
    	),
		'order' => 'ASC',
    	'orderby' => 'title',
		'posts_per_page' => -1, // Retrieve all posts
	);
	if (!in_array($role, array('FM Admin','Administrator','Keymaster'))) {
		$meta_query = array('meta_query' => array(
				array(
					'key'     => 'Choose_Role',
					'value'   => sprintf(':"%s";', $role),
					'compare' => 'LIKE',
				),
			),
		);
		$args = array_merge($args, $meta_query);
	}
	$posts_query = new WP_Query( $args );
    $includeFile = plugin_dir_path( __FILE__ ).'templates/view-shared-document.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output; 
}

/* Ajax Search */
add_action('wp_ajax_axxiem_ajax_shared_content', 'axxiem_ajax_shared_content');
add_action('wp_ajax_nopriv_axxiem_ajax_shared_content', 'axxiem_ajax_shared_content');

function axxiem_ajax_shared_content() {
	global $paged, $wp_roles, $post;
	$cat 				= ! empty(  $_POST['filterByCat'] ) 			? $_POST['filterByCat'] 		: '';
	$keywords 			= ! empty(  $_POST['keywords'] ) 			? $_POST['keywords'] 		: '';
    $sortBy 				= ! empty( $_POST['sortBy'] ) 				? $_POST['sortBy'] 			: 'title';
	$user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
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
	$includeFile = plugin_dir_path( __FILE__ ).'templates/filter-shared-document.php';
	include( $includeFile );
	wp_die();
}

// Ajax directory View
add_action('wp_ajax_axxiem_shared_document_directory_view', 'axxiem_shared_document_directory_view');
add_action('wp_ajax_nopriv_axxiem_shared_document_directory_view', 'axxiem_shared_document_directory_view');
function axxiem_shared_document_directory_view(){
	global $paged, $post;
	
	$cat_id = ! empty(  $_POST['cat_id'] ) 	? $_POST['cat_id'] 	: 0;
	
	$args = array(
			'taxonomy' => 'shared-document-category',
			'orderby'  => 'name',
			'parent' => $cat_id,
			'hide_empty' => false,
			'order'    => 'ASC'
		);
	$cats = get_categories($args);
	
	if ($cat_id) {
		$term = get_term( $cat_id, 'shared-document-category' );
		$args = array(
			'post_type' => 'shared-documents',
			'tax_query' => array(
				array(
					'taxonomy' => 'shared-document-category',
					'field'     => 'term_id',
					'terms'     => $cat_id
				),
			),
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1, // Retrieve all posts
		);
		
	} else {
		$args = array(
			'post_type' => 'shared-documents',
			'tax_query' => array(
				array(
					'taxonomy' => 'shared-document-category',
					'operator' => 'NOT EXISTS',
				),
			),
			'order' => 'ASC',
			'orderby' => 'title',
			'posts_per_page' => -1, // Retrieve all posts
		);
	}
	$posts_query = new WP_Query( $args );
	$includeFile = plugin_dir_path( __FILE__ ).'templates/directory-stucture-view-document.php';
	include( $includeFile );
	wp_die();
}

// Add File name to the shared document post title
// code added by SA
add_action('save_post', 'update_post_title_with_uploaded_file', 10, 3);
function update_post_title_with_uploaded_file($post_id, $post, $update) {
    // Check if this is a 'shared-documents' post type
    if ($post->post_type != 'shared-documents') {
        return;
    }
	
	$roles = get_field('assign_to', $post_id);
	$file = get_field('upload_file', $post_id);
	if($file){
		if($roles) {
			if (empty($post->post_title)) {
				$file_name = pathinfo($file['filename'], PATHINFO_FILENAME);
            	$file_extension = pathinfo($file['filename'], PATHINFO_EXTENSION);
            	// Concatenate the file name and extension
            	$message = $file_name . '.' . $file_extension;
			} else {
				$message = $post->post_title;
			}
			$type = 1;
			$title = 'Document Shared';
			$author_id = $post->post_author;
			$user_name = get_the_author_meta( 'user_nicename' , $author_id );
			$category_name = wp_get_post_terms( $post_id, 'shared-document-category' )[0]->name;
			$sub_title = $user_name . ' uploaded a document in ' .$category_name;
			if (count($roles) == 4 ){
				$topic = 'ADAPP_ALL';
				send_fcm_notification($topic, $title, $sub_title,'',"1");
			} else {
				foreach($roles as $role) {
					if ($role == 'Staff'){
						$topic = 'ADAPP_STAFF';
					}
					if ($role == 'Supervisor'){
						$topic = 'ADAPP_SUPERVISOR';
					}
					if ($role == 'FM Admin'){
						$topic = 'ADAPP_ADMIN';
					}
					if ($role == 'Human Resources'){
						$topic = 'ADAPP_HR';
					}
					if ($role == 'Coalition Staff'){
						$topic = 'ADAPP_COALITION_STAFF';
					}
					
					send_fcm_notification($topic, $title, $sub_title,'',"1");
				}
			}
		}
	}
	
    // Check if the post title is empty
    if (empty($post->post_title)) {
        // Get the uploaded file field (assuming the field name is 'upload_file')
        $file = get_field('upload_file', $post_id);

        // If there is an uploaded file, use its name and extension for the post title
        if ($file) {
            $file_name = pathinfo($file['filename'], PATHINFO_FILENAME);
            $file_extension = pathinfo($file['filename'], PATHINFO_EXTENSION);

            // Concatenate the file name and extension
            $full_title = $file_name . '.' . $file_extension;

            // Update the post title
            $post_data = array(
                'ID' => $post_id,
                'post_title' => $full_title,
            );
            // Remove the action to prevent an infinite loop
            remove_action('save_post', 'update_post_title_with_uploaded_file', 10);
            // Update the post
            wp_update_post($post_data);
            // Re-add the action
            add_action('save_post', 'update_post_title_with_uploaded_file', 10, 3);
        }
    }
}

function get_category_parents_breadcrumb($category_id, $visited = array()) {

	$breadcrumb = '';
	$taxonomy = 'shared-document-category';
    $parent = get_term( $category_id, $taxonomy );
	
    if (is_wp_error($parent)) {
        return '';
    }
	$name = $parent->name;
    if ($parent->parent) {
		array_push($visited,$parent->parent);
        $breadcrumb .= get_category_parents_breadcrumb($parent->parent, $visited);
    }
    $breadcrumb .= ' > <a href="javascript:void(0)" class="directory-view" data-id="' . $parent->term_id . '">' . esc_html($name) . '</a>' . $separator;

    return $breadcrumb;
}
// ******************* Add Visible to column ******************* //

// Add custom columns to the shared-documents post type table and set their order
add_filter('manage_shared-documents_posts_columns', 'set_custom_edit_shared_documents_columns');
function set_custom_edit_shared_documents_columns($columns) {
    // Create a new array to store the new column order
    $new_columns = array();

    // Add columns in the desired order
    $new_columns['cb'] = $columns['cb']; // Checkbox column
    $new_columns['title'] = __('Title');
    $new_columns['author'] = __('Author');
    $new_columns['taxonomy-shared-document-category'] = __('Category'); // Add taxonomy column
    $new_columns['assign_to'] = __('Visible to');
    $new_columns['date'] = __('Date');

    return $new_columns;
}

// Populate the new column with the ACF field value
add_action('manage_shared-documents_posts_custom_column', 'custom_shared_documents_column', 10, 2);
function custom_shared_documents_column($column, $post_id) {
    if ('assign_to' === $column) {
        $assign_to = get_field('assign_to', $post_id);

        if (is_array($assign_to)) {
            // Handle case where 'assign_to' is an array (e.g., multiple users)
            $assign_to_names = array();

            foreach ($assign_to as $assignee) {
                if (is_array($assignee)) {
                    $assign_to_names[] = esc_html($assignee['display_name']); // Assuming it's a user field
                } else {
                    $assign_to_names[] = esc_html($assignee);
                }
            }

            echo implode(', ', $assign_to_names);
        } elseif ($assign_to) {
            // Handle case where 'assign_to' is a single value
            if (is_array($assign_to)) {
                echo esc_html($assign_to['display_name']); // Assuming it's a user field
            } else {
                echo esc_html($assign_to);
            }
        } else {
            echo __('Not assigned', 'textdomain');
        }
    }
}
// Make the 'Visible to' and 'Author' columns sortable
add_filter('manage_edit-shared-documents_sortable_columns', 'assign_to_sortable_columns');
function assign_to_sortable_columns($columns) {
    $columns['assign_to'] = 'assign_to';
    $columns['author'] = 'author';
    return $columns;
}
// Add custom CSS to set the width of the "Visible to" column
add_action('admin_head', 'custom_column_width');
function custom_column_width() {
    echo '<style>
        .column-assign_to {
            width: 200px !important;
        }
    </style>';
}

// Handle the sorting
add_action('pre_get_posts', 'assign_to_orderby');
function assign_to_orderby($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('assign_to' == $orderby) {
        $query->set('meta_key', 'assign_to');
        $query->set('orderby', 'meta_value');
    }
}
// ******************* End ******************* //


// code added by dashboard
add_shortcode('axxiem-dashboard-document-list', 'axxiem_dashboard_document_list');
function axxiem_dashboard_document_list($atts, $content=null){
    ob_start();
	
	global $wp_roles, $content, $wpdb;
    $user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	extract(shortcode_atts(array(
		'limit'   => 3
	), $atts));
	
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
		'posts_per_page' => $limit, // Retrieve all posts
	);
	$posts_query = new WP_Query( $args );
	
	$tax_query = array(
		array(
			'taxonomy' => 'adapp_posting_category',
			'field'    => 'slug',
			'terms'    => 'weekly-notes'
		)
	);
	
	$args = array(
		'post_type' => 'adapp_postings',
		'tax_query'      => $tax_query,
		'order' => 'DESC',
    	'orderby' => 'date',
		'posts_per_page' => $limit, // Retrieve all posts
	);
	
	$weekly_posts_query = new WP_Query( $args );
    $includeFile = plugin_dir_path( __FILE__ ).'templates/dashboard-shared-document.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output; 
}


// code added by dashboard
add_shortcode('axxiem-adapp-post-list', 'axxiem_adapp_post_list');
function axxiem_adapp_post_list($atts, $content=null){
	global $content, $wpdb, $wp_roles,  $paged;
    ob_start();
	extract(shortcode_atts(array(
		'limit'   => 10
	), $atts));
	
	$user = wp_get_current_user();
    $role = '';
	// Query posts not in any category (Uncategorized)
	$args = array(
		'post_type' => 'adapp_postings',
		'meta_query'     => array(
			array(
				'key'     => '_is_pinned',
				'value'   => '1',
				'compare' => '='
			)
			// 👉 Add other filters like Choose_Role or dynamic_key here if needed
		),
		'orderby'        => array(
			'menu_order'     => 'DESC',       // then order by menu_order
			'date'           => 'DESC',      // fallback to date if needed
		),
		'posts_per_page' => $limit, // Retrieve all posts
		'paged'	=> $paged
	);
	$posts_query = new WP_Query( $args );

	$non_pinned_args = array(
		'post_type'      => 'adapp_postings',
		'posts_per_page' => $limit - $posts_query->post_count,
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
    $includeFile = plugin_dir_path( __FILE__ ).'templates/adapp-post-lists.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output; 
}

// Ajax Pagination
add_action('wp_ajax_axxiem_adapp_post_ajax_pagination', 'axxiem_adapp_post_ajax_pagination');
add_action('wp_ajax_nopriv_axxiem_adapp_post_ajax_pagination', 'axxiem_adapp_post_ajax_pagination');

function axxiem_adapp_post_ajax_pagination() {
	global $paged, $wp_roles, $post;
	$posts_per_page 	= ! empty( $_POST['limit'] ) 				? $_POST['limit'] 						: 10;
    $paged 	= ! empty( $_POST['paged'] ) 				? $_POST['paged'] 						: 0;
	$user = wp_get_current_user();
    $role = '';
	$args = array ( 
		'post_type' => 'adapp_postings',
		'post_status'		=> 'publish',
		'orderby'        => array(
			'menu_order'     => 'DESC',       // then order by menu_order
			'date'           => 'DESC',      // fallback to date if needed
		),
		'posts_per_page'	=> $posts_per_page,
		'paged'				=> $paged,
	);
	 if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	$posts_query = new WP_Query( $args );
	$post_count	= $posts_query->post_count;
	$count		= 0;
	$includeFile =  plugin_dir_path( __FILE__ ).'templates/ajax-loadmore-adapp-post.php';
	include( $includeFile );
	wp_die();
}

add_action('wp_ajax_axxiem_adapp_post_ajax_count', 'axxiem_adapp_post_ajax_count');
add_action('wp_ajax_nopriv_axxiem_adapp_post_ajax_count', 'axxiem_adapp_post_ajax_count');

function axxiem_adapp_post_ajax_count() {
	global $wp_roles;
    $user = wp_get_current_user();
    $role = '';
	$posts_per_page 	= ! empty( $_POST['limit'] ) 				? $_POST['limit'] 						: 10;
    $paged 	= ! empty( $_POST['paged'] ) 				? $_POST['paged'] 						: 0;
	
	$args = array ( 
		'post_type' => 'adapp_postings',
		'post_status'		=> 'publish',
		'orderby'        => array(
			'menu_order'     => 'DESC',       // then order by menu_order
			'date'           => 'DESC',      // fallback to date if needed
		),
		'posts_per_page'	=> $posts_per_page,
		'paged'				=> $paged + 1,
	);
	
	if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	
	$query 		= new WP_Query( $args );
	$post_count	= $query->post_count;
	echo json_encode(array('post_count'=>$post_count));
	wp_die();
}

function scan_and_upload_file( $file_path, $post_id) {
	ini_set('max_execution_time', '0');
	ini_set('memory_limit', '-1');
	ini_set('upload_max_filesize', '512M');
	ini_set('post_max_size', '512M');
	ini_set('max_input_time', '1200');
	$target_dir = 'shared-document';
	$wp_upload_dir = wp_upload_dir();
	// Get the file type
	$filetype = wp_check_filetype(basename($file_path), null);
	$target_file_path =  $wp_upload_dir['basedir'] . '/' . $target_dir .  '/' . date('_Y_m_d_H_i_s_') . basename($file_path);

	if (copy($file_path, $target_file_path)) {
		//die("File not copy to exact location");
		$attachment = array(
			'guid'           => wp_upload_dir()['url'] . '/' . basename($file_path),
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_file_name(pathinfo($file_path, PATHINFO_FILENAME)),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Upload the file and get the attachment ID
		$attachment_id = wp_insert_attachment($attachment, $target_file_path, $post_id);

		// Check for errors
		if (is_wp_error($attachment_id)) {
			return 'Error uploading file: ' . $attachment_id->get_error_message();
		}

		// Include the required file for metadata
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate the metadata for the attachment and update the database record
		$attachment_data = wp_generate_attachment_metadata($attachment_id, $target_file_path);
		wp_update_attachment_metadata($attachment_id, $attachment_data);

		// Attach the uploaded file to the post
		wp_update_post(array(
			'ID' =>  $attachment_id,
			'post_parent' => $post_id
		));

		update_post_meta( $post_id, 'upload_file', $attachment_id );
		update_post_meta( $post_id, '_upload_file', 'field_6699373cd8b17' );
	}

	
	return 'File uploaded and attached to post with ID: ' . $post_id;
}

//add_action('admin_init', 'deleteAllSharedPost');
function axxiem_migrate_shared_document() { 
	global $pagenow, $firephp, $wpdb;
	if ( isset($_GET['post_type']) && 'shared-documents' == $_GET['post_type'] ) {
		$list_all_dir_files = [];
		$upload_dir = wp_upload_dir();
		$base_dir = $upload_dir['basedir'] .'/wp-file-manager-pro/Shared_Documents';
		$list_all_dir_files = list_directories_and_files($base_dir);
		if (!empty($list_all_dir_files)) {
			foreach ($list_all_dir_files as $file) {
				if ($file['type'] == 'directory' && $file['name'] !== '.tmb') {
					echo $file['name'] . "<br/>";
					$parent_category = ($file['level'] == 0 ) ? 0 : trim($file['parent_dir']);
					echo $category_id = check_and_create_category(trim($file['name']), 'shared-document-category', $parent_category);
					echo "<br/>";
				} 
				/*if ($file['type'] == 'file' && $file['parent_dir'] !== '.tmb') {
					$query = "INSERT INTO wp_axxiem_shared_document (name, path, parent_dir) VALUES ('" . $file['name'] ."', '" . $file['path'] . "', '" . $file['parent_dir'] . "')";
					$wpdb->query($query);
					//read_and_upload_file($file['path'], $post_id);
				} */
			}
		}
	}
}

function list_directories_and_files($dir, $level = 0) {
    $result = [];

    // Check if the directory exists
    if (!is_dir($dir)) {
        return $result;
    }

    // Open the directory
    $files = scandir($dir);

    // Loop through each file
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }

        $fullPath = $dir . DIRECTORY_SEPARATOR . $file;
        $item = [
            'name' => $file,
            'path' => $fullPath,
            'type' => is_dir($fullPath) ? 'directory' : 'file',
            'level' => $level,
			'parent_dir' => is_dir($fullPath) ? substr($dir, strrpos($dir, '/' )+1) : substr($dir, strrpos($dir, '/' )+1)
        ];

        $result[] = $item;

        // If the item is a directory, recursively read its contents
        if (is_dir($fullPath)) {
            $result = array_merge($result, list_directories_and_files($fullPath, $level + 1));
        }
    }

    return $result;
}

function check_and_create_category($category_name, $taxonomy = 'shared-document-category', $parent_category = '') {
    // Check if the category exists by name
    $category = get_term_by('name', $category_name, $taxonomy);

    // If the category doesn't exist, create it
    if (!$category) {
		if (!empty($parent_category)) {
			$p_category = get_term_by('name', $parent_category, $taxonomy);
			$parent_category = $p_category->term_id;
		}
        $new_category = wp_insert_term(
            $category_name, // The category name
            $taxonomy, // The taxonomy (category by default)
			array(
				'parent' => !empty($parent_category) ? $parent_category : 0
			)
        );

        if (is_wp_error($new_category)) {
            // Handle the error
            return;
        } else {
            return $new_category['term_id'];
        }
    } else {
        return $category->term_id;
    }
}
function update_all_categories() {
    // Get all categories
	$args = array(
		'taxonomy' => 'shared-document-category',
		'hide_empty' => false
	);
	$categories = get_categories($args);
	//echo "<Pre>"; print_r($categories);
    foreach ($categories as $category) {
        //wp_delete_term($category->term_id, 'shared-document-category');
		/*wp_update_term($category->term_id, 'shared-document-category', array(
			'choose_role' => array('Staff','Supervisor','FM admin','Human Resources',''),
		));*/
		//$meta_id = add_term_meta( $category->term_id, '_choose_role', 'field_669a84b9aa8e3', true );
		
		$meta_id = add_term_meta( $category->term_id, 'choose_role', array('Staff','Supervisor','FM admin','Human Resources','Coalition Staff'), true );
    }
}

function deleteAllSharedPost(){
	$allposts= get_posts( array('post_type'=>'shared-documents','numberposts'=>-1) );
	foreach ($allposts as $eachpost) {
		update_post_meta( $eachpost->ID, 'assign_to', array('Staff', 'Supervisor', 'FM Admin', 'Human Resources', 'Coalition Staff') );
	  //wp_delete_post( $eachpost->ID, true );
	}
}
function register_axxiem_shared_document_webhook_endpoint() {
    add_rewrite_rule('^shared-document-handler/([^/]*)', 'index.php?axxiem_page_limit=$matches[1]', 'top');
}

add_action('init', 'register_axxiem_shared_document_webhook_endpoint');
function add_query_vars($vars) {
    $vars[] = 'axxiem_page_limit';
    return $vars;
}

add_filter('query_vars', 'add_query_vars');
function readSharedDocumentDatabase () {
	global $wpdb;
	$num_rows = $wpdb->get_var('SELECT COUNT(*) FROM wp_axxiem_shared_document');
	$total_loop_count =  ceil($num_rows/50);
	for ($i = 0; $i <= $total_loop_count; $i++) {
		$targetUrl = 'https://demo11.axxiem.com/index.php?axxiem_page_limit=' . $i;
		$args = array(
        	'timeout' => 5
    	);
		$response = wp_remote_get($targetUrl, $args);
	}
}

function handle_shared_document_webhook_request() {
    global $wp_query, $wpdb;
	
    if (isset($wp_query->query_vars['axxiem_page_limit'])) {
        // Your webhook handling logic here
		ini_set('max_execution_time', '0');
		ini_set('memory_limit', '-1');
		$offset = 15;
		$batch_size = $wp_query->query_vars['axxiem_page_limit'];
		$batch_size = ($batch_size - 1) * $offset;
		$table_name = $wpdb->prefix . 'axxiem_shared_document';
		
		$records = $wpdb->get_results($wpdb->prepare(
			"SELECT * FROM {$table_name} WHERE status = 0 and parent_dir = 'powerful-powerpoints' LIMIT $batch_size, $offset"));
		
		// Collect the IDs of the records to update
		//echo "<pre>"; print_r($records); exit;
		$ids_to_update = array();
		if (!empty($records)) { 
			foreach ($records as $record) {
				$ids_to_update[] = $record->id;
				$postTitle = trim($record->name);	
				$parent_category = trim($record->parent_dir);
				$new_post = array(
					'post_title'    => $postTitle,
					'post_type'  => 'shared-documents',
					'post_status'   => 'publish',
					'post_author'   => 1, // ID of the author
					'tax_input' => array('shared-document-category' => array($parent_category)) // We'll set the category later
				);
				// Insert the post into the database
				$post_id = wp_insert_post($new_post);
				wp_set_object_terms($post_id, $parent_category, 'shared-document-category', true);

				scan_and_upload_file( $record->path, $post_id);
				$updated = $wpdb->query($wpdb->prepare(
					"UPDATE {$table_name} SET status = 1 WHERE id = " . $record->id 
				));
			}
			
		}
        //process_axxiem_shared_document_webhook($limit);
        // Stop further processing to avoid 404 error
    }
}
add_action('template_redirect', 'handle_shared_document_webhook_request');
function my_custom_mime_types($mimes) {
    // Add .pub file extension
    $mimes['pub'] = 'application/x-mspublisher';
	$mimes['pptm'] = 'application/vnd.ms-powerpoint.presentation.macroEnabled.12';
    return $mimes;
}
add_filter('upload_mimes', 'my_custom_mime_types');

function custom_column_header( $columns ){
	$columns['featured_on_homepage'] = 'Feature on Homepage';
	$columns['choose_role'] = 'Assign To';
	return $columns;
}
add_filter( "manage_edit-shared-document-category_columns", 'custom_column_header', 10);

function add_shared_document_category_column_content( $content, $column_name, $term_id ) {
    $term = get_term( $term_id, 'shared-document-category' );
    switch ( $column_name ) {
        case 'featured_on_homepage':
            // Do your stuff here with $term or $term_id
			$content =  get_field('featured_on_homepage', $term);
            break;
		case 'choose_role':
            // Do your stuff here with $term or $term_id
			$content =  get_field('choose_role', $term);
			$content = !empty($content) ? implode(',' , $content) : '';
			//echo "<pre>"; print_r($content);
            break;	

        default:
            break;
    }

    return $content;
}

add_filter( 'manage_shared-document-category_custom_column', 'add_shared_document_category_column_content', 10, 3 );

add_shortcode('axxiem-feature-document-list', 'axxiem_feature_document_list');
function axxiem_feature_document_list($atts, $content=null){
	ob_start();
	global $wp_roles, $content, $wpdb;
    $user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	
	extract(shortcode_atts(array(
		'limit'   => 5
	), $atts));
	
	// Query posts not in any category (Uncategorized)
	$args = array(
		'post_type' => 'shared-documents',
		'order' => 'DESC',
    	'orderby' => 'date',
		'posts_per_page' => 5, // Retrieve all posts
	);
	if (!empty($role) && !in_array($role, array('FM Admin','Administrator','Keymaster'))) {
		$meta_query = array('meta_query' => array(
			'relation' => 'AND',	
				array(
					'key'     => 'assign_to',
					'value'   => sprintf(':"%s";', $role),
					'compare' => 'LIKE',
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
			),
		);
		$args = array_merge($args, $meta_query);
	} else {
		$meta_query = array('meta_query' => array(
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
    $includeFile = plugin_dir_path( __FILE__ ).'templates/view-feature-document.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output; 
}

add_shortcode('axxiem-recent-document-view', 'axxiem_recent_document_view');
function axxiem_recent_document_view($atts, $content=null){
	ob_start();
	global $wp_roles, $content, $wpdb;
    $user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	
	extract(shortcode_atts(array(
		'limit'   => 5
	), $atts));
	
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
	// Query posts not in any category (Uncategorized)
	
	$posts_query = new WP_Query( $args );
    $includeFile = plugin_dir_path( __FILE__ ).'templates/view-recent-document.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output; 
}

add_action('wp_ajax_axxiem_store_shared_recent_directory_view', 'axxiem_store_shared_recent_directory_view');
add_action('wp_ajax_nopriv_axxiem_store_shared_recent_directory_view', 'axxiem_store_shared_recent_directory_view');

function axxiem_store_shared_recent_directory_view() {
	global $wp_roles, $content, $wpdb;
    $user = wp_get_current_user();
    $role = '';
    if($user){
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
            foreach ( $user->roles as $us_role ) {
				if(in_array($us_role, array('staff', 'admins', 'hr' , 'supervisor', 'administrator', 'coalition_staff'))) {
                	$role = $wp_roles->roles[$us_role]['name'];
					break;
				}
			}
        }
    }
	if ($role) {
		$table_name = $wpdb->prefix . "axxiem_shared_document_views";
		$insert_data = array(
			'post_id' =>$_POST['post_id'],
			'role_type' => $role,
			'created_date' => date('Y-m-d h:i:s'),
			'user_id' => $user->ID
		);
		$wpdb->insert($table_name,$insert_data);
	}
}

add_action('save_post', 'update_adapp_posting', 10, 3);
function update_adapp_posting($post_id, $post, $update) {
    global $wpdb;
	// Check if this is a 'shared-documents' post type
    if ($post->post_type != 'adapp_postings') {
        return;
    }
	
	if ( 'publish' === get_post_status( $post_id ) ) {
		$title = "New Message";
		$author_id = $post->post_author;
		$user_name = get_the_author_meta( 'user_nicename' , $author_id );
		$sub_title = $user_name . " posted a new message";
		$roles = get_field('Choose_Role', $post_id);
		if ($roles) {
			foreach($roles as $role) {
				$dynamic_key = $role;
				if ($role == 'Staff') {
					$topic = 'ADAPP_STAFF';
				}
				if ($role == 'Supervisor') {
					$topic = 'ADAPP_SUPERVISOR';
				}
				if ($role == 'Human Resources') {
					$dynamic_key = 'human_resources';
					$topic = 'ADAPP_HR';
				}
				if ($role == 'FM admin') {
					$dynamic_key = 'fm_admin';
					$topic = 'ADAPP_ADMIN';
				}
				$notify_users = get_post_meta( $post_id, strtolower($dynamic_key), true );
				if($notify_users) {
					foreach($notify_users as $user_id) {
						$table_name = $wpdb->prefix . "axxiem_user_devices";
						$userdata = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}axxiem_user_devices WHERE user_id = %d order by id desc", $user_id ) );
						foreach($userdata as $u_info) {
							$device_token = $u_info->device_token;
							send_fcm_notification($topic, $title, $sub_title, $device_token,"2");
						}
					}
				} else{
					send_fcm_notification($topic, $title, $sub_title,'',"2");
				}
			}
		}
	}
}
//add_action('admin_init', 'custom_notify');
function custom_notify(){
	$topic = 'ADAPP_ADMIN';
	$title = 'testing title';
	$sub_title = 'sub_title';
	send_fcm_notification($topic, $title, $sub_title,'',"2");
}


function add_new_subscriber_role() {
    add_role(
        'coalition_staff',  // Role ID
        __( 'Coalition Staff' ), // Role Name
        array(
            'read' => true, // Allows viewing posts and pages
            'delete_posts' => false,
            'edit_posts' => false,
            'edit_published_posts' => false,
            'publish_posts' => false,
            'upload_files' => false,
        )
    );
}
add_action( 'init', 'add_new_subscriber_role' );
//add_action( 'admin_init', 'update_all_categories' );

function my_cpt_add_pin_meta_box() {
    if ( current_user_can('admins') ) { 
		add_meta_box('my_cpt_pin_meta', 'Pin Post', 'my_cpt_pin_meta_callback', 'adapp_postings', 'side', 'high');
	}
	add_meta_box( 'axxiem_notice_attachment', 'Post Attachment', 'axxiem_notice_attachment', 'adapp_postings');
}
add_action('add_meta_boxes', 'my_cpt_add_pin_meta_box');

function my_cpt_pin_meta_callback($post) {
    $value = get_post_meta($post->ID, '_is_pinned', true);
    ?>
    <label>
        <input type="checkbox" name="my_cpt_is_pinned" value="1" <?php checked($value, 1); ?> />
        Pin this post on top
    </label>
    <?php
    wp_nonce_field('my_cpt_pin_nonce_action', 'my_cpt_pin_nonce');
}

function my_cpt_save_pin_meta($post_id) {
    if (!isset($_POST['my_cpt_pin_nonce']) || !wp_verify_nonce($_POST['my_cpt_pin_nonce'], 'my_cpt_pin_nonce_action')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['my_cpt_is_pinned'])) {
        update_post_meta($post_id, '_is_pinned', 1);
    } else {
        update_post_meta($post_id, '_is_pinned', 0);
    }
}
add_action('save_post_adapp_postings', 'my_cpt_save_pin_meta');

function my_cpt_add_custom_column($columns) {
    $columns['pinned'] = 'Pinned';
    return $columns;
}
add_filter('manage_adapp_postings_posts_columns', 'my_cpt_add_custom_column');

function my_cpt_show_custom_column($column, $post_id) {
    if ($column == 'pinned') {
        echo get_post_meta($post_id, '_is_pinned', true) ? '📌 Yes' : '';
    }
}
add_action('manage_adapp_postings_posts_custom_column', 'my_cpt_show_custom_column', 10, 2);

add_shortcode('axxiem-weekly-notes', 'axxiem_weekly_notes_list');
function axxiem_weekly_notes_list($atts, $content=null){
	global $content, $paged;
	ob_start();
	extract(shortcode_atts(array(
		'limit'   => 10
	), $atts));
	
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
		'order' => 'DESC',
    	'orderby' => 'date',
		'posts_per_page' => 10, // Retrieve all posts
	);
	
	$posts_query = new WP_Query( $args );
	$includeFile = plugin_dir_path( __FILE__ ).'templates/adapp-weekly-notes.php';
	include( $includeFile );
	$output = ob_get_clean();
	$output = wpautop(trim($output));
    return $output;
}

function axxiem_notice_attachment($post) {
	global $wpdb;
	$notice_attachment = get_post_meta( $post->ID, 'wp_custom_attachment', true );
	$html = '';
	if ( !empty($notice_attachment) ) { 
		foreach($notice_attachment as $notice) {
			
	     $html .= '<div><p><a href="' . wp_get_attachment_url($notice) . '" target="_blank"> ' . wp_get_attachment_url($notice) . '</a></p><input type="hidden" name="old_attachment[]" value="'. $notice .'" /></div>'; 
		}
	}

	$html .= '<p class="description">Upload Attachment.</p>';
	$html .= '<input id="wp_custom_attachment" name="wp_custom_attachment[]" size="25" type="file" />';
	echo $html;
}

add_action('save_post_adapp_postings', 'my_cpt_save_adapp_postings');

function my_cpt_save_adapp_postings( $post_id ) {
	$files = !empty($_FILES) ?  $_FILES['wp_custom_attachment'] : '';	
	//echo "<pre>"; print_r($_FILES); exit;
	$all_uploads = [];
	if($files) {
		for($i=0; $i< count($files['name']); $i++){
			if(!empty($files['tmp_name'][$i])){
				$file = array(
					'name'     => $files['name'][$i],
					'type'     => $files['type'][$i],
					'tmp_name' => $files['tmp_name'][$i],
					'error'    => $files['error'][$i],
					'size'     => $files['size'][$i]
				);
				echo "<pre>"; print_r($file); exit;
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
	}
	if(!empty($_POST['old_attachment'])) {
		$all_uploads = array_merge($all_uploads,$_POST['old_attachment']);
	}
	update_post_meta($post_id, 'wp_custom_attachment', $all_uploads);
}

//add_action('admin_init', 'read_ms_emails_from_graph');

function weeklynotes_oath(){
	$authUrl = 'https://login.microsoftonline.com/' . MS_GRAPH_TENANT_ID . '/oauth2/v2.0/authorize?' . http_build_query([
		'client_id'     => MS_GRAPH_CLIENT_ID,
		'response_type' => 'code',
		'redirect_uri'  => 'https://demo11.axxiem.com/?weblistner=weeklynotes',
		'response_mode' => 'query',
		'scope'         => 'offline_access https://graph.microsoft.com/Mail.Read',
		'state'         => '12345' // optional
	]);

	wp_redirect($authUrl);
	exit;
}

function read_ms_emails_from_graph() {
    $accessToken = get_ms_graph_access_token();
    if (!$accessToken) return;

    $emails = get_ms_graph_emails($accessToken);

    if (!empty($emails['value'])) {
        foreach ($emails['value'] as $email) {
			echo "<pre>"; print_r($email);
			if(!$email['isRead']){
				if (str_contains($email['subject'], "Weekly Notes")) {
					createWeeklyNotes($email['subject'], $email['bodyPreview'], $email['id'], $email['hasAttachments'], $accessToken);
				}
			}
            //error_log('Subject: ' . $email['subject']);
            // Process/store in DB as needed
        }
    }
	exit;
}

function createWeeklyNotes($title, $body, $messageId, $attachment, $accessToken) {
	/*$post_id = wp_insert_post(array (
		'post_type' => 'adapp_postings',
		'post_title' => $title,
		'post_content' => $body,
		'post_author' => 1,
		'post_status' => 'publish',
		'comment_status' => 'closed',   // if you prefer
		'ping_status' => 'closed',      // if you prefer
	));
	wp_set_object_terms($post_id, 'weekly-notes', 'adapp_posting_category'); */
	if ($attachment == 1) {
		$attached = getEmailAttachments($accessToken, $messageId);
		echo "<pre>"; print_r($attached); exit;
	}
}

function getEmailAttachments($accessToken, $messageId) {
    $url = "https://graph.microsoft.com/v1.0/me/messages/{$messageId}/attachments";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer {$accessToken}",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function get_ms_graph_access_token() {
    $refreshToken = get_option('ms_graph_refresh_token');
	$url = 'https://login.microsoftonline.com/' . MS_GRAPH_TENANT_ID . '/oauth2/v2.0/token';
    $response = wp_remote_post($url, [
        'body' => [
            'client_id'     => MS_GRAPH_CLIENT_ID,
            'scope'         => 'offline_access https://graph.microsoft.com/Mail.Read',
            'client_secret' => MS_GRAPH_CLIENT_SECRET,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken
        ]
    ]);

    if (is_wp_error($response)) return false;
    $data = json_decode(wp_remote_retrieve_body($response), true);

    return $data['access_token'] ?? false;
}

function get_ms_graph_emails($accessToken) {
    $url = 'https://graph.microsoft.com/v1.0/me/mailFolders/inbox/messages?$top=5';
    $response = wp_remote_get($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken
        ]
    ]);

    if (is_wp_error($response)) return [];
    return json_decode(wp_remote_retrieve_body($response), true);
}

function get_ms_access_token_from_refresh() {
    $refreshToken = get_option('ms_graph_refresh_token');
    if (!$refreshToken) return false;

    $url = 'https://login.microsoftonline.com/' . MS_GRAPH_TENANT_ID . '/oauth2/v2.0/token';

    $response = wp_remote_post($url, [
        'body' => [
            'client_id'     => MS_GRAPH_CLIENT_ID,
            'scope'         => 'offline_access https://graph.microsoft.com/Mail.Read',
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
            'client_secret' => MS_GRAPH_CLIENT_SECRET
        ]
    ]);

    if (is_wp_error($response)) return false;

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (!empty($data['access_token'])) {
        // If Microsoft returns a new refresh token, save it
        if (!empty($data['refresh_token'])) {
            update_option('ms_graph_refresh_token', $data['refresh_token']);
        }
        return $data['access_token'];
    }

    return false;
}
