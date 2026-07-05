<?php
/**
 * File: inc/seo/schema-services.php
 * Description: Smart SEO Engine ดึงข้อมูลบริการมาทำ Service & FAQPage Schema อัตโนมัติ
 * Note: ไฟล์นี้ถูก include มาจาก Router (seo-engine.php) ในจังหวะ wp_head แล้ว จึงไม่ต้องใช้ add_action ซ้ำ
 */

if ( ! defined( 'ABSPATH' ) ) exit;

global $post;

// ---------------------------------------------------
// 1. ดึงข้อมูลเตรียมทำ Service Schema
// ---------------------------------------------------
$price_value  = get_post_meta( $post->ID, '_starting_price_value', true );
$service_name = get_the_title( $post->ID );

// ⚡ ดึง Description จาก TSF เป็นหลัก (ถ้าไม่มีค่อยใช้ Excerpt)
$service_desc = '';
if ( function_exists( 'the_seo_framework' ) ) {
    $service_desc = the_seo_framework()->get_description( [ 'id' => $post->ID ] );
}
if ( empty( $service_desc ) ) {
    $service_desc = get_the_excerpt( $post->ID );
}

// ---------------------------------------------------
// 2. ดึงข้อมูลเตรียมทำ FAQ Schema
// ---------------------------------------------------
$faqs = get_post_meta( $post->ID, '_service_faqs', true );

// โครงสร้างหลัก (ใช้ @graph)
$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => []
];

// --- ก้อนที่ 1: Service Schema ---
$service_schema = [
    '@type'       => 'Service',
    'name'        => $service_name,
    'description' => wp_strip_all_tags( $service_desc ),
    'provider'    => [
        '@type' => 'Organization',
        'name'  => get_bloginfo( 'name' ),
        'url'   => home_url()
    ]
];

if ( ! empty( $price_value ) ) {
    $service_schema['offers'] = [
        '@type'         => 'Offer',
        'price'         => $price_value,
        'priceCurrency' => 'THB'
    ];
}
$schema['@graph'][] = $service_schema;

// --- ก้อนที่ 2: FAQPage Schema ---
if ( ! empty( $faqs ) && is_array( $faqs ) ) {
    $faq_schema = [
        '@type'      => 'FAQPage',
        'mainEntity' => []
    ];

    foreach ( $faqs as $faq ) {
        if ( ! empty( $faq['question'] ) && ! empty( $faq['answer'] ) ) {
            $faq_schema['mainEntity'][] = [
                '@type' => 'Question',
                'name'  => wp_strip_all_tags( $faq['question'] ),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text'  => wp_strip_all_tags( $faq['answer'] )
                ]
            ];
        }
    }
    
    // ถ้ามีคำถามจริงๆ ค่อยยัดลง Graph
    if ( ! empty( $faq_schema['mainEntity'] ) ) {
        $schema['@graph'][] = $faq_schema;
    }
}

// 🚀 พ่นโค้ดลง <head> โดยตรง (เพราะ Router สั่งให้รันใน wp_head แล้ว)
echo "\n";
echo '<script type="application/ld+json">' . "\n";
echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ) . "\n";
echo '</script>' . "\n";