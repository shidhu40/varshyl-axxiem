<?php $bgArray = ['blueBg','yellowBg','greenBg']; ?>
<div class="adapp-leftbar">
	<?php
	if (!empty($posts_query->have_posts())) { ?>
		<div class="blog-posts">
			<h2 class="post-heading">
				Weekly Notes <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/clock.svg'; ?>" border="0" />
			</h2>
			<div class="post-listing">
				<ul>
					 <?php while ( $posts_query->have_posts() ) {
            				$posts_query->the_post();
            				$shared_post_id = get_the_ID();
            				$author_id = get_post_field('post_author', $shared_post_id);
            				// Get the author's display name
            				$author_name = get_the_author_meta('display_name', $author_id);
            				$publish_date = get_the_date('F j, Y');
            				// Get the post content
            				$post_content = get_the_content();
        			?>
						<li id="post-<?php the_ID(); ?>" class="adapp-post-list" data-paged="1">
							<div class="user-name <?php echo $bgArray[array_rand($bgArray, 1)];?> ">
								<?php echo substr($author_name, 0, 1); ?>
							</div>
							<div class="post-details">
								<h3><?php echo get_the_title( $shared_post_id ); ?></h3>
								<div class="post-content-short"><?php echo( $post_content ); ?></div>
								<div class="user-date"><?php echo $publish_date;?></div>
							</div>
						</li>
        			<?php } ?>
				</ul>
			</div>
			<?php if($posts_query->max_num_pages > 1) { ?>
				<div class="adapp_ajax_post_pagination">
					<a href="javascript:void(0)" class="adapp-post-ajax-loader" limit = "<?php echo $limit; ?>" >Load More</a>
				</div>
			<?php } ?>
		</div>
	<?php wp_reset_postdata(); } ?>	
</div>
