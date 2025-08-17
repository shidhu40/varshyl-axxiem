<?php
/**
 * The template for displaying search results pages.
 *
 * @package Sydney
 */

get_header(); ?>
<script>var pfHeaderImgUrl = '';var pfHeaderTagline = '';var pfdisableClickToDel = 0;var pfHideImages = 0;var pfImageDisplayStyle = 'block';var pfDisablePDF = 0;var pfDisableEmail = 0;var pfDisablePrint = 0;var pfCustomCSS = '';var pfEncodeImages = 0;var pfShowHiddenContent = 0;var pfBtVersion='2';(function(){var js,pf;pf=document.createElement('script');pf.type='text/javascript';pf.src='//cdn.printfriendly.com/printfriendly.js';document.getElementsByTagName('head')[0].appendChild(pf)})();</script>	
<div class="search-banner">
<div class="lsow-hero-header lsow-section-bg-cover" style="padddd-top:100px; paddimg-bottom:100px; background-image: url(https://demo11.axxiem.com/wp-content/uploads/2018/03/inner-banner-1.jpg);">	 	<div class="lsow-overlay" style="background-color: rgba(51, 51, 51, 0.7);"></div>
    <div class="lsow-header-content">
		<div class="lsow-standard-header">
			<div class="container"> <header class="page-header">
				<h3><?php printf( __( 'Search Results for: %s', 'sydney' ), '<span>' . get_search_query() . '</span>' ); ?></h31>
			</header><!-- .page-header -->  </div>       
        </div>		
	</div>
</div></div>

	<div id="primary" class="content-area col-md-12">
		<main id="main" class="post-wrap" role="main">

		<?php if ( have_posts() ) : ?>

			
			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'content', 'search' );
				?>

			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>