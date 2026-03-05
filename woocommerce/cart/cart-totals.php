<?php
/**
 * GUSTABE Custom Cart Totals (Modern App Style + Translation Ready)
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

    <div class="gustabe-summary-card">
        
        <h3 class="summary-title">
            <?php echo function_exists('pll__') ? pll__('สรุปคำสั่งซื้อ') : 'สรุปคำสั่งซื้อ'; ?>
        </h3>

        <div class="summary-content">
            <div class="summary-row subtotal">
                <span class="label">
                    <?php echo function_exists('pll__') ? pll__('ยอดรวมสินค้า') : 'ยอดรวมสินค้า'; ?>
                </span>
                <span class="value"><?php wc_cart_totals_subtotal_html(); ?></span>
            </div>

            <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
                <div class="summary-row coupon">
                    <span class="label coupon-label">
                        <i class="huge huge-coupon-01"></i> 
                        <?php 
                            // แสดงชื่อคูปอง
                            echo wc_cart_totals_coupon_label( $coupon, false ); 
                        ?>
                    </span>
                    <span class="value discount-price"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
                <?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
                
                <div class="shipping-wrapper">
                    <?php wc_cart_totals_shipping_html(); ?>
                </div>

                <?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
            <?php endif; ?>

            <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                <div class="summary-row fee">
                    <span class="label"><?php echo esc_html( $fee->name ); ?></span>
                    <span class="value"><?php wc_cart_totals_fee_html( $fee ); ?></span>
                </div>
            <?php endforeach; ?>

            <?php 
            if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
                if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                    foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { ?>
                        <div class="summary-row tax">
                            <span class="label"><?php echo esc_html( $tax->label ); ?></span>
                            <span class="value"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                        </div>
                    <?php }
                } else { ?>
                    <div class="summary-row tax">
                        <span class="label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
                        <span class="value"><?php wc_cart_totals_taxes_total_html(); ?></span>
                    </div>
                <?php }
            }
            ?>
        </div>

        <div class="summary-divider"></div>

        <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
        <div class="summary-row grand-total">
            <span class="label">
                <?php echo function_exists('pll__') ? pll__('ยอดสุทธิ') : 'ยอดสุทธิ'; ?>
            </span>
            <span class="value total-price"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>
        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

        <div class="wc-proceed-to-checkout">
            <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
        </div>

    </div>

	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>