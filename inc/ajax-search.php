<?php
/**
 * Custom Ajax Search Logic (Updated: Support Search Type)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_ajax_gustabe_ajax_search', 'gustabe_ajax_search_handler' );
add_action( 'wp_ajax_nopriv_gustabe_ajax_search', 'gustabe_ajax_search_handler' );

function gustabe_ajax_search_handler() {
    $keyword = isset( $_POST['keyword'] ) ? sanitize_text_field( $_POST['keyword'] ) : '';
    // ★ รับค่าประเภทการค้นหา (product, post, all)
    $search_type = isset( $_POST['search_type'] ) ? sanitize_text_field( $_POST['search_type'] ) : 'product';

    if ( empty( $keyword ) ) { wp_send_json_error(); }

    // กำหนด Post Type ตามที่เลือกมา
    $post_types = array('product'); // Default
    if ( $search_type === 'post' ) {
        $post_types = array('post');
    } elseif ( $search_type === 'all' ) {
        $post_types = array('product', 'post');
    }

    $args = array(
        'post_type'      => $post_types,
        'post_status'    => 'publish',
        'posts_per_page' => 6,
        's'              => $keyword,
        'lang'           => function_exists('pll_current_language') ? pll_current_language() : '',
    );

    $search_query = new WP_Query( $args );
    $results = array();

    if ( $search_query->have_posts() ) {
        while ( $search_query->have_posts() ) {
            $search_query->the_post();
            global $product;
            
            $is_product = ( get_post_type() === 'product' );
            $price_html = '';
            $image_url  = '';

            if ( $is_product && $product ) {
                $image_url = wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' );
                $price_html = $product->get_price_html();
            } else {
                // ถ้าเป็นบทความ
                $image_url = get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' );
                $price_html = '<span style="font-size:12px; color:#999;">' . get_the_date() . '</span>';
            }

            if(empty($image_url)) $image_url = wc_placeholder_img_src();

            $results[] = array(
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'image' => $image_url,
                'price' => $price_html, // ถ้าเป็นบทความ จะเป็นวันที่แทน
                'is_product' => $is_product
            );
        }
        wp_reset_postdata();
    }

    if ( ! empty( $results ) ) {
        wp_send_json_success( $results );
    } else {
        wp_send_json_error( 'Not found' );
    }
    wp_die();
}