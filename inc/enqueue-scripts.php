<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Load Scripts & Styles
function hello_elementor_child_enqueue_all_assets() {
    
    // 1.1 โหลด CSS
    // ธีมแม่
    wp_enqueue_style( 'hello-elementor-parent-style', get_template_directory_uri() . '/style.css' );
    // ธีมลูก
    wp_enqueue_style( 'hello-elementor-child-style', get_stylesheet_uri(), array( 'hello-elementor-parent-style' ), '1.0.0' );
    // Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array( 'hello-elementor-child-style' ), '5.3.0' );
    // Huge Icons
    wp_enqueue_style( 'huge-icons', get_stylesheet_directory_uri() . '/huge-icons/huge-icons.min.css', array(), '1.0.0' );

    // 1.2 โหลด JS
    // Bootstrap JS
    wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.0', true );

    // ในไฟล์ inc/enqueue-scripts.php

    if ( is_cart() ) {
        wp_enqueue_script( 
            'gustabe-cart-qty', 
            get_stylesheet_directory_uri() . '/js/cart-qty.js', 
            array( 'jquery' ), 
            time(), 
            true 
        );

        // ★★★ เพิ่มท่อนนี้ครับ (สำคัญมาก!) ★★★
        wp_localize_script( 'gustabe-cart-qty', 'gustabe_ajax', array(
            'url'   => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'gustabe_cart_nonce' )
        ));
    }

    // ------------------------------------------------------------------
    // ★★★ GUSTABE AJAX SEARCH SETUP (รองรับ Polylang) ★★★
    // ------------------------------------------------------------------
    
    // เตรียมข้อความสำหรับแปลภาษา (PHP -> JS)
    $i18n_view_all   = function_exists('pll__') ? pll__('ดูผลลัพธ์ทั้งหมด') : 'View all results';
    $i18n_no_results = function_exists('pll__') ? pll__('ไม่พบข้อมูลที่ค้นหา') : 'No results found';
    $i18n_error      = function_exists('pll__') ? pll__('เกิดข้อผิดพลาด โปรดลองใหม่') : 'Error, please try again';
    $i18n_sale       = function_exists('pll__') ? pll__('ลดราคา') : 'Sale';

    // ส่งค่าตัวแปรไปให้ JavaScript ใช้งาน
    wp_localize_script( 'bootstrap-js', 'gustabe_ajax_vars', array(
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'gustabe_search_nonce' ),
        'i18n'     => array(
            'view_all'   => $i18n_view_all,
            'no_results' => $i18n_no_results,
            'error'      => $i18n_error,
            'sale'       => $i18n_sale
        )
    ));

    // Logic การทำงานของ Ajax Search
    $custom_search_js = "
    jQuery(document).ready(function($) {
        
        // 1. Auto Focus: เมื่อเปิด Modal ให้ Cursor ไปกระพริบที่ช่องค้นหาทันที
        var myModalEl = document.getElementById('search-popup-modal');
        if(myModalEl){
            myModalEl.addEventListener('shown.bs.modal', function () {
                var input = $(this).find('#gustabe-search-input');
                if(input.length) input.focus();
            });
        }

        // 2. --- CORE AJAX SEARCH LOGIC ---
        var searchInput = $('#gustabe-search-input');
        var resultsDiv  = $('#gustabe-search-results');
        var spinner     = $('#gustabe-search-spinner');
        var defaultState = $('#search-default-state');
        var typingTimer;
        var doneTypingInterval = 500; // รอ 0.5 วินาทีหลังหยุดพิมพ์

        // ฟังก์ชันเริ่มค้นหา
        function triggerSearch() {
            clearTimeout(typingTimer);
            var keyword = searchInput.val();
            
            // ★ ดึงค่าจากปุ่ม Radio ที่เลือกอยู่ (Product / Post / All)
            var searchType = $('input[name=\"search_type_selector\"]:checked').val();

            // ถ้าคำค้นหาสั้นไป หรือถูกลบ
            if (keyword.length < 2) {
                resultsDiv.empty().hide();
                defaultState.show(); // โชว์ส่วนแนะนำกลับมา
                spinner.hide();
                return;
            }

            // ซ่อนส่วนแนะนำ + โชว์ Spinner
            defaultState.hide();
            spinner.show();

            typingTimer = setTimeout(function() {
                $.ajax({
                    url: gustabe_ajax_vars.ajax_url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'gustabe_ajax_search',
                        keyword: keyword,
                        search_type: searchType, // ส่งประเภทการค้นหา
                        nonce: gustabe_ajax_vars.nonce
                    },
                    success: function(response) {
                        spinner.hide();
                        resultsDiv.empty().show();

                        if (response.success && response.data.length > 0) {
                            
                            // วนลูปสร้างรายการสินค้า/บทความ
                            $.each(response.data, function(index, item) {
                                var priceHtml = item.price; // ถ้าเป็นบทความจะเป็นวันที่
                                var saleBadge = item.on_sale ? '<span class=\"badge bg-danger ms-2\" style=\"font-size:10px;\">' + gustabe_ajax_vars.i18n.sale + '</span>' : '';
                                
                                var html = `
                                    <a href=\"` + item.url + `\" class=\"gustabe-search-item d-flex align-items-center p-3 text-decoration-none border-bottom\">
                                        <div class=\"me-3 flex-shrink-0\">
                                            <img src=\"` + item.image + `\" style=\"width:50px; height:50px; object-fit:cover; border-radius:8px; border:1px solid #eee;\">
                                        </div>
                                        <div class=\"flex-grow-1\">
                                            <div class=\"text-dark fw-bold\" style=\"font-size:14px; line-height:1.4;\">` + item.title + saleBadge + `</div>
                                            <div class=\"text-secondary mt-1\" style=\"font-size:13px;\">` + priceHtml + `</div>
                                        </div>
                                        <div class=\"text-muted\">
                                            <i class=\"huge huge-arrow-right-02\" style=\"font-size:16px;\"></i>
                                        </div>
                                    </a>
                                `;
                                resultsDiv.append(html);
                            });
                            
                            // ปุ่มดูผลลัพธ์ทั้งหมด (View All)
                            var viewAllLink = '/?s=' + keyword + '&search_type=' + searchType;
                            var viewAllText = gustabe_ajax_vars.i18n.view_all; // ใช้คำที่แปลแล้ว
                            resultsDiv.append('<a href=\"' + viewAllLink + '\" class=\"d-block text-center p-3 text-muted small bg-light fw-bold text-decoration-none\">' + viewAllText + '</a>');

                        } else {
                            // ไม่เจอข้อมูล
                            var noResultText = gustabe_ajax_vars.i18n.no_results;
                            resultsDiv.html('<div class=\"p-4 text-center text-muted\"><i class=\"huge huge-search-02 mb-2 d-block\" style=\"font-size:32px; opacity:0.3;\"></i>' + noResultText + '</div>');
                        }
                    },
                    error: function() {
                        spinner.hide();
                        var errorText = gustabe_ajax_vars.i18n.error;
                        resultsDiv.html('<div class=\"p-3 text-center text-danger\">' + errorText + '</div>').show();
                    }
                });
            }, doneTypingInterval);
        }

        // Event: เมื่อพิมพ์ (Keyup) หรือ เปลี่ยนประเภท (Change Radio)
        searchInput.on('keyup', triggerSearch);
        $('input[name=\"search_type_selector\"]').on('change', function() {
            if(searchInput.val().length >= 2) {
                triggerSearch(); // ค้นใหม่ทันทีที่เปลี่ยนประเภท
            }
        });
        
    });
    ";
    wp_add_inline_script( 'bootstrap-js', $custom_search_js );

    // 8. Admin Login Script (เฉพาะแอดมิน)
    if ( is_admin() ) {
        wp_enqueue_script( 'admin-login-script', get_stylesheet_directory_uri() . '/js/admin-login.js', array( 'jquery' ), '1.0.0', true );
    }
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_all_assets' );


// ------------------------------------------------------------------
// Font Awesome Setup (แยกออกมาให้เป็นระเบียบ)
// ------------------------------------------------------------------

// 2. โหลด Font Awesome 6.5.2 CDN
function hello_elementor_child_enqueue_font_awesome_cdn() {
    wp_enqueue_style( 'font-awesome-my-cdn', 'https://use.fontawesome.com/releases/v6.5.2/css/all.css', array(), '6.5.2' );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_font_awesome_cdn', 10 );

// 3. ยกเลิก Font Awesome เก่าของ Elementor (เพื่อลดความซ้ำซ้อน)
function custom_dequeue_elementor_font_awesome() {
    wp_dequeue_style( 'font-awesome-css' );
    wp_dequeue_style( 'font-awesome-5' );
    wp_dequeue_style( 'font-awesome-4-shim' );
    wp_dequeue_style( 'elementor-icons-fa-solid' );
    wp_dequeue_style( 'elementor-icons-fa-regular' );
    wp_dequeue_style( 'elementor-icons-fa-brands' );
    wp_dequeue_style( 'elementor-icons' );
}
add_action( 'wp_enqueue_scripts', 'custom_dequeue_elementor_font_awesome', 999 );