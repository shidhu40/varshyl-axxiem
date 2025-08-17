<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Sydney
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

$rightside_visible = true;
if(strpos($_SERVER['REQUEST_URI'],'/my-account/') !== false){
	$rightside_visible = false;
}elseif(strpos($_SERVER['REQUEST_URI'],'/checkout/') !== false){
	$rightside_visible = false;
}elseif(strpos($_SERVER['REQUEST_URI'],'/cart/') !== false){
	$rightside_visible = false;
}
?>
<?php if($rightside_visible === true) {  ?>
<div id="secondary" class="widget-area col-md-3" role="complementary">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</div><!-- #secondary -->
<?php } ?>
