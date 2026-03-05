<?php
/**
 * Custom Payment Method List Item (App Style with Icons)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1. กำหนดไอคอนให้แต่ละวิธีชำระเงิน (เลือกจาก Huge Icons ที่คุณมี)
$icon_class = 'huge-credit-card'; // ค่าเริ่มต้น

switch ( $gateway->id ) {
    case 'bacs': // โอนเงินธนาคาร
        $icon_class = 'huge-bank'; 
        break;
    case 'cod': // เก็บเงินปลายทาง
        $icon_class = 'huge-money-bag-02'; 
        break;
    case 'cheque': // เช็ค
        $icon_class = 'huge-bill-01'; 
        break;
    case 'ppcp-gateway': // PayPal
        $icon_class = 'huge-paypal'; 
        break;
    case 'promptpay': // พร้อมเพย์ (ถ้ามี)
        $icon_class = 'huge-qr-code'; 
        break;
    // เพิ่มวิธีอื่นๆ ตรงนี้ได้เลย
}
?>

<li class="wc_payment_method payment_method_<?php echo esc_attr( $gateway->id ); ?> gustabe-payment-card">
	
    <input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />

    <label for="payment_method_<?php echo esc_attr( $gateway->id ); ?>" class="payment-label">
        <div class="payment-icon">
            <i class="huge <?php echo $icon_class; ?>"></i>
        </div>
        <div class="payment-info">
		    <span class="payment-title"><?php echo $gateway->get_title(); ?></span>
        </div>
        <div class="check-icon">
            <i class="huge huge-checkmark-circle-02"></i>
        </div>
	</label>
    
    <?php if ( $gateway->has_fields() || $gateway->get_description() ) : ?>
		<div class="payment_box payment_method_<?php echo esc_attr( $gateway->id ); ?>">
			<?php $gateway->payment_fields(); ?>
		</div>
	<?php endif; ?>
    
</li>