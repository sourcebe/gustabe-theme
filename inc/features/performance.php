<?php
/**
 * dir: inc/features/
 * file: performance.php
 * หน้าที่: The Core Cleanup - กวาดล้างสคริปต์และแท็กขยะที่ WP สร้างขึ้นมาใน <head>
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'gustabe_core_cleanup' ) ) {
    function gustabe_core_cleanup() {
        
        // 1. ปิดระบบ WP Emoji (เพราะ Browser สมัยนี้รองรับ Emoji ในตัวหมดแล้ว ไม่ต้องโหลดสคริปต์ให้หนัก)
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

        // 2. ลบแท็ก Generator (ป้องกัน Hacker รู้เวอร์ชัน WordPress ของเรา)
        remove_action( 'wp_head', 'wp_generator' );

        // 3. ลบลิงก์ XML-RPC & API โบราณ (ไม่ได้ใช้โปรแกรม Windows Live Writer เขียนบล็อกแล้ว ก็ลบทิ้งเลย)
        remove_action( 'wp_head', 'rsd_link' );
        remove_action( 'wp_head', 'wlwmanifest_link' );

        // 4. ลบ Shortlink (The SEO Framework จัดการ Canonical ให้แล้ว Shortlink จึงรกเปล่าๆ)
        remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

        // 5. ปิด REST API link ใน <head> (ลบแค่แท็ก link ไม่ได้ปิดระบบ REST API จริงๆ)
        remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
        remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
    }
}
add_action( 'init', 'gustabe_core_cleanup' );


// 6. ปิดระบบ WP Embed (สคริปต์ที่ทำให้เว็บอื่นดึงโพสต์เราไปแปะ) เพื่อลด HTTP Request
function gustabe_deregister_embed_script(){
    wp_deregister_script( 'wp-embed' );
}
add_action( 'wp_footer', 'gustabe_deregister_embed_script' );


// 7. (โบนัส) เอา Dashicons ออกจากหน้าบ้าน (ถ้าเราใช้ Huge Icons / SVG อยู่แล้ว ก็ไม่ต้องโหลด Dashicons ให้หนัก ยกเว้นตอนแอดมินล็อกอิน)
function gustabe_dequeue_dashicons() {
    if ( ! is_user_logged_in() ) {
        wp_deregister_style( 'dashicons' );
    }
}
add_action( 'wp_enqueue_scripts', 'gustabe_dequeue_dashicons' );

// 8. ปิด WooCommerce Cart Fragments AJAX (ตัวการทำเว็บช้า) ในหน้าที่ไม่จำเป็น
function gustabe_disable_cart_fragments() {
    // ปิดการทำงานของ wc-ajax=get_refreshed_fragments ในหน้า My Account และ Checkout
    if ( function_exists('is_account_page') && is_account_page() || function_exists('is_checkout') && is_checkout() ) {
        wp_dequeue_script( 'wc-cart-fragments' );
    }
}
add_action( 'wp_enqueue_scripts', 'gustabe_disable_cart_fragments', 99 );

// 9. ปิดการทำงานของ WooCommerce Admin (Analytics) และ Marketing Hub ที่ทำให้หน่วง
add_filter( 'woocommerce_admin_disabled', '__return_true' );
add_filter( 'woocommerce_marketing_menu_items', '__return_empty_array' );
add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true' );

// 10. ปิดสคริปต์ตรวจสอบรหัสผ่าน (zxcvbn) ที่หนักมาก (400KB+) ถ้าไม่ใช่หน้าสมัครสมาชิก/รีเซ็ตรหัสผ่าน
function gustabe_remove_heavy_woo_scripts() {
    if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
        wp_dequeue_script( 'wc-password-strength-meter' );
    }
}
add_action( 'wp_print_scripts', 'gustabe_remove_heavy_woo_scripts', 100 );