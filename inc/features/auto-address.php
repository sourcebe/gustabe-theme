<?php
/**
 * dir  inc/features/
 * file auto-address.php
 * * ผูกคลาสระบบที่อยู่อัตโนมัติ และใส่สไตล์ Tailwind ให้ช่องกรอก
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. เพิ่ม Class ให้ช่องกรอกเพื่อจับคู่กับ Javascript ของ Thailand.js
 */
add_filter( 'woocommerce_default_address_fields', 'gustabe_setup_thailand_auto_address_fields' );
function gustabe_setup_thailand_auto_address_fields( $fields ) {
    // ตำบล / แขวง
    if ( isset( $fields['address_2'] ) ) {
        $fields['address_2']['label']       = __( 'ตำบล / แขวง', 'gustabe' );
        $fields['address_2']['placeholder'] = __( 'พิมพ์ชื่อตำบล...', 'gustabe' );
        $fields['address_2']['class'][]     = 'thailand-subdistrict'; 
        $fields['address_2']['required']    = true;
    }

    // อำเภอ / เขต
    if ( isset( $fields['city'] ) ) {
        $fields['city']['label']       = __( 'อำเภอ / เขต', 'gustabe' );
        $fields['city']['placeholder'] = __( 'พิมพ์ชื่ออำเภอ...', 'gustabe' );
        $fields['city']['class'][]     = 'thailand-district'; 
    }

    // จังหวัด
    if ( isset( $fields['state'] ) ) {
        $fields['state']['class'][] = 'thailand-province'; 
    }

    // รหัสไปรษณีย์
    if ( isset( $fields['postcode'] ) ) {
        $fields['postcode']['class'][] = 'thailand-zipcode'; 
    }

    return $fields;
}

/**
 * 2. เติมคลาสของ Tailwind ลงไปในช่องกรอก (Input) และชื่อหัวข้อ (Label) ทั่วทั้งระบบ
 */
add_filter( 'woocommerce_form_field_args', 'gustabe_apply_tailwind_to_checkout_fields', 10, 3 );
function gustabe_apply_tailwind_to_checkout_fields( $args, $key, $value ) {
    // คลาสสำหรับกล่อง Input (Hacker Style: โปร่งใส, ขอบล่าง, สีเขียว, ฟอนต์ Monospace)
    $args['input_class'][] = 'w-full bg-transparent border-0 border-b border-slate-600 text-emerald-400 font-mono text-sm rounded-none focus:ring-0 focus:border-emerald-400 block px-0 py-2 transition-all outline-none shadow-none';
    
    // คลาสสำหรับ Label (Hacker Style: สีเทาดำ, ฟอนต์ Monospace, ตัวพิมพ์ใหญ่)
    $args['label_class'][] = 'text-xs font-mono text-slate-400 uppercase tracking-widest';
    
    return $args;
}