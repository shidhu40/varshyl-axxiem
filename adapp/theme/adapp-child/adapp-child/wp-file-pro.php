<?php
ob_start();
/**
 * Template Name: Axxiem File Pro
 *
 * Axxiem File Pro
 *
 * @author Axxiem
 * @since 1.0.0
 */
if (is_user_logged_in()){
	$user = wp_get_current_user();
	$role = $user->roles[0];
	$user_id = $user->ID;
	$user_login = $user->user_login;
	$allowed_permission = $hidden_file = '';
	if($role == 'hr'){
		$directory_path = 'wp-content/adms-root/staff/';
	}elseif($role == 'fm_admin'){
		$directory_path = 'wp-content/adms-root/staff/';
	}elseif($role == 'supervisor'){
		$directory_path = 'wp-content/adms-root/staff/'.$user_login.'_'.ucfirst($role);
		$hidden_file = 'shashi/HR | harish/HR';
	}elseif($role == 'staff'){
		$reporting_to = get_user_meta($user_id, 'reporting_to');
		$reporting_id = $reporting_to[0];
		$reporting_manager = get_userdata($reporting_id);
		$reporting_user_login = $reporting_manager->user_login;
		$directory_path = 'wp-content/adms-root/staff/'.$reporting_user_login.'_Supervisor'.'/'.$user_login;
		$allowed_permission ="mkfile,rename,paste,ban,copy,download,upload,cut,edit,search,info";
	}
}
get_header(); ?>
<div id="primary" class="content-area col-md-9">
	<main id="main" class="post-wrap" role="main">
		<article <?php post_class(); ?>>

			<header class="entry-header">
				
			</header><!-- .entry-header -->

			<div class="entry-post">
				<?php the_content(); ?>
				
				<?php echo do_shortcode( '[wp_file_manager_admin]' ); ?>
			</div><!-- .entry-post -->

			<footer class="entry-footer">
				<?php sydney_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		</article><!-- #post-## -->	
	</main><!-- #main -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<script>
jQuery.noConflict();
jQuery(document).ready( function(e){
	jQuery(document).on('click','#upload_document',function(){
		var pdata = {
			action: "axxiem_upload_hr_file",
		}
		jQuery.ajax({
			url: fmparam.adminajax,
			type: "POST",
			data: pdata,
			async:false,
			success: function(response){
				// Add response in Modal body
      			jQuery('.modal-body').html(response);

      			// Display Modal
     			jQuery('#empModal').modal('show'); 
			},
			error: function (jqXHR, textStatus, errorThrown) {
			  if (jqXHR.status == 500) {
				  console.debug("Internal error: " + jqXHR.responseText)
			  } else {
				  console.debug("Unexpected error.")
			  }
			} 
		});
	});
});
</script>
<?php get_footer(); ?>