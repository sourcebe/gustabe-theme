<?php
/**
 * Registers the custom post type 'our_service'.
 */
function create_our_service_cpt() {
    // Register Custom Post Type
    $labels = array(
        'name'               => _x( 'Our Services', 'Post Type General Name', 'gtwp-starter-child' ),
        'singular_name'      => _x( 'Service', 'Post Type Singular Name', 'gtwp-starter-child' ),
        'menu_name'          => __( 'Our Services', 'gtwp-starter-child' ),
        'name_admin_bar'     => __( 'Service', 'gtwp-starter-child' ),
        'add_new'            => __( 'Add New Service', 'gtwp-starter-child' ),
        'add_new_item'       => __( 'Add New Service', 'gtwp-starter-child' ),
        'new_item'           => __( 'New Service', 'gtwp-starter-child' ),
        'edit_item'          => __( 'Edit Service', 'gtwp-starter-child' ),
        'view_item'          => __( 'View Service', 'gtwp-starter-child' ),
        'all_items'          => __( 'All Services', 'gtwp-starter-child' ),
        'search_items'       => __( 'Search Services', 'gtwp-starter-child' ),
        'not_found'          => __( 'No Services found', 'gtwp-starter-child' ),
        'not_found_in_trash' => __( 'No Services found in Trash', 'gtwp-starter-child' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'our-service' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'       => 5,
        'menu_icon'          => 'dashicons-hammer',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'taxonomies'          => array( 'our_service_category', 'our_service_tag' ), // Add custom taxonomies here
    );

    register_post_type( 'our_service', $args );
}
add_action( 'init', 'create_our_service_cpt' );




/**
 * Adds 'our_service' to the list of post types supported by the pricing plugin.
 *
 * @param array $post_types An array of supported post types.
 * @return array The modified array of supported post types.
 */
function gustabe_drv_add_our_service_support( $post_types ) {
    $post_types[] = 'our_service';
    return $post_types;
}
add_filter( 'gustabe_drv_supported_post_types', 'gustabe_drv_add_our_service_support' );




/**
 * Registers the custom taxonomy 'our_service_category'.
 */
function create_our_service_taxonomy() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'              => _x( 'Service Categories', 'Taxonomy General Name', 'gtwp-starter-child' ),
        'singular_name'     => _x( 'Service Category', 'Taxonomy Singular Name', 'gtwp-starter-child' ),
        'search_items'      => __( 'Search Categories', 'gtwp-starter-child' ),
        'all_items'         => __( 'All Categories', 'gtwp-starter-child' ),
        'parent_item'       => __( 'Parent Category', 'gtwp-starter-child' ),
        'parent_item_colon' => __( 'Parent Category:', 'gtwp-starter-child' ),
        'edit_item'         => __( 'Edit Category', 'gtwp-starter-child' ),
        'update_item'       => __( 'Update Category', 'gtwp-starter-child' ),
        'add_new_item'      => __( 'Add New Category', 'gtwp-starter-child' ),
        'new_item_name'     => __( 'New Category Name', 'gtwp-starter-child' ),
        'menu_name'         => __( 'Service Categories', 'gtwp-starter-child' ),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'our-service-category' ),
    );

    register_taxonomy( 'our_service_category', array( 'our_service' ), $category_args );
}
add_action( 'init', 'create_our_service_taxonomy' );

/**
 * Registers the custom taxonomy 'our_service_tag'.
 */
function create_our_service_tags() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'                       => _x( 'Service Tags', 'Taxonomy General Name', 'gtwp-starter-child' ),
        'singular_name'              => _x( 'Service Tag', 'Taxonomy Singular Name', 'gtwp-starter-child' ),
        'search_items'               => __( 'Search Tags', 'gtwp-starter-child' ),
        'popular_items'              => __( 'Popular Tags', 'gtwp-starter-child' ),
        'all_items'                  => __( 'All Tags', 'gtwp-starter-child' ),
        'edit_item'                  => __( 'Edit Tag', 'gtwp-starter-child' ),
        'view_item'                  => __( 'View Tag', 'gtwp-starter-child' ),
        'update_item'                => __( 'Update Tag', 'gtwp-starter-child' ),
        'add_new_item'               => __( 'Add New Tag', 'gtwp-starter-child' ),
        'new_item_name'              => __( 'New Tag Name', 'gtwp-starter-child' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'gtwp-starter-child' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'gtwp-starter-child' ),
        'choose_from_most_used'      => __( 'Choose from the most used tags', 'gtwp-starter-child' ),
        'not_found'                  => __( 'No tags found', 'gtwp-starter-child' ),
        'menu_name'                  => __( 'Service Tags', 'gtwp-starter-child' ),
    );

    $tag_args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'our-service-tag' ),
    );

    register_taxonomy( 'our_service_tag', 'our_service', $tag_args );
}
add_action( 'init', 'create_our_service_tags' );

/**
 * Add gallery metabox.
 */
function add_our_service_gallery_metabox() {
    add_meta_box(
        'our_service_gallery',     // Metabox ID
        __( 'Gallery', 'gtwp-starter-child' ),   // Metabox Title
        'our_service_gallery_callback', // Callback function
        'our_service',         // Post Type to display metabox
        'normal',           // Metabox position
        'high'              // Priority level
    );
}
add_action( 'add_meta_boxes', 'add_our_service_gallery_metabox' );

/**
 * Our Service Gallery Callback.
 */
function our_service_gallery_callback( $post ) {
    $gallery = get_post_meta( $post->ID, '_our_service_gallery', true );
    $gallery = is_array( $gallery ) ? $gallery : [];
    ?>
    <div class="our-service-gallery-wrapper">
        <button id="upload_gallery_button" class="button"><?php _e( 'Add Images to Gallery', 'gtwp-starter-child' ); ?></button>
        <div id="gallery_images_container">
            <ul id="gallery_images_list">
                <?php
                foreach ( $gallery as $image_id ) {
                    if ( $image_id > 0 ) {
                        $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
                        $alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                        if ( $image_url ) {
                            printf(
                                '<li data-id="%1$s">
                                  <img src="%2$s" alt="%3$s">
                                  <button class="remove-gallery-image">×</button>
                                </li>',
                                esc_attr( $image_id ),
                                esc_url( $image_url ),
                                esc_attr( $alt_text )
                            );
                        }
                    }
                }
                ?>
            </ul>
        </div>
        <input type="hidden" id="our_service_gallery_input" name="our_service_gallery" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>">
    </div>
    <?php
}

/**
 * Enqueue Scripts and Styles.
 */
function enqueue_our_service_gallery_scripts( $hook ) {
    if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
        return;
    }

    global $post;
    if ( $post->post_type !== 'our_service' ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'our-service-gallery-script', get_stylesheet_directory_uri() . '/js/our-service-gallery.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'our-service-gallery-style', get_stylesheet_directory_uri() . '/css/our-service-gallery.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_our_service_gallery_scripts' );

/**
 * Save Our Works Gallery.
 */
function save_our_service_gallery( $post_id ) {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['our_service_gallery'] ) ) {
        $gallery = array_filter( explode( ',', sanitize_text_field( $_POST['our_service_gallery'] ) ), 'intval' );
        update_post_meta( $post_id, '_our_service_gallery', $gallery );
    }
}
add_action( 'save_post', 'save_our_service_gallery' );

/**
 * Shortcode แสดงแกลเลอรี่ภาพในรูปแบบ Slider.
 *
 * @return string HTML ของ Slider
 */
function our_service_gallery_shortcode() {
    global $post;
    if ( ! $post || get_post_type( $post ) !== 'our_service' ) {
        return '';
    }

    $gallery = get_post_meta( $post->ID, '_our_service_gallery', true );
    $gallery = is_array( $gallery ) ? $gallery : [];

    if ( empty( $gallery ) ) {
        return '';
    }

    $output = '<div class="our-service-gallery-slider">';
    $output .= '<div class="swiper-container">';
    $output .= '<div class="swiper-wrapper">';

    // ***** ต้องมี foreach loop ตรงนี้ *****
    foreach ( $gallery as $image_id ) {
        // ***** โค้ดการประมวลผลแต่ละภาพต้องอยู่ *ข้างใน* loop นี้ *****
        if ( $image_id > 0 ) {
            $image_url = wp_get_attachment_image_url( $image_id, 'large' );
            $full_image_data = wp_get_attachment_image_src( $image_id, 'full' );

            // *** การตรวจสอบที่คุณเพิ่มเข้ามา (ถูกต้องแล้ว) ***
            if ( $full_image_data && is_array($full_image_data) ) {
                $full_image_url = $full_image_data[0];
                $alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
                $caption  = get_post_field( 'post_excerpt', $image_id );

                // ตรวจสอบ $image_url ด้วย เผื่อกรณี large size ไม่มี แต่ full มี
                if ( $image_url ) {
                    $output .= '<div class="swiper-slide">';
                    // ใช้ $full_image_url สำหรับลิงก์ Fancybox และ $image_url สำหรับ background
                    $output .= '<a href="' . esc_url( $full_image_url ) . '" data-fancybox="gallery" data-caption="' . esc_attr( $caption ) . '" style="display: block; width: 100%; height: 100%; background-image: url(\'' . esc_url( $image_url ) . '\'); background-size: cover; background-repeat: no-repeat; background-position: center;" title="' . esc_attr( $alt_text ) . '"></a>';
                    $output .= '</div>';
                } else {
                     // อาจจะ Log error เพิ่มเติมถ้า large URL หายไป
                     error_log("Gallery Shortcode Warning: Failed to get large image URL for ID: " . $image_id . " on post ID: " . $post->ID);
                }

            } else {
                // Log error ถ้า $full_image_data ไม่ถูกต้อง
                error_log("Gallery Shortcode Warning: Invalid image ID or failed to get full image source for ID: " . $image_id . " on post ID: " . $post->ID);
            }
        }
        // ***** จบส่วนประมวลผลแต่ละภาพ *****
    }
    // ***** จบ foreach loop *****

    $output .= '</div>'; // ปิด swiper-wrapper
    // เพิ่มส่วนควบคุม Slider
    $output .= '<div class="swiper-pagination"></div>';
    $output .= '<div class="swiper-button-next"></div>';
    $output .= '<div class="swiper-button-prev"></div>';
    $output .= '</div>'; // ปิด swiper-container
    $output .= '</div>'; // ปิด our-service-gallery-slider

    // enqueue scripts and styles (ส่วนนี้เหมือนเดิม)
    wp_enqueue_script( 'swiper-bundle-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array( 'jquery' ), '10.2.0', true );
    wp_enqueue_style( 'swiper-bundle-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.2.0' );
    wp_enqueue_script( 'our-service-gallery-slider-script', get_stylesheet_directory_uri() . '/js/our-service-gallery-slider.js', array( 'jquery', 'swiper-bundle-js' ), '1.0.0', true );
    wp_enqueue_style( 'our-service-gallery-slider-style', get_stylesheet_directory_uri() . '/css/our-service-gallery-slider.css', array( 'swiper-bundle-css' ), '1.0.0' );

    wp_enqueue_script( 'fancybox-js', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
    wp_enqueue_style( 'fancybox-css', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), '3.5.7' );

    return $output;
}
add_shortcode( 'our_service_gallery', 'our_service_gallery_shortcode' );

/**
 * Registers strings for 'our_service' CPT with Polylang.
 */
function register_our_service_strings_with_polylang() {
    if ( function_exists( 'pll_register_string' ) ) {
        $labels = array(
            'Our Services',
            'Service',
            'Our Services',
            'Service',
            'Add New Service',
            'Add New Service',
            'New Service',
            'Edit Service',
            'View Service',
            'All Services',
            'Search Services',
            'No Services found',
            'No Services found in Trash',
            'Service Categories',
            'Service Category',
            'Search Categories',
            'All Categories',
            'Parent Category',
            'Parent Category:',
            'Edit Category',
            'Update Category',
            'Add New Category',
            'New Category Name',
            'Service Categories',
            'Service Tags',
            'Service Tag',
            'Search Tags',
            'Popular Tags',
            'All Tags',
            'Edit Tag',
            'Update Tag',
            'Add New Tag',
            'New Tag Name',
            'Separate tags with commas',
            'Add or remove tags',
            'Choose from the most used tags',
            'No tags found',
            'Service Tags',
            'Gallery', // เพิ่ม label ของ Gallery Metabox
            'Add Images to Gallery', // เพิ่ม label ของปุ่ม Add Images
        );
        foreach ( $labels as $label ) {
            pll_register_string( $label, $label, 'gtwp-starter-child', false );
        }
    }
}

/**
 * Registers 'our_service' CPT and taxonomies with Polylang.
 */
function register_our_service_with_polylang() {
    if ( function_exists( 'pll_register_post_type' ) && function_exists( 'pll_register_taxonomy' ) ) {
        pll_register_post_type( 'our_service' );
        pll_register_taxonomy( 'our_service_category' );
        pll_register_taxonomy( 'our_service_tag' );
    }
}
























