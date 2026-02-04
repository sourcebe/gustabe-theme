<?php
/**
 * Child theme functions
 * แยกไฟล์ระบบ (Modular) เพื่อความสะอาดและดูแลง่าย
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// 1. โหลด Text Domain (สำคัญ! ต้องอยู่บนสุด)
function hello_child_load_textdomain() {
    load_child_theme_textdomain( 'hello-elementor-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'hello_child_load_textdomain' );

// 2. เรียกใช้ไฟล์ย่อยจากโฟลเดอร์ /inc/
require_once get_stylesheet_directory() . '/inc/enqueue-scripts.php'; // สคริปต์และ CSS
require_once get_stylesheet_directory() . '/inc/theme-setup.php';     // ตั้งค่าธีม, เมนู, ค้นหา
require_once get_stylesheet_directory() . '/inc/polylang-setup.php';  // แปลภาษา
require_once get_stylesheet_directory() . '/inc/woocommerce-app.php'; // ฟีเจอร์ร้านค้า (Cards, Filter, Logic)
require_once get_stylesheet_directory() . '/inc/woo-siggleproduct.php'; // ปรับแต่ง (siggle-page)
require_once get_stylesheet_directory() . '/inc/ajax-search.php';//คันหาแบบปรับแต่ง

// 3. เรียกใช้ไฟล์ Includes เดิมของคุณ (Admin Login, CPT)
// (ถ้าคุณย้ายไฟล์พวกนี้ไปไว้ใน inc แล้ว ก็แก้ path ให้ตรงนะครับ)
require_once get_stylesheet_directory() . '/includes/admin-login-customizations.php';
require_once get_stylesheet_directory() . '/includes/comment-meta-functions.php';
require_once get_stylesheet_directory() . '/includes/media-svg-functions.php';
require_once get_stylesheet_directory() . '/includes/security-functions.php';
require_once get_stylesheet_directory() . '/includes/woocommerce-features.php';

// Custom Post Types
require_once get_stylesheet_directory() . '/includes/cpt/cpt-our-activity.php';
require_once get_stylesheet_directory() . '/includes/cpt/cpt-our-factory.php';
require_once get_stylesheet_directory() . '/includes/cpt/cpt-our-service.php';