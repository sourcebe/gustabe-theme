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

    // ตัวอย่างการสร้างช่องตั้งค่าข้อความ (Text Setting)
    $wp_customize->add_setting( 'gustabe_header_text', array(
        'default'           => 'Welcome to Gustabe',
        'sanitize_callback' => 'sanitize_text_field', // กรองข้อมูลเพื่อความปลอดภัย
    ) );

    $wp_customize->add_control( 'gustabe_header_text', array(
        'label'       => __( 'Header Custom Text', 'gustabe' ),
        'description' => __( 'ข้อความที่จะแสดงในส่วนหัวของเว็บ', 'gustabe' ),
        'section'     => 'gustabe_general_section',
        'type'        => 'text',
    ) );

    // สามารถเพิ่ม Section หรือ Setting อื่นๆ ต่อจากนี้ได้เลยครับ...
}
// add_action( 'customize_register', 'gustabe_theme_customize_register' );
