<?php
/**
 * Theme Customizer Settings
 * ใช้สำหรับสร้างเมนูตั้งค่าในหน้าระบบหลังบ้าน (Appearance -> Customize)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // ป้องกันการเข้าถึงไฟล์โดยตรง
}

/**
 * ลงทะเบียนระบบ Customizer ของตีม Gustabe
 */
function gustabe_theme_customize_register( $wp_customize ) {
    
    // 1. สร้าง Panel หลักสำหรับตีม Gustabe
    $wp_customize->add_panel( 'gustabe_theme_options', array(
        'title'       => __( 'GUSTABE Settings', 'gustabe' ),
        'description' => __( 'ตั้งค่าส่วนต่างๆ ของตีม Gustabe แบบ Geekๆ ได้ที่นี่', 'gustabe' ),
        'priority'    => 160,
    ) );

    // ==========================================
    // Section 1: General Settings (ตั้งค่าทั่วไป)
    // ==========================================
    $wp_customize->add_section( 'gustabe_general_section', array(
        'title'    => __( 'General Settings', 'gustabe' ),
        'panel'    => 'gustabe_theme_options',
        'priority' => 10,
    ) );

    // P3: เปลี่ยน id ของ Setting ให้ตรงกับที่ header.php ใช้งาน (announcement_text)
    $wp_customize->add_setting( 'announcement_text', array(
        'default'           => 'Welcome to Gustabe',
        'sanitize_callback' => 'sanitize_text_field', // กรองข้อมูลเพื่อความปลอดภัย
    ) );

    $wp_customize->add_control( 'announcement_text', array(
        'label'       => __( 'Header Announcement Text', 'gustabe' ),
        'description' => __( 'ข้อความที่จะแสดงในส่วนหัวของเว็บ (Announcement Bar)', 'gustabe' ),
        'section'     => 'gustabe_general_section',
        'type'        => 'text',
    ) );

    // P3: เพิ่มช่องตั้งค่า Copyright
    $wp_customize->add_setting( 'footer_copyright_text', array(
        'default'           => '© ' . date('Y') . ' Gustabe. All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'footer_copyright_text', array(
        'label'       => __( 'Footer Copyright Text', 'gustabe' ),
        'description' => __( 'ข้อความลิขสิทธิ์ที่ส่วนท้ายเว็บ', 'gustabe' ),
        'section'     => 'gustabe_general_section',
        'type'        => 'text',
    ) );

    // ==========================================
    // Section 2: Contact Info
    // ==========================================
    $wp_customize->add_section( 'gustabe_contact_section', array(
        'title'    => __( 'Contact Info', 'gustabe' ),
        'panel'    => 'gustabe_theme_options',
        'priority' => 20,
    ) );

    $wp_customize->add_setting( 'gustabe_contact_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'gustabe_contact_address', array(
        'label'       => __( 'Address', 'gustabe' ),
        'section'     => 'gustabe_contact_section',
        'type'        => 'textarea',
    ) );

    $wp_customize->add_setting( 'gustabe_contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gustabe_contact_phone', array(
        'label'       => __( 'Phone Number', 'gustabe' ),
        'section'     => 'gustabe_contact_section',
        'type'        => 'text',
    ) );

    $wp_customize->add_setting( 'gustabe_contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'gustabe_contact_email', array(
        'label'       => __( 'Email Address', 'gustabe' ),
        'section'     => 'gustabe_contact_section',
        'type'        => 'email',
    ) );

    $wp_customize->add_setting( 'gustabe_contact_hours', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'gustabe_contact_hours', array(
        'label'       => __( 'Business Hours', 'gustabe' ),
        'section'     => 'gustabe_contact_section',
        'type'        => 'text',
    ) );

    // ==========================================
    // Section 3: Social Media
    // ==========================================
    $wp_customize->add_section( 'gustabe_social_section', array(
        'title'    => __( 'Social Media', 'gustabe' ),
        'panel'    => 'gustabe_theme_options',
        'priority' => 30,
    ) );

    $social_networks = array(
        'facebook'  => 'Facebook URL',
        'x'         => 'X (Twitter) URL',
        'instagram' => 'Instagram URL',
        'youtube'   => 'YouTube URL',
        'line'      => 'LINE ID (e.g. @gustabe or personal id)',
    );

    foreach ( $social_networks as $key => $label ) {
        $wp_customize->add_setting( 'gustabe_social_' . $key, array(
            'default'           => '',
            'sanitize_callback' => ( $key === 'line' ) ? 'sanitize_text_field' : 'esc_url_raw',
        ) );
        $wp_customize->add_control( 'gustabe_social_' . $key, array(
            'label'       => __( $label, 'gustabe' ),
            'section'     => 'gustabe_social_section',
            'type'        => ( $key === 'line' ) ? 'text' : 'url',
        ) );
    }
}
add_action( 'customize_register', 'gustabe_theme_customize_register' );
