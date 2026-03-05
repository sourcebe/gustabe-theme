<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. เพิ่ม Section ตั้งค่า Card Design
add_filter( 'woocommerce_get_sections_products', 'hello_child_add_card_section' );
function hello_child_add_card_section( $sections ) {
    $sections['card_design'] = __( 'Product Card Design', 'hello-elementor-child' );
    return $sections;
}

add_filter( 'woocommerce_get_settings_products', 'hello_child_card_design_settings', 10, 2 );
function hello_child_card_design_settings( $settings, $current_section ) {
    if ( 'card_design' == $current_section ) {
        $settings = array();
        $settings[] = array( 'name' => __( 'Product Card Customization', 'hello-elementor-child' ), 'type' => 'title', 'id' => 'hello_card_design_options' );
        $settings[] = array(
            'name'     => __( 'เลือกรูปแบบการ์ด', 'hello-elementor-child' ),
            'id'       => 'hello_child_card_style',
            'type'     => 'select',
            'options'  => array(
                'style-1' => 'Style 1: Marketplace',
                'style-2' => 'Style 2: Quick Shop (+/-)',
                'style-3' => 'Style 3: Variation Focus',
                'style-4' => 'Style 4: Minimal Brand',
            ),
            'default'  => 'style-1',
        );
        $settings[] = array( 'type' => 'sectionend', 'id' => 'hello_card_design_options' );
        return $settings;
    }
    return $settings;
}

// 2. Inject Class การ์ดสินค้า
add_filter( 'post_class', 'hello_child_add_card_style_class', 10, 3 );
function hello_child_add_card_style_class( $classes, $class, $post_id ) {
    if ( 'product' === get_post_type( $post_id ) && ! is_admin() ) {
        $selected_style = get_option( 'hello_child_card_style', 'style-1' );
        $classes[] = 'hello-card-' . $selected_style;
    }
    return $classes;
}

// 3. JS สำหรับปุ่ม +/-
add_action( 'wp_footer', 'hello_child_card_quantity_script' );
function hello_child_card_quantity_script() {
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(document).on('click', '.qty-btn.minus', function() {
            var $input = $(this).siblings('.qty-input');
            var val = parseInt($input.val());
            if (val > 1) { $input.val(val - 1).trigger('change'); }
        });
        $(document).on('click', '.qty-btn.plus', function() {
            var $input = $(this).siblings('.qty-input');
            var val = parseInt($input.val());
            var max = $input.attr('max');
            if (!max || val < parseInt(max)) { $input.val(val + 1).trigger('change'); }
        });
        $(document).on('change keyup', '.qty-input', function() {
            var qty = $(this).val();
            var $addBtn = $(this).closest('.style-2-action-row').find('.ajax_add_to_cart');
            $addBtn.attr('data-quantity', qty);
        });
    });
    </script>
    <?php
}

// 4. LOGIC กรองสินค้า (On Sale / In Stock)
add_action( 'woocommerce_product_query', 'hello_child_handle_custom_filters' );
function hello_child_handle_custom_filters( $q ) {
    if ( is_admin() ) return;
    if ( isset( $_GET['filter_on_sale'] ) && $_GET['filter_on_sale'] == '1' ) {
        $product_ids_on_sale = wc_get_product_ids_on_sale();
        $q->set( 'post__in', $product_ids_on_sale );
    }
    if ( isset( $_GET['filter_in_stock'] ) && $_GET['filter_in_stock'] == '1' ) {
        $meta_query = $q->get( 'meta_query' );
        $meta_query[] = array( 'key' => '_stock_status', 'value' => 'instock', 'compare' => '=' );
        $q->set( 'meta_query', $meta_query );
    }
}

// 5. [POPUP] Filter (Minimal Dual Slider + Button Pills)
add_action( 'wp_footer', 'hello_child_render_mobile_filter_popup' );
function hello_child_render_mobile_filter_popup() {
    if ( ! is_shop() && ! is_product_taxonomy() ) return;

    $min_val = isset( $_GET['min_price'] ) ? intval( $_GET['min_price'] ) : 0;
    $max_val = isset( $_GET['max_price'] ) ? intval( $_GET['max_price'] ) : 5000; // Default max
    $on_sale   = isset( $_GET['filter_on_sale'] ) ? 'checked' : '';
    $in_stock  = isset( $_GET['filter_in_stock'] ) ? 'checked' : '';
    ?>
    <div id="filter-overlay" class="custom-filter-overlay"></div>
    <div id="filter-panel" class="custom-filter-panel minimal-theme">
        <form action="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" method="GET" id="mobile-filter-form">
            
            <div class="filter-header">
                <h3><?php if(function_exists('pll_e')) pll_e('ตัวกรองสินค้า'); else echo 'ตัวกรองสินค้า'; ?></h3>
                <span id="close-filter-btn" class="close-icon">&times;</span>
            </div>

            <div class="filter-body">
                <div class="filter-section">
                    <div class="status-btn-row">
                        <label class="status-btn-pill">
                            <input type="checkbox" name="filter_on_sale" value="1" <?php echo $on_sale; ?>>
                            <span class="btn-content">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
                                <?php if(function_exists('pll_e')) pll_e('ลดราคา'); else echo 'ลดราคา'; ?>
                            </span>
                        </label>
                        <label class="status-btn-pill">
                            <input type="checkbox" name="filter_in_stock" value="1" <?php echo $in_stock; ?>>
                            <span class="btn-content">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                <?php if(function_exists('pll_e')) pll_e('พร้อมส่ง'); else echo 'พร้อมส่ง'; ?>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="filter-section">
                    <h4 class="section-title"><?php if(function_exists('pll_e')) pll_e('ช่วงราคา'); else echo 'ช่วงราคา'; ?></h4>
                    <div class="range-slider-wrapper">
                        <div class="price-display">
                            <div class="price-box"><span class="currency">฿</span><input type="number" id="input-min" name="min_price" value="<?php echo $min_val; ?>"></div>
                            <span class="sep">-</span>
                            <div class="price-box"><span class="currency">฿</span><input type="number" id="input-max" name="max_price" value="<?php echo $max_val; ?>"></div>
                        </div>
                        <div class="slider-container">
                            <div class="slider-track"></div>
                            <input type="range" min="0" max="5000" value="<?php echo $min_val; ?>" id="range-min">
                            <input type="range" min="0" max="5000" value="<?php echo $max_val; ?>" id="range-max">
                        </div>
                    </div>
                </div>

                <?php
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                if ( $attribute_taxonomies ) :
                    foreach ( $attribute_taxonomies as $tax ) :
                        $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                        $label_name    = wc_attribute_label( $taxonomy_name );
                        $terms = get_terms( array( 'taxonomy' => $taxonomy_name, 'hide_empty' => true ) );
                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
                            ?>
                            <div class="filter-section">
                                <h4 class="section-title"><?php echo esc_html( $label_name ); ?></h4>
                                <div class="options-grid">
                                    <?php foreach ( $terms as $term ) : 
                                        $key = 'filter_' . $tax->attribute_name;
                                        $checked = ( isset( $_GET[$key] ) && in_array( $term->slug, explode(',', $_GET[$key]) ) ) ? 'checked' : '';
                                    ?>
                                        <label class="option-pill minimal">
                                            <input type="checkbox" name="<?php echo esc_attr($key); ?>[]" value="<?php echo esc_attr($term->slug); ?>" <?php echo $checked; ?>>
                                            <span><?php echo esc_html($term->name); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php
                        endif;
                    endforeach;
                endif;
                ?>
                <?php if( is_search() ): ?>
                    <input type="hidden" name="s" value="<?php echo get_search_query(); ?>">
                    <input type="hidden" name="post_type" value="product">
                <?php endif; ?>
            </div>

            <div class="filter-footer">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-reset"><?php if(function_exists('pll_e')) pll_e('ล้างค่า'); else echo 'ล้างค่า'; ?></a>
                <button type="submit" class="btn-apply"><?php if(function_exists('pll_e')) pll_e('ดูผลลัพธ์'); else echo 'ดูผลลัพธ์'; ?></button>
            </div>
        </form>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // Toggle Panel
        $('#mobile-filter-trigger').on('click', function(e) { e.preventDefault(); $('#filter-panel').addClass('is-visible'); $('#filter-overlay').addClass('is-visible'); $('body').addClass('no-scroll'); });
        $('#close-filter-btn, #filter-overlay').on('click', function() { $('#filter-panel').removeClass('is-visible'); $('#filter-overlay').removeClass('is-visible'); $('body').removeClass('no-scroll'); });

        // Dual Slider Logic
        const rangeMin = document.getElementById("range-min");
        const rangeMax = document.getElementById("range-max");
        const inputMin = document.getElementById("input-min");
        const inputMax = document.getElementById("input-max");
        const sliderTrack = document.querySelector(".slider-track");
        const sliderMaxValue = rangeMin ? rangeMin.max : 5000;
        const minGap = 0;
        function slideOne() { if(parseInt(rangeMax.value)-parseInt(rangeMin.value)<=minGap){rangeMin.value=parseInt(rangeMax.value)-minGap;} inputMin.value=rangeMin.value; fillColor(); }
        function slideTwo() { if(parseInt(rangeMax.value)-parseInt(rangeMin.value)<=minGap){rangeMax.value=parseInt(rangeMin.value)+minGap;} inputMax.value=rangeMax.value; fillColor(); }
        function fillColor() {
            if(!sliderTrack) return;
            percent1 = (rangeMin.value/sliderMaxValue)*100; percent2 = (rangeMax.value/sliderMaxValue)*100;
            sliderTrack.style.background = `linear-gradient(to right, #e0e0e0 ${percent1}% , #04a39c ${percent1}% , #04a39c ${percent2}%, #e0e0e0 ${percent2}%)`;
        }
        if(rangeMin && rangeMax) {
            rangeMin.addEventListener("input", slideOne); rangeMax.addEventListener("input", slideTwo);
            inputMin.addEventListener("input", function(){ rangeMin.value = this.value; fillColor(); });
            inputMax.addEventListener("input", function(){ rangeMax.value = this.value; fillColor(); });
            fillColor();
        }
    });
    </script>
    <?php
}

// 6. [POPUP] Sort (Translation Ready)
add_action( 'wp_footer', 'hello_child_render_mobile_sort_popup' );
function hello_child_render_mobile_sort_popup() {
    if ( ! is_shop() && ! is_product_taxonomy() ) return;
    $orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : 'menu_order';
    
    if ( function_exists( 'pll__' ) ) {
        $catalog_orderby_options = array(
            'menu_order' => pll__( 'ค่าเริ่มต้น' ),
            'popularity' => pll__( 'ได้รับความนิยมสูงสุด' ),
            'rating'     => pll__( 'คะแนนเฉลี่ยสูงสุด' ),
            'date'       => pll__( 'สินค้าใหม่ล่าสุด' ),
            'price'      => pll__( 'ราคาน้อยไปมาก' ),
            'price-desc' => pll__( 'ราคามากไปน้อย' ),
        );
    } else {
        $catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array() ); // Fallback
    }
    ?>
    <div id="sort-overlay" class="custom-filter-overlay"></div>
    <div id="sort-panel" class="custom-filter-panel sort-panel-height">
        <div class="filter-header">
            <h3><?php if(function_exists('pll_e')) pll_e('เรียงลำดับตาม'); else echo 'เรียงลำดับตาม'; ?></h3>
            <span id="close-sort-btn" class="close-icon">&times;</span>
        </div>
        <div class="filter-body">
            <ul class="sort-options-list">
                <?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
                    <li>
                        <a href="<?php echo esc_url( add_query_arg( 'orderby', $id ) ); ?>" 
                           class="sort-option-item <?php echo ( $orderby == $id ) ? 'active' : ''; ?>">
                            <?php echo esc_html( $name ); ?>
                            <?php if ( $orderby == $id ) : ?><svg class="check-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg><?php endif; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#mobile-sort-trigger').on('click', function(e) { e.preventDefault(); $('#sort-panel').addClass('is-visible'); $('#sort-overlay').addClass('is-visible'); $('body').addClass('no-scroll'); });
        $('#close-sort-btn, #sort-overlay').on('click', function() { $('#sort-panel').removeClass('is-visible'); $('#sort-overlay').removeClass('is-visible'); $('body').removeClass('no-scroll'); });
    });
    </script>
    <?php
}



// 7. [SCRIPT] Toast Notification Auto Close (แก้ไข: ให้ทำงานทุกหน้า)
add_action( 'wp_footer', 'hello_child_toast_notification_script' );
function hello_child_toast_notification_script() {
    // ลบเงื่อนไข if (...) ทิ้งไปเลยครับ เพื่อให้มันทำงานทุกหน้าที่มีการแจ้งเตือน
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // ตรวจสอบว่ามี Toast Wrapper เกิดขึ้นไหม
        if ( $('.gustabe-toast-wrapper').length > 0 ) {
            
            // ตั้งเวลา 4 วินาที
            setTimeout(function(){
                // 1. เพิ่ม class ให้จางหาย
                $('.gustabe-toast-item').addClass('fade-out');
                
                // 2. รอ Animation จบ 0.5 วิ แล้วลบ Element ทิ้ง
                setTimeout(function(){
                    $('.gustabe-toast-wrapper').remove();
                }, 500);
                
            }, 4000); 
        }
    });
    </script>
    <?php
}


// 8. [TRANSLATION] Custom Toast Message (Polylang Support)
// แปลงข้อความ "Address changed successfully." ให้เป็นข้อความของเราและรองรับการแปล
add_filter( 'gettext', 'hello_child_translate_toast_message', 20, 3 );
function hello_child_translate_toast_message( $translated_text, $text, $domain ) {
    
    // ดักจับเฉพาะข้อความของ WooCommerce
    if ( 'woocommerce' === $domain ) {
        // เช็คข้อความต้นฉบับภาษาอังกฤษของ WooCommerce
        if ( $text === 'Address changed successfully.' ) {
            
            // ถ้ามี Polylang ให้ดึงคำแปลมาแสดง
            if ( function_exists( 'pll__' ) ) {
                $translated_text = pll__( 'Address saved successfully' );
            } else {
                // ถ้าไม่มี Polylang ให้โชว์ภาษาอังกฤษเป็นค่าตั้งต้น
                $translated_text = 'Address saved successfully';
            }
        }
    }
    return $translated_text;
}


// 9. [FEATURE] Confirm Receipt Button (ปุ่มยืนยันรับสินค้า)
// ส่วนที่ 1: Javascript สำหรับกดปุ่ม
add_action( 'wp_footer', 'gustabe_confirm_receipt_script' );
function gustabe_confirm_receipt_script() {
    if ( ! is_account_page() ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.btn-confirm-receipt').on('click', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var order_id = $btn.data('order-id');
            var nonce = $btn.data('nonce');

            if (confirm('ยืนยันว่าคุณได้รับสินค้าและตรวจสอบเรียบร้อยแล้ว?')) {
                $btn.addClass('loading').prop('disabled', true).html('<i class="huge huge-loading-02"></i> กำลังบันทึก...');
                
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'gustabe_confirm_order_receipt',
                        order_id: order_id,
                        nonce: nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            // โหลดหน้าใหม่เพื่อโชว์สถานะสำเร็จ
                            location.reload(); 
                        } else {
                            alert('เกิดข้อผิดพลาด: ' + response.data);
                            $btn.removeClass('loading').prop('disabled', false).html('<i class="huge huge-checkmark-circle-02"></i> ลองอีกครั้ง');
                        }
                    },
                    error: function() {
                        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                        $btn.removeClass('loading').prop('disabled', false);
                    }
                });
            }
        });
    });
    </script>
    <style>
        /* ปุ่มยืนยันรับสินค้า สีส้มเด่นๆ */
        .btn-confirm-receipt {
            background: #ff9800 !important;
            color: #fff !important;
            border: none;
            padding: 8px 15px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.3s;
        }
        .btn-confirm-receipt:hover { background: #f57c00 !important; transform: translateY(-2px); }
        .btn-confirm-receipt.loading { opacity: 0.7; pointer-events: none; }
    </style>
    <?php
}

// ส่วนที่ 2: PHP จัดการเปลี่ยนสถานะเป็น Completed
add_action( 'wp_ajax_gustabe_confirm_order_receipt', 'gustabe_handle_confirm_receipt' );
function gustabe_handle_confirm_receipt() {
    // ตรวจสอบความปลอดภัย
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';

    if ( ! wp_verify_nonce( $nonce, 'confirm-receipt-' . $order_id ) ) {
        wp_send_json_error( 'Invalid request' );
    }

    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        wp_send_json_error( 'Order not found' );
    }

    // ตรวจสอบว่าเป็นเจ้าของออเดอร์จริงไหม
    if ( $order->get_user_id() !== get_current_user_id() ) {
        wp_send_json_error( 'Permission denied' );
    }

    // เปลี่ยนสถานะเป็น Completed
    $order->update_status( 'completed', 'ลูกค้ากดยืนยันรับสินค้าผ่านหน้าเว็บ' );
    
    wp_send_json_success();
}


// 10. [SYSTEM] เพิ่มสถานะ "อยู่ระหว่างขนส่ง" (Shipped) ในหลังบ้าน
add_action( 'init', 'gustabe_register_shipped_order_status' );
function gustabe_register_shipped_order_status() {
    register_post_status( 'wc-shipped', array(
        'label'                     => '🚚 อยู่ระหว่างขนส่ง', // ชื่อที่จะโชว์
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'อยู่ระหว่างขนส่ง <span class="count">(%s)</span>', 'อยู่ระหว่างขนส่ง <span class="count">(%s)</span>' )
    ) );
}

// เพิ่มเข้าไปใน Dropdown รายการสถานะ
add_filter( 'wc_order_statuses', 'gustabe_add_shipped_to_order_statuses' );
function gustabe_add_shipped_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        // แทรกต่อจาก "กำลังดำเนินการ" (Processing)
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-shipped'] = _x( '🚚 อยู่ระหว่างขนส่ง', 'Order status', 'gustabe' );
        }
    }
    return $new_order_statuses;
}

// (แถม) ทำให้สถานะนี้ถือว่า "จ่ายเงินแล้ว" (ดาวน์โหลดของได้ / ตัดสต็อก)
add_filter( 'woocommerce_order_is_paid_statuses', 'gustabe_shipped_is_paid' );
function gustabe_shipped_is_paid( $statuses ) {
    $statuses[] = 'shipped';
    return $statuses;
}


// 11. [SCRIPT] Order Quick View Popup (Slide-up Sheet)
add_action( 'wp_footer', 'gustabe_quick_view_script' );
function gustabe_quick_view_script() {
    if ( ! is_account_page() ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // เปิด Popup
        $('.trigger-popup').on('click', function(e) {
            e.preventDefault();
            var targetID = $(this).data('target');
            $('#' + targetID).addClass('is-visible');
            $('body').addClass('no-scroll'); // ล็อคไม่ให้หลังบ้านเลื่อน
        });

        // ปิด Popup (กดปุ่ม X หรือ กดพื้นหลังดำ)
        $('.close-popup-btn, .gustabe-popup-overlay').on('click', function(e) {
            if (e.target !== this && !$(e.target).hasClass('close-popup-btn')) return; // ถ้ากดโดนเนื้อหาข้างในไม่ต้องปิด
            $('.gustabe-popup-overlay').removeClass('is-visible');
            $('body').removeClass('no-scroll');
        });
    });
    </script>
    <?php
}



// ==========================================
// 12. [SYSTEM] AJAX Order Quick View (The Fix)
// ==========================================

// ส่วนที่ 1: สร้าง Popup เปล่าๆ ไว้ที่ Footer (รอรับข้อมูล)
add_action( 'wp_footer', 'gustabe_render_global_order_popup' );
function gustabe_render_global_order_popup() {
    if ( ! is_account_page() ) return;
    ?>
    <div id="gustabe-global-order-popup" class="gustabe-popup-overlay">
        <div class="gustabe-popup-content slide-up-sheet">
            <div class="popup-header">
                <div class="ph-left" id="gop-header-info">
                    <h3>Loading...</h3>
                </div>
                <button class="close-popup-btn">&times;</button>
            </div>
            
            <div class="popup-body scrollable" id="gop-body-content">
                <div class="gop-loading"><i class="huge huge-loading-02 spin"></i> กำลังโหลดข้อมูล...</div>
            </div>

            <div class="popup-footer" id="gop-footer-content">
                </div>
        </div>
    </div>
    <?php
}

// ส่วนที่ 2: AJAX Handler (PHP) ดึงข้อมูลออเดอร์
add_action( 'wp_ajax_gustabe_get_order_details', 'gustabe_ajax_get_order_details' );
function gustabe_ajax_get_order_details() {
    // ... (ส่วนตรวจสอบ Security คงเดิม) ...
    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : '';
    if ( ! wp_verify_nonce( $nonce, 'view-order-' . $order_id ) ) wp_send_json_error( 'Invalid request' );
    $order = wc_get_order( $order_id );
    if ( ! $order || $order->get_user_id() !== get_current_user_id() ) wp_send_json_error( 'Order not found' );

    // 1. Header (Translatable)
    ob_start();
    ?>
    <h3><?php esc_html_e( 'รายการสินค้า', 'gustabe' ); ?></h3>
    <span><?php printf( esc_html__( 'Order #%s', 'gustabe' ), $order->get_order_number() ); ?></span>
    <?php
    $header_html = ob_get_clean();

    // 2. Body (Translatable)
    ob_start();
    ?>
    <ul class="quick-order-items">
        <?php foreach ( $order->get_items() as $item_id => $item ) : 
            $product = $item->get_product(); ?>
            <li class="qo-item">
                <div class="qo-img"><?php echo $product ? $product->get_image(array(60,60)) : ''; ?></div>
                <div class="qo-info">
                    <span class="qo-name"><?php echo esc_html( $item->get_name() ); ?></span>
                    <div class="qo-meta">
                        <span class="qo-qty">x <?php echo esc_html( $item->get_quantity() ); ?></span>
                        <span class="qo-price"><?php echo wc_price( $order->get_item_total( $item, false, true ) ); ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php
    $body_html = ob_get_clean();

    // 3. Footer (Translatable)
    ob_start();
    ?>
    <div class="pf-summary">
        <span><?php esc_html_e( 'ยอดสุทธิ', 'gustabe' ); ?></span>
        <strong><?php echo $order->get_formatted_order_total(); ?></strong>
    </div>
    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="btn-full-action">
        <?php esc_html_e( 'ดูรายละเอียดเต็ม', 'gustabe' ); ?>
    </a>
    <?php
    $footer_html = ob_get_clean();

    wp_send_json_success( array(
        'header' => $header_html,
        'body'   => $body_html,
        'footer' => $footer_html
    ));
}

// ส่วนที่ 3: Javascript (ควบคุมการทำงาน)
add_action( 'wp_footer', 'gustabe_ajax_quick_view_script' );
function gustabe_ajax_quick_view_script() {
    if ( ! is_account_page() ) return;
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $popup = $('#gustabe-global-order-popup');
        var $header = $('#gop-header-info');
        var $body = $('#gop-body-content');
        var $footer = $('#gop-footer-content');
        var loadingHTML = '<div class="gop-loading"><i class="huge huge-loading-02 spin"></i> กำลังโหลดข้อมูล...</div>';

        // เปิด Popup และเรียก AJAX
        $('.trigger-popup-ajax').on('click', function(e) {
            e.preventDefault();
            var order_id = $(this).data('order-id');
            var nonce = $(this).data('nonce');

            // Reset เป็นสถานะ Loading ก่อน
            $header.html('<h3>Loading...</h3>');
            $body.html(loadingHTML);
            $footer.empty();
            $popup.addClass('is-visible');
            $('body').addClass('no-scroll');

            // เรียกข้อมูล
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: { action: 'gustabe_get_order_details', order_id: order_id, nonce: nonce },
                success: function(response) {
                    if (response.success) {
                        $header.html(response.data.header);
                        $body.html(response.data.body);
                        $footer.html(response.data.footer);
                    } else {
                        $body.html('<p class="gop-error">เกิดข้อผิดพลาด: ' + response.data + '</p>');
                    }
                }
            });
        });

        // ปิด Popup
        $('.close-popup-btn, .gustabe-popup-overlay').on('click', function(e) {
            if (e.target !== this && !$(e.target).hasClass('close-popup-btn')) return;
            $popup.removeClass('is-visible');
            $('body').removeClass('no-scroll');
        });
    });
    </script>
    <style>
        /* เพิ่ม CSS สำหรับ Loading */
        .gop-loading { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 200px; color: #999; gap: 15px; }
        .gop-loading i { font-size: 30px; color: #04a39c; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .spin { animation: spin 1s linear infinite; }
        .gop-error { color: red; text-align: center; padding: 20px; }
    </style>
    <?php
}



/**
 * GUSTABE AJAX CART UPDATE
 * รับค่าจาก JS -> อัปเดตตะกร้า -> ส่ง HTML กลับไปแปะหน้าเว็บ
 */
add_action( 'wp_ajax_gustabe_update_cart_qty', 'gustabe_ajax_update_cart_qty' );
add_action( 'wp_ajax_nopriv_gustabe_update_cart_qty', 'gustabe_ajax_update_cart_qty' );

function gustabe_ajax_update_cart_qty() {
    // 1. เช็คความปลอดภัย
    if ( ! isset($_POST['nonce']) || ! wp_verify_nonce($_POST['nonce'], 'gustabe_cart_nonce') ) {
        wp_send_json_error( 'Security check failed' );
    }

    $cart_item_key = sanitize_text_field( $_POST['key'] );
    $qty = intval( $_POST['qty'] );

    // 2. อัปเดตจำนวนสินค้าในตะกร้าจริง
    if ( $cart_item_key && $qty >= 0 ) {
        WC()->cart->set_quantity( $cart_item_key, $qty, true );
        WC()->cart->calculate_totals();
        WC()->cart->calculate_shipping();
    }

    // 3. เตรียมข้อมูลส่งกลับ (HTML ก้อนใหม่)
    
    // 3.1: ราคารวม (Cart Totals) - ดึงไฟล์ cart-totals.php ที่เราเพิ่งสร้าง
    ob_start();
    woocommerce_cart_totals(); 
    $cart_totals_html = ob_get_clean();

    // 3.2: ราคารายชิ้น (Item Subtotal)
    $cart_item = WC()->cart->get_cart_item( $cart_item_key );
    $item_subtotal = '';
    if ( $cart_item ) {
        $item_subtotal = WC()->cart->get_product_subtotal( $cart_item['data'], $cart_item['quantity'] );
    }

    // 3.3: ข้อความ Rewards (Progress Bar)
    // (เราต้องเขียน Logic ดึง HTML ส่วนนี้ใหม่ หรือส่งแค่ยอดรวมไปคำนวณใน JS ก็ได้)
    // เพื่อความง่าย ให้ส่งยอดรวมไปอัปเดตหลอด
    $cart_subtotal_float = WC()->cart->get_subtotal();

    wp_send_json_success( array(
        'cart_totals'   => $cart_totals_html,
        'item_subtotal' => $item_subtotal,
        'cart_total_float' => $cart_subtotal_float,
        'msg' => 'Updated'
    ));
}



