<?php 
ob_clean(); 
ob_start();
if (!is_user_logged_in()) {
	if (strpos($_SERVER['REQUEST_URI'],'/forums/') !== false){
		if(!session_id())
		{
			session_start();
		}
		$_SESSION['refere_url'] = $_SERVER['REQUEST_URI'];
		$url = home_url('/login/');
		wp_safe_redirect( $url );
		exit;
	}
	if($_SERVER['REQUEST_URI']=='/dashboard/event-calendar/'){
		$url = home_url('/event-calendar/');
		wp_safe_redirect( $url );
		exit;
	}
}else{
	if(!current_user_can('customer')){
		if($_SERVER['REQUEST_URI']=='/event-calendar/'){
			$redirectUrl = home_url('/dashboard/event-calendar/');
			header("Location: $redirectUrl");
			exit;
		}
	}
}
if($_SERVER['REQUEST_URI']=='/shop/'){
	$url = home_url('/event-calendar/');
	wp_safe_redirect( $url );
	exit;
}
$body_id = '';
if (is_user_logged_in()) {	
	if(current_user_can('staff')){
		$body_id = 'axxi_staff';
	}
	if(current_user_can('customer')){
		$body_id = 'axxi_customer';
		if (strpos($_SERVER['REQUEST_URI'],'/forums/') !== false || $_SERVER['REQUEST_URI']=='/dashboard/'){
			$redirectUrl = home_url('/my-account/');
			header("Location: $redirectUrl");
			exit;
		}
		if($_SERVER['REQUEST_URI']=='/dashboard/event-calendar/'){
			$redirectUrl = home_url('/event-calendar/');
			header("Location: $redirectUrl");
			exit;
		}
	}
}

/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sydney
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="stylesheet" href="https://demo11.axxiem.com/wp-content/themes/sydney/style.css?ver=" type="text/css" media="all" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
	<?php if ( get_theme_mod('site_favicon') ) : ?>
		<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
	<?php endif; ?>
<?php endif; ?>

<?php wp_head(); ?>

<link href="https://fonts.googleapis.com/css?family=Noto+Serif:400,700,700i" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:300,300i,400,400i,500,500i,600,600i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Carter+One" rel="stylesheet"> 
<script> 
  var inline = 1; 
</script>	
<meta name="google-site-verification" content="XHTRJNO8k9F3MbvFe0gGPT68gBIBwBibYssfYBV67q4" />
<script>
 //var pfHeaderImgUrl = 'https://demo11.axxiem.com/wp-content/uploads/2018/11/Adapp-print-logo.png';
 //var pfHeaderTagline = 'ADAPP.org';
 var pfdisableClickToDel = '1';
 var pfImagesSize = 'medium';
 var pfImageDisplayStyle = 'right';
 var pfEncodeImages = '0';
 var pfShowHiddenContent  = '1';
 var pfDisableEmail = '0';
 var pfDisablePDF = '0';
 var pfDisablePrint = '0';
 var pfCustomCSS = 'https://demo11.axxiem.com/wp-content/themes/adapp-child/printfriendly.css';
 var pfPlatform = 'WordPress';
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ua = navigator.userAgent;

    if (ua.includes("wv") || ua.includes("Version/")) {
        document.body.classList.add("in-app-webview");
    } 
});	

</script>
<style>
.in-app-webview .site-header,
.in-app-webview .site-footer {
    display: none !important;
    opacity: 0 !important;
    height: 0px !important;
    overflow: hidden !important;
    position: absolute !important;
    top: -9999px !important;
}
.in-app-webview.single-tribe_events	div#content {
    margin-top: -80px!important;
}
.in-app-webview	.tribe-events-single {
    margin-top: 0;
}	
.in-app-webview	.page-wrap {
    padding-bottom: 0;
}
.in-app-webview	.single .hentry {
    margin-bottom: 0;
}
.page-wrap .events-for-mobile-back {
	display:none;
}
.in-app-webview	.page-wrap .events-for-mobile-back {
    padding: 20px 0;
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
	display:block;
}
</style>
</head>
<!--<div id="myNav" class="overlay2" style="width:0%">
					  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
					 <div class="overlay-content">
						<?php
					wp_nav_menu( array( 
						'theme_location' => 'my-custom-menu', 
						'container_class' => 'custom-menu-class' ) ); 
					?>
	</div></div>-->

<body <?php body_class(); ?> id="<?php echo $body_id; ?>">
<?php wp_body_open(); ?>
<?php do_action('sydney_before_site'); //Hooked: sydney_preloader() ?>

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sydney' ); ?></a>

	<?php do_action('sydney_before_header'); //Hooked: sydney_header_clone() ?>

	<header id="masthead" class="site-header" role="banner" style="border-bottom: 4px solid #a9c041;">
		<div class="header-wrap">
           <div class="container">
                <div class="row">
				<div class="col-md-3 col-sm-8 col-xs-12" style="display:none">
		        <?php if ( get_theme_mod('site_logo') ) : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><img class="site-logo" src="<?php echo esc_url(get_theme_mod('site_logo')); ?>" alt="<?php bloginfo('name'); ?>" /></a>
		        <?php else : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>	        
		        <?php endif; ?>
				</div>
				<div class="col-md-9 col-sm-8 col-xs-12" >
<h3>ADAPP Staff Documents</h3>
				</div>
					<div class="header-top-right">
						  <a href="https://demo90.axxiem.com/" target="_blank">
							  <img src="https://demo11.axxiem.com/wp-content/uploads/2024/12/logo-adapp.png" border="0&quot;&quot;"><br>Go to ADAPP</a></div>
				
				</div>
			</div>
			
		</div>
	</header><!-- #masthead -->

	<div id="content" class="page-wrap" style="padding-top: 80px !important;">

		<div class="container content-wrapper">
			<div class="row">	
				<div class="breadcrumb"><div class="bread-inner"><?php global $post;

$queried_object = get_queried_object();
        $obj = get_post_type_object( 'tribe_events' );
 $from = '';
        $to = ' » ' . $obj->labels->name . '';
        $to .= ' » '. $queried_object->post_title .'';
       // $output = str_replace( $from, $to, $output );
        //echo $output; 
if($post->post_type == 'topic'){ 
			if(empty($post->ID)){
				echo "<a href='".get_option('home')."'>ADAPP</a> > <a href='".home_url('/dashboard/')."'>Dashboard</a> > <a href='".home_url('/topics/')."' class='current-item'>Topic</a> ";	
			}else{
				echo "<a href='".get_option('home')."'>ADAPP</a> > <a href='".home_url('/dashboard/')."'>Dashboard</a> > <a href='".home_url('/topics/')."' class=''>Topic</a> > <a href='' class='current-item'>". $post->post_name."</a>";	
			}
			
		}elseif($post->post_type == 'forum'){ 
			if(empty($post->ID)){
				echo "<a href='".get_option('home')."'>ADAPP</a> > <a href='".home_url('/dashboard/')."'>Dashboard</a> > <a href='".home_url('/forums/')."' class='current-item'>Forums</a> ";	
			}else{
				echo "<a href='".get_option('home')."'>ADAPP</a> > <a href='".home_url('/dashboard/')."'>Dashboard</a> > <a href='".home_url('/forums/')."' class=''>Forums</a> > <a href='' class='current-item'>". $post->post_name."</a>";	
			}
			
		}elseif($post->post_type == 'news'){
	echo "<a href='";
	echo get_option('home');
	echo "'>";
	echo "ADAPP";
	echo "</a>";
	$childUrl = home_url('/latest-news/');
	echo $output =  " > <a href='".$childUrl."'>News</a> > ";
	echo  "<a class='current-item' href='";
	echo get_permalink( get_page_by_path($post->post_name));
	echo "'>";
	echo the_title();
	echo "</a>";
}else{ if(function_exists('bcn_display'))
{
    global $wp;
$url = home_url( $wp->request );
$current_url =  parse_url($url, PHP_URL_PATH);
$subPath = explode('event/',$current_url);
if(isset($subPath[1]) && !empty($subPath[1])){
	echo "<a href='";
	echo get_option('home');
	echo "'>";
	echo "ADAPP";
	echo "</a>";
	$childUrl = home_url('/event-calendar/');
	echo $output =  " > <a href='".$childUrl."'>Events</a> > ";
	
	echo  "<a class='current-item' href='";
	echo get_permalink( get_page_by_path($subPath[1]));
	echo "'>";
	echo the_title();
	echo "</a>";
	
	
}else{
	if($_SERVER['REQUEST_URI']=='/event-calendar/'){
		bcn_display();
		$childUrl = home_url('/event-calendar/');
		echo $output =  " > <a class='current-item' href='".$childUrl."'>Event Calendar</a> ";
	}elseif($_SERVER['REQUEST_URI'] =='/attendee-registration/?provider=tribe_wooticket'){
		echo "<a href='";
	echo get_option('home');
	echo "'>";
	echo "ADAPP";
	echo "</a>";
	$childUrl = home_url('/cart/');
	echo $output =  " > <a href='".$childUrl."'>Cart</a> > <a class='current-item' href=''>Attendee Registration</a>";
		
	}else{
		bcn_display();
	}
}
	
					} }
			?><ul class="like-share-container">
						<li><a href="http://adappresources.connectwithkids.com/"><i class="fa fa-share"></i> Share</a><?php echo do_shortcode('[Sassy_Social_Share count="1" text="aa"]') ?> </li>
						<li><i class="fa fa-print"></i> <?php if( function_exists( 'pf_current_page_button' ) ) { echo pf_current_page_button(); } ?> </li>
				
			</ul>
					</div></div>
				
<script>
jQuery(document).ready(function($){
	jQuery(document).on('click','#search-toggle',function() {
		jQuery('.teafields-site-search').toggle();
		return false;
	});
	if($( ".news_li" ).length < 1) {
		$('#pg-2-5').hide();
	}
	if($( ".type-tribe_events" ).length < 1) {
		$('#pg-2-7').hide();
	}

});
</script>
<style>.site-header h3{padding:20px 0 0 0;} .site-header.float-header h3{padding:0px 0 0 0; font-size:16px;}
</style>
