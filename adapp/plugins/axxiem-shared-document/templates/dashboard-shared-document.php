<?php $bgArray = ['blueBg','yellowBg','greenBg']; ?>
<div class="adapp-leftbar">
	<?php if (!empty($posts_query->have_posts())) { ?>
		<div class="blog-posts">
			<h2 class="post-heading">
				Posts <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/clock.svg'; ?>" border="0" />
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
					<li>
						<div class="user-name <?php echo $bgArray[array_rand($bgArray, 1)];?> "><?php echo substr($author_name, 0, 1); ?></div>
						<div class="post-details">
							<h3><?php echo get_the_title( $shared_post_id ); ?></h3>
							<div class="post-content-short"><?php echo ( $post_content ); ?></div>
							<div class="user-date"><?php echo $publish_date;?></div>
						</div>
					</li>
					<?php } ?>
				</ul>
		</div>
	</div>
	<div class="view-moore"><a href="https://demo11.axxiem.com/adapp-post/">View More <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/arrow-right.svg'; ?>" border="0" /></a></div>
	<?php wp_reset_postdata(); } ?>	
	
	<?php if (!empty($weekly_posts_query->have_posts())) { ?>
		<div class="blog-posts">
			<h2 class="post-heading">
				Weekly Notes <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/clock.svg'; ?>" border="0" />
			</h2>
			<div class="post-listing">
				<ul>
					<?php while ( $weekly_posts_query->have_posts() ) {
						$weekly_posts_query->the_post();
						$shared_post_id = get_the_ID();
						$author_id = get_post_field('post_author', $shared_post_id);
						// Get the author's display name
						$author_name = get_the_author_meta('display_name', $author_id);
						$publish_date = get_the_date('F j, Y');
						// Get the post content
						$post_content = get_the_content(); ?>
						<li>
							<div class="user-name <?php echo $bgArray[array_rand($bgArray, 1)];?> "><?php echo substr($author_name, 0, 1); ?></div>
							<div class="post-details">
								<h3><?php echo get_the_title( $shared_post_id ); ?></h3>
								<div class="post-content-short"><?php echo ( $post_content ); ?></div>
								<div class="user-date"><?php echo $publish_date;?></div>
							</div>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="view-moore">
			<a href="https://demo11.axxiem.com/weekly-notes/">View More <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/arrow-right.svg'; ?>" border="0" /></a>
		</div>
	<?php wp_reset_postdata(); } ?>	
	
	
	<?php if(!empty($filtered_categories)) { 
    // Get the latest 6 categories
    $latest_categories = array_slice($filtered_categories, 0, 6);
?>
    <div class="doctype-container">
		<h2 class="post-heading">Shared Documents</h2>
		<ul>
            <?php foreach($latest_categories as $cat) { 
                $cat_icon = get_field('thumbnail', $cat);
            ?>
            <li>
                <a href="<?php echo esc_url(add_query_arg( 'cat_id', $cat->term_id, site_url( '/documents/' )))?>">
                    <img src="<?php echo ($cat_icon) ? $cat_icon : plugin_dir_url( dirname( __FILE__ ) ) . 'images/Folder-Secure-icon.png' ; ?>" border="0">
                </a>
                <h3>
                    <a href="<?php echo esc_url(add_query_arg( 'cat_id', $cat->term_id, site_url( '/documents/' )))?>">
                        <?php echo $cat->name; ?>
                    </a>
                </h3>
            </li>
            <?php } ?>
        </ul> 
    </div>
    <div class="view-documents"><a href="<?php echo esc_url(site_url( '/documents/' )); ?>">View All Shared Documents <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/arrow-right.svg'; ?>" border="0" />
        </a>
    </div>
<?php } ?>
</div>
