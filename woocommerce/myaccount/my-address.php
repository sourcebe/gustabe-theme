<?php
/**
 * GUSTABE ADDRESS LIST (Modern Card Style V1)
 * ออกแบบใหม่: ใช้ Card Layout + Empty State + Huge Icons
 */

defined( 'ABSPATH' ) || exit;

$customer_id = get_current_user_id();

if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing'  => __( 'Billing address', 'woocommerce' ),
			'shipping' => __( 'Shipping address', 'woocommerce' ),
		),
		$customer_id
	);
} else {
	$get_addresses = apply_filters(
		'woocommerce_my_account_get_addresses',
		array(
			'billing' => __( 'Billing address', 'woocommerce' ),
		),
		$customer_id
	);
}

// Helper Function: เช็คว่ามีที่อยู่จริงหรือไม่ (โดยการดึง field แรกมาเช็ค)
function gustabe_check_address_exists($type, $customer_id) {
    $key = ($type == 'billing') ? 'billing_address_1' : 'shipping_address_1';
    $val = get_user_meta($customer_id, $key, true);
    return !empty($val);
}
?>

<p>
    <?php echo apply_filters( 'woocommerce_my_account_my_address_description', esc_html__( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ); ?>
</p>

<div class="gustabe-address-grid">

	<?php foreach ( $get_addresses as $name => $address_title ) : ?>
        <?php
            // 1. เตรียมข้อมูล
            $address_html = wc_get_account_formatted_address( $name );
            $has_data     = gustabe_check_address_exists($name, $customer_id);
            $edit_url     = wc_get_endpoint_url( 'edit-address', $name );
            
            // 2. เลือกไอคอน
            $icon_class = ('billing' === $name) ? 'huge-invoice-01' : 'huge-delivery-truck-02';
        ?>

        <div class="gustabe-address-card <?php echo $has_data ? 'has-data' : 'is-empty'; ?>">
            
            <?php if ( $has_data ) : ?>
                <div class="card-icon-wrapper">
                    <i class="huge <?php echo $icon_class; ?>"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title"><?php echo esc_html( $address_title ); ?></h3>
                    <address class="card-address-text">
                        <?php echo wp_kses_post( $address_html ); ?>
                    </address>
                </div>
                <a href="<?php echo esc_url( $edit_url ); ?>" class="card-edit-btn" title="<?php esc_attr_e( 'Edit', 'woocommerce' ); ?>">
                    <i class="huge huge-pencil-edit-02"></i>
                </a>

            <?php else : ?>
                <a href="<?php echo esc_url( $edit_url ); ?>" class="empty-state-link">
                    <div class="empty-icon-circle">
                        <i class="huge huge-plus-sign"></i>
                    </div>
                    <div class="empty-text">
                        <?php 
                        // ข้อความรองรับการแปล (ถ้าใช้ Polylang ให้ไปเพิ่มใน String Translation)
                        echo ('billing' === $name) ? __( 'Add Billing Address', 'woocommerce' ) : __( 'Add Shipping Address', 'woocommerce' ); 
                        ?>
                    </div>
                 </a>
            <?php endif; ?>

        </div>

	<?php endforeach; ?>

</div>