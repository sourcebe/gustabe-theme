<?php
/**
 * Registers the custom post type 'our_activity'.
 */
function create_our_activity_cpt() {
    // Labels for the custom post type
    $labels = array(
        'name'               => _x( 'My Activities', 'Post Type General Name', 'gustabe-cpt-manager' ),
        'singular_name'      => _x( 'Activity', 'Post Type Singular Name', 'gustabe-cpt-manager' ),
        'menu_name'          => __( 'My Activity', 'gustabe-cpt-manager' ),
        'name_admin_bar'     => __( 'Activity', 'gustabe-cpt-manager' ),
        'add_new'            => __( 'Add New Activity', 'gustabe-cpt-manager' ),
        'add_new_item'       => __( 'Add New Activity', 'gustabe-cpt-manager' ),
        'new_item'           => __( 'New Activity', 'gustabe-cpt-manager' ),
        'edit_item'          => __( 'Edit Activity', 'gustabe-cpt-manager' ),
        'view_item'          => __( 'View Activity', 'gustabe-cpt-manager' ),
        'all_items'          => __( 'All Activities', 'gustabe-cpt-manager' ),
        'search_items'       => __( 'Search Activities', 'gustabe-cpt-manager' ),
        'not_found'          => __( 'No Activities found', 'gustabe-cpt-manager' ),
        'not_found_in_trash' => __( 'No Activities found in Trash', 'gustabe-cpt-manager' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'our-activity' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'       => 5,
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'taxonomies'          => array( 'our_activity_category', 'our_activity_tag' ), // Add custom taxonomies here
    );

    register_post_type( 'our_activity', $args );
}
add_action( 'init', 'create_our_activity_cpt' );

/**
 * Registers the custom taxonomy 'our_activity_category'.
 */
function create_our_activity_taxonomy() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'              => _x( 'Activity Categories', 'Taxonomy General Name', 'gustabe-cpt-manager' ),
        'singular_name'     => _x( 'Activity Category', 'Taxonomy Singular Name', 'gustabe-cpt-manager' ),
        'search_items'      => __( 'Search Categories', 'gustabe-cpt-manager' ),
        'all_items'         => __( 'All Categories', 'gustabe-cpt-manager' ),
        'parent_item'       => __( 'Parent Category', 'gustabe-cpt-manager' ),
        'parent_item_colon' => __( 'Parent Category:', 'gustabe-cpt-manager' ),
        'edit_item'         => __( 'Edit Category', 'gustabe-cpt-manager' ),
        'update_item'       => __( 'Update Category', 'gustabe-cpt-manager' ),
        'add_new_item'      => __( 'Add New Category', 'gustabe-cpt-manager' ),
        'new_item_name'     => __( 'New Category Name', 'gustabe-cpt-manager' ),
        'menu_name'         => __( 'Activity Categories', 'gustabe-cpt-manager' ),
    );

    $category_args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'our-activity-category' ),
    );

    register_taxonomy( 'our_activity_category', array( 'our_activity' ), $category_args );
}
add_action( 'init', 'create_our_activity_taxonomy' );

/**
 * Registers the custom taxonomy 'our_activity_tag'.
 */
function create_our_activity_tags() {
    // Labels for the custom taxonomy
    $labels = array(
        'name'                       => _x( 'Activity Tags', 'Taxonomy General Name', 'gustabe-cpt-manager' ),
        'singular_name'              => _x( 'Activity Tag', 'Taxonomy Singular Name', 'gustabe-cpt-manager' ),
        'search_items'               => __( 'Search Tags', 'gustabe-cpt-manager' ),
        'popular_items'              => __( 'Popular Tags', 'gustabe-cpt-manager' ),
        'all_items'                  => __( 'All Tags', 'gustabe-cpt-manager' ),
        'edit_item'                  => __( 'Edit Tag', 'gustabe-cpt-manager' ),
        'view_item'                  => __( 'View Tag', 'gustabe-cpt-manager' ),
        'update_item'                => __( 'Update Tag', 'gustabe-cpt-manager' ),
        'add_new_item'               => __( 'Add New Tag', 'gustabe-cpt-manager' ),
        'new_item_name'              => __( 'New Tag Name', 'gustabe-cpt-manager' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'gustabe-cpt-manager' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'gustabe-cpt-manager' ),
        'choose_from_most_used'      => __( 'Choose from the most used tags', 'gustabe-cpt-manager' ),
        'not_found'                  => __( 'No tags found', 'gustabe-cpt-manager' ),
        'menu_name'                  => __( 'Activity Tags', 'gustabe-cpt-manager' ),
    );

    $tag_args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'rewrite'               => array( 'slug' => 'our-activity-tag' ),
    );

    register_taxonomy( 'our_activity_tag', 'our_activity', $tag_args );
}
add_action( 'init', 'create_our_activity_tags' );

/**
 * Customize archive title.
 */
function customize_our_activity_archive_title( $title ) {
    if ( is_post_type_archive( 'our_activity' ) ) {
        $title = __( 'Our Activities | ', 'gustabe-cpt-manager' ) . get_bloginfo( 'name' );
    }
    return $title;
}
add_filter( 'pre_get_document_title', 'customize_our_activity_archive_title' );

/**
 * Add gallery metabox.
 */
function add_our_activity_gallery_metabox() {
    add_meta_box(
        'our_activity_gallery',     // Metabox ID
        __( 'Gallery', 'gustabe-cpt-manager' ),   // Metabox Title
        'our_activity_gallery_callback', // Callback function
        'our_activity',         // Post Type to display metabox
        'normal',           // Metabox position
        'high'              // Priority level
    );
}
add_action( 'add_meta_boxes', 'add_our_activity_gallery_metabox' );

/**
 * Our Works Gallery Callback.
 */
function our_activity_gallery_callback( $post ) {
    $gallery = get_post_meta( $post->ID, '_our_activity_gallery', true );
    $gallery = is_array( $gallery ) ? $gallery : [];
    ?>
    <div class="our-activity-gallery-wrapper">
        <button id="upload_gallery_button" class="button"><?php _e( 'Add Images to Gallery', 'gustabe-cpt-manager' ); ?></button>
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
        <input type="hidden" id="our_activity_gallery_input" name="our_activity_gallery" value="<?php echo esc_attr( implode( ',', $gallery ) ); ?>">
    </div>
    <?php
}

/**
 * Enqueue Scripts and Styles.
 */
function enqueue_our_activity_gallery_scripts( $hook ) {
    if ( 'post.php' !== $hook && 'post-new.php' !== $hook ) {
        return;
    }

    global $post;
    if ( $post->post_type !== 'our_activity' ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script( 'our-activity-gallery-script', get_stylesheet_directory_uri() . '/js/our-activity-gallery.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_style( 'our-activity-gallery-style', get_stylesheet_directory_uri() . '/css/our-activity-gallery.css', array(), '1.0.0' );
}
add_action( 'admin_enqueue_scripts', 'enqueue_our_activity_gallery_scripts' );

/**
 * Save Our Works Gallery.
 */
function save_our_activity_gallery( $post_id ) {
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( isset( $_POST['our_activity_gallery'] ) ) {
        $gallery = array_filter( explode( ',', sanitize_text_field( $_POST['our_activity_gallery'] ) ), 'intval' );
        update_post_meta( $post_id, '_our_activity_gallery', $gallery );
    }
}
add_action( 'save_post', 'save_our_activity_gallery' );

/**
 * Shortcode แสดงแกลเลอรี่ภาพในรูปแบบ Slider.
 *
 * @return string HTML ของ Slider
 */
function our_activity_gallery_shortcode() {
    global $post;
    if ( ! $post || get_post_type( $post ) !== 'our_activity' ) {
        return ''; // ออกจากฟังก์ชัน ถ้าไม่ใช่ post type our_activity
    }

    $gallery = get_post_meta( $post->ID, '_our_activity_gallery', true );
    $gallery = is_array( $gallery ) ? $gallery : [];

    if ( empty( $gallery ) ) {
        return ''; // ออกจากฟังก์ชัน ถ้าไม่มีภาพในแกลเลอรี่
    }

    $output = '<div class="our-activity-gallery-slider">';
    $output .= '<div class="swiper-container">';
    $output .= '<div class="swiper-wrapper">';

    foreach ( $gallery as $image_id ) {
        if ( $image_id > 0 ) {
            $image_url = wp_get_attachment_image_url( $image_id, 'large' ); // ใช้ขนาด large
            $full_image_data = wp_get_attachment_image_src( $image_id, 'full' );
            $full_image_url = $full_image_data[0];
            $alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
            $caption  = get_post_field( 'post_excerpt', $image_id );
            if ( $image_url ) {
                $output .= '<div class="swiper-slide">';
                $output .= '<a href="' . esc_url( $full_image_url ) . '" data-fancybox="gallery" data-caption="' . esc_attr( $caption ) . '" style="display: block; width: 100%; height: 100%; background-image: url(\'' . esc_url( $image_url ) . '\'); background-size: cover; background-repeat: no-repeat; background-position: center;" title="' . esc_attr( $alt_text ) . '"></a>';
                $output .= '</div>';
            }
        }
    }

    $output .= '</div>';
    $output .= '<div class="swiper-pagination"></div>';
    $output .= '<div class="swiper-button-next"></div>';
    $output .= '<div class="swiper-button-prev"></div>';
    $output .= '</div>';
    $output .= '</div>';

    // enqueue scripts and styles
    wp_enqueue_script( 'swiper-bundle-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array( 'jquery' ), '10.2.0', true );
    wp_enqueue_style( 'swiper-bundle-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.2.0' );
    wp_enqueue_script( 'our-activity-gallery-slider-script', get_stylesheet_directory_uri() . '/js/our-activity-gallery-slider.js', array( 'jquery', 'swiper-bundle-js' ), '1.0.0', true );
    wp_enqueue_style( 'our-activity-gallery-slider-style', get_stylesheet_directory_uri() . '/css/our-activity-gallery-slider.css', array( 'swiper-bundle-css' ), '1.0.0' );

    wp_enqueue_script( 'fancybox-js', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array( 'jquery' ), '3.5.7', true );
    wp_enqueue_style( 'fancybox-css', 'https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), '3.5.7' );

    return $output;
}
add_shortcode( 'our_activity_gallery', 'our_activity_gallery_shortcode' );

/**
 * Registers strings for 'our_activity' CPT with Polylang.
 */
function register_our_activity_strings_with_polylang() {
    if ( function_exists( 'pll_register_string' ) ) {
        $labels = array(
            'My Activities',
            'Activity',
            'My Activity',
            'Activity',
            'Add New Activity',
            'Add New Activity',
            'New Activity',
            'Edit Activity',
            'View Activity',
            'All Activities',
            'Search Activities',
            'No Activities found',
            'No Activities found in Trash',
            'Activity Categories',
            'Activity Category',
            'Search Categories',
            'All Categories',
            'Parent Category',
            'Parent Category:',
            'Edit Category',
            'Update Category',
            'Add New Category',
            'New Category Name',
            'Activity Categories',
            'Activity Tags',
            'Activity Tag',
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
            'Activity Tags',
        );
        foreach ( $labels as $label ) {
            pll_register_string( $label, $label, 'gustabe-cpt-manager', false );
        }
    }
}

/**
 * Registers 'our_activity' CPT and taxonomies with Polylang.
 */
function register_our_activity_with_polylang() {
    if ( function_exists( 'pll_register_post_type' ) && function_exists( 'pll_register_taxonomy' ) ) {
        pll_register_post_type( 'our_activity' );
        pll_register_taxonomy( 'our_activity_category' );
        pll_register_taxonomy( 'our_activity_tag' );
    }
}