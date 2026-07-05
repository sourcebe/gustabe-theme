<?php
/**
 * Plugin Name: Gustabe Core Engine
 * Description: The Core Engine for registering Custom Post Types and Taxonomies. (Zero Plugin Policy)
 * Version: 1.1.0
 * Author: WP Custom Master ⚡
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// -----------------------------------------------------------------------------
// 1. ฟังก์ชันสร้าง Custom Post Type: Our Works
// -----------------------------------------------------------------------------
function gustabe_register_core_cpt() {

    $labels_works = [
        'name'                  => _x( 'ผลงานของเรา', 'Post Type General Name', 'gustabe-core' ),
        'singular_name'         => _x( 'ผลงาน', 'Post Type Singular Name', 'gustabe-core' ),
        'menu_name'             => __( 'ผลงาน (Portfolio)', 'gustabe-core' ),
        'all_items'             => __( 'ผลงานทั้งหมด', 'gustabe-core' ),
        'add_new_item'          => __( 'เพิ่มผลงานใหม่', 'gustabe-core' ),
        'edit_item'             => __( 'แก้ไขผลงาน', 'gustabe-core' ),
        'view_item'             => __( 'ดูผลงาน', 'gustabe-core' ),
        'search_items'          => __( 'ค้นหาผลงาน', 'gustabe-core' ),
        'not_found'             => __( 'ไม่พบผลงาน', 'gustabe-core' ),
        'not_found_in_trash'    => __( 'ไม่พบผลงานในถังขยะ', 'gustabe-core' ),
        'featured_image'        => __( 'รูปภาพหน้าปก', 'gustabe-core' ),
        'set_featured_image'    => __( 'ตั้งค่ารูปภาพหน้าปก', 'gustabe-core' ),
    ];

    $args_works = [
        'label'                 => __( 'ผลงาน', 'gustabe-core' ),
        'description'           => __( 'รวบรวมผลงานของบริษัท', 'gustabe-core' ),
        'labels'                => $labels_works,
        'supports'              => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
        // ⚡ อัปเดต: ผูก CPT นี้เข้ากับ 3 Taxonomies ใหม่ของเรา
        'taxonomies'            => [ 'project_type', 'project_platform', 'tech_stack' ],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20, 
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true, 
        'exclude_from_search'   => false, 
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true, 
        'rewrite'               => [ 'slug' => 'our-works', 'with_front' => true ], 
    ];

    register_post_type( 'our_works', $args_works );
}
add_action( 'init', 'gustabe_register_core_cpt', 0 );


// -----------------------------------------------------------------------------
// 2. ฟังก์ชันสร้าง Custom Taxonomies (3 แกนหลัก)
// -----------------------------------------------------------------------------
function gustabe_register_core_taxonomies() {

    // --- แกนที่ 1: ประเภทผลงาน (Project Type) เช่น E-Commerce, Landing Page ---
    $labels_type = [
        'name'              => _x( 'ประเภทผลงาน', 'Taxonomy General Name', 'gustabe-core' ),
        'singular_name'     => _x( 'ประเภทผลงาน', 'Taxonomy Singular Name', 'gustabe-core' ),
        'menu_name'         => __( 'ประเภทผลงาน', 'gustabe-core' ),
        'all_items'         => __( 'ประเภทผลงานทั้งหมด', 'gustabe-core' ),
        'add_new_item'      => __( 'เพิ่มประเภทผลงาน', 'gustabe-core' ),
    ];
    register_taxonomy( 'project_type', [ 'our_works' ], [
        'labels'            => $labels_type,
        'hierarchical'      => true, // ทำงานเหมือน Category (ติ๊กกล่อง Checkbox)
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'project-type' ], 
    ]);

    // --- แกนที่ 2: แพลตฟอร์ม (Platform) เช่น Web, iOS, Android, PC ---
    $labels_platform = [
        'name'              => _x( 'แพลตฟอร์ม', 'Taxonomy General Name', 'gustabe-core' ),
        'singular_name'     => _x( 'แพลตฟอร์ม', 'Taxonomy Singular Name', 'gustabe-core' ),
        'menu_name'         => __( 'แพลตฟอร์ม', 'gustabe-core' ),
        'all_items'         => __( 'แพลตฟอร์มทั้งหมด', 'gustabe-core' ),
        'add_new_item'      => __( 'เพิ่มแพลตฟอร์ม', 'gustabe-core' ),
    ];
    register_taxonomy( 'project_platform', [ 'our_works' ], [
        'labels'            => $labels_platform,
        'hierarchical'      => true, // ทำงานเหมือน Category (ติ๊กกล่อง Checkbox)
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'platform' ], 
    ]);

    // --- แกนที่ 3: เทคโนโลยี (Tech Stack) เช่น WordPress, React, Tailwind ---
    $labels_stack = [
        'name'              => _x( 'เทคโนโลยีที่ใช้', 'Taxonomy General Name', 'gustabe-core' ),
        'singular_name'     => _x( 'เทคโนโลยี', 'Taxonomy Singular Name', 'gustabe-core' ),
        'menu_name'         => __( 'Tech Stack', 'gustabe-core' ),
        'all_items'         => __( 'เทคโนโลยีทั้งหมด', 'gustabe-core' ),
        'add_new_item'      => __( 'เพิ่มเทคโนโลยี', 'gustabe-core' ),
    ];
    register_taxonomy( 'tech_stack', [ 'our_works' ], [
        'labels'            => $labels_stack,
        'hierarchical'      => false, // ⚡ ทำงานเหมือน Tag (พิมพ์แล้วกด Enter) เพื่อความ Geek!
        'public'            => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'rewrite'           => [ 'slug' => 'tech-stack' ], 
    ]);
}
add_action( 'init', 'gustabe_register_core_taxonomies', 0 );


// -----------------------------------------------------------------------------
// 3. ปิด Gutenberg (Block Editor) และบังคับใช้ Classic Editor สำหรับ CPT นี้
// -----------------------------------------------------------------------------
function gustabe_disable_gutenberg_for_our_works( $current_status, $post_type ) {
    if ( $post_type === 'our_works' ) {
        return false;
    }
    return $current_status;
}
add_filter( 'use_block_editor_for_post_type', 'gustabe_disable_gutenberg_for_our_works', 10, 2 );