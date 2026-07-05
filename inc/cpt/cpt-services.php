<?php
/**
 * dir: inc/cpt/
 * file: cpt-services.php
 * หน้าที่: ลงทะเบียน Custom Post Type 'services' และ Taxonomy 'service_category'
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function gustabe_register_cpt_services() {
    
    // -----------------------------------------------------------------------------
    // 1. ลงทะเบียน Taxonomy (หมวดหมู่บริการ เช่น Web Dev, SEO, Marketing)
    // -----------------------------------------------------------------------------
    $tax_labels = [
        'name'              => 'หมวดหมู่บริการ',
        'singular_name'     => 'หมวดหมู่',
        'search_items'      => 'ค้นหาหมวดหมู่',
        'all_items'         => 'หมวดหมู่ทั้งหมด',
        'parent_item'       => 'หมวดหมู่หลัก',
        'parent_item_colon' => 'หมวดหมู่หลัก:',
        'edit_item'         => 'แก้ไขหมวดหมู่',
        'update_item'       => 'อัปเดตหมวดหมู่',
        'add_new_item'      => 'เพิ่มหมวดหมู่ใหม่',
        'new_item_name'     => 'ชื่อหมวดหมู่ใหม่',
        'menu_name'         => 'หมวดหมู่บริการ',
    ];

    $tax_args = [
        'hierarchical'      => true, // ทำงานแบบมีลำดับชั้นเหมือน Category
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'service-category' ],
        'show_in_rest'      => true, // จำเป็นสำหรับ The SEO Framework
    ];

    register_taxonomy( 'service_category', [ 'services' ], $tax_args );

    // -----------------------------------------------------------------------------
    // 2. ลงทะเบียน Custom Post Type: Services
    // -----------------------------------------------------------------------------
    $cpt_labels = [
        'name'               => 'Services',
        'singular_name'      => 'Service',
        'menu_name'          => 'Services',
        'name_admin_bar'     => 'Service',
        'add_new'            => 'เพิ่มบริการใหม่',
        'add_new_item'       => 'เพิ่มบริการใหม่',
        'new_item'           => 'บริการใหม่',
        'edit_item'          => 'แก้ไขบริการ',
        'view_item'          => 'ดูหน้าบริการ',
        'all_items'          => 'บริการทั้งหมด',
        'search_items'       => 'ค้นหาบริการ',
        'not_found'          => 'ไม่พบบริการ',
        'not_found_in_trash' => 'ไม่พบบริการในถังขยะ',
    ];

    $cpt_args = [
        'labels'              => $cpt_labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => [ 'slug' => 'services', 'with_front' => false ], // URL จะเป็น: /services/seo
        'capability_type'     => 'post',
        'has_archive'         => true, // แนะนำให้เป็น false แล้วไปสร้าง Page ชื่อ Services เองเพื่อคุม Layout ได้ 100%
        'hierarchical'        => false,
        'menu_position'       => 21,    // วางไว้ใกล้ๆ กับ Our Works
        'menu_icon'           => 'dashicons-awards', // ไอคอนเหรียญรางวัล เพิ่มความขลังให้หน้าบริการ
        'show_in_rest'        => true,
        
        // ⚡ THE MASTERPIECE LOGIC: ปิด 'editor' ทิ้ง เพื่อบังคับใช้ Zone Builder
        'supports'            => [ 'title', 'thumbnail', 'revisions' ], 
    ];

    register_post_type( 'services', $cpt_args );
}
// รันตอน init
add_action( 'init', 'gustabe_register_cpt_services', 0 );