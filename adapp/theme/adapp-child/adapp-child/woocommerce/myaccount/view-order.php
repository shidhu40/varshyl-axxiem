<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>
<p>
<?php
printf(
	/* translators: 1: order number 2: order date 3: order status */
	esc_html__( 'Order #%1$s was placed on %2$s and is currently %3$s.', 'woocommerce' ),
	'<mark class="order-number">' . $order->get_order_number() . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'<mark class="order-status">' . wc_get_order_status_name( $order->get_status() ) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
);
?>
</p>

<?php if($order->get_status() == 'completed') { 
	$att = tribe_tickets_get_attendees($order_id);
	$attendee_id = $att[0]['attendee_id'];
	$event_id = $att[0]['event_id'];
	echo Tribe__Extension__PDF_Tickets::instance()->ticket_link( $attendee_id );
} ?>
<?php if ( $notes ) : ?>
	<h2><?php esc_html_e( 'Order updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>
<?php  
	$att = tribe_tickets_get_attendees($order_id);
	$event_id = $att[0]['event_id'];
	$dasa_event = get_post_meta($event_id, 'dasa_event', true );
	if($dasa_event ==1 || $dasa_event =='1'){ ?>
		<h2 style="color: #a7bf56; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">DASA Training Details</h2>
		<p>Click the link provided below to login and access the 3-hour DASA Self-Study Course.  This is part one of the two-part training.  Once you have completed the self-study portion of the DASA Training, you will receive an email with the zoom link for the 3-hour Live Virtual portion of the DASA Training.   To receive your certificate, you must attend the scheduled 3-hour Live Virtual Training.</p> 
				<p><a href="https://dasa-training.com/login/" target="_blank">DASA Self-Study Course</a>!</p> 
				<p>(The 3-hour DASA Self-Study Course, must be completed 24 hours before the start of the 3-hour Live Virtual portion of the DASA Training)</p>
	<?php } ?>
<?php do_action( 'woocommerce_view_order', $order_id ); ?>
