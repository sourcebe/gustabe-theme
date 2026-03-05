<?php
/**
 * Checkout Page - Gustabe Theme
 * Structure: Timeline > Grid Layout (Left: Form / Right: Summary)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. เช็ค Login/Register (มาตรฐาน Woo)
do_action( 'woocommerce_before_checkout_form', $checkout );
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) return;
?>

<div class="checkout-timeline">
    <div class="step completed"> 
        <div class="icon"><i class="huge huge-checkmark-circle-02"></i></div>
        <span><?php echo function_exists('pll__') ? pll__('ตะกร้า') : 'ตะกร้า'; ?></span>
    </div>
    <div class="line active"></div> 
    <div class="step active"> 
        <div class="icon"><i class="huge huge-credit-card"></i></div>
        <span><?php echo function_exists('pll__') ? pll__('ชำระเงิน') : 'ชำระเงิน'; ?></span>
    </div>
    <div class="line"></div>
    <div class="step">
        <div class="icon"><i class="huge huge-package-delivered"></i></div> 
        <span><?php echo function_exists('pll__') ? pll__('สำเร็จ') : 'สำเร็จ'; ?></span>
    </div>
</div>

<form name="checkout" method="post" class="checkout woocommerce-checkout gustabe-checkout-grid" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

    <div class="col-left">
        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div class="gustabe-card form-card" id="customer_details">
                <div class="card-header">
                    <div class="icon-wrap"><i class="huge huge-location-01"></i></div>
                    <h3><?php echo function_exists('pll__') ? pll__('ที่อยู่จัดส่ง') : 'ที่อยู่จัดส่ง'; ?></h3>
                </div>
                
                <div class="card-body">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    
                    <?php 
                    // ★ แก้ไขจุดที่ 1: ปิดไม่ให้ Note โผล่ตรงนี้ (ซ่อนจาก Shipping Section)
                    add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
                    do_action( 'woocommerce_checkout_shipping' ); 
                    remove_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
                    ?>
                </div>
            </div>

            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <div class="gustabe-card note-card">
                 <div class="card-header">
                    <div class="icon-wrap"><i class="huge huge-note-02"></i></div>
                    <h3><?php echo function_exists('pll__') ? pll__('เพิ่มเติม') : 'เพิ่มเติม'; ?></h3>
                </div>
                <div class="card-body">
                    <div class="woocommerce-additional-fields">
                        <?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
                            <?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <div class="col-right">
        <div class="sticky-summary">
            
            <div class="gustabe-card summary-card">
                 <div class="card-header">
                    <div class="icon-wrap"><i class="huge huge-shopping-bag-02"></i></div>
                    <h3><?php echo function_exists('pll__') ? pll__('สรุปคำสั่งซื้อ') : 'สรุปคำสั่งซื้อ'; ?></h3>
                </div>
                
                <div class="card-body">
                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                    </div>
                </div>
            </div>

            <div class="trust-badge">
                 <i class="huge huge-shield-check"></i> 
                 <?php echo function_exists('pll__') ? pll__('ข้อมูลของคุณปลอดภัย 100%') : 'ข้อมูลของคุณปลอดภัย 100%'; ?>
            </div>

        </div>
    </div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>