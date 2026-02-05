<?php
/**
 * GUSTABE AUTO ADDRESS (V7: Separated Logic)
 * ช่องค้นหา (Search) แยกกับช่องรับค่า (Data) เพื่อความเสถียร
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_enqueue_scripts', 'gustabe_load_local_thai_address' );

function gustabe_load_local_thai_address() {
    if ( ! is_account_page() && ! is_checkout() ) {
        return;
    }

    $base_url = get_stylesheet_directory_uri() . '/assets/thailand/';

    wp_enqueue_style( 'local-thailand-css', $base_url . 'jquery.Thailand.min.css' );
    wp_enqueue_script( 'local-jql', $base_url . 'JQL.min.js', array('jquery'), '1.0', true );
    wp_enqueue_script( 'local-typeahead', $base_url . 'typeahead.bundle.js', array('local-jql'), '1.0', true );
    wp_enqueue_script( 'local-thailand-core', $base_url . 'jquery.Thailand.js', array('local-typeahead'), '1.0', true );

    // Config แยกช่องค้นหากับช่องรับค่า
    $script_data = array(
        'database' => $base_url . 'db.json',
        'billing' => array(
            'search'   => '#billing_address_search', // ★ ช่องค้นหา (Dummy)
            'zip'      => '#billing_postcode',       // ★ ช่องรับค่าจริง (Real DB)
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

    wp_add_inline_script( 'local-thailand-core', '
    (function($){
        $(document).ready(function(){
            var config = ' . json_encode($script_data) . ';

            function initThaiAddress( type ) {
                var fields = config[type];
                var $searchBox = $(fields.search); // จับที่ช่องค้นหา

                if($searchBox.length > 0){
                    $.Thailand({
                        database: config.database,
                        $search: $searchBox, // ให้ Typeahead ทำงานที่ช่องค้นหา
                        onDataFill: function(data){
                            console.log("✅ Selected:", data);
                            
                            var setVal = function(sel, val) {
                                var $el = $(sel);
                                if($el.length) {
                                    $el.val(val).trigger("change");
                                    if( window.Select2 && $el.hasClass("select2-hidden-accessible") ) {
                                        $el.trigger("change.select2");
                                    }
                                }
                            };

                            // กรอกข้อมูลลงช่องจริง
                            setVal(fields.district, data.district);
                            setVal(fields.amphoe, data.amphoe);
                            setVal(fields.province, data.province);
                            setVal(fields.zip, data.zipcode); // กรอกรหัสไปรษณีย์ลงช่องจริง
                            
                            // โฟกัสไปบ้านเลขที่
                            setTimeout(function(){ $("#" + type + "_address_1").focus(); }, 100);
                        }
                    });
                }
            }
            
            initThaiAddress("billing");
            initThaiAddress("shipping");

            $(document.body).on("updated_checkout", function(){
                initThaiAddress("billing");
                initThaiAddress("shipping");
            });
        });
    })(jQuery);
    ');
}

// CSS ส่วน Makeover (เหมือนเดิม)
add_action('wp_head', 'gustabe_custom_form_styles');
function gustabe_custom_form_styles() {
    if ( is_account_page() || is_checkout() ) {
        ?>
        <style>
            .tt-menu { background: #fff; border: 1px solid #eee; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); margin-top: 5px; width: 100%; z-index: 99999 !important; }
            .tt-suggestion { padding: 12px 20px; font-size: 14px; color: #555; cursor: pointer; border-bottom: 1px solid #f9f9f9; }
            .tt-suggestion:hover, .tt-suggestion.tt-cursor { background: #04a39c; color: #fff; }
            .woocommerce-input-wrapper input[type="text"], .woocommerce-input-wrapper input[type="tel"], .woocommerce-input-wrapper input[type="email"] {
                border-radius: 50px !important; padding: 12px 20px !important; height: 50px !important; border: 1px solid #e0e0e0 !important; background-color: #f9f9f9 !important;
            }
        </style>
        <?php
    }
}