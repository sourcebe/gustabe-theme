<?php
/**
 * Customer Details (App Style - Detailed Breakdown)
 * File: woocommerce/order/order-details-customer.php
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();

// ฟังก์ชันช่วยดึงชื่อจังหวัดเต็ม (ป้องกันการโชว์เป็นรหัส เช่น TH-10)
function gustabe_get_full_state_name( $country_code, $state_code ) {
    $countries = WC()->countries->get_states( $country_code );
    return isset( $countries[ $state_code ] ) ? $countries[ $state_code ] : $state_code;
}
?>

<section class="gustabe-customer-app-layout">
    
    <div class="customer-column">
        <div class="column-header">
            <div class="icon-box"><i class="huge huge-invoice-03"></i></div>
            <h4>ข้อมูลใบเสร็จ</h4>
        </div>
        
        <div class="column-content">
            <div class="detail-rows">
                
                <div class="d-row">
                    <span class="d-label">ชื่อ-นามสกุล:</span>
                    <span class="d-value highlight"><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></span>
                </div>

                <?php if ( $order->get_billing_company() ) : ?>
                    <div class="d-row">
                        <span class="d-label">บริษัท:</span>
                        <span class="d-value"><?php echo esc_html( $order->get_billing_company() ); ?></span>
                    </div>
                <?php endif; ?>

                <div class="d-row">
                    <span class="d-label">ที่อยู่:</span>
                    <span class="d-value"><?php echo esc_html( $order->get_billing_address_1() ); ?></span>
                </div>

                <?php if ( $order->get_billing_address_2() ) : ?>
                    <div class="d-row">
                        <span class="d-label">แขวง/ตำบล:</span>
                        <span class="d-value"><?php echo esc_html( $order->get_billing_address_2() ); ?></span>
                    </div>
                <?php endif; ?>

                <div class="d-row">
                    <span class="d-label">เขต/อำเภอ:</span>
                    <span class="d-value"><?php echo esc_html( $order->get_billing_city() ); ?></span>
                </div>

                <div class="d-row two-col">
                    <div class="sub-col">
                        <span class="d-label">จังหวัด:</span>
                        <span class="d-value"><?php echo esc_html( gustabe_get_full_state_name( $order->get_billing_country(), $order->get_billing_state() ) ); ?></span>
                    </div>
                    <div class="sub-col">
                        <span class="d-label">รหัสไปรษณีย์:</span>
                        <span class="d-value highlight"><?php echo esc_html( $order->get_billing_postcode() ); ?></span>
                    </div>
                </div>

            </div>

            <div class="contact-section">
                <?php if ( $order->get_billing_phone() ) : ?>
                    <a href="tel:<?php echo esc_attr( $order->get_billing_phone() ); ?>" class="contact-pill">
                        <i class="huge huge-smart-phone-01"></i>
                        <span><?php echo esc_html( $order->get_billing_phone() ); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ( $order->get_billing_email() ) : ?>
                    <a href="mailto:<?php echo esc_attr( $order->get_billing_email() ); ?>" class="contact-pill">
                        <i class="huge huge-mail-02"></i>
                        <span><?php echo esc_html( $order->get_billing_email() ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ( $show_shipping ) : ?>
        <div class="customer-column">
            <div class="column-header">
                <div class="icon-box"><i class="huge huge-delivery-truck-02"></i></div>
                <h4>ที่อยู่จัดส่ง</h4>
            </div>
            
            <div class="column-content">
                <div class="detail-rows">
                    
                    <div class="d-row">
                        <span class="d-label">ผู้รับสินค้า:</span>
                        <span class="d-value highlight"><?php echo esc_html( $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name() ); ?></span>
                    </div>

                    <div class="d-row">
                        <span class="d-label">ที่อยู่:</span>
                        <span class="d-value"><?php echo esc_html( $order->get_shipping_address_1() ); ?></span>
                    </div>

                    <?php if ( $order->get_shipping_address_2() ) : ?>
                        <div class="d-row">
                            <span class="d-label">แขวง/ตำบล:</span>
                            <span class="d-value"><?php echo esc_html( $order->get_shipping_address_2() ); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="d-row">
                        <span class="d-label">เขต/อำเภอ:</span>
                        <span class="d-value"><?php echo esc_html( $order->get_shipping_city() ); ?></span>
                    </div>

                    <div class="d-row two-col">
                        <div class="sub-col">
                            <span class="d-label">จังหวัด:</span>
                            <span class="d-value"><?php echo esc_html( gustabe_get_full_state_name( $order->get_shipping_country(), $order->get_shipping_state() ) ); ?></span>
                        </div>
                        <div class="sub-col">
                            <span class="d-label">รหัสไปรษณีย์:</span>
                            <span class="d-value highlight"><?php echo esc_html( $order->get_shipping_postcode() ); ?></span>
                        </div>
                    </div>
                </div>

                <div class="shipping-note">
                    <i class="huge huge-package-delivered"></i>
                    <span>สินค้าจะถูกจัดส่งมาที่นี่</span>
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>