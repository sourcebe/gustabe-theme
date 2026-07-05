<?php
/**
 * dir: inc/seo/
 * file: schema-our_works.php
 * หน้าที่: ปั้น JSON-LD ประเภท SoftwareApplication สำหรับหน้าผลงาน
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id = get_the_ID();

// 1. ดึงข้อมูล Meta จากที่นายเตรียมไว้
$live_url = get_post_meta( $post_id, '_live_url', true ) ?: get_permalink(); // ถ้าไม่มีลิงก์จริง ใช้ลิงก์โพสต์แทน
$app_store = get_post_meta( $post_id, '_app_store_url', true );
$year = get_post_meta( $post_id, '_project_year', true );
$client_name = get_post_meta( $post_id, '_client_name', true );

// 2. ลอจิกฉลาดๆ: เช็คว่างานนี้มี App Store ไหม ถ้ามีให้มองเป็น Mobile App ถ้าไม่มีเป็น Web App
$app_category = !empty( $app_store ) ? 'MobileApplication' : 'WebApplication';

// 3. ประกอบร่าง Array (Mapping to Schema.org)
$schema_data = [
    '@context'            => 'https://schema.org',
    '@type'               => 'SoftwareApplication',
    'name'                => get_the_title(),
    'applicationCategory' => $app_category,
    'operatingSystem'     => 'All',
    'url'                 => esc_url( $live_url ),
    'datePublished'       => $year,
    'author'              => [
        '@type' => 'Organization',
        'name'  => 'GUSTABE' // ชื่อ Agency ของเรา
    ]
];

// ถ้ามีชื่อลูกค้า ให้ใส่เข้าไปในฐานะผู้ว่าจ้าง (producer หรือ about)
if ( ! empty( $client_name ) ) {
    $schema_data['about'] = [
        '@type' => 'Organization',
        'name'  => $client_name
    ];
}

// 4. แปลง Array เป็น JSON และพ่นลง <head>
// ใช้ JSON_UNESCAPED_SLASHES เพื่อไม่ให้ลิงก์พัง (ไม่ให้เกิด \\/) และ UNICODE รองรับภาษาไทย
echo "\n<!-- GUSTABE Custom Schema : " . esc_html($app_category) . " -->\n";
echo '<script type="application/ld+json">' . wp_json_encode( $schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";