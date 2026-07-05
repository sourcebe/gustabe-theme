<?php
/**
 * dir /
 * file functions.php
 * ศูนย์บัญชาการหลักของธีม (Main Functions)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // ป้องกันการเข้าถึงไฟล์โดยตรง
}

// ⚠️ ซ่อน PHP Notices (เช่น จากปลั๊กอิน ACF) ที่ชอบพ่น HTML ออกมาทำลายโครงสร้าง JSON ของ Customizer
error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE & ~E_DEPRECATED);
ini_set('display_errors', 0);

// 1. ประกาศตัวแปรคงที่ (Constants) เพื่อให้เรียกใช้ Path ง่ายๆ ทั่วทั้งธีม
if ( ! defined( 'GUSTABE_THEME_VERSION' ) ) {
	define( 'GUSTABE_THEME_VERSION', '2.0.2' );
}
define( 'GUSTABE_THEME_DIR', get_stylesheet_directory() );
define( 'GUSTABE_THEME_URI', get_stylesheet_directory_uri() );
define( 'GUSTABE_IS_DEV_MODE', true ); // 🔴 สวิตช์เปิด-ปิด โหมด Development

// 2. เรียกใช้ไฟล์ Setup พื้นฐาน
require_once GUSTABE_THEME_DIR . '/inc/setup/theme-setup.php';
require_once GUSTABE_THEME_DIR . '/inc/setup/enqueue-scripts.php';
require_once GUSTABE_THEME_DIR . '/inc/setup/customizer.php';

// 3. ระบบ Core Engine ที่เราตกลงกันไว้ (Zero Plugin Logic)
require_once GUSTABE_THEME_DIR . '/inc/features/performance.php'; // กวาดล้างไฟล์ขยะของ WP
require_once GUSTABE_THEME_DIR . '/inc/features/seo-engine.php';  // Custom Schema & TSF Hooks



// 5. โหลดฟังก์ชันดัดแปลง WooCommerce (โหลดเฉพาะเมื่อติดตั้ง WooCommerce แล้ว)
if ( class_exists( 'WooCommerce' ) ) {
    require_once GUSTABE_THEME_DIR . '/inc/woocommerce/woo-core.php';
    require_once GUSTABE_THEME_DIR . '/inc/woocommerce/woo-account.php';
    require_once GUSTABE_THEME_DIR . '/inc/features/auto-address.php'; // Auto Address
}

// 6. โหลดฟีเจอร์เสริมพิเศษ
require_once GUSTABE_THEME_DIR . '/inc/features/ajax-search.php';