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
    // ⭐️ 0. โหลด Custom Assets (Huge Icons ถูกย้ายไป Inline ใน wp_head แทนเพราะไฟล์เล็กมาก)

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
    // โหลดไฟล์ Vanilla JS ควบคุม Header (Mobile Menu & Smart Sticky)
    wp_enqueue_script( 'gustabe-header-js', GUSTABE_THEME_URI . '/assets/js/modules/gustabe-header.js', [], GUSTABE_THEME_VERSION, true );

    // โหลด Smooth Scroll Engine ทุกหน้า เพื่อเตรียมรองรับ Floating Popup Menu
    wp_enqueue_script( 'gustabe-smooth-scroll', GUSTABE_THEME_URI . '/assets/js/smooth-scroll.js', [], GUSTABE_THEME_VERSION, true );

    // ---------------------------------------------------
    // ⚡ 2. The Smart Cleaner (กักบริเวณ WooCommerce & Gutenberg)
    // ---------------------------------------------------
    if ( class_exists( 'WooCommerce' ) ) {
        // เว้นหน้าของ WooCommerce ทั้งหมด เพื่อให้ปุ่ม Add to Cart แบบ AJAX และระบบ Shop ทำงานได้สมบูรณ์
        if ( ! ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
            wp_dequeue_style( 'wc-blocks-vendors-style' );
            wp_dequeue_style( 'wc-blocks-style' );

            wp_dequeue_script( 'wc-add-to-cart' );
            wp_dequeue_script( 'woocommerce' );
            wp_dequeue_script( 'wc-cart-fragments' );
            wp_dequeue_script( 'wc-jquery-blockui' );
            wp_dequeue_script( 'wc-js-cookie' );
        }
    }

    // บล็อกไฟล์ CSS ขยะของ Gutenberg
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );

    // ---------------------------------------------------
    // ⚡ 3. Portfolio Engine (โหลดเฉพาะหน้ารวมผลงานหลักที่มี Client-Side Filter)
    // ---------------------------------------------------
    if ( is_post_type_archive( 'our_works' ) ) {
        wp_enqueue_script(
            'gustabe-portfolio-engine',
            GUSTABE_THEME_URI . '/assets/js/portfolio-engine.js',
            [],
            GUSTABE_THEME_VERSION,
            true
        );
    }

    // ---------------------------------------------------
    // 3. ระบบที่อยู่อัตโนมัติ (Thailand.js) - โหลดเฉพาะหน้า Checkout และ Edit Address
    // ---------------------------------------------------
    if ( function_exists( 'is_woocommerce' ) && ( is_checkout() || is_wc_endpoint_url( 'edit-address' ) ) ) {
        
        // 1. โหลด Dependencies จาก CDN ของ earthchie
        wp_enqueue_script( 'jql', 'https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js', array(), null, true );
        wp_enqueue_script( 'typeahead', 'https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js', array( 'jquery' ), null, true );
        wp_enqueue_script( 'jquery-thailand', 'https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js', array( 'jquery', 'jql', 'typeahead' ), null, true );
        
        // Fix: jquery.Thailand.js uses $ globally without a wrapper, so we must map it in WordPress
        wp_add_inline_script( 'jquery-thailand', 'window.$ = window.jQuery;', 'before' );

        // 2. CSS สำหรับตกแต่ง Dropdown ของ Thailand.js (จะเขียนเพิ่มในสไตล์ Hacker)
        // wp_enqueue_style( 'gustabe-thailand-address', GUSTABE_THEME_URI . '/assets/css/thailand-address.css', [], GUSTABE_THEME_VERSION );

        // 3. แนบ Initialization Script
        wp_add_inline_script( 'jquery-thailand', '
            jQuery(document).ready(function($) {
                $.Thailand({
                    database: "https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/database/db.json", 
                    $district: $(".thailand-subdistrict input"),
                    $amphoe: $(".thailand-district input"),
                    $province: $(".thailand-province input"),
                    $zipcode: $(".thailand-zipcode input")
                });
            });
        ' );
    }


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

    // 3. ระเบิด WooCommerce Blocks / Style Engine ที่กวนใจ
    wp_dequeue_script( 'wc-cart-frontend' );
    wp_dequeue_script( 'wp-style-engine' );
}
// ใช้ Priority 999 เพื่อให้แน่ใจว่าทำงานเป็นคนสุดท้ายของเว็บ!
add_action( 'wp_enqueue_scripts', 'gustabe_nuke_stubborn_bloats', 999 );

// ---------------------------------------------------
// ⚡ 6. Remove Annoying Prefetch Hints (แก้ 503 Service Unavailable)
// ---------------------------------------------------
function gustabe_remove_wc_blocks_prefetch( $hints, $relation_type ) {
    if ( 'prefetch' === $relation_type || 'preload' === $relation_type ) {
        foreach ( $hints as $key => $hint ) {
            $url = is_array( $hint ) && isset( $hint['href'] ) ? $hint['href'] : ( is_string( $hint ) ? $hint : '' );
            
            // ถอด prefetch/preload ของ cart-frontend.js และ style-engine.min.js ออก
            if ( $url && ( strpos( $url, 'cart-frontend.js' ) !== false || strpos( $url, 'style-engine.min.js' ) !== false ) ) {
                unset( $hints[ $key ] );
            }
        }
    }
    return $hints;
}
add_filter( 'wp_resource_hints', 'gustabe_remove_wc_blocks_prefetch', 10, 2 );






// -----------------------------------------------------------------------------
// ⚡ ปลดล็อกจำนวนการแสดงผล CPT Our Works เพื่อใช้กับ Fake JS Pagination
// -----------------------------------------------------------------------------
function gustabe_portfolio_unlimited_query( $query ) {
    // เช็คว่าไม่ใช่หน้า Admin, เป็น Query หลัก และอยู่หน้า Archive 'our_works'
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'our_works' ) ) {
        // P3: จำกัดการดึงข้อมูลที่ 60 ชิ้น เพื่อป้องกันปัญหาคอขวดในอนาคต แทนการใช้ -1
        $query->set( 'posts_per_page', 60 );
    }
}
add_action( 'pre_get_posts', 'gustabe_portfolio_unlimited_query' );

// -----------------------------------------------------------------------------
// ⚡ Inline Critical CSS: เอา CSS ของไอคอนมาฝังในหน้าเว็บเลย (เพราะไฟล์มันเล็กแค่ 1KB)
// -----------------------------------------------------------------------------
function gustabe_inline_huge_icons() {
    $css_path = GUSTABE_THEME_DIR . '/assets/huge-icons/huge-icons-purged.min.css';
    if ( ! file_exists( $css_path ) ) {
        $css_path = GUSTABE_THEME_DIR . '/huge-icons/huge-icons-purged.min.css';
    }

    if ( file_exists( $css_path ) ) {
        $css_content = file_get_contents( $css_path );
        // เผื่อกรณีไฟล์ไม่ได้เป็น Base64 data URI จะเปลี่ยน path สัมพัทธ์ให้เป็น Absolute URI ของธีม
        $css_content = str_replace( 'url("huge-icons.', 'url("' . GUSTABE_THEME_URI . '/assets/huge-icons/huge-icons.', $css_content );

        echo "<style id='gustabe-huge-icons-inline'>\n";
        echo $css_content;
        echo "\n</style>\n";
    }
}
// ใช้ Priority 5 เพื่อให้อยู่บนๆ ของ Header (ก่อน Tailwind)
add_action( 'wp_head', 'gustabe_inline_huge_icons', 5 );

// -----------------------------------------------------------------------------
// ⚡ ป้องกัน JS บล็อกการแสดงผลเว็บ (Fix Render-Blocking JS)
// -----------------------------------------------------------------------------
function gustabe_defer_all_scripts( $tag, $handle, $src ) {
    // เติม defer เข้าไปเฉพาะสคริปต์ของธีม เพื่อความปลอดภัย
    $allowed_defer_handles = array( 'gustabe-header-js', 'gustabe-search-js', 'gustabe-smooth-scroll', 'gustabe-portfolio-engine' );
    if ( in_array( $handle, $allowed_defer_handles ) ) {
        return str_replace( ' src', ' defer="defer" src', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'gustabe_defer_all_scripts', 10, 3 );

// -----------------------------------------------------------------------------
// ⚡ โหลดไฟล์ CSS ตัวเล็กๆ แบบเบื้องหลัง (Async CSS) เพื่อแก้ปัญหา Render-Blocking
// -----------------------------------------------------------------------------
function gustabe_async_non_critical_css( $html, $handle, $href, $media ) {
    // รายชื่อ CSS ที่เราจะสั่งให้โหลดเบื้องหลัง (ห้ามใส่ Tailwind เด็ดขาด ไม่งั้นเว็บจะเละก่อนเสี้ยววิ)
    $async_handles = array( 'gustabe-style', 'gustabe-search-css', 'wc-blocks-style', 'wc-blocks', 'wc-blocks-vendors-style' );

    if ( in_array( $handle, $async_handles ) ) {
        // ใช้เทคนิค Print Media Type สลับเป็น All ทันทีที่โหลดเสร็จ (ทริคมาตรฐานระดับโลกที่โค้ดไม่รก)
        $html = "<link rel='stylesheet' href='" . esc_url( $href ) . "' media='print' onload=\"this.media='all'\" />\n";
        $html .= "<noscript><link rel='stylesheet' href='" . esc_url( $href ) . "' media='" . $media . "' /></noscript>\n";
    }
    return $html;
}
add_filter( 'style_loader_tag', 'gustabe_async_non_critical_css', 10, 4 );
