<?php

/**
 * The template for displaying latest news items.
 *
 * @package WordPress
 * @subpackage Choros1
 * Template Name: News
 */
get_header(); ?>
<div class="search-banner">
<div class="lsow-hero-header lsow-section-bg-cover" style="padddd-top:100px; paddimg-bottom:100px; background-image: url(http://demo11.axxiem.com/wp-content/uploads/2018/03/inner-banner-1.jpg);">	 	<div class="lsow-overlay" style="background-color: rgba(51, 51, 51, 0.7);"></div>
    <div class="lsow-header-content">
		<div class="lsow-standard-header">
			<div class="container"> <header class="page-header">
				<h3>Latest News</h3>
			</header><!-- .page-header -->  </div>       
        </div>		
	</div>
</div></div>
<!--content area starts here-->
<div id="content-wrapper">
	<!--content goes here-->
	<div id="content-area-news" class="float-left">
		<div class="entry-content">
			<p><?php $args=array(
				'news_items' => 25
				);			
			jep_latest_news_loop($args);?></p>
		</div><!-- .entry-content -->		
	</div>
	<!--content goes ends-->
</div>
<script type="text/javascript">
function show_hide(div_id, bool_val) { 
	if(bool_val == 'show'){
		document.getElementById('news_content_'+div_id).style.display = "none";
		document.getElementById('news_content_large_'+div_id).style.display = "block";
	} else {
		document.getElementById('news_content_'+div_id).style.display = "block";
		document.getElementById('news_content_large_'+div_id).style.display = "none";
	}
}
</script>
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
