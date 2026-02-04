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