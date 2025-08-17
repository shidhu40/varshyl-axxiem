<?php
/**
 * Template Name: Shared Document Template
 *
 * Shared Document Template
 *
 * @author Axxiem
 * @since 1.0.0
 */
ob_clean(); 
ob_start();
if (!is_user_logged_in()) {
	$url = home_url('/login/');
	wp_safe_redirect( $url );
	exit;
}
if (is_user_logged_in()){
	$user = wp_get_current_user();
    $username = $user->user_login; 
	$role = $user->roles[0];
}
get_header(); 
global $post;
$page_slug = get_post_field('post_name', $post->ID);
?>
<style>
	.breadcrumb{
		display:none;
	}
</style>
<div id="primary" class="content-area col-md-12">
	<main id="main" class="post-wrap" role="main">
		<article <?php post_class(); ?>>
			<div class="addapp-dashboard">
				<div class="adapp-nav">
					<ul>
						<li class="<?php echo ($page_slug == 'dashboard') ? 'active' : '' ; ?>"><a href="<?php echo esc_url(site_url( '/dashboard/' )); ?>">Dashboard</a></li>
						<li class="<?php echo ($page_slug == 'adapp-post') ? 'active' : '' ; ?>"><a href="<?php echo esc_url(site_url( '/adapp-post/' )); ?>">Posts</a></li>
						<li class="<?php echo ($page_slug == 'adms') ? 'active' : '' ; ?>"><a href="<?php echo esc_url(site_url( '/adms/' )); ?>">My Documents</a></li>
						<li class="<?php echo ($page_slug == 'documents') ? 'active' : '' ; ?>" ><a href="<?php echo esc_url(site_url( '/documents/' )); ?>">Shared Documents</a></li>
						<li class="<?php echo ($page_slug == 'event-calendar') ? 'active' : '' ; ?>"><a href="<?php echo esc_url(site_url( '/dashboard/event-calendar/' )); ?>">Events Calendar</a></li>
						<li class="<?php echo ($page_slug == 'weekly-notes') ? 'active' : '' ; ?>"><a href="<?php echo esc_url(site_url( '/weekly-notes/' )); ?>">Weekly Notes</a></li>
						<li class="logged-user">
							<?php if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	$username = $current_user->display_name;
							?>

							<a href="<?php echo esc_url( get_edit_profile_url( $current_user->ID ) ); ?>" class="active-user"><?php echo esc_html( $username ); ?></a> | <a href="<?php echo esc_url( admin_url() ); ?>" class="dashboard-link">
							<img src="https://demo11.axxiem.com/wp-content/plugins/axxiem-shared-document/images/dashboard.png" alt="Dashboard" style="width: 24px; height: 24px; vertical-align: middle;">
							</a>  | 
							<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">Logout</a>
							<?php } else { ?>
							<a href="<?php echo esc_url( wp_login_url() ); ?>">Login</a>
							<?php } ?>
						</li>
				</ul>
			</div>
			<div class="entry-post">
				<?php the_content(); ?>
				<div class="adapp-rightbar">
						<div class="featured-block">
							<h3>Featured Documents </h3>
							<?php echo do_shortcode('[axxiem-feature-document-list]'); ?>
						</div>
						<div class="featured-block recent-block">
							<h3>Recent Documents </h3>
							<?php echo do_shortcode('[axxiem-recent-document-view]'); ?>
						</div>
					</div>
				</div><!-- .entry-post -->
			</div>
			<footer class="entry-footer">
				<?php sydney_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-## -->	
	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>