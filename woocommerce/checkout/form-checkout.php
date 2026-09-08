<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="flex flex-col w-full max-w-4xl mx-auto items-center justify-center">
    <!-- Checkout Form Container -->
    <div class="w-full flex flex-col gap-8">
        <form name="checkout" method="post" class="checkout woocommerce-checkout w-full bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 lg:p-10 mb-12 shadow-xl backdrop-blur-xl" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

            <?php if ( $checkout->get_checkout_fields() ) : ?>
                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                <div class="col2-set" id="customer_details">
                    <div class="col-1 mb-8">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>
                    <div class="col-2 mb-8">
                        <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                    </div>
                </div>
                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
            <?php endif; ?>
            
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
            
            <h3 id="order_review_heading" class="text-xl font-mono text-emerald-400 mb-6 flex items-center gap-2 mt-8">
                <i class="huge huge-shopping-cart-01"></i> <?php esc_html_e( 'Your order', 'woocommerce' ); ?>
            </h3>
            
            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

            <div id="order_review" class="woocommerce-checkout-review-order bg-black/20 border border-slate-800 p-6 rounded-xl font-mono text-slate-300">
                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
            </div>

            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
