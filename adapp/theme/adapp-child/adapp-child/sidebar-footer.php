<?php
/**
 *
 * @package Sydney
 */
?>


	<?php //Set widget areas classes based on user choice
		$widget_areas = get_theme_mod('footer_widget_areas', '3');
		if ($widget_areas == '3') {
			$cols = 'col-md-4';
		} elseif ($widget_areas == '4') {
			$cols = 'col-md-3';
		} elseif ($widget_areas == '2') {
			$cols = 'col-md-6';
		} else {
			$cols = 'col-md-12';
		}
	?>

	<div id="sidebar-footer" class="footer-widgets widget-area" role="complementary">
		
			<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
				<div class="sidebar-column <?php echo $cols; ?> logo-container">
					<div class="container">
					<?php dynamic_sidebar( 'footer-1'); ?>
					</div>
				</div>
			<?php endif; ?>	
			<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
				<div class="sidebar-column <?php echo $cols; ?> contact-details">
					<div class="container">
					<?php dynamic_sidebar( 'footer-2'); ?>
					</div>
					<div class="overlay" style="background-color: rgb(0, 0, 0);"></div>
				</div>
			<?php endif; ?>	
			<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
				<div class="sidebar-column <?php echo $cols; ?> links-footer">
					<div class="container">
					<?php dynamic_sidebar( 'footer-3'); ?>
					</div>
				</div>
			<?php endif; ?>	
			<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
				<div class="sidebar-column <?php echo $cols; ?>">
					<div class="container">
					<?php dynamic_sidebar( 'footer-4'); ?>
					</div>
				</div>
			<?php endif; ?>	

	</div>