<?php
/**
 * dir: inc/features/
 * file: seo-engine.php
 * หน้าที่: Router ควบคุมการพ่น JSON-LD Schema.org ลง <head> (ทำงานร่วมกับ TSF)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'gustabe_render_custom_schema' ) ) {
    function gustabe_render_custom_schema() {
        
        // 1. ถ้าไม่ใช่หน้า Single Post (เช่น หน้า Home, Archive) ให้หยุดทำงาน ปล่อยเป็นหน้าที่ของ TSF
        if ( ! is_single() ) return;

        // 2. ดึงชื่อ CPT ของหน้าที่กำลังเปิดอยู่
        $post_type = get_post_type();

        // 3. กำหนด Path ไปหาไฟล์ Schema เฉพาะของ CPT นั้นๆ
        // เช่น ถ้าเป็น our_works ก็จะไปหาไฟล์ /inc/seo/schema-our_works.php
        $schema_file = GUSTABE_THEME_DIR . '/inc/seo/schema-' . $post_type . '.php';

        // 4. ถ้ามีไฟล์นั้นอยู่จริง ให้เรียกมาทำงาน
        if ( file_exists( $schema_file ) ) {
            require_once $schema_file;
        }
    }
}
// ใช้ Hook wp_head ลำดับที่ 99 เพื่อให้โค้ด Schema ของเราไปอยู่ต่อท้ายแท็กของ TSF
add_action( 'wp_head', 'gustabe_render_custom_schema', 99 );