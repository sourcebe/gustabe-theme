<?php
/**
 * dir  inc/setup/
 * file enqueue-scripts.php
 * * จัดการโหลดไฟล์ CSS / JS (รวมถึง Tailwind V4 CDN) แบบมีเงื่อนไข
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function gustabe_enqueue_assets() {

    // ---------------------------------------------------
    // ⭐️ 0. โหลด Custom Assets (Huge Icons & Search)
    // ---------------------------------------------------
    wp_enqueue_style( 'gustabe-huge-icons', GUSTABE_THEME_URI . '/assets/huge-icons/huge-icons.min.css', [], GUSTABE_THEME_VERSION );
    
    wp_enqueue_style( 'gustabe-search-css', GUSTABE_THEME_URI . '/assets/css/search-engine.css', [], GUSTABE_THEME_VERSION );
    wp_enqueue_script( 'gustabe-search-js', GUSTABE_THEME_URI . '/assets/js/search-engine.js', [], GUSTABE_THEME_VERSION, true );
    wp_localize_script( 'gustabe-search-js', 'gustabeData', [
        'root_url' => esc_url_raw( rest_url() ),
        'nonce'    => wp_create_nonce( 'wp_rest' )
    ] );

    // ---------------------------------------------------
    // ⚡ 1. โหลด Tailwind CSS & Main Style (Zero CDN)
    // ---------------------------------------------------
    wp_enqueue_style( 'gustabe-tailwind-build', GUSTABE_THEME_URI . '/assets/css/tailwind-build.css', [], GUSTABE_THEME_VERSION );
    wp_enqueue_style( 'gustabe-style', GUSTABE_THEME_URI . '/style.css', [], GUSTABE_THEME_VERSION );

    // ---------------------------------------------------
    // ⚡ 1.5 โหลด Core Utility Scripts (Global JS)
    // ---------------------------------------------------
    // โหลด Smooth Scroll Engine ทุกหน้า เพื่อเตรียมรองรับ Floating Popup Menu (โหลดที่ Footer เพื่อไม่ให้บล็อกสปีดเว็บ)
    wp_enqueue_script( 'gustabe-smooth-scroll', GUSTABE_THEME_URI . '/assets/js/smooth-scroll.js', [], GUSTABE_THEME_VERSION, true );

    // ---------------------------------------------------
    // ⚡ 2. The Great De-bloat (Part 2): สกัด Font Awesome จาก Elementor
    // ---------------------------------------------------
    // เราใช้ Huge Icons เป็นหลัก จึงต้องปลด Font Awesome 4 และ 5 ที่ Elementor ชอบแอบโหลดมาเผื่อทิ้งให้หมด
    wp_deregister_style( 'font-awesome' );
    wp_deregister_style( 'font-awesome-4-shim' );
    wp_deregister_script( 'font-awesome-4-shim' );
    wp_deregister_style( 'elementor-icons-fa-solid' );
    wp_deregister_style( 'elementor-icons-fa-regular' );
    wp_deregister_style( 'elementor-icons-fa-brands' );

    // ---------------------------------------------------
    // ⚡ 3. The Smart Cleaner (กักบริเวณ WooCommerce & Gutenberg)
    // ---------------------------------------------------
    // 3.1 สกัดกั้น WooCommerce แบบเด็ดขาด 100% ทุกหน้า (ตามแผน The Great Nuke)
    add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
    
    if ( class_exists( 'WooCommerce' ) ) {
        wp_dequeue_style( 'woocommerce-layout' );
        wp_dequeue_style( 'woocommerce-smallscreen' );
        wp_dequeue_style( 'woocommerce-general' );
        wp_dequeue_style( 'woocommerce-inline' ); 
        wp_dequeue_style( 'wc-blocks-vendors-style' );
        wp_dequeue_style( 'wc-blocks-style' );
        
        wp_dequeue_script( 'wc-add-to-cart' );
        wp_dequeue_script( 'woocommerce' );
        wp_dequeue_script( 'wc-cart-fragments' );
        wp_dequeue_script( 'wc-jquery-blockui' );
        wp_dequeue_script( 'wc-js-cookie' );
    }

    // 3.2 บล็อกไฟล์ CSS ขยะของ Gutenberg
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );


    // ⚡3.3 Portfolio Engine (โหลดเฉพาะหน้ารวมผลงานและหมวดหมู่)
    // ---------------------------------------------------
    // เช็คว่าตอนนี้อยู่หน้า Archive ของ our_works หรือหน้า Taxonomy หมวดหมู่ผลงานหรือไม่
    if ( is_post_type_archive( 'our_works' ) || is_tax( ['project_type', 'project_platform', 'tech_stack'] ) ) {
        wp_enqueue_script( 
            'gustabe-portfolio-engine', // ชื่อ ID ของสคริปต์
            GUSTABE_THEME_URI . '/assets/js/portfolio-engine.js', // Path ไปหาไฟล์
            [], // ไม่พึ่งพาปลั๊กอินหรือสคริปต์อื่น (Zero jQuery)
            GUSTABE_THEME_VERSION, 
            true // true = โหลดไว้ที่ Footer (ดึงสปีดเว็บ)
        );
    }

    // ---------------------------------------------------
    // 3. ตัวอย่างการโหลด CSS แบบมีเงื่อนไข (โหลดเฉพาะหน้า)
    // ---------------------------------------------------
    /*
    // โหลดไฟล์ CSS ของ WooCommerce ดัดแปลง เฉพาะตอนอยู่ในหน้าร้านค้าเท่านั้น
    if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
        wp_enqueue_style( 'gustabe-woo-custom', GUSTABE_THEME_URI . '/assets/css/woocommerce-custom.css', [], GUSTABE_THEME_VERSION );
    }

    // โหลดไฟล์ CSS สำหรับระบบที่อยู่ เฉพาะหน้า Checkout และหน้า Edit Address
    if ( is_checkout() || is_wc_endpoint_url( 'edit-address' ) ) {
        wp_enqueue_style( 'gustabe-thailand-address', GUSTABE_THEME_URI . '/assets/css/thailand-address.css', [], GUSTABE_THEME_VERSION );
        // โหลด JS ที่อยู่ตรงนี้ด้วย...
    }
    */

    
}
add_action( 'wp_enqueue_scripts', 'gustabe_enqueue_assets', 20 );


// ---------------------------------------------------
// ⚡ 5. The Ultimate Nuke (ลงดาบคิวสุดท้าย Priority 999)
// ---------------------------------------------------
function gustabe_nuke_stubborn_bloats() {
    // 1. ระเบิด Gutenberg Global Styles (แก้ปัญหาเฉพาะของ WP 6.0+)
    wp_dequeue_style( 'global-styles' );
    wp_deregister_style( 'global-styles' );

    // 2. ระเบิด Font Awesome ของ Elementor (แบบถอนรากถอนโคน)
    wp_dequeue_style( 'font-awesome' );
    wp_dequeue_style( 'font-awesome-4-shim' );
    wp_dequeue_script( 'font-awesome-4-shim' );
    wp_dequeue_style( 'elementor-icons-fa-solid' );
    wp_dequeue_style( 'elementor-icons-fa-regular' );
    wp_dequeue_style( 'elementor-icons-fa-brands' );
}
// ใช้ Priority 999 เพื่อให้แน่ใจว่าทำงานเป็นคนสุดท้ายของเว็บ!
add_action( 'wp_enqueue_scripts', 'gustabe_nuke_stubborn_bloats', 999 );



         


// -----------------------------------------------------------------------------
// ⚡ ปลดล็อกจำนวนการแสดงผล CPT Our Works เพื่อใช้กับ Fake JS Pagination
// -----------------------------------------------------------------------------
function gustabe_portfolio_unlimited_query( $query ) {
    // เช็คว่าไม่ใช่หน้า Admin, เป็น Query หลัก และอยู่หน้า Archive 'our_works'
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'our_works' ) ) {
        $query->set( 'posts_per_page', -1 ); // -1 = ดึงมาทั้งหมดแบบไร้ขีดจำกัด
    }
}
add_action( 'pre_get_posts', 'gustabe_portfolio_unlimited_query' );