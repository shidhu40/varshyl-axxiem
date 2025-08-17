<div class="adapp-leftbar">
		<div class="shared-documents">
			<div class="searchbar-con">
				<div class="input-container"><input type="search" id="myInputText" class="document-search" placeholder="Search by name/category" title="Type in a name"></div><select class="sortBy" onchange="filterDocument()">
<option value="name">Sort by Name</option><option value="date">Sort by Date</option></select><select class="filterByCat" onchange="filterDocument()">
					<option value="">Filter by Folder</option>
					<?php foreach($all_cats_list as $cat) { ?>
					<option value="<?php echo $cat->term_id; ?>" <?php if(isset($_GET['cat_id']) && $_GET['cat_id'] == $cat->term_id) { echo 'selected="selected"'; } ?> ><?php echo $cat->name; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
	   <div class="documents-list-container">
			<div class="doc-breadcrumb"><a href="javascript:void(0);" class="directory-view" data-id="">Shared Documents</a></div>
<div id="axxi-loader" class="lds-dual-ring hidden overlay-loader"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/244.gif'; ?>" border="0" /></div>
			<div class="documents-lists">

				<ul>
					<?php if (!empty($cats)) { foreach($cats as $shared_cat) { ?>
					<li><a href="javascript:void(0);" class="directory-view" data-id="<?php echo $shared_cat->term_id;?>"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'images/Folder-Secure-icon.svg'; ?>" border="0" />  <?php echo $shared_cat->name;?></a></li>
					<?php } } ?>
					<?php if (!empty($posts_query->have_posts())) { 
							while ( $posts_query->have_posts() ) {
								$posts_query->the_post();
								$shared_post_id = get_the_ID();
								$upload_file_array = get_field( 'upload_file',$shared_post_id);
								$upload_file = $upload_file_array['url'];
					?>
								<li><a href="<?php echo $upload_file;?>" target="_blank" class="recent_document_view" data-id="<?php echo $shared_post_id;?>">
									<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . checkFileExtention($upload_file); ?>" border="0" /> <?php echo get_the_title( $shared_post_id ); ?></a></li>
						<?php }
							wp_reset_postdata();
						} ?>
				</ul>
			</div>
		</div>
</div>
