<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Customizer: Logo Mobile
function hello_elementor_child_customizer_mobile_logo( $wp_customize ) {
    $wp_customize->add_section( 'hello_elementor_child_mobile_logo_section', array(
        'title'      => esc_html__( 'โลโก้สำหรับมือถือ', 'hello-elementor-child' ),
        'priority'   => 30,
    ) );
    $wp_customize->add_setting( 'hello_elementor_child_mobile_logo', array(
        'default'   => '',
        'type'      => 'theme_mod',
        'sanitize_callback' => 'absint', 
    ) );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'hello_elementor_child_mobile_logo', array(
        'label'    => esc_html__( 'โลโก้สำหรับมือถือ/แท็บเล็ต', 'hello-elementor-child' ),
        'section'  => 'hello_elementor_child_mobile_logo_section',
        'mime_type' => 'image',
    ) ) );
}
add_action( 'customize_register', 'hello_elementor_child_customizer_mobile_logo' );

// Register Menus
function hello_elementor_child_register_menus() {
    register_nav_menus([
        'menu-1' => esc_html__( 'Primary Menu', 'hello-elementor-child' ),
        'footer-menu' => esc_html__( 'Footer Menu', 'hello-elementor-child' ),
    ]);
}
add_action( 'after_setup_theme', 'hello_elementor_child_register_menus' );

// Search Logic
function hello_elementor_child_modify_search_query( $query ) {
    if ( $query->is_search() && ! is_admin() && $query->is_main_query() ) {
        $search_type = get_query_var( 'search_type' );
        if ( $search_type === 'post' ) {
            $query->set( 'post_type', 'post' );
        } elseif ( $search_type === 'product' ) {
            $query->set( 'post_type', 'product' );
        } elseif ( $search_type === 'all' || empty( $search_type ) ) {
            $query->set( 'post_type', array( 'post', 'product', 'page' ) );
        }
    }
}
add_action( 'pre_get_posts', 'hello_elementor_child_modify_search_query' );

function hello_elementor_child_add_search_query_var( $vars ) {
    $vars[] = 'search_type';
    return $vars;
}
add_filter( 'query_vars', 'hello_elementor_child_add_search_query_var' );

// Customizer: Announcement Text
function hello_elementor_child_customizer_announcement( $wp_customize ) {
    $wp_customize->add_section( 'announcement_section', array( 'title' => __( 'แถบประกาศด้านบน', 'hello-elementor-child' ), 'priority' => 20 ) );
    $wp_customize->add_setting( 'announcement_text', array( 'default' => 'จัดส่งฟรีทั่วประเทศ!', 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ) );
    $wp_customize->add_control( 'announcement_text', array( 'label' => __( 'ข้อความประกาศ', 'hello-elementor-child' ), 'section' => 'announcement_section', 'type' => 'text' ) );
}
add_action( 'customize_register', 'hello_elementor_child_customizer_announcement' );

// Smart Auth URL
function get_smart_auth_url() {
    if ( is_user_logged_in() ) { return wc_get_page_permalink( 'myaccount' ); } 
    $page = get_page_by_path( 'login' ); 
    $page_id = $page ? $page->ID : get_option( 'woocommerce_myaccount_page_id' );
    if ( function_exists( 'pll_get_post' ) ) {
        $translated_id = pll_get_post( $page_id );
        if ( $translated_id ) { $page_id = $translated_id; }
    }
    return get_permalink( $page_id );
}

// Footer Menus
function hello_child_register_footer_menus() {
    register_nav_menus([
        'footer-quick-links'      => esc_html__( 'Footer: Quick Links (Col 2)', 'hello-elementor-child' ),
        'footer-customer-service' => esc_html__( 'Footer: Customer Service (Col 3)', 'hello-elementor-child' ),
    ]);
}
add_action( 'after_setup_theme', 'hello_child_register_footer_menus' );

// Footer Customizer (Contact Info)
function hello_child_footer_customizer( $wp_customize ) {
    $wp_customize->add_section( 'hello_child_footer_contact', array( 'title' => __( 'ข้อมูลติดต่อ (Footer)', 'hello-elementor-child' ), 'priority' => 30 ) );
    $fields = array(
        'footer_contact_address'  => array( 'label' => 'ที่อยู่', 'type' => 'textarea' ),
        'footer_contact_phone'    => array( 'label' => 'เบอร์โทร 1', 'type' => 'text' ),
        'footer_contact_phone_2'  => array( 'label' => 'เบอร์โทร 2', 'type' => 'text' ),
        'footer_contact_email'    => array( 'label' => 'อีเมล', 'type' => 'text' ),
        'footer_contact_line'     => array( 'label' => 'Line ID', 'type' => 'text' ),
        'footer_social_facebook'  => array( 'label' => 'Facebook URL', 'type' => 'url' ),
        'footer_social_instagram' => array( 'label' => 'Instagram URL', 'type' => 'url' ),
        'footer_social_line_url'  => array( 'label' => 'Line URL', 'type' => 'url' ),
        'footer_copyright_text'   => array( 'label' => 'Copyright', 'type' => 'text' ),
    );
    foreach ( $fields as $id => $field ) {
        $wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => ( $field['type'] === 'url' ? 'esc_url_raw' : 'wp_kses_post' ) ) );
        $wp_customize->add_control( $id, array( 'label' => $field['label'], 'section' => 'hello_child_footer_contact', 'type' => $field['type'] ) );
    }
}
add_action( 'customize_register', 'hello_child_footer_customizer' );



// เพิ่มส่วนตั้งค่ารูปปกหน้า Shop (Shop Cover)
function hello_child_shop_cover_customizer( $wp_customize ) {
    $wp_customize->add_section( 'hello_child_shop_cover_section', array(
        'title'    => __( 'รูปปกหน้าสินค้า (Shop Cover)', 'hello-elementor-child' ),
        'priority' => 25,
    ) );

    // Setting สำหรับรูปภาพ
    $wp_customize->add_setting( 'shop_cover_default_image', array(
        'default'   => '',
        'type'      => 'theme_mod',
        'sanitize_callback' => 'absint',
    ) );

    // Control อัปโหลดรูป
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'shop_cover_default_image', array(
        'label'       => __( 'รูปปกเริ่มต้น (Default)', 'hello-elementor-child' ),
        'description' => __( 'รูปนี้จะแสดงเมื่อหมวดสินค้านั้นไม่มีรูปปก หรืออยู่ที่หน้า Shop รวม', 'hello-elementor-child' ),
        'section'     => 'hello_child_shop_cover_section',
        'mime_type'   => 'image',
    ) ) );
}
add_action( 'customize_register', 'hello_child_shop_cover_customizer' );