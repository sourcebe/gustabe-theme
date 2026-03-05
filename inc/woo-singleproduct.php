<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================
   CUSTOM SINGLE PRODUCT LAYOUT
   ========================================= */

// 1. ลบส่วนที่ไม่จำเป็นออก (SKU, Category, Tags) ให้หน้าดูคลีน
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

// 2. ลบ Heading ของ Tabs ด้านล่าง (Description) เพราะมันซ้ำซ้อน
add_filter( 'woocommerce_product_description_heading', '__return_null' );

// 3. ปรับลำดับ: อยากเอาราคา (Price) มาไว้ใต้ชื่อ (Title) ทันที (เผื่อธีมเดิมมันสลับกัน)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 6 ); // ใส่ priority 6 ให้ต่อจาก Title (5)

// 4. (Optional) ย้ายคำอธิบายสั้น (Excerpt) ไปไว้ใต้ปุ่มซื้อแทน (ถ้าชอบ)
// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
// add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 31 );





/**
 * 1. เพิ่มตัวเลือก Gallery Layout ใน Customize > WooCommerce > Product Images
 */
add_action( 'customize_register', 'gustabe_customize_register' );
function gustabe_customize_register( $wp_customize ) {
    
    // สร้าง Setting
    $wp_customize->add_setting( 'product_gallery_layout', array(
        'default'   => 'slider', // ค่าเริ่มต้น
        'transport' => 'refresh',
    ) );

    // สร้าง Control (Dropdown)
    $wp_customize->add_control( 'product_gallery_layout', array(
        'label'    => __( 'Gallery Layout Design', 'hello-elementor-child' ),
        'description' => __( 'เลือกรูปแบบการแสดงผลรูปภาพสินค้า', 'hello-elementor-child' ),
        'section'  => 'woocommerce_product_images', // ไปโผล่ในหมวดรูปสินค้าของ Woo
        'settings' => 'product_gallery_layout',
        'type'     => 'select',
        'choices'  => array(
            'slider'    => '1. Modern Slider (มาตรฐานแอป)',
            'vertical'  => '2. Fashion Scroll (เรียงยาวลงมา)',
            'grid'      => '3. Mosaic Grid (ตารางสวยงาม)',
            'sticky'    => '4. Sticky Split (แบ่งครึ่งจอ)',
        ),
    ) );
}





/**
 * 2. ปิด Gallery เดิม แล้วใส่ Custom Gallery ของเรา
 */
// ปิดของเดิม
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
// ใส่ของใหม่
add_action( 'woocommerce_before_single_product_summary', 'gustabe_custom_product_gallery', 20 );

function gustabe_custom_product_gallery() {
    global $product;
    
    // ดึงค่า Layout ที่เลือกไว้ (ถ้าไม่มี ให้ใช้ slider เป็นค่าเริ่ม)
    $layout = get_theme_mod( 'product_gallery_layout', 'slider' );
    
    // ดึง ID รูปภาพทั้งหมด
    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id  = $product->get_image_id();
    
    // ถ้าไม่มีรูปเลยสักรูป
    if ( ! $main_image_id ) { 
        echo '<div class="gallery-placeholder">No Image</div>'; 
        return; 
    }

    // เตรียม Array รูปทั้งหมด (รูปหลัก + รูปย่อย)
    $all_images = array();
    if ( $main_image_id ) $all_images[] = $main_image_id;
    if ( $attachment_ids ) $all_images = array_merge( $all_images, $attachment_ids );

    // เริ่มสร้าง HTML Wrapper โดยใส่ class ตามชื่อ Layout
    // เช่น class="gustabe-gallery-wrapper layout-slider"
    echo '<div class="gustabe-gallery-wrapper layout-' . esc_attr( $layout ) . '">';
    
    // --- CASE A: SLIDER (ใช้ Swiper Structure) ---
    if ( $layout === 'slider' ) {
        echo '<div class="swiper myProductSwiper"><div class="swiper-wrapper">';
        foreach ( $all_images as $img_id ) {
            $url = wp_get_attachment_image_url( $img_id, 'large' ); // รูปใหญ่
            echo '<div class="swiper-slide"><img src="' . esc_url($url) . '" /></div>';
        }
        echo '</div><div class="swiper-pagination"></div></div>'; // จบ Swiper ใหญ่
        
        // Thumbs (รูปเล็กข้างล่าง)
        if ( count($all_images) > 1 ) {
             echo '<div class="gallery-thumbs-row">';
             foreach ( $all_images as $index => $img_id ) {
                 $thumb_url = wp_get_attachment_image_url( $img_id, 'thumbnail' );
                 echo '<div class="thumb-item" onclick="gallerySlideTo('.$index.')"><img src="' . esc_url($thumb_url) . '" /></div>';
             }
             echo '</div>';
        }

    // --- CASE B, C, D: SCROLL / GRID / STICKY (เน้นโชว์รูปดิบๆ CSS จัดการเอง) ---
    } else {
        echo '<div class="gallery-grid-container">';
        foreach ( $all_images as $index => $img_id ) {
            // ถ้ารูปแรก ให้เป็นรูปใหญ่ (Hero)
            $size = ( $index === 0 && $layout !== 'grid' ) ? 'full' : 'large';
            $url = wp_get_attachment_image_url( $img_id, $size );
            
            // ใส่ class แยกให้รูปแรก
            $img_class = ( $index === 0 ) ? 'gallery-hero-img' : 'gallery-item-img';
            
            echo '<div class="gallery-item item-index-'.esc_attr($index).'">';
            echo '<img src="' . esc_url($url) . '" class="'.esc_attr($img_class).'" loading="lazy" />';
            echo '</div>';
        }
        echo '</div>';
    }
    
    echo '</div>'; // จบ Wrapper
}




// เรียก Swiper JS/CSS จาก CDN (ถ้าธีมยังไม่มี)
add_action( 'wp_enqueue_scripts', 'gustabe_enqueue_gallery_scripts' );
function gustabe_enqueue_gallery_scripts() {
    if ( is_product() ) {
        wp_enqueue_style( 'swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css' );
        wp_enqueue_script( 'swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), null, true );
    }
}

// สคริปต์สั่งทำงาน
add_action( 'wp_footer', 'gustabe_gallery_init_script' );
function gustabe_gallery_init_script() {
    if ( ! is_product() ) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // เช็คว่าเลือก layout slider ไหม ถ้าใช่ค่อยรัน
        if ( document.querySelector('.layout-slider .myProductSwiper') ) {
            var swiper = new Swiper(".myProductSwiper", {
                pagination: { el: ".swiper-pagination", dynamicBullets: true },
                spaceBetween: 10,
            });
            
            // ฟังก์ชันกด Thumb แล้วเปลี่ยนรูป
            window.gallerySlideTo = function(index) {
                swiper.slideTo(index);
            }
        }
    });
    </script>
    <?php
}


/**
 * 4. เพิ่ม Class ให้ Body ตาม Layout ที่เลือก (เพื่อให้ CSS ดักจับได้)
 */
add_filter( 'body_class', 'gustabe_add_layout_class' );
function gustabe_add_layout_class( $classes ) {
    if ( is_product() ) {
        // ดึงค่าที่เลือกจาก Customize
        $layout = get_theme_mod( 'product_gallery_layout', 'slider' );
        // เพิ่ม class ชื่อ "product-layout-xxxx"
        $classes[] = 'product-layout-' . $layout;
    }
    return $classes;
}

/**
 * เปลี่ยนป้าย Sale ให้โชว์เป็น % ส่วนลด (เช่น -25%)
 */
add_filter( 'woocommerce_sale_flash', 'gustabe_show_sale_percentage', 20, 3 );
function gustabe_show_sale_percentage( $html, $post, $product ) {
    if ( $product->is_on_sale() ) {
        $percentage = 0;

        // กรณี 1: สินค้าธรรมดา (Simple Product)
        if ( $product->is_type( 'simple' ) || $product->is_type( 'external' ) ) {
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_sale_price();

            if ( $regular_price > 0 ) {
                $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
            }
        } 
        // กรณี 2: สินค้ามีตัวเลือก (Variable Product)
        elseif ( $product->is_type( 'variable' ) ) {
            $prices = $product->get_variation_prices();
            $max_percentage = 0;

            foreach ( $prices['regular_price'] as $key => $regular_price ) {
                // หาเฉพาะตัวที่ลดราคา
                $sale_price = $prices['sale_price'][$key];
                if ( $sale_price < $regular_price && $regular_price > 0 ) {
                    $curr_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                    if ( $curr_percentage > $max_percentage ) {
                        $max_percentage = $curr_percentage;
                    }
                }
            }
            $percentage = $max_percentage;
        }

        // ถ้าคำนวณได้ % ให้โชว์ป้าย
        if ( $percentage > 0 ) {
            // output: <span class="onsale">-20%</span>
            return '<span class="onsale">-' . $percentage . '%</span>';
        }
    }
    
    // ถ้าคำนวณไม่ได้ ให้ใช้ค่าเดิมไปก่อน
    return $html;
}







/**
 * GUSTABE SUITE: Product Page Makeover (All-in-One)
 * 1. JS: แปลง Dropdown เป็นปุ่ม Swatches + ปุ่ม +/-
 * 2. PHP: ระบบเลือกสีหลังบ้าน (Safe Version)
 * 3. CSS Generator: ดึงสีมาแสดงหน้าเว็บ
 */

// ส่วนที่ 1: ฝัง Script สร้างปุ่ม (Frontend JS)
add_action( 'wp_footer', 'gustabe_product_script_enhancement' );
function gustabe_product_script_enhancement() {
    if ( ! is_product() ) return;
    ?>
    <script>
    jQuery(document).ready(function($) {

        // if ( window.matchMedia("(max-width: 991px)").matches ) {
        //     return; 
        // }

        
        // --- A. แปลง Select เป็นปุ่ม Swatches ---
        var $form = $('form.variations_form');
        
        if ( $form.length ) {
            $form.find('select').each(function() {
                var $select = $(this);
                if ( $select.css('display') == 'none' ) return;
                
                // หา Label เพื่อดูว่าเป็น "สี" หรือไม่
                var $row = $select.closest('tr');
                var labelText = $row.find('.label label').text().toLowerCase();
                // คำที่บ่งบอกว่าเป็นสี
                var isColor = labelText.includes('สี') || labelText.includes('color') || labelText.includes('colour') || labelText.includes('ลาย');
                
                var $swatchContainer = $('<div class="gustabe-swatches-wrap"></div>');
                
                $select.find('option').each(function() {
                    var val = $(this).val();
                    var text = $(this).text();
                    if ( val === '' ) return;
                    
                    // สร้าง Class เพื่อดึงสีจาก PHP
                    var cleanVal = val.toLowerCase().replace(/[^a-z0-9]/g, '-');
                    var colorClass = isColor ? 'is-color swatch-val-' + cleanVal : '';
                    
                    var $btn = $('<div class="gustabe-swatch-item ' + colorClass + '" data-value="' + val + '">' + text + '</div>');
                    
                    $btn.click(function() {
                        $select.val( val ).trigger('change'); // สั่ง Select เปลี่ยนค่า
                        $swatchContainer.find('.gustabe-swatch-item').removeClass('selected');
                        $(this).addClass('selected');
                    });
                    
                    $swatchContainer.append($btn);
                });
                
                $select.after($swatchContainer);
                $select.addClass('gustabe-hide-select'); // ซ่อน Select เดิม
                $row.addClass('gustabe-variation-row');
            });
            
            // ล้างค่าเมื่อกด Reset
            $form.on('reset_data', function() {
                $('.gustabe-swatch-item').removeClass('selected');
            });
        }

        // --- B. สร้างปุ่ม +/- (Quantity) ---
        var $qtyDiv = $('form.cart .quantity');
        if ( $qtyDiv.length && !$qtyDiv.find('.qty-btn').length ) {
            var $minus = $('<button type="button" class="qty-btn qty-minus">-</button>');
            var $plus = $('<button type="button" class="qty-btn qty-plus">+</button>');
            
            $qtyDiv.prepend($minus);
            $qtyDiv.append($plus);
            
            $qtyDiv.find('.qty-btn').click(function() {
                var $input = $(this).siblings('input.qty');
                var val = parseInt($input.val()) || 0;
                var step = parseInt($input.attr('step')) || 1;
                var min = parseInt($input.attr('min')) || 1;
                
                if ( $(this).hasClass('qty-minus') ) {
                    if ( val > min ) $input.val( val - step ).trigger('change');
                } else {
                    $input.val( val + step ).trigger('change');
                }
            });
        }
    });
    </script>
    <?php
}

// ส่วนที่ 2: ระบบเลือกสีหลังบ้าน (Safe Version - กันเว็บพัง)
if ( class_exists( 'WooCommerce' ) ) {

    // เพิ่มช่องเลือกสี
    add_action( 'admin_init', 'gustabe_safe_add_color_field' );
    function gustabe_safe_add_color_field() {
        if ( ! function_exists( 'wc_get_attribute_taxonomies' ) ) return;
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                add_action( $taxonomy_name . '_add_form_fields', 'gustabe_add_color_picker_field', 10, 2 );
                add_action( $taxonomy_name . '_edit_form_fields', 'gustabe_edit_color_picker_field', 10, 2 );
            }
        }
    }

    function gustabe_add_color_picker_field( $taxonomy ) {
        ?>
        <div class="form-field term-color-wrap">
            <label for="term_color">เลือกสี (Color Swatch)</label>
            <input type="color" name="gustabe_term_color" id="gustabe_term_color" value="#ffffff" style="max-width: 100px;">
            <p>จิ้มเลือกสีสำหรับตัวเลือกนี้</p>
        </div>
        <?php
    }

    function gustabe_edit_color_picker_field( $term, $taxonomy ) {
        $color = get_term_meta( $term->term_id, 'gustabe_term_color', true );
        $color = ( ! empty( $color ) ) ? $color : '#ffffff';
        ?>
        <tr class="form-field term-color-wrap">
            <th scope="row"><label for="term_color">เลือกสี (Color Swatch)</label></th>
            <td>
                <input type="color" name="gustabe_term_color" id="gustabe_term_color" value="<?php echo esc_attr( $color ); ?>">
                <p class="description">จิ้มเลือกสีสำหรับตัวเลือกนี้</p>
            </td>
        </tr>
        <?php
    }

    // บันทึกค่าสี
    add_action( 'created_term', 'gustabe_save_term_color', 10, 3 );
    add_action( 'edit_term', 'gustabe_save_term_color', 10, 3 );
    function gustabe_save_term_color( $term_id, $tt_id = '', $taxonomy = '' ) {
        if ( isset( $_POST['gustabe_term_color'] ) && ! empty( $_POST['gustabe_term_color'] ) ) {
            update_term_meta( $term_id, 'gustabe_term_color', sanitize_hex_color( $_POST['gustabe_term_color'] ) );
        }
    }

    // แสดง CSS สีหน้าเว็บ (Safe Load)
    add_action( 'wp_head', 'gustabe_safe_output_swatch_css', 99 );
    function gustabe_safe_output_swatch_css() {
        if ( ! is_product() ) return;
        $product_id = get_the_ID();
        $product = wc_get_product( $product_id );
        if ( ! $product ) return;
        
        $attributes = $product->get_attributes();
        if ( ! $attributes ) return;

        $css_output = "";
        foreach ( $attributes as $attribute ) {
            if ( $attribute->is_taxonomy() ) {
                $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );
                if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
                    foreach ( $terms as $term ) {
                        $color = get_term_meta( $term->term_id, 'gustabe_term_color', true );
                        $slug = strtolower( preg_replace('/[^a-z0-9]/i', '-', $term->slug) );
                        
                        if ( $color && $color !== '#ffffff' ) {
                            $css_output .= ".swatch-val-{$slug} { background-color: {$color} !important; }\n";
                        } else {
                            // ถ้าเป็นสีขาว หรือไม่ได้เลือกสี ให้ใส่ขอบจะได้ไม่จม
                            $css_output .= ".swatch-val-{$slug} { background-color: #ffffff; border: 1px solid #ddd; }\n";
                        }
                    }
                }
            }
        }
        
        if ( ! empty( $css_output ) ) {
            echo '<style id="gustabe-dynamic-swatches">' . $css_output . '</style>';
        }
    }
    
    // แสดงคอลัมน์สีในหน้า Admin
    add_filter( 'manage_product_page_product_attributes_columns', 'gustabe_add_color_column_header' );
    function gustabe_add_color_column_header( $columns ) {
        $new_columns = array();
        foreach ( $columns as $key => $value ) {
            $new_columns[$key] = $value;
            if ( $key === 'cb' ) {
                $new_columns['gustabe_color_preview'] = __( 'ตัวอย่างสี', 'woocommerce' );
            }
        }
        return $new_columns;
    }
    add_filter( 'manage_product_custom_column', 'gustabe_add_color_column_content', 10, 3 );
    function gustabe_add_color_column_content( $content, $column_name, $term_id ) {
        if ( $column_name !== 'gustabe_color_preview' ) return $content;
        $color = get_term_meta( $term_id, 'gustabe_term_color', true );
        if ( $color ) {
            $content = sprintf('<div style="width:30px;height:30px;border-radius:50%%;background-color:%s;border:1px solid #ddd;"></div>', esc_attr($color));
        } else {
            $content = '<span style="color:#999;">—</span>';
        }
        return $content;
    }
    add_action( 'admin_init', 'gustabe_register_color_column_for_all_attributes' );
    function gustabe_register_color_column_for_all_attributes() {
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        if ( $attribute_taxonomies ) {
            foreach ( $attribute_taxonomies as $tax ) {
                $taxonomy_name = wc_attribute_taxonomy_name( $tax->attribute_name );
                add_filter( "manage_edit-{$taxonomy_name}_columns", 'gustabe_add_color_column_header' );
                add_filter( "manage_{$taxonomy_name}_custom_column", 'gustabe_add_color_column_content', 10, 3 );
            }
        }
    }
}



/**
 * Remove WooCommerce Tabs and display content sequentially
 * เปลี่ยนแท็บให้เป็นเนื้อหาเรียงยาวลงมา
 */
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
add_action( 'woocommerce_after_single_product_summary', 'gustabe_output_product_data_stacked', 10 );

function gustabe_output_product_data_stacked() {
    global $product;
    
    // เตรียมคำแปลหัวข้อ
    $txt_desc = function_exists('pll__') ? pll__('รายละเอียดสินค้า') : 'รายละเอียดสินค้า';
    $txt_attr = function_exists('pll__') ? pll__('ข้อมูลจำเพาะ') : 'ข้อมูลจำเพาะ';
    
    // 1. ส่วนคำอธิบาย (Description)
    $desc_content = get_the_content();
    if ( ! empty( $desc_content ) ) {
        echo '<div class="product-section-stacked product-description" id="description">'; // เพิ่ม id="description" ให้ FAB วิ่งมาหาเจอ
        echo '<h2 class="section-title">' . esc_html($txt_desc) . '</h2>';
        the_content();
        echo '</div>';
    }
    
    // 2. ข้อมูลเพิ่มเติม (Additional Info)
    if ( $product && ( $product->has_attributes() || apply_filters( 'wc_product_enable_dimensions_display', $product->has_weight() || $product->has_dimensions() ) ) ) {
        echo '<div class="product-section-stacked product-attributes">';
        echo '<h2 class="section-title">' . esc_html($txt_attr) . '</h2>';
        wc_display_product_attributes( $product );
        echo '</div>';
    }
    
    // 3. รีวิว (Reviews)
    if ( comments_open() ) {
        echo '<div class="product-section-stacked product-reviews" id="reviews">';
        comments_template();
        echo '</div>';
    }
}


/**
 * ============================================================
 * GUSTABE: REGISTER PRODUCT HEADINGS FOR POLYLANG
 * ลงทะเบียนคำว่า "รายละเอียดสินค้า" และ "ข้อมูลจำเพาะ" ให้แปลได้
 * ============================================================
 */
add_action( 'init', 'gustabe_register_product_strings' );
function gustabe_register_product_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // (ชื่ออ้างอิง, ข้อความไทย, หมวดหมู่)
        pll_register_string( 'product_desc_heading', 'รายละเอียดสินค้า', 'Gustabe Product' );
        pll_register_string( 'product_additional_heading', 'ข้อมูลจำเพาะ', 'Gustabe Product' );
    }
}



/**
 * GUSTABE: Register Card Strings
 * ลงทะเบียนคำศัพท์ในการ์ดสินค้า
 */
add_action( 'init', 'gustabe_register_card_strings' );
function gustabe_register_card_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // (ชื่ออ้างอิง, ข้อความไทย, หมวดหมู่)
        pll_register_string( 'card_sold_label', 'ขายแล้ว', 'Gustabe Card' );
        pll_register_string( 'card_sold_unit', 'ชิ้น', 'Gustabe Card' );
        pll_register_string( 'card_out_stock', 'สินค้าหมด', 'Gustabe Card' );
        pll_register_string( 'card_view_details', 'ดูรายละเอียด', 'Gustabe Card' );
        pll_register_string( 'card_add_cart', 'ใส่ตะกร้า', 'Gustabe Card' );
    }
}





/* =================================================================
   CUSTOM REVIEW SYSTEM (FULL SUITE & POLYLANG FIXED)
   ================================================================= */

/* 1. เพิ่มช่องตั้งค่าหัวข้อรีวิว (WooCommerce > Settings > Products) */
add_filter( 'woocommerce_product_settings', 'custom_review_add_setting' );
function custom_review_add_setting( $settings ) {
    $updated_settings = array();
    foreach ( $settings as $section ) {
        $updated_settings[] = $section;
        if ( isset( $section['id'] ) && 'woocommerce_enable_reviews' == $section['id'] ) {
            $updated_settings[] = array(
                'title'    => __( 'Review Criteria', 'woocommerce' ),
                'desc'     => __( 'ใส่ชื่อหัวข้อรีวิว คั่นด้วยลูกน้ำ (เช่น: คุณภาพ,การจัดส่ง)', 'woocommerce' ),
                'id'       => 'custom_review_criteria_list',
                'type'     => 'text',
                'css'      => 'min-width:300px;',
                'desc_tip' => true,
            );
        }
    }
    return $updated_settings;
}

/* 2. ฟังก์ชันลงทะเบียนคำแปล (ทำงานตอนกด Save Setting) */
add_action( 'update_option_custom_review_criteria_list', 'custom_register_polylang_strings', 10, 3 );
function custom_register_polylang_strings( $old_value, $value, $option ) {
    if ( function_exists( 'pll_register_string' ) && ! empty( $value ) ) {
        $criteria_array = explode( ',', $value );
        foreach ( $criteria_array as $criteria ) {
            $criteria = trim( $criteria );
            if ( ! empty( $criteria ) ) {
                pll_register_string( 'Custom Review Criteria', $criteria, 'WooCommerce Review' );
            }
        }
    }
}

/* 3. ฟังก์ชันบังคับลงทะเบียนคำแปลทันที (Force Register - รวมหัวข้อด้วย) */
add_action( 'init', 'custom_force_polylang_strings' );
function custom_force_polylang_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // ลงทะเบียนหัวข้อ
        pll_register_string( 'Review Header', 'Additional Ratings', 'WooCommerce Review' );
        
        // ลงทะเบียน Criteria ที่ตั้งไว้
        $criteria_string = get_option( 'custom_review_criteria_list' );
        if ( ! empty( $criteria_string ) ) {
            $criteria_array = explode( ',', $criteria_string );
            foreach ( $criteria_array as $criteria ) {
                $criteria = trim( $criteria );
                if ( ! empty( $criteria ) ) {
                    pll_register_string( 'Criteria: ' . $criteria, $criteria, 'WooCommerce Review' );
                }
            }
        }
    }
}

/* 4. สร้างฟอร์มดาว (Frontend) ★★★ แก้ไขจุดที่ผิดให้แล้ว ★★★ */
add_action( 'woocommerce_product_review_comment_form_args', 'custom_review_add_star_fields' );
function custom_review_add_star_fields( $comment_form ) {
    $criteria_string = get_option( 'custom_review_criteria_list' );
    
    if ( ! empty( $criteria_string ) ) {
        $criteria_array = explode( ',', $criteria_string );
        
        // ★★★ จุดที่แก้: ให้ดึงคำแปลจาก Polylang ★★★
        $header_text = function_exists('pll__') ? pll__('Additional Ratings') : 'Additional Ratings';
        
        $fields_html = '<div class="custom-criteria-container">';
        $fields_html .= '<h4>' . esc_html( $header_text ) . '</h4>'; // ใช้ตัวแปรที่ดึงจาก Polylang
        
        foreach ( $criteria_array as $criteria ) {
            $criteria_source = trim( $criteria );
            if ( empty( $criteria_source ) ) continue;
            
            // แปลหัวข้อย่อย
            $criteria_label = function_exists( 'pll__' ) ? pll__( $criteria_source ) : $criteria_source;
            
            // สร้าง ID (MD5)
            $slug = 'criteria_' . md5( $criteria_source ); 
            
            $fields_html .= '<div class="custom-criteria-row star-row">';
            $fields_html .= '<div class="criteria-label">' . esc_html( $criteria_label ) . '</div>';
            $fields_html .= '<div class="criteria-stars">';
            
            for ( $i = 5; $i >= 1; $i-- ) {
                $fields_html .= '<input type="radio" id="' . $slug . '-' . $i . '" name="' . $slug . '" value="' . $i . '" class="sub-rating-input">';
                $fields_html .= '<label for="' . $slug . '-' . $i . '">★</label>';
            }
            
            $fields_html .= '</div></div>';
        }
        $fields_html .= '</div>';

        // JS คำนวณค่าเฉลี่ย
        $fields_html .= "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const subRatings = document.querySelectorAll('.sub-rating-input');
            const mainRatingSelect = document.getElementById('rating'); 
            
            if(mainRatingSelect) {
                const ratingContainer = mainRatingSelect.closest('.comment-form-rating');
                if(ratingContainer) ratingContainer.style.display = 'none';
                mainRatingSelect.required = false; 
            }

            function updateAverage() {
                let total = 0;
                let count = 0;
                const criteriaGroups = {};

                subRatings.forEach(input => {
                    if (input.checked) {
                        const groupName = input.name;
                        criteriaGroups[groupName] = parseInt(input.value);
                    }
                });

                for (let key in criteriaGroups) {
                    total += criteriaGroups[key];
                    count++;
                }

                if (count > 0 && mainRatingSelect) {
                    let average = Math.round(total / count);
                    mainRatingSelect.value = average; 
                }
            }

            subRatings.forEach(input => {
                input.addEventListener('change', updateAverage);
            });
        });
        </script>
        ";

        $comment_form['comment_field'] = $fields_html . $comment_form['comment_field'];
    }
    return $comment_form;
}

/* 5. บันทึกคะแนนลง Database */
add_action( 'comment_post', 'custom_review_save_ratings' );
function custom_review_save_ratings( $comment_id ) {
    $criteria_string = get_option( 'custom_review_criteria_list' );
    if ( ! empty( $criteria_string ) ) {
        $criteria_array = explode( ',', $criteria_string );
        foreach ( $criteria_array as $criteria ) {
            $slug = 'criteria_' . md5( trim( $criteria ) );
            if ( isset( $_POST[ $slug ] ) ) {
                update_comment_meta( $comment_id, $slug, intval( $_POST[ $slug ] ) );
            }
        }
    }
}

/* 6. แสดงผลคะแนนในหน้าคอมเมนต์ + รองรับการแปล */
add_action( 'woocommerce_review_meta', 'custom_review_display_meta', 20 );
function custom_review_display_meta( $comment ) {
    $criteria_string = get_option( 'custom_review_criteria_list' );
    if ( ! empty( $criteria_string ) ) {
        $criteria_array = explode( ',', $criteria_string );
        echo '<div class="custom-review-breakdown-list">';
        foreach ( $criteria_array as $criteria ) {
            $criteria_source = trim( $criteria );
            
            // แปลหัวข้อตอนแสดงผล
            $criteria_label = function_exists( 'pll__' ) ? pll__( $criteria_source ) : $criteria_source;
            
            $slug = 'criteria_' . md5( $criteria_source );
            $rating = get_comment_meta( $comment->comment_ID, $slug, true );
            
            if ( $rating ) {
                echo '<div class="breakdown-item">';
                echo '<span class="bd-label">' . esc_html( $criteria_label ) . ': </span>';
                echo '<span class="bd-stars" style="color:#FFC107;">' . str_repeat('★', intval($rating)) . str_repeat('☆', 5 - intval($rating)) . '</span>';
                echo '</div>';
            }
        }
        echo '</div>';
    }
}



/**
 * GUSTABEDEV: REVIEW NAVIGATION (Arrows + Drag to Scroll)
 * สร้างปุ่มลูกศรซ้ายขวา และระบบลากเมาส์สไลด์
 */
add_action( 'wp_footer', 'gustabedev_add_review_navigation_script' );
function gustabedev_add_review_navigation_script() {
    // ทำงานเฉพาะหน้าสินค้า
    if ( ! is_product() ) return; 
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.querySelector('#reviews ol.commentlist');
        
        // ถ้าไม่มีรีวิว หรือรีวิวไม่เยอะพอให้สไลด์ ก็ไม่ต้องโชว์ปุ่ม
        if (!slider || slider.scrollWidth <= slider.clientWidth) return;

        // --- 1. สร้าง HTML ปุ่มลูกศร ---
        const wrapper = document.createElement('div');
        wrapper.className = 'gustabedev-slider-wrapper';
        
        // ย้าย slider เข้าไปใน wrapper เพื่อจัดตำแหน่งปุ่มเทียบกับกล่องนี้
        slider.parentNode.insertBefore(wrapper, slider);
        wrapper.appendChild(slider);

        // สร้างปุ่ม < (Previous)
        const prevBtn = document.createElement('button');
        prevBtn.className = 'gustabedev-nav-btn prev-btn';
        prevBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>';
        
        // สร้างปุ่ม > (Next)
        const nextBtn = document.createElement('button');
        nextBtn.className = 'gustabedev-nav-btn next-btn';
        nextBtn.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>';

        // ใส่ปุ่มเข้าไป
        wrapper.appendChild(prevBtn);
        wrapper.appendChild(nextBtn);

        // --- 2. สั่งงานปุ่มกด ---
        const scrollAmount = 320; // ระยะที่เลื่อนต่อการกด 1 ครั้ง
        
        nextBtn.addEventListener('click', () => {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
        
        prevBtn.addEventListener('click', () => {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });

        // --- 3. ระบบลากเมาส์ (Drag to Scroll) ---
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active'); // เปลี่ยน Cursor เป็นมือกำ
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // ความเร็วในการลาก
            slider.scrollLeft = scrollLeft - walk;
        });

        // --- 4. ซ่อนปุ่มเมื่อสุดทาง ---
        const checkArrows = () => {
            // ซ้ายสุดให้ซ่อนปุ่มซ้าย
            prevBtn.style.opacity = slider.scrollLeft <= 10 ? '0' : '1';
            prevBtn.style.pointerEvents = slider.scrollLeft <= 10 ? 'none' : 'auto';
            
            // ขวาสุดให้ซ่อนปุ่มขวา
            const maxScroll = slider.scrollWidth - slider.clientWidth;
            nextBtn.style.opacity = slider.scrollLeft >= maxScroll - 10 ? '0' : '1';
            nextBtn.style.pointerEvents = slider.scrollLeft >= maxScroll - 10 ? 'none' : 'auto';
        };

        slider.addEventListener('scroll', checkArrows);
        window.addEventListener('resize', checkArrows);
        checkArrows(); // เช็คทีนึงตอนโหลด
    });
    </script>
    <?php
}




/**
 * GUSTABEDEV: FORCE TEMPLATE (Custom Criteria Support)
 * รองรับระบบรีวิวแยกที่เขียนเอง (criteria_md5)
 */
add_filter( 'woocommerce_product_review_list_args', 'gustabedev_force_review_callback' );
function gustabedev_force_review_callback( $args ) {
    $args['callback'] = 'gustabedev_custom_review_display';
    return $args;
}

function gustabedev_custom_review_display($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    $rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
    
    // ★★★ 1. เตรียมข้อมูล Breakdown (ตาม Logic โค้ดของคุณ) ★★★
    $criteria_data = array();
    $criteria_string = get_option( 'custom_review_criteria_list' );
    
    if ( ! empty( $criteria_string ) ) {
        $criteria_array = explode( ',', $criteria_string );
        foreach ( $criteria_array as $criteria ) {
            $criteria_source = trim( $criteria );
            if ( empty( $criteria_source ) ) continue;
            
            // สร้าง Key แบบเดียวกับตอนบันทึก (MD5)
            $slug = 'criteria_' . md5( $criteria_source );
            $sub_rating = get_comment_meta( $comment->comment_ID, $slug, true );
            
            if ( $sub_rating ) {
                // ถ้ามีค่า ให้เก็บไว้แสดงผล
                $criteria_data[] = array(
                    'label' => function_exists('pll__') ? pll__($criteria_source) : $criteria_source,
                    'score' => intval($sub_rating)
                );
            }
        }
    }
    ?>
    
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div class="gustabedev-review-card">
            
            <div class="gustabe-layer-header">
                <div class="gustabe-avatar">
                    <?php echo get_avatar( $comment, 50 ); ?>
                </div>
                <div class="gustabe-name">
                    <strong><?php echo get_comment_author(); ?></strong>
                    <span class="date"><?php echo get_comment_date(); ?></span>
                </div>
            </div>

            <?php if ( $rating && 'yes' === get_option( 'woocommerce_enable_review_rating' ) ) : ?>
                <div class="gustabe-layer-rating">
                    <div style="color: #FFC107; font-size: 20px; letter-spacing: 2px; line-height: 1;">
                        <?php 
                        for ($i = 1; $i <= 5; $i++) {
                            echo ($i <= $rating) ? '★' : '<span style="color: #e0e0e0;">★</span>';
                        }
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $criteria_data ) ) : ?>
                <div class="gustabe-layer-breakdown">
                    <div class="custom-review-breakdown-list">
                        <?php foreach ( $criteria_data as $item ) : ?>
                            <div class="breakdown-item">
                                <span class="bd-label"><?php echo esc_html( $item['label'] ); ?>: </span>
                                <span class="bd-stars" style="color: #FFC107; letter-spacing: 1px;">
                                    <?php 
                                    for ($j = 1; $j <= 5; $j++) {
                                        echo ($j <= $item['score']) ? '★' : '<span style="color: #e0e0e0;">★</span>';
                                    }
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="gustabe-layer-text">
                <?php comment_text(); ?>
            </div>

            <div class="gustabe-layer-images">
                <?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>
            </div>

        </div>
    </li>
    <?php
}



/**
 * GUSTABEDEV: Category Scroller Drag-to-Scroll
 * ทำให้แถบหมวดหมู่ใช้เมาส์ลากบน PC ได้
 */
add_action( 'wp_footer', 'gustabedev_category_drag_script' );
function gustabedev_category_drag_script() {
    // โหลดเฉพาะหน้าสินค้า หรือหน้า Shop
    if ( ! is_shop() && ! is_product_category() && ! is_product() ) return;
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.querySelector('.app-category-scroller');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active'); // เปลี่ยน Cursor เป็นมือกำ
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault(); // ป้องกันการคลุมดำ text
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // ความเร็วในการลาก (ยิ่งเลขเยอะยิ่งไว)
            slider.scrollLeft = scrollLeft - walk;
        });
    });
    </script>
    <?php
}


/**
 * ============================================================
 * GUSTABE: FLOATING FAB MENU (With Polylang Support)
 * ============================================================
 */

// 1. ลงทะเบียนคำศัพท์ให้ Polylang (เพื่อให้ไปแปลในหน้า String Translations ได้)
add_action( 'init', 'gustabe_register_fab_strings' );
function gustabe_register_fab_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        // (ชื่อ String, ข้อความต้นฉบับ, หมวดหมู่)
        pll_register_string( 'fab_related', 'สินค้าใกล้เคียง', 'Gustabe FAB' );
        pll_register_string( 'fab_reviews', 'อ่านรีวิว', 'Gustabe FAB' );
        pll_register_string( 'fab_desc', 'รายละเอียด', 'Gustabe FAB' );
        pll_register_string( 'fab_price', 'ราคา/สั่งซื้อ', 'Gustabe FAB' );
    }
}

// 2. ฟังก์ชันสร้างปุ่มเมนู
add_action( 'wp_footer', 'gustabe_floating_product_menu' );
function gustabe_floating_product_menu() {
    // ทำงานเฉพาะหน้าสินค้า
    if ( ! is_product() ) return;

    // เตรียมตัวแปรคำศัพท์ (ถ้ามี Polylang ก็แปล ถ้าไม่มีก็ใช้คำไทย)
    $txt_related = function_exists('pll__') ? pll__('สินค้าใกล้เคียง') : 'สินค้าใกล้เคียง';
    $txt_reviews = function_exists('pll__') ? pll__('อ่านรีวิว') : 'อ่านรีวิว';
    $txt_desc    = function_exists('pll__') ? pll__('รายละเอียด') : 'รายละเอียด';
    $txt_price   = function_exists('pll__') ? pll__('ราคา/สั่งซื้อ') : 'ราคา/สั่งซื้อ';

    ?>
    
    <div id="gustabe-fab-container" class="gustabe-fab-wrapper">
        
        <div class="gustabe-fab-items">
            
            <a href="#related" class="fab-item" data-tooltip="<?php echo esc_attr($txt_related); ?>" onclick="gustabeScrollTo(event, '.related.products')">
                <span class="fab-label"><?php echo esc_html($txt_related); ?></span>
                <div class="fab-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </div>
            </a>

            <a href="#reviews" class="fab-item" data-tooltip="<?php echo esc_attr($txt_reviews); ?>" onclick="gustabeScrollTo(event, '#reviews')">
                <span class="fab-label"><?php echo esc_html($txt_reviews); ?></span>
                <div class="fab-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                </div>
            </a>

            <a href="#description" class="fab-item" data-tooltip="<?php echo esc_attr($txt_desc); ?>" onclick="gustabeScrollTo(event, '.product-description, #tab-description')">
                <span class="fab-label"><?php echo esc_html($txt_desc); ?></span>
                <div class="fab-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                </div>
            </a>

            <a href="#top" class="fab-item" data-tooltip="<?php echo esc_attr($txt_price); ?>" onclick="gustabeScrollTo(event, 'body')">
                <span class="fab-label"><?php echo esc_html($txt_price); ?></span>
                <div class="fab-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
                </div>
            </a>

        </div>

        <button class="gustabe-fab-main-btn" onclick="toggleFabMenu()">
            <div class="icon-menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            </div>
            <div class="icon-close">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
        </button>

    </div>

    <script>
    function toggleFabMenu() {
        document.getElementById('gustabe-fab-container').classList.toggle('active');
    }
    function gustabeScrollTo(e, selector) {
        e.preventDefault();
        document.getElementById('gustabe-fab-container').classList.remove('active');
        let target = document.querySelector(selector);
        if (target) {
            const headerOffset = 100; 
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            window.scrollTo({ top: offsetPosition, behavior: "smooth" });
        }
    }
    </script>
    <?php
}