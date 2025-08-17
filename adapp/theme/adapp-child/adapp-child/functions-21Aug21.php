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
add_action('admin_footer-post.php', 'wpyog_append_post_status_list');
function wpyog_append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';
     if($post->post_type == 'news'){
          if($post->post_status == 'archive'){
               $complete = ' selected="selected"';
               $label = '<span id="post-status-display"> Archived</span>';
          }
          echo '
          <script>
          jQuery(document).ready(function($){
               $("select#post_status").append("<option value="archive" '.$complete.'>Archive</option>");
               $(".misc-pub-section label").append("'.$label.'");
          });
          </script>
          ';
     }
}

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
if (!current_user_can('administrator') && !current_user_can('admins') && !is_admin()) {
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
 
add_action( 'widgets_init', 'wpb_widgets_init' );
/* add_action("init", function () {
	remove_shortcode("sp_news");
	add_shortcode( 'sp_news', 'wpyog_sp_news' );
}); */
function wpyog_sp_news( $atts, $content = null ){
    // setup the query
    extract(shortcode_atts(array(
		"limit"                 => '',	
		"category"              => '',
		"grid"                  => '',
        "show_date"             => '',
        "show_category_name"    => '',
        "show_content"          => '',
		"show_full_content"     => '',
        "content_words_limit"   => '',
        "pagination_type"       => 'numeric',
		"post_status"       => 'publish',
	), $atts));
	
    // Define limit
    if( $limit ) { 
		$posts_per_page = $limit; 
	} else {
		$posts_per_page = '-1';
	}
	
    if( $category ) { 
		$cat = $category; 
	} else {
		$cat = '';
	}
	
    if( $grid ) { 
		$gridcol = $grid; 
	} else {
		$gridcol = '1';
	}
    
    if( $show_date ) { 
        $showDate = $show_date; 
    } else {
        $showDate = 'true';
    }
	
    if( $show_category_name ) { 
        $showCategory = $show_category_name; 
    } else {
        $showCategory = 'true';
    }
    
    if( $show_content ) { 
        $showContent = $show_content; 
    } else {
        $showContent = 'true';
    }
	
    if( $show_full_content ) { 
        $showFullContent = $show_full_content; 
    } else {
        $showFullContent = 'false';
    }
	
    if( $content_words_limit ) { 
        $words_limit = $content_words_limit; 
    } else {
        $words_limit = '20';
    }

    if($pagination_type == 'numeric'){

       $pagination_type = 'numeric';
    }else{

        $pagination_type = 'next-prev';
    }

	ob_start();
	
	global $paged;
	
    if(is_home() || is_front_page()) {
		  $paged = get_query_var('page');
	} else {
		 $paged = get_query_var('paged');
	}

	$post_type 		= 'news';
	$orderby 		= 'date';
	$order 			= 'DESC';

    $args = array ( 
        'post_type'      => $post_type,
        'post_status'    => $post_status,
        'orderby'        => $orderby,
        'order'          => $order,
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
    );

    if($cat != "") {
        $args['tax_query'] = array(
            array(
                'taxonomy'  => 'news-category',
                'field'     => 'term_id',
                'terms'     => $cat
            ));
    }

    $query = new WP_Query($args);
    global $post;
    $post_count = $query->post_count;
    $count = 0;
	?>
	<div class="wpnawfree-plugin news-clearfix">
	<?php
    if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
        
        $count++;
        $terms = get_the_terms( $post->ID, 'news-category' );
        $news_links = array();

        if($terms) {
            foreach ( $terms as $term ) {
                $term_link = get_term_link( $term );
                $news_links[] = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
            }
        }
        
        $cate_name = join( ", ", $news_links );
        $css_class="wpnaw-news-post";

        if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == ($count - 1) % $grid ) ) || 1 == $count ) { $css_class .= ' wpnaw-first'; }
        if ( ( is_numeric( $grid ) && ( $grid > 0 ) && ( 0 == $count % $grid ) ) || $post_count == $count ) { $css_class .= ' wpnaw-last'; }
        if($showDate == 'true'){ $date_class = "has-date"; } else { $date_class = "has-no-date";} ?>
	
    	<div id="post-<?php the_ID(); ?>" class="news type-news news-col-<?php echo $gridcol.' '.$css_class.' '.$date_class; ?>">
			<div class="news-inner-wrap-view news-clearfix <?php  if ( !has_post_thumbnail()) { echo 'wpnaw-news-no-image'; } ?>">	
				<?php  if ( has_post_thumbnail()) {   ?>
				<div class="news-thumb">    			
				<?php if($gridcol == '1'){ ?>    					
							<div class="grid-news-thumb">    				    
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a>
							</div>
						<?php } else if($gridcol > '2') { ?>    					
							<div class="grid-news-thumb">	    				    
								<a href="<?php the_permalink(); ?>">	<?php the_post_thumbnail('medium_large'); ?></a>
							</div>
						<?php	} else { ?>        			    
							<div class="grid-news-thumb">        				
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a>
							</div>
						<?php }  ?>
				</div>	
				<?php }  ?>	
				<div class="news-content">    			
					<?php if($gridcol == '1') {                    
						if($showDate == 'true'){ ?>        				
							<div class="date-post">            			
								<h2><span><?php echo get_the_date('j'); ?></span></h2>            			
								<p><?php echo get_the_date('M y'); ?></p>
							</div>
						<?php }?>
					<?php } else {  ?>    				
						<div class="grid-date-post">        			
							<?php echo ($showDate == "true")? get_the_date() : "" ;?>                    
							<?php echo ($showDate == "true" && $showCategory == "true" && $cate_name != '') ? " / " : "";?>                    
							<?php echo ($showCategory == 'true' && $cate_name != '') ? $cate_name : ""?>
						</div>
					<?php  } ?>    			
					<div class="post-content-text">    				
						<?php the_title( sprintf( '<h3 class="news-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' );	?>    			    
						<?php if($showCategory == 'true' && $gridcol == '1'){ ?>    				
							<div class="news-cat">                        
								<?php echo $cate_name; ?>
							</div>
						<?php }?>
						<?php if($showContent == 'true'){?>        			 
							<div class="news-content-excerpt">            			
								<?php  if($showFullContent == "false" ) {
									$excerpt = get_the_content(); ?>                				
									<div class="news-short-content">                                    
										<?php echo string_limit_newswords( $post->ID, $excerpt, $words_limit, '...'); ?>
									</div>                				
									<a href="<?php the_permalink(); ?>" class="news-more-link"><?php _e( 'Read More', 'sp-news-and-widget' ); ?></a>	
								<?php } else {             				
									the_content();
								} ?>
							</div><!-- .entry-content -->
						<?php }?>
					</div>
				</div>
			</div><!-- #post-## -->
        </div><!-- #post-## -->
    <?php  endwhile; endif; ?>
	</div>		
    <div class="news_pagination">        
        <?php if($pagination_type == 'numeric'){ 
            echo news_pagination( array( 'paged' => $paged , 'total' => $query->max_num_pages ) );
        }else{ ?>    		
            <div class="button-news-p"><?php next_posts_link( ' Next >>', $query->max_num_pages ); ?></div>    		
            <div class="button-news-n"><?php previous_posts_link( '<< Previous' ); ?> </div>
        <?php } ?>
	</div><?php
    
    wp_reset_query(); 
				
	return ob_get_clean();
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


function custom_widget_featured_image() {
	global $post;

	echo tribe_event_featured_image( $post->ID, 'thumbnail' );
}
add_action( 'tribe_events_list_widget_before_the_event_title', 'custom_widget_featured_image' );
/* Add Custom field in right ride */
add_action( 'add_meta_boxes_news', 'news_add_meta_boxes' );

add_action( 'save_post', 'wpypg_save_news_meta');

function news_add_meta_boxes( $post ){
	add_meta_box(
		'wpyog_news_archive',
		'Archive',
		'wpyog_news_archive',
		'news',
		'side',
		'high'
	);
}

/**
 * Output the HTML for the metabox.
 */
function wpyog_news_archive() {
	global $post;
	// Output the field
	$checkedStatus = ($post->post_status == 'archive')?'checked="checked"':'';
	echo '<input type="checkbox" name="archive_status" '.$checkedStatus .' >';
	echo '<label>News Archive</label>';
}
function wpypg_save_news_meta($post_id){
    if ( ! wp_is_post_revision( $post_id ) ){
		if ( 'news' == $_POST['post_type'] ) {
			if(!empty($_POST['archive_status'])){
				$status = 'archive';
			}else{
				$status = $_POST['post_status'];
			}
			// unhook this function so it doesn't loop infinitely
			remove_action('save_post', 'wpypg_save_news_meta');
			// update the post, which calls save_post again
			wp_update_post(array('ID'    =>  $post_id,'post_status'   =>$status));
			// re-hook this function
			add_action('save_post', 'wpypg_save_news_meta');
		}
	}
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

global $pagenow;
if($pagenow == 'post-new.php'){
	add_filter( 'siteorigin_panels_data', 'axxiem_panels_data_filter' , 10, 2 );
}

function axxiem_panels_data_filter( $panels_data, $post ){
	return unserialize('a:4:{s:7:"widgets";a:2:{i:0;a:7:{s:5:"title";s:0:"";s:4:"text";s:0:"";s:20:"text_selected_editor";s:7:"tinymce";s:5:"autop";s:2:"on";s:22:"so_sidebar_emulator_id";s:21:"sow-editor-5709410000";s:11:"option_name";s:17:"widget_sow-editor";s:11:"panels_info";a:7:{s:5:"class";s:31:"SiteOrigin_Widget_Editor_Widget";s:3:"raw";b:0;s:4:"grid";i:0;s:4:"cell";i:0;s:2:"id";i:0;s:9:"widget_id";s:36:"09f87e3d-e64d-4e85-aef9-5ecd3fbd42ac";s:5:"style";a:6:{s:27:"background_image_attachment";b:0;s:18:"background_display";s:4:"tile";s:15:"so_cpt_readonly";s:0:"";s:17:"content_alignment";s:4:"left";s:11:"title_color";s:7:"#443f3f";s:14:"headings_color";s:7:"#443f3f";}}}i:1;a:13:{s:5:"image";s:0:"";s:14:"image_fallback";s:0:"";s:4:"size";s:4:"full";s:5:"align";s:7:"default";s:11:"title_align";s:7:"default";s:5:"title";s:0:"";s:14:"title_position";s:6:"hidden";s:3:"alt";s:0:"";s:3:"url";s:0:"";s:5:"bound";s:2:"on";s:22:"so_sidebar_emulator_id";s:20:"sow-image-5709410001";s:11:"option_name";s:16:"widget_sow-image";s:11:"panels_info";a:7:{s:5:"class";s:30:"SiteOrigin_Widget_Image_Widget";s:3:"raw";b:0;s:4:"grid";i:0;s:4:"cell";i:1;s:2:"id";i:1;s:9:"widget_id";s:36:"963344e2-4ddb-4f14-8661-70c42aed56ee";s:5:"style";a:6:{s:27:"background_image_attachment";b:0;s:18:"background_display";s:4:"tile";s:15:"so_cpt_readonly";s:0:"";s:17:"content_alignment";s:4:"left";s:11:"title_color";s:7:"#443f3f";s:14:"headings_color";s:7:"#443f3f";}}}}s:5:"grids";a:1:{i:0;a:4:{s:5:"cells";i:2;s:5:"style";a:6:{s:12:"lsow_dark_bg";s:0:"";s:14:"cell_alignment";s:10:"flex-start";s:5:"align";s:0:"";s:16:"background_image";b:0;s:7:"overlay";s:0:"";s:13:"overlay_color";s:7:"#000000";}s:5:"ratio";d:0.61803397999999998;s:15:"ratio_direction";s:5:"right";}}s:10:"grid_cells";a:2:{i:0;a:4:{s:4:"grid";i:0;s:5:"index";i:0;s:6:"weight";d:0.61803399209205734;s:5:"style";a:0:{}}i:1;a:4:{s:4:"grid";i:0;s:5:"index";i:1;s:6:"weight";d:0.38196600790794272;s:5:"style";a:0:{}}}s:4:"name";s:5:"57097";}');
}

//BBpress New Topic Button // 
add_shortcode('axxiem_bbp_topic', 'wpmu_bbp_create_new_topic', 10);
function wpmu_bbp_create_new_topic(){
	
	if ( isset($_GET['ForumId']) ){
		
		return do_shortcode("[bbp-topic-form forum_id=".$_GET['ForumId']."]");
		
	}else{
		
		return do_shortcode("[bbp-topic-form]");
		
	}
}
//End BBpress New Topic Button //

function bbp_enable_visual_editor( $args = array() ) {
    $args['tinymce'] = true;
    return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'bbp_enable_visual_editor' );

add_action( 'edit_user_profile', 'wk_custom_user_profile_fields' );
 
function wk_custom_user_profile_fields( $user )
{
	$user_role = $user->roles[0]; 
	if(in_array($user_role,array('staff','supervisor','hr','fm_admin'))){
	echo '<h3 class="heading">Reporting Manager</h3>';
		
		$args = array('role__in' => array('supervisor','hr' ));
    	$users = get_users( $args );
		
		$reporting_to = get_the_author_meta( 'reporting_to', $user->ID ) 
    ?>
    
    <table class="form-table">
	<tr>
            <th><label for="contact">Reporting to</label></th>
 
	    <td><select type="text" class="input-text form-control" name="reporting_to" id="reporting_to" >
				<option value="0">Select Reporting Manager</option>
				<?php foreach($users as $user) { ?>
					<option value="<?php echo $user->ID; ?>" <?php if($reporting_to == $user->ID) { echo 'selected="selected"'; }?>><?php echo $user->data->display_name; ?></option>
			    <?php } ?>
			</select>
		        </td>
 
	</tr>
    </table>
    
    <?php }
}

add_action( 'user_new_form', 'axxiem_custom_user_profile_fields' );
function axxiem_custom_user_profile_fields(){
	echo '<table class="form-table">
	<tr>
        <th><label for="contact">Reporting to</label></th>
	    <td>
			<select type="text" class="input-text form-control" name="reporting_to" id="reporting_to" >
			</select>
		</td>
	</tr>
</table>'; ?>
<script>
jQuery.noConflict();
jQuery(document).ready( function(e){ 	
	jQuery('#role').on('change',function(){
		reportingManager();
	});
	reportingManager();
});
function reportingManager(){
	var role_id = jQuery('#role').val();
	if(role_id !==""){
		var searchData = {
			action: 'axxiem_reporting_manager',
			role_id:role_id,
		}
		jQuery.ajax({
			url: ajaxurl,
			type: "POST",
			data: searchData,
			success: function(data){
				jQuery('#reporting_to').html(data);
			},
			error: function(errorThrown){
				alert(errorThrown);
			} 
		});
	}
}
</script>
<?php }
add_action( 'edit_user_profile_update', 'wk_save_custom_user_profile_fields' );
 
/**
*   @param User Id $user_id
*/
function wk_save_custom_user_profile_fields( $user_id )
{
    if (array_key_exists('reporting_to', $_POST)) {
		update_user_meta( $user_id,'reporting_to',$_POST['reporting_to']); 
    }
}

add_action('user_register','axxiem_user_function'); 
function axxiem_user_function($user_id){
	$upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
	$wp_content_dir = $upload_dir;
	$users = get_user_by( 'id',$user_id);
	$user_name = $users->user_login;
	$role = $users->roles[0];
	if(in_array($role,array('staff','hr','supervisor','fm_admin'))){
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
		axxiem_custom_wp_filemanager_roles();
	}
}

add_action('delete_user','axxiem_delete_user'); 
function axxiem_delete_user($user_id){
	$users = get_user_by( 'id',$user_id);
	$user_name = $users->user_login;
	$role = $users->roles[0];
	axxiem_custom_wp_filemanager_roles();
	removeWPFileCustomer();
}
add_action( 'set_user_role','axxiem_user_role_update', 10, 2);
function axxiem_user_role_update($user_id)
{
    $users = get_user_by( 'id',$user_id);
	$user_name = $users->user_login;
	$role = $users->roles[0];
	axxiem_custom_wp_filemanager_roles();
}

add_action( 'wp_ajax_nopriv_axxiem_reporting_manager', 'axxiem_reporting_manager' );
add_action( 'wp_ajax_axxiem_reporting_manager', 'axxiem_reporting_manager' );
function axxiem_reporting_manager(){
	$role_id = $_POST['role_id'];
	$args = array('role__in' => array('supervisor','hr'));
	$users = get_users( $args );
	
	$html = '';
	if(in_array($role_id,array('staff','supervisor'))){
		$html .='<option value="0">Select reporting manager</option>';
		foreach($users as $user) {
			$html .='<option value="'.$user->ID.'">'.$user->data->display_name.'</option>';
		}
	}
	echo $html;
	wp_die();
}

// Function to change email address
function wpb_sender_email( $original_email_address ) {
    return 'info@demo11.axxiem.com';
}
 
// Function to change sender name
function wpb_sender_name( $original_email_from ) {
    return 'ADAPP';
}
 
// Hooking up our functions to WordPress filters 
add_filter( 'wp_mail_from', 'wpb_sender_email' );
add_filter( 'wp_mail_from_name', 'wpb_sender_name' );

add_action('save_post', 'axxiem_automatically_send_notifation', 10,3);
function axxiem_automatically_send_notifation($post_id, $post,$update){
	if ( 'topic' == $post->post_type || 'forum' == $post->post_type) {
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
		if (wp_is_post_autosave($post_id)) {
			return;
		}

		if (isset($post->post_status) && (! $update) && 'auto-draft' == $post->post_status) {
			return;
		 }
		
		$users = get_users( [ 'role__in' => [ 'staff'] ] );
		
		$subject = "New Topic Created: "  . $post->post_title;
		if ( 'forum' == $post->post_type){
			$subject = "New Forum Created: "  . $post->post_title;
		}
		$admin_email = get_option( 'admin_email' );
		$arrUsers = array();
		$current_user = wp_get_current_user();
		//$to = $current_user->user_email;
		$headers[] = "From: ADAPP <".$admin_email.">";
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		foreach($users as $user){
			$headers[] = "BCC: ".$user->data->user_email;
		}
		
		// create the from details 
		$to = $admin_email;
		// separate the users array
		// concatenate a message together
		$message  = 'Dear Member,'. "<br/><br/>";
		
		if ( 'forum' == $post->post_type){
			$form_link = home_url('/forums/forum/'.$post->post_name);
			$message .= 'Please be advised that a new Forum has been created which may interest you on demo11.axxiem.com. Please <a href="'. $form_link.'">click here</a> to visit the new Forum' . "<br/>";
		}else{
			$form_link = home_url('/forums/topic/'.$post->post_name); 
			$message .= 'Please be advised that a new Topic has been created which may interest you on demo11.axxiem.com. Please <a href="'. $form_link.'">click here</a> to visit the new topic' . "<br/>";
		}
	   
		$message .= 'Feel free to leave a comment' . "<br/><br/>";
		$message .= 'Thanks,' . "<br/>";
		$message .= 'Team ADAPP';
		// and finally send the email
		wp_mail($to, $subject , $message, $headers );
		return $post_ID;
	}
}
add_action( 'add_meta_boxes', 'axxiem_event_meta_box' );

function axxiem_event_meta_box(){
	add_meta_box('axxiem_event_title',__( 'DASA Event', 'dasaevent' ),'axxiem_event_meta_box_callback',array('tribe_events'),'side','default');
	add_meta_box('axxiem_product_category',__( 'Product Category', 'axxiem_product_category' ),'axxiem_product_category_meta_box_callback',array('tribe_events'),'side','default');
}

function axxiem_event_meta_box_callback( $post){
    $checkbox_value = get_post_meta( $post->ID, 'dasa_event', true );
	if($checkbox_value == '1') {
		echo "<input value='1' name ='dasa_event' type='checkbox' id='dasa_event' checked='checked'> Dasa Event";
	}else{
		echo "<input value='1' name ='dasa_event' type='checkbox' id='dasa_event'> Dasa Event";
	}
	
	
}

function axxiem_product_category_meta_box_callback( $post){
    $select_value = get_post_meta( $post->ID, 'axxiem_product_category', true );
	
	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false ;
	$cat_args = array(
		'orderby'    => $orderby,
		'order'      => $order,
		'hide_empty' => $hide_empty,
	);
 
	$product_categories = get_terms( 'product_cat', $cat_args );
 
	if( !empty($product_categories) ){
		echo '<select name="axxiem_product_category"><option value="">Select Category</option>';
		foreach ($product_categories as $key => $category) {
			$optionSelected = ($category->slug == $select_value)?'selected="selected"':'';
			echo '<option value="'.$category->slug.'" '.$optionSelected.'>';
			echo $category->name;
			echo '</option>';
		}
		echo '</select>';
	}
}

add_action('save_post', 'axxiem_event_save_postdata');
function axxiem_event_save_postdata($post_id){
	if($_POST['post_type'] == 'tribe_events'){
		$dasa_event = !empty($_POST['dasa_event'])?$_POST['dasa_event']:'';
		update_post_meta($post_id,'dasa_event',$dasa_event); 
		
		$axxiem_product_category = !empty($_POST['axxiem_product_category'])?$_POST['axxiem_product_category']:'';
		update_post_meta($post_id,'axxiem_product_category',$axxiem_product_category); 
	}
}

/**
 * Adds event start date to ticket order titles in email and checkout screens.
 *
 * @return string
 */
function tribe_add_date_to_order_title( $title, $item ) {
  $event = tribe_events_get_ticket_event( $item['product_id'] );
 
  if ( $event !== false ) {
    $title .= ' - ' . tribe_get_start_date( $event );
  }
 
  return $title;
}
 
add_filter( 'woocommerce_order_item_name', 'tribe_add_date_to_order_title', 100, 2 );

add_action( 'event_tickets_after_save_ticket', function( $post_id, $ticket ) {
    $product = wc_get_product( $ticket->ID );
    $product->set_catalog_visibility( 'visible' );
    $product->save();
}, 100, 2 );
function tribe_wootix_no_hijack() {
	if ( ! class_exists( 'Tribe__Tickets_Plus__Commerce__WooCommerce__Main' ) ) return;
	$woo_tickets = Tribe__Tickets_Plus__Commerce__WooCommerce__Main::get_instance();
	//remove_filter( 'post_type_link', array( $woo_tickets, 'hijack_ticket_link' ), 10, 4  );
}

add_action( 'init', 'tribe_wootix_no_hijack' );

/*
* replace read more buttons for out of stock items
**/
if (!function_exists('woocommerce_template_loop_add_to_cart')) {
	function woocommerce_template_loop_add_to_cart() {
		global $product;
		if (!$product->is_in_stock()) {
			echo '<a href="'.get_permalink().'" rel="nofollow" class="outstock_button">Out of Stock</a>';
		}else{
			woocommerce_get_template('loop/add-to-cart.php');
		}
	}
}

// Remove links from thumbnails
add_action( 'init', function() {

	// Woo core hooks
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

	// Theme hooks
	remove_action( 'wpex_woocommerce_loop_thumbnail_before', 'woocommerce_template_loop_product_link_open', 0 );
	remove_action( 'wpex_woocommerce_loop_thumbnail_after', 'woocommerce_template_loop_product_link_close', 11 );

} );

// Custom heading output
if ( ! function_exists( 'woocommerce_template_loop_product_title' ) ) {
	function woocommerce_template_loop_product_title() {
		echo '<h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2>';
	}
}
add_action( 'woocommerce_order_actions', 'axxiem_woo_order_actions' );
function axxiem_woo_order_actions( $actions ) {
    $actions['resend_order_processing_email'] =  'Resend Order Processing Email';
    return $actions;
}

add_action( 'woocommerce_order_action_resend_order_processing_email', 'mind_woo_resend_order_processing_email' );
function mind_woo_resend_order_processing_email( $order ) {
	$order_id = $order->get_id();
	$allmails = WC()->mailer()->emails;
	$email = $allmails['WC_Email_Customer_Processing_Order'];
	$email->trigger( $order_id );
	$order->add_order_note( '"Order Processing" Email Resent' );
}
//add order details to Stripe payment metadata
function axxiem_filter_wc_stripe_payment_metadata( $metadata, $order, $source ) {
    $order_data = $order->get_data();
    $count = 1;
    foreach( $order->get_items() as $item_id => $line_item ){
        $product = $line_item->get_product();
        $product_name = $product->get_name();
        $item_quantity = $line_item->get_quantity();
        $item_total = $line_item->get_total();
        $metadata['Ticket/Product '.$count] = 'Product name: '.$product_name.' | Quantity: '.$item_quantity;
        $count += 1;
    }
	wp_mail('psudhir20@gmail.com','WooCommerce Stripe Meta Tag',$product_name);
    return $metadata;
}
add_filter( 'wc_stripe_payment_metadata', 'axxiem_filter_wc_stripe_payment_metadata', 10, 3 );

add_filter( 'wc_stripe_generate_payment_request', 'axx_filter_wc_stripe_payment_descriptionmod', 3, 10 );
function axx_filter_wc_stripe_payment_descriptionmod( $post_data, $order, $source ) {
	foreach( $order->get_items() as $item_id => $line_item ){
		$item_data = $line_item->get_data();
		$product = $line_item->get_product();
		$product_name = $product->get_name();
	}
	$post_data['description'] = sprintf( __( '%1$s - Order %2$s | %3$s', 'woocommerce-gateway-stripe' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $order->get_order_number() , $product_name );
	return $post_data;
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
		$class = ($wp_query->query_vars['event_status'] == "upcomming") ? ' class="current"' : '';
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
		$class = ($wp_query->query_vars['event_status'] == "completed") ? ' class="current"' : '';
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

function axxiem_custom_wp_filemanager_roles(){
	$role_array['wp_filemanager_nonce_field'] = 'ff74dbb86b';
	$role_array['_wp_http_referer'] = '/wp-admin/admin.php?page=wp_file_manager_settings';
	$role_array['fm_user_roles'] = array(0 => 'staff',1 => 'staff_pro',2 => 'supervisor',3 => 'hr',4 => 'fm_admin');
	
	$role_array['private_folder_access'] = '';
	$role_array['fm_max_upload_size'] = '2';
	$role_array['lang'] = 'en';
	$role_array['theme'] = 'default';
	$role_array['wp_fm_view'] = 'list';
	$role_array['code_editor_theme'] = 'default';
	$role_array['select_user_roles'] = array (1 => 'staff',2 => 'staff',3 => 'fm_admin',4 => 'fm_admin',5 => 'hr',6 => 'hr',7=> 'hr');
	$role_array['seprate_folder'] = array(
		0 => '',
		1 => '*',
		2 => 'wp-content/uploads/wp-file-manager-pro/Shared_Documents',
		3 => '*',
		4 => 'wp-content/uploads/wp-file-manager-pro/',
		5 => '*',
		6 => 'wp-content/uploads/wp-file-manager-pro/users',
		7 => 'wp-content/uploads/wp-file-manager-pro/Shared_Documents');
	
	//HR - restrict My and Personnel folders
	$hr_hidden_dir = [];
	$all_users = get_users( [ 'role__in' => ['staff','supervisor','hr','fm_admin'] ] );
	if ( ! empty( $all_users ) ) {
		$k=0;
		foreach($all_users as $st_user){
			$hr_hidden_dir[$k++] = $st_user->data->user_login.'/my|'.$st_user->data->user_login.'/personnel';
		}
	}
	$hr_hidden_dir_str = implode('|',$hr_hidden_dir);
	
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
	$fmadmin_hidden_dir = [];
	$all_users = get_users( [ 'role__in' => ['staff','supervisor','hr'] ] );
	if ( ! empty( $all_users ) ) {
		$k=0;
		foreach($all_users as $st_user){
			$fmadmin_hidden_dir[$k++] = 'users/'.$st_user->data->user_login.'/hr';
		}
	}
	$fmadmin_hidden_dir_str = implode('|',$fmadmin_hidden_dir);
	
	//Hide all user who are not Staff, Supervisor, HR or FM Admin - For FM 
	$all_users = get_users( [ 'role__not_in' => ['staff','supervisor','hr','fm_admin'] ] );
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
	$non_adapp_users_str = implode('|',$non_adapp_users);
	$non_adapp_users_for_hr_str = implode('|',$non_adapp_users_for_hr);
	
	$role_array['restrict_folders'] = array (
		1 => '',
		2 => 'adapp_supervisor_information',
		3 => '',
		4 => $fmadmin_hidden_dir_str.'|'.$non_adapp_users_str.'|fm_backup',
		5 => '',
		6 => $hr_hidden_dir_str.'|'.$non_adapp_users_for_hr_str,
		7 => 'adapp_supervisor_information',
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
				'meta_compare' => "="
			); 
			$reporting_staff_users = new WP_User_Query( $args );
			
			if ( ! empty( $reporting_staff_users->results ) ) {
				foreach($reporting_staff_users->results as $st_user){
					$hide_staff_folder[$j++] = $st_user->data->user_login .'/hr';
					$exclude_staff_user[$j++] = $st_user->ID;
				}
			}
			$args  = array(
				'role__in ' => array('staff','supervisor','hr','fm_admin','administrator')
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

			//Add their own folder access
			$role_array['select_users'][$i] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$i] = '*';
			$role_array['restrict_user_folders'][$i] = '';
			$role_array['restrict_user_files'][$i] = '';
			$role_array['users_fileoperations_'.$i] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
			//Add shared documents access
			$k = $i+1;
			$role_array['select_users'][$k] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$k] = 'wp-content/uploads/wp-file-manager-pro/Shared_Documents/';
			$role_array['restrict_user_folders'][$k] = '';
			$role_array['restrict_user_files'][$k] = '';
			$role_array['users_fileoperations_'.$k] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
			//Add their restricted users folder access
			$m = $i+2;
			$role_array['select_users'][$m] = $s_user->data->user_login;
			$role_array['user_seprate_folder'][$m] = 'wp-content/uploads/wp-file-manager-pro/users/';
			$role_array['restrict_user_folders'][$m] = $restrict_user_folders;
			$role_array['restrict_user_files'][$m] = '';
			$role_array['users_fileoperations_'.$m] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
		
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
					'meta_compare' => "="
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
				$all_users = get_users( [ 'role__in' => ['staff','supervisor','hr','fm_admin'] ] );
				if ( ! empty( $all_users ) ) {
					$k=0;
					foreach($all_users as $st_user){
						//check if user is reporting to HR, then dont hide my and personnel
						if(!in_array($st_user->ID,$reporting_staff_users_arr))
							$hr_hidden_dir[$k++] = $st_user->data->user_login.'/my|'.$st_user->data->user_login.'/personnel';
					}
				}
				$hr_hidden_dir_str = implode('|',$hr_hidden_dir);


				//Add their own folder access
				$role_array['select_users'][$i] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$i] = '*';
				$role_array['restrict_user_folders'][$i] = '';
				$role_array['restrict_user_files'][$i] = '';
				$role_array['users_fileoperations_'.$i] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
				//Add shared documents access
				$k = $i+1;
				$role_array['select_users'][$k] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$k] = 'wp-content/uploads/wp-file-manager-pro/Shared_Documents/';
				$role_array['restrict_user_folders'][$k] = '';
				$role_array['restrict_user_files'][$k] = '';
				$role_array['users_fileoperations_'.$k] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');
				//Add restricted folders
				$m = $i+2;
				$role_array['select_users'][$m] = $s_user->data->user_login;
				$role_array['user_seprate_folder'][$m] = 'wp-content/uploads/wp-file-manager-pro/users/';
				$role_array['restrict_user_folders'][$m] = $hr_hidden_dir_str.'|'.$non_adapp_users_for_hr_str;
				$role_array['restrict_user_files'][$m] = '';
				$role_array['users_fileoperations_'.$m] = array (0 => 'mkdir', 1 => 'mkfile',2 => 'rename',3 => 'duplicate', 4 => 'archive',5 => 'extract',6 => 'cut', 7 => 'rm',8 => 'empty',9 => 'resize');

				$i = $i+3;
			}
		}
	
	$role_array['display_fm_on_pages'] = 'after_content';
	$role_array['without_login_shortcode'] = '';
	update_option( 'wp_filemanager_options', $role_array );
}

function removeWPFileCustomer(){
	$blogusers = get_users( array( 'role__not_in' => array( 'staff','supervisor','hr','fm_admin','administrator') ) );
	$upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
	$wp_content_dir = $upload_dir;
	foreach ( $blogusers as $user ) {
		$user_name = $user->data->user_login;
	    $path = ABSPATH .'wp-content/uploads/wp-file-manager-pro/users/'.$user_name;
		if (is_dir($path)){
			deleteAllPath($path,true);
		}
	}
}
//add_action( 'admin_init', 'findOfflinefiles' );
function deleteAllPath($dir, $remove = false) {
 	$structure = glob(rtrim($dir, "/").'/*');
 	if (is_array($structure)) {
 		foreach($structure as $file) {
 			if (is_dir($file))
 				deleteAllPath($file,true);
 			else if(is_file($file))
 				unlink($file);
 		}
 	}
 	if($remove)
 	rmdir($dir);
}

function findOfflinefiles(){
	global $wpdb;
	$filePaths = $wpdb->get_results($wpdb->prepare("Select file_path, file_name from {$wpdb->prefix}wpfb_files where file_offline = %s",'1'));
	if(!empty($filePaths)){
 		foreach($filePaths as $file){
			$path = ABSPATH .'wp-content/uploads/wp-file-manager-pro/Shared_Documents/'.$file->file_path;
			$copy_path = ABSPATH .'wp-content/uploads/wp-file-manager-pro/offline_documents/'.$file->file_path;
			if(!file_exists($copy_path)){
				if(file_exists($path)){
					if(copy($path, $copy_path)){
						echo $path."<br/>";
					}
				}
			}
		}
		
	}
}
