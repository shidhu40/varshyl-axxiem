<ul>
	<?php if (!empty($posts_query->have_posts())) { 
		while ( $posts_query->have_posts() ) {
			$posts_query->the_post();
			$shared_post_id = get_the_ID();
			$upload_file_array = get_field( 'upload_file',$shared_post_id);
			$upload_file = $upload_file_array['url'];
		?>
		<li><a href="<?php echo $upload_file;?>" target="_blank" data-id="<?php echo $shared_post_id;?>"> <?php echo get_the_title( $shared_post_id ); ?></a></li>
		<?php }
		wp_reset_postdata();
	} ?>
</ul>