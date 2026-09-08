<?php
/**
 * dir:  inc/features/
 * file: ajax-search.php
 * หน้าที่: Pure REST API สำหรับระบบ Smart Search Engine (Zero Plugin)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. ลงทะเบียน REST API Endpoint
add_action( 'rest_api_init', function () {
    // เส้นทาง API: /wp-json/gustabe/v1/search?keyword=คำค้นหา&type=products
    register_rest_route( 'gustabe/v1', '/search', [
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => 'gustabe_rest_search_handler',
        'permission_callback' => '__return_true'
    ] );
} );

// 2. สมองกลประมวลผล
function gustabe_rest_search_handler( WP_REST_Request $request ) {
    $keyword = sanitize_text_field( $request->get_param( 'keyword' ) );
    $type    = sanitize_text_field( $request->get_param( 'type' ) ); // รับค่า /products, /blog

    // The Logic: แมปคำสั่ง Prefix ให้ตรงกับ Post Type ของ WordPress
    $type_map = [
        'products'  => 'product',
        'portfolio' => 'our_works',
        'blog'      => 'post',
        'all'       => ['post', 'product', 'our_works']
    ];

    // ถ้าไม่มี type ส่งมา หรือส่งมาผิด ให้ค้นหาทั้งหมด (all)
    $post_type = isset( $type_map[$type] ) ? $type_map[$type] : $type_map['all'];

    $args = [
        'post_type'      => $post_type,
        'post_status'    => 'publish',
        'posts_per_page' => 8, // ดึงมาแค่ 8 ชิ้นให้ Palette โหลดไว
    ];

    // ถ้ามีการพิมพ์คำค้นหา ให้เพิ่มเงื่อนไข 's'
    if ( ! empty( $keyword ) ) {
        $args['s'] = $keyword;
    }

    $query = new WP_Query( $args );
    $results = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $pt = get_post_type();
            
            $item = [
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'url'   => esc_url( get_permalink() ),
                'type'  => esc_html( $pt ),
                'image' => esc_url( get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ) ?: '' ),
                'price' => '',
            ];

            // ดึงราคาและรูปสินค้า (ถ้าเป็น WooCommerce)
            if ( $pt === 'product' && function_exists( 'wc_get_product' ) ) {
                $product = wc_get_product( get_the_ID() );
                if ( $product ) {
                    $item['price'] = $product->get_price_html();
                    if ( empty( $item['image'] ) ) {
                        $item['image'] = wc_placeholder_img_src( 'thumbnail' );
                    }
                }
            }

            $results[] = $item;
        }
        wp_reset_postdata();
    }

    // ส่งคืนข้อมูลเป็น JSON
    return rest_ensure_response( $results );
}