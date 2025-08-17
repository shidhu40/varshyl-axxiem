<?php 
$trail_breadcrum = '';
if(isset($term)) {
	if ($term->parent == 0) {
		$trail_breadcrum .= ' > ' .  $term->name;
	} else {
		$breadcrumb = get_category_parents_breadcrumb($term->term_id);
		$trail_breadcrum .=  rtrim($breadcrumb, ' » '); //get_categories_by_parent($term->parent) . ' > ' . $term->name;
	}
}?>
<div class="doc-breadcrumb"><a href="javascript:void(0)" class="directory-view" data-id="">Shared Documents</a>  <?php echo $trail_breadcrum; ?>  </div>
<div id="axxi-loader" class="lds-dual-ring hidden overlay-loader"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/244.gif'; ?>" border="0" /></div>
<div class="documents-lists">
	<ul>
		<?php $search_result = false;
		 if (!empty($categories)) { $search_result = true;
			foreach($categories as $shared_cat) { ?>
				<li><a href="javascript:void(0);" class="directory-view" data-id="<?php echo $shared_cat->term_id;?>"><img src="<?php echo 				plugin_dir_url( dirname( __FILE__ ) ) . 'images/Folder-Secure-icon.svg'; ?>" border="0" />  <?php echo $shared_cat->name;?></a></li>
		<?php } }
		if (!empty($posts_query->have_posts())) { 
			$search_result = true;
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				$shared_post_id = get_the_ID();
				$upload_file_array = get_field( 'upload_file',$shared_post_id);
				$upload_file = $upload_file_array['url'];
			?>
			<li><a href="<?php echo $upload_file;?>" target="_blank" class="recent_document_view" data-id="<?php echo $shared_post_id;?>">
			<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . checkFileExtention($upload_file); ?>" border="0" /> <?php echo 					get_the_title( $shared_post_id ); ?></a></li>
			<?php }
				wp_reset_postdata();
			} ?>
		<?php if (!$search_result) { ?>
		<div class="no-record" style="display:none;">It seems we can’t find what you’re looking for.</div>
		<?php } ?>
	</ul>
</div>
