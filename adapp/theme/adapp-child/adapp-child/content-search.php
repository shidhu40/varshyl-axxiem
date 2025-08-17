<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Sydney
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php 
		$redirectUrl = get_permalink();
		
		if ('accordion-content' == get_post_type()) { 
			$post_id = get_the_ID();
			$post_slug = get_post_field( 'post_name', $post_id );
			$cat_terms 		= get_the_terms( $post_id, 'accordion_category' ); 
			$cat_slug = $cat_terms[0]->slug;
			$redirectUrl = get_option('home') . '/' . $cat_slug . '#' . $post_slug ; 
		}
		?>
		<?php the_title( sprintf( '<h2 class="title-post entry-title"><a href="%s" rel="bookmark">', esc_url( $redirectUrl ) ), '</a></h2>' ); ?>

		<?php if ( 'post' == get_post_type() && get_theme_mod('hide_meta_index') != 1 ) : ?>
		<div class="meta-post">
			<?php sydney_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-post" <?php sydney_do_schema( 'entry_content' ); ?>>
		<?php if ( (get_theme_mod('full_content_home') == 1 && is_home() ) || (get_theme_mod('full_content_archives') == 1 && is_archive() ) ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php if ('accordion-content' == get_post_type()) { 
	echo $str_string = wpnw_limit_words( $post_id, $excerpt, 40 , '...');
	echo '<br/><a href="' . $redirectUrl . '" class="more">Read More</a>';
} else {
	the_excerpt();
}?>
		<?php endif; ?>

		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'sydney' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-post -->

	<footer class="entry-footer">
		<?php sydney_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->