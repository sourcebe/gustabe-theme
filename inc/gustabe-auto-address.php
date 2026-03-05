<?php
/**
 * GUSTABE AUTO ADDRESS (V8: Hybrid Logic)
 * รองรับทั้งหน้า Edit Address (มีช่องค้นหา) และ Checkout (ไม่มีช่องค้นหา)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'gustabe_load_local_thai_address' );

function gustabe_load_local_thai_address() {
    // โหลดเฉพาะหน้า Checkout หรือ หน้าแก้ไขที่อยู่ (My Account)
    if ( ! is_account_page() && ! is_checkout() ) {
        return;
    }

    $base_url = get_stylesheet_directory_uri() . '/assets/thailand/';

    // 1. Load Assets
    wp_enqueue_style( 'local-thailand-css', $base_url . 'jquery.Thailand.min.css' );
    wp_enqueue_script( 'local-jql', $base_url . 'JQL.min.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'local-typeahead', $base_url . 'typeahead.bundle.js', array('local-jql'), '1.0', true );
    wp_enqueue_script( 'local-thailand-core', $base_url . 'jquery.Thailand.js', array('local-typeahead'), '1.0', true );

    // 2. Config (ตั้งค่ามาตรฐานแบบเดิมไว้ก่อน)
    $script_data = array(
        'database' => $base_url . 'db.json',
        'billing' => array(
            'search'   => '#billing_address_search', // ช่องค้นหา (มีในหน้า Edit Address)
            'zip'      => '#billing_postcode',       // ช่องรับค่าจริง
            'district' => '#billing_address_2',
            'amphoe'   => '#billing_city',
            'province' => '#billing_state',
        ),
        'shipping' => array(
            'search'   => '#shipping_address_search',
            'zip'      => '#shipping_postcode',
            'district' => '#shipping_address_2',
            'amphoe'   => '#shipping_city',
            'province' => '#shipping_state',
        )
    );

    // 3. JS Logic (แบบ Hybrid)
    wp_add_inline_script( 'local-thailand-core', '
    (function($){
        $(document).ready(function(){
            var config = ' . json_encode($script_data) . ';

            function initThaiAddress( type ) {
                var fields = config[type];
                var $searchBox = $(fields.search); // ลองหาช่อง "ค้นหาที่อยู่" ก่อน
                var isFallback = false;

                // ★ ไฮไลท์: ถ้าหาช่องค้นหาไม่เจอ (เช่นอยู่หน้า Checkout) -> ให้ใช้ช่อง Zip เป็นช่องค้นหาแทน
                if( $searchBox.length === 0 ) {
                    $searchBox = $(fields.zip);
                    isFallback = true; // จำไว้ว่าเรากำลังใช้โหมด Fallback
                }

                if($searchBox.length > 0){
                    $.Thailand({
                        database: config.database,
                        $search: $searchBox, 
                        
                        onDataFill: function(data){
                            console.log("✅ Auto Address Filled:", data);
                            
                            // ฟังก์ชันช่วยเติมค่าลง Input / Select2
                            var setVal = function(sel, val) {
                                var $el = $(sel);
                                if($el.length) {
                                    $el.val(val).trigger("change");
                                    // ถ้าเป็น Dropdown (Select2)
                                    if( $el.hasClass("select2-hidden-accessible") ) {
                                        var $opt = $el.find("option").filter(function(){
                                            return $(this).text().indexOf(val) !== -1;
                                        });
                                        if($opt.length) $el.val($opt.val()).trigger("change");
                                    }
                                }
                            };

                            // 1. เติมข้อมูลลงช่องต่างๆ
                            setVal(fields.amphoe, data.amphoe);
                            setVal(fields.province, data.province);
                            
                            // 2. จัดการช่องรหัสไปรษณีย์ (สำคัญ!)
                            // ไม่ว่า Search Box จะเป็นตัวไหน เราต้องยัดค่า Zipcode ที่ถูกต้องกลับลงไปในช่อง Zip เสมอ
                            // เพื่อแก้ปัญหา "ค่าไม่จำ" หรือ "ค่าเพี้ยน"
                            setVal(fields.zip, data.zipcode);
                            
                            // 3. จัดการตำบล (เอาไปแปะท้าย Address 1 เผื่อ Address 2 โดนซ่อน)
                            var $addr1 = $("#" + type + "_address_1");
                            var currentAddr = $addr1.val() || "";
                            var tambonText = "ต." + data.district;
                            
                            if(currentAddr.indexOf(tambonText) === -1) {
                                $addr1.val(currentAddr + " " + tambonText + " ").trigger("change");
                            }
                            
                            // เติม Address 2 ไว้ด้วย (ตามมาตรฐาน)
                            setVal(fields.district, data.district);

                            // 4. Focus ไปช่องถัดไป
                            if(isFallback) {
                                // ถ้าค้นด้วย Zip -> เด้งไป Address 1 (ให้กรอกบ้านเลขที่ต่อ)
                                setTimeout(function(){ $addr1.focus(); }, 100);
                            } else {
                                // ถ้าค้นด้วย Search Box -> เด้งไป Zip (หรือ Address 1 แล้วแต่ชอบ)
                                setTimeout(function(){ $addr1.focus(); }, 100);
                            }
                        }
                    });
                }
            }
            
            // รันฟังก์ชัน
            initThaiAddress("billing");
            initThaiAddress("shipping");

            // รันซ้ำเมื่อ Woo อัปเดตหน้า (AJAX)
            $(document.body).on("updated_checkout", function(){
                initThaiAddress("billing");
                initThaiAddress("shipping");
            });
        });
    })(jQuery);
    ');
}

// CSS แต่ง Dropdown
add_action('wp_head', 'gustabe_custom_form_styles');
function gustabe_custom_form_styles() {
    if ( is_account_page() || is_checkout() ) {
        ?>
        <style>
            .tt-menu { background: #fff; border: 1px solid #eee; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-top: 5px; width: 100%; z-index: 99999 !important; }
            .tt-suggestion { padding: 12px 20px; font-size: 14px; color: #555; cursor: pointer; border-bottom: 1px solid #f9f9f9; }
            .tt-suggestion:hover, .tt-suggestion.tt-cursor { background: #04a39c; color: #fff; }
            /* ปรับแต่ง Input ให้สวยงาม */
            .woocommerce-input-wrapper input[type="text"], .woocommerce-input-wrapper input[type="tel"], .woocommerce-input-wrapper input[type="email"] {
                border-radius: 50px !important; padding: 12px 20px !important; height: 50px !important; border: 1px solid #e0e0e0 !important; background-color: #f9f9f9 !important;
            }
        </style>
        <?php
    }
}