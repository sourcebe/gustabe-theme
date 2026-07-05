<?php
/**
 * dir  inc/woocommerce/
 * file woo-core.php
 * * ปรับแต่งระบบร้านค้าพื้นฐาน และเปลี่ยนข้อความต่างๆ ให้รองรับ Polylang
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. เปลี่ยนข้อความปุ่ม "Add to cart" ในหน้ารายละเอียดสินค้า
 */
add_filter( 'woocommerce_product_single_add_to_cart_text', 'gustabe_custom_cart_btn_text' );
function gustabe_custom_cart_btn_text() {
    // ใช้ __() เพื่อให้ Polylang/Loco Translate มองเห็นข้อความนี้และนำไปแปลได้
    return __( 'หยิบใส่ตะกร้า', 'gustabe' );
}

/**
 * 2. เปลี่ยนข้อความปุ่ม "Add to cart" ในหน้ารวมสินค้า (Shop/Archive)
 */
add_filter( 'woocommerce_product_add_to_cart_text', 'gustabe_custom_archive_cart_btn_text' );
function gustabe_custom_archive_cart_btn_text() {
    return __( 'เลือกสินค้า', 'gustabe' );
}

/**
 * 3. ลบ Breadcrumbs ดั้งเดิมของ WooCommerce (ถ้าคุณใช้ Elementor สร้าง Header/Title เองแล้ว)
 * เอา Comment (//) ออก หากต้องการลบทิ้ง
 */
// remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/**
 * 4. Disable WooCommerce Default CSS
 * (เราใช้ Tailwind CSS แทนทั้งหมดเพื่อความเร็วและตัดปัญหาสไตล์ตีกัน)
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );