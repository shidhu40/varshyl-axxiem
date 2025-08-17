<?php 
/**
 * Template Name: Login Page AA
 * @package WordPress
 * @subpackage Choros1
 * Template Name: News
 */
get_header(); ?>
<div class="search-banner" style="display:none">
<div class="lsow-hero-header lsow-section-bg-cover" style="padddd-top:100px; paddimg-bottom:100px; background-image: url(http://demo11.axxiem.com/wp-content/uploads/2018/03/inner-banner-1.jpg);">	 	<div class="lsow-overlay" style="background-color: rgba(51, 51, 51, 0.7);"></div>
    <div class="lsow-header-content">
		<div class="lsow-standard-header">
			<div class="container"> <header class="page-header">
				<h3>Staff Login</h3>
			</header><!-- .page-header -->  </div>       
        </div>		
	</div>
</div></div>
<div id="content-wrapper">
	<!--content goes here-->
	<div id="content-area-news" class="float-left">
		<div class="entry-content">
        <?php 
            global $user_login;
            // In case of a login error.
            if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    	            <div class="aa_error">
    		            <p><?php _e( 'FAILED: Try again!', 'AA' ); ?></p>
    	            </div>
            <?php 
                endif;
            // If user is already logged in.
            if ( is_user_logged_in() ) : ?>

                <div class="aa_logout"> 
                    
                    <?php 
                        _e( 'Hello', 'AA' ); 
                        echo $user_login; 
                    ?>
                    
                    </br>
                    
                    <?php _e( 'You are already logged in.', 'AA' ); ?>

                </div>

                <a id="wp-submit" href="<?php echo wp_logout_url(); ?>" title="Logout">
                    <?php _e( 'Logout', 'AA' ); ?>
                </a>

            <?php 
                // If user is not logged in.
                else: 
                
                    // Login form arguments.
                    $args = array(
                        'echo'           => true,
                        'redirect'       => home_url( '/wp-admin/' ), 
                        'form_id'        => 'loginform',
                        'label_username' => __( 'Username' ),
                        'label_password' => __( 'Password' ),
                        'label_remember' => __( 'Remember Me' ),
                        'label_log_in'   => __( 'Log In' ),
                        'id_username'    => 'user_login',
                        'id_password'    => 'user_pass',
                        'id_remember'    => 'rememberme',
                        'id_submit'      => 'wp-submit',
                        'remember'       => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    ); 
                    
                    // Calling the login form.
                    wp_login_form( $args );
                endif;
        ?> 

	</div>
	</div>
	</div>
	<!-- /section -->

<?php get_footer(); ?>