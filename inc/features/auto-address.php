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
    // คลาสสำหรับกล่อง Input
    $args['input_class'][] = 'w-full bg-gray-50 border border-gray-200 text-gray-800 text-md rounded-xl focus:ring-2 focus:ring-[#04a39c] focus:border-[#04a39c] block px-4 py-3 transition-all outline-none';
    
    // คลาสสำหรับ Label
    $args['label_class'][] = 'block text-sm font-medium text-gray-700 mb-1';
    
    return $args;
}