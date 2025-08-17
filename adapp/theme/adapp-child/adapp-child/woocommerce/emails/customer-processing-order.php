<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Hi %s,', 'woocommerce' ), esc_html( $order->get_billing_first_name() ) ); ?></p>

<?php /* translators: %s: Order number */ ?>
<p><?php printf( esc_html__( 'Just to let you know — we\'ve received your order #%s, and it is now being processed:', 'woocommerce' ), esc_html( $order->get_order_number() ) ); ?></p>

<?php
	foreach ($order->get_items() as $item) {
			$product_id = $item['product_id'];
			$dasa_event = get_post_meta($product_id, 'dasa_event', true );
			if($dasa_event ==1 || $dasa_event =='1'){ ?>
				<p>Thank you for registering for the training.</p>
				<h2 style="color: #a7bf56; display: block; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; font-size: 18px; font-weight: bold; line-height: 130%; margin: 0 0 18px; text-align: left;">DASA Training Details</h2>
				<p>Click the link provided below to login and access the 3-hour DASA Self-Study Course.  This is part one of the two-part training.  Once you have completed the self-study portion of the DASA Training, you will receive an email with the zoom link for the 3-hour Live Virtual portion of the DASA Training.   To receive your certificate, you must attend the scheduled 3-hour Live Virtual Training.</p> 
				<p><a href="https://dasa-training.com/login/" style="-webkit-box-align: baseline;align-items: baseline;border-width: 0px;border-radius: 3px;box-sizing: border-box;display: inline-flex;background: rgb(0, 82, 204);color: rgb(255, 255, 255);cursor: pointer;height: 2.28571em;line-height: 2.28571em;padding: 0px 10px;vertical-align: middle;width: auto;-webkit-box-pack: center;justify-content: center;outline: none;box-shadow: transparent 0px 0px 0px 2px;margin: 0px;text-decoration:none;">CLICK HERE FOR DASA SELF STUDY COURSE</a></p>  
				<p>(The 3-hour DASA Self-Study Course, must be completed before the start of the 3-hour Live Virtual portion of the DASA Training)</p>
	<?php }
} ?>

<?php 
/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
