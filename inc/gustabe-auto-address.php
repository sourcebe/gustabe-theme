<?php
/**
 * GUSTABE AUTO ADDRESS (LOCAL + STANDARD FORM STYLE)
 * โหลดไฟล์จากเครื่อง และปรับแต่งฟอร์มมาตรฐานให้สวยงาม
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'gustabe_load_local_thai_address' );

function gustabe_load_local_thai_address() {
    // 1. ทำงานเฉพาะหน้า My Account หรือ Checkout
    if ( ! is_account_page() && ! is_checkout() ) {
        return;
    }

    $base_url = get_stylesheet_directory_uri() . '/assets/thailand/';

    // 2. โหลด CSS ของระบบ Auto Address
    wp_enqueue_style( 'local-thailand-css', $base_url . 'jquery.Thailand.min.css' );

    // 3. โหลด JS (เรียงลำดับถูกต้อง)
    wp_enqueue_script( 'local-jql', $base_url . 'JQL.min.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'local-typeahead', $base_url . 'typeahead.bundle.js', array('local-jql'), '1.0', true );
    wp_enqueue_script( 'local-thailand-core', $base_url . 'jquery.Thailand.js', array('local-typeahead'), '1.0', true );

    // 4. ตั้งค่า Config (ใช้ ID มาตรฐานของ WooCommerce)
    $script_data = array(
        'database' => $base_url . 'db.json',
        'billing' => array(
            'zip'      => '#billing_postcode',   // รหัสไปรษณีย์
            'district' => '#billing_address_2', // ตำบล (WooCommerce มาตรฐานมักใช้ช่องนี้)
            'amphoe'   => '#billing_city',      // อำเภอ
            'province' => '#billing_state',     // จังหวัด
        ),
        'shipping' => array(
            'zip'      => '#shipping_postcode',
            'district' => '#shipping_address_2',
            'amphoe'   => '#shipping_city',
            'province' => '#shipping_state',
        )
    );

    // 5. สั่งทำงาน
    wp_add_inline_script( 'local-thailand-core', '
    (function($){
        $(document).ready(function(){
            console.log("🚀 Gustabe System: Ready!");
            var config = ' . json_encode($script_data) . ';

            function initThaiAddress( type ) {
                var fields = config[type];
                var $zip = $(fields.zip);

                if($zip.length > 0){
                    $.Thailand({
                        database: config.database,
                        $search: $zip,
                        onDataFill: function(data){
                            console.log("✅ Selected:", data);
                            
                            // ฟังก์ชันช่วยหยอดค่า
                            var setVal = function(sel, val) {
                                var $el = $(sel);
                                if($el.length) {
                                    $el.val(val).trigger("change");
                                    // ถ้าเป็น Select2 (Dropdown มาตรฐาน Woo) ให้สั่งอัปเดตด้วย
                                    if( window.Select2 && $el.hasClass("select2-hidden-accessible") ) {
                                        $el.trigger("change.select2");
                                    }
                                }
                            };

                            setVal(fields.district, data.district);
                            setVal(fields.amphoe, data.amphoe);
                            setVal(fields.province, data.province);
                            setVal(fields.zip, data.zipcode);
                            
                            // โฟกัสไปบ้านเลขที่
                            setTimeout(function(){ $("#" + type + "_address_1").focus(); }, 100);
                        }
                    });
                }
            }
            
            // รันครั้งแรก
            initThaiAddress("billing");
            initThaiAddress("shipping");

            // รันซ้ำเมื่อหน้า Checkout โหลดใหม่ (AJAX)
            $(document.body).on("updated_checkout", function(){
                initThaiAddress("billing");
                initThaiAddress("shipping");
            });
        });
    })(jQuery);
    ');
}

// 6. เพิ่ม CSS แต่งหน้าตาฟอร์ม (Makeover CSS)
add_action('wp_head', 'gustabe_custom_form_styles');
function gustabe_custom_form_styles() {
    if ( is_account_page() || is_checkout() ) {
        ?>
        <style>
            /* --- แปลงโฉม Input ให้เป็นแคปซูลมนๆ --- */
            .woocommerce-input-wrapper input[type="text"],
            .woocommerce-input-wrapper input[type="tel"],
            .woocommerce-input-wrapper input[type="email"],
            .select2-container .select2-selection--single {
                border-radius: 50px !important; /* ความมน */
                padding: 12px 20px !important;
                height: 50px !important;
                border: 1px solid #e0e0e0 !important;
                background-color: #f9f9f9 !important;
                box-shadow: inset 0 2px 4px rgba(0,0,0,0.02) !important;
                font-size: 15px !important;
                transition: all 0.3s ease !important;
                display: flex !important;
                align-items: center !important;
            }

            /* ตอนกดเลือก (Focus) ให้ขึ้นสีเขียว */
            .woocommerce-input-wrapper input:focus,
            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: #04a39c !important;
                background-color: #fff !important;
                box-shadow: 0 4px 15px rgba(4, 163, 156, 0.15) !important;
            }

            /* จัดการ Dropdown ของ Select2 ให้สวยขึ้น */
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100% !important;
                right: 15px !important;
            }
            .select2-dropdown {
                border-radius: 12px !important;
                border: 1px solid #eee !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
            }

            /* --- ปรับแต่งกล่องผลลัพธ์ Auto Complete (สำคัญมาก) --- */
            .tt-menu {
                background: #fff;
                border: 1px solid #eee;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.15);
                margin-top: 5px;
                padding: 5px 0;
                width: 100%;
                z-index: 99999 !important; /* ลอยทับทุกอย่าง */
            }
            .tt-suggestion {
                padding: 12px 20px;
                font-size: 14px;
                color: #555;
                cursor: pointer;
                border-bottom: 1px solid #f9f9f9;
            }
            .tt-suggestion:hover, .tt-suggestion.tt-cursor {
                background: #04a39c; /* สีธีมเขียว */
                color: #fff;
            }
        </style>
        <?php
    }
}