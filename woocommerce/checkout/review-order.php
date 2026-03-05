<?php
/**
 * Custom Review Order (App Style - No Table)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="gustabe-review-order-list shop_table woocommerce-checkout-review-order-table">
    
    <div class="review-items-container">
        <?php
        do_action( 'woocommerce_review_order_before_cart_contents' );

        foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

            if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                ?>
                <div class="review-item">
                    <div class="item-info">
                        <div class="item-name">
                            <?php echo apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ); ?>
                        </div>
                        <div class="item-meta">
                            <span class="qty">จำนวน: x<?php echo $cart_item['quantity']; ?></span>
                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        </div>
                    </div>
                    <div class="item-total">
                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                    </div>
                </div>
                <?php
            }
        }

        do_action( 'woocommerce_review_order_after_cart_contents' );
        ?>
    </div>

    <div class="review-divider"></div>

    <div class="review-totals-container">
        
        <div class="total-row subtotal">
            <span class="label"><?php _e( 'ยอดรวมสินค้า', 'woocommerce' ); ?></span>
            <span class="value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <div class="total-row shipping">
                <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
                <span class="label"><?php _e( 'การจัดส่ง', 'woocommerce' ); ?></span>
                <span class="value"><?php wc_cart_totals_shipping_html(); ?></span>
                <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="total-row fee">
                <span class="label"><?php echo esc_html( $fee->name ); ?></span>
                <span class="value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <div class="total-row tax">
                <span class="label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                <span class="value"><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
        <?php endif; ?>
        
        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="total-row coupon">
                <span class="label text-success"><i class="huge huge-coupon-01"></i> <?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span class="value text-success"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <div class="review-divider-bold"></div>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
        <div class="total-row grand-total">
            <span class="label"><?php _e( 'ยอดสุทธิ', 'woocommerce' ); ?></span>
            <span class="value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

    </div>
</div>