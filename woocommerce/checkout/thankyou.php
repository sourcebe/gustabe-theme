<?php
/**
 * Thank You Page (Fixed: Button for Everyone)
 */
defined( 'ABSPATH' ) || exit;
?>

<div class="gustabe-order-received-app">

    <div class="checkout-timeline">
        <div class="step completed"><div class="icon"><i class="huge huge-checkmark-circle-02"></i></div><span>ตะกร้า</span></div>
        <div class="line active"></div>
        <div class="step completed"><div class="icon"><i class="huge huge-credit-card"></i></div><span>ชำระเงิน</span></div>
        <div class="line active"></div>
        <div class="step active"><div class="icon"><i class="huge huge-package-delivered"></i></div><span>สำเร็จ</span></div>
    </div>

    <div class="thankyou-content-wrapper">
        <?php if ( $order ) : ?>
            
            <?php if ( $order->has_status( 'failed' ) ) : ?>
                <div class="gustabe-card error-card">
                    <div class="status-icon error"><i class="huge huge-cancel-circle"></i></div>
                    <h2>การชำระเงินล้มเหลว</h2>
                    <p>กรุณาลองใหม่อีกครั้ง</p>
                    <div class="actions">
                        <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay">ชำระเงินอีกครั้ง</a>
                    </div>
                </div>

            <?php else : ?>

                <div class="gustabe-card success-card">
                    <div class="status-icon success"><i class="huge huge-checkmark-circle-02"></i></div>
                    <h2>ขอบคุณสำหรับการสั่งซื้อ!</h2>
                    <p>รหัสคำสั่งซื้อ: <strong>#<?php echo $order->get_order_number(); ?></strong></p>
                    
                    <ul class="order-meta-list">
                        <li class="meta-item"><span>วันที่</span><strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong></li>
                        <li class="meta-item"><span>ยอดรวม</span><strong class="price"><?php echo $order->get_formatted_order_total(); ?></strong></li>
                        <li class="meta-item"><span>ชำระด้วย</span><strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong></li>
                    </ul>

                    <div class="order-actions" style="margin-top: 25px; text-align: center;">
                         <a href="<?php echo esc_url( home_url() ); ?>" class="button" style="border-radius: 50px; padding: 12px 30px; background: #04a39c; color: #fff; text-decoration: none;">
                            <i class="huge huge-home-03"></i> กลับหน้าแรก
                         </a>
                         
                         <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() ) : ?>
                             <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="button alt" style="border-radius: 50px; padding: 12px 30px; margin-left: 10px; background: #eee; color: #333; text-decoration: none;">
                                สั่งซื้อเพิ่ม
                             </a>
                         <?php endif; ?>
                    </div>
                </div>

                <div class="order-details-section">
                    <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
                </div>

            <?php endif; ?>

        <?php else : ?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">ได้รับคำสั่งซื้อแล้ว</p>
        <?php endif; ?>
    </div>
</div>