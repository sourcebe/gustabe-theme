<?php
/**
 * dir  inc/setup/
 * file theme-setup.php
 * * จัดการตั้งค่าเริ่มต้นของธีม (Theme Supports, Polylang Text Domain)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'gustabe_theme_setup' ) ) {
    
    function gustabe_theme_setup() {
        
        // 1. โหลด Text Domain สำหรับ Polylang (เพื่อใช้แปลภาษา)
        // ต้องไปสร้างโฟลเดอร์ /languages/ รอไว้ในธีมด้วยนะครับ
        load_theme_textdomain( 'gustabe', GUSTABE_THEME_DIR . '/languages' );

        // 2. เปิดใช้งานฟีเจอร์พื้นฐานของ WordPress
        add_theme_support( 'title-tag' );
        add_theme_support( 'post-thumbnails' );

        // 3. ประกาศตัวอย่างเป็นทางการว่า "ธีมนี้รองรับ WooCommerce" (แก้ปัญหาหน้าพัง)
        add_theme_support( 'woocommerce' );
        
        // 4. เปิดใช้งานระบบแกลลอรี่ของ WooCommerce (ซูมภาพ, สไลด์, รูปขยาย)
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );

        // 5. ลงทะเบียนตำแหน่งเมนู (ถ้ามี)
        register_nav_menus([
            'menu-1' => __( 'เมนูหลัก (Primary Menu)', 'gustabe' ),
            'menu-2' => __( 'เมนูส่วนท้าย (Footer Menu)', 'gustabe' ),
            'footer-quick-links'      => __( 'Footer Quick Links', 'gustabe' ),
            'footer-customer-service' => __( 'Footer Customer Service', 'gustabe' ),
        ]);
    }
}
// สั่งให้ฟังก์ชันนี้ทำงานตอนที่ WordPress กำลังตั้งค่าธีม
add_action( 'after_setup_theme', 'gustabe_theme_setup' );





// -----------------------------------------------------------------------------
// ⚡ Load Custom Post Types & Taxonomies
// -----------------------------------------------------------------------------
function gustabe_load_custom_post_types() {
    
    // กำหนด Path ไปยังโฟลเดอร์ cpt
    $cpt_dir = get_stylesheet_directory() . '/inc/cpt/';

    // 1. โหลด CPT: Our Works
    $cpt_our_works = $cpt_dir . 'cpt-our-works.php';
    if ( file_exists( $cpt_our_works ) ) {
        require_once $cpt_our_works;
    }

    // [สำหรับอนาคต] 2. โหลด CPT: Services
    $cpt_services = $cpt_dir . 'cpt-services.php';
    if ( file_exists( $cpt_services ) ) {
        require_once $cpt_services;
     }

}
// เรียกใช้งานฟังก์ชันทันที (ไฟล์ theme-setup.php นี้ต้องถูก require ใน functions.php อยู่แล้ว)
gustabe_load_custom_post_types();




// 1. โหลดไฟล์ Meta Box Logic
$meta_our_works = get_stylesheet_directory() . '/inc/meta-boxes/meta-our-works.php';
if ( file_exists( $meta_our_works ) ) {
    require_once $meta_our_works;
}

// 2. โหลดไฟล์ JS สำหรับ Gallery หลังบ้าน (เฉพาะหน้า edit ของ our_works)
function gustabe_admin_enqueue_gallery_scripts( $hook_suffix ) {
    global $post_type;

    // เช็คว่าต้องเป็นหน้า post.php หรือ post-new.php และเป็น CPT 'our_works' เท่านั้น
    if ( ( $hook_suffix === 'post.php' || $hook_suffix === 'post-new.php' ) && $post_type === 'our_works' ) {
        
        // โหลดระบบ Media ของ WordPress (จำเป็นสำหรับ popup อัปโหลดรูป)
        wp_enqueue_media();
        
        // โหลด Vanilla JS ของเรา (ใช้ filemtime ป้องกันบราวเซอร์แคชไฟล์เก่า)
        $gallery_js_file = get_stylesheet_directory() . '/assets/js/admin/portfolio-gallery.js';
        $gallery_js_ver  = file_exists( $gallery_js_file ) ? filemtime( $gallery_js_file ) : '1.2.0';

        wp_enqueue_script( 
            'gustabe-portfolio-gallery-js', 
            get_stylesheet_directory_uri() . '/assets/js/admin/portfolio-gallery.js', 
            [], 
            $gallery_js_ver, 
            true 
        );

        // ส่งตัวแปร AJAX และ Nonce ให้ JavaScript
        global $post;
        wp_localize_script( 'gustabe-portfolio-gallery-js', 'gustabeAdminData', [
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'gustabe_auto_screenshot_nonce' ),
            'post_id'  => $post ? $post->ID : 0,
        ] );
    }
}
add_action( 'admin_enqueue_scripts', 'gustabe_admin_enqueue_gallery_scripts' );



// โหลดไฟล์ Meta Box Logic ของ Services
$meta_services = get_stylesheet_directory() . '/inc/meta-boxes/meta-services.php';
if ( file_exists( $meta_services ) ) {
    require_once $meta_services;
}





