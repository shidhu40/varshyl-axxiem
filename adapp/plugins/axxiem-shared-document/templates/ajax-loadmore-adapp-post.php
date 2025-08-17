<?php $bgArray = ['blueBg','yellowBg','greenBg']; ?>
	<?php if (!empty($posts_query->have_posts())) { 
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				$shared_post_id = get_the_ID();
				$author_id = get_post_field('post_author', $shared_post_id);
				// Get the author's display name
				$author_name = get_the_author_meta('display_name', $author_id);
				$publish_date = get_the_date('F j, Y');
				// Get the post content
				$post_content = get_the_content();
			?>
				<li id="post-<?php the_ID(); ?>" class="adapp-post-list" data-paged="<?php echo $paged;?>">
					<div class="user-name <?php echo $bgArray[array_rand($bgArray, 1)];?> ">
						<?php echo substr($author_name, 0, 1); ?>
					</div>
					<div class="post-details">
						<h3><?php echo get_the_title( $shared_post_id ); ?></h3>
						<div class="post-content-short"><?php echo wp_trim_words( $post_content, 40, '...' ); ?></div>
						<div class="user-date"><?php echo $author_name;?>  •  <?php echo $publish_date;?></div>
					</div>
				</li>
        			<?php } 
	wp_reset_postdata(); } 
?>	

