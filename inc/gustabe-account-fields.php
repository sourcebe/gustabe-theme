<?php
/**
 * GUSTABE ACCOUNT FIELDS
 * ระบบจัดการฟิลด์ข้อมูลเสริมสำหรับสมาชิก (เบอร์โทร, วันเกิด, Social ครบวงจร)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. บันทึกข้อมูลเมื่อกด Save Changes
add_action( 'woocommerce_save_account_details', 'gustabe_save_extra_account_fields' );

function gustabe_save_extra_account_fields( $user_id ) {
    
    // --- เบอร์โทรศัพท์ (Sync กับ Billing Phone) ---
    if ( isset( $_POST['account_phone'] ) ) {
        $phone = sanitize_text_field( $_POST['account_phone'] );
        update_user_meta( $user_id, 'billing_phone', $phone );
        update_user_meta( $user_id, 'phone_number', $phone );
    }

    // --- วันเกิด ---
    if ( isset( $_POST['account_birth_date'] ) ) {
        update_user_meta( $user_id, 'date_of_birth', sanitize_text_field( $_POST['account_birth_date'] ) );
    }

    // --- Social Media (General) ---
    if ( isset( $_POST['account_line_id'] ) ) {
        update_user_meta( $user_id, 'line_id', sanitize_text_field( $_POST['account_line_id'] ) );
    }
    if ( isset( $_POST['account_facebook_url'] ) ) {
        update_user_meta( $user_id, 'facebook_url', sanitize_url( $_POST['account_facebook_url'] ) );
    }
    if ( isset( $_POST['account_instagram'] ) ) {
        update_user_meta( $user_id, 'instagram_handle', sanitize_text_field( $_POST['account_instagram'] ) );
    }
    if ( isset( $_POST['account_x_twitter'] ) ) {
        update_user_meta( $user_id, 'x_handle', sanitize_text_field( $_POST['account_x_twitter'] ) );
    }

    // --- Social Media (Chinese) ---
    if ( isset( $_POST['account_wechat'] ) ) {
        update_user_meta( $user_id, 'wechat_id', sanitize_text_field( $_POST['account_wechat'] ) );
    }
    if ( isset( $_POST['account_tiktok'] ) ) {
        update_user_meta( $user_id, 'tiktok_handle', sanitize_text_field( $_POST['account_tiktok'] ) );
    }
}

// 2. Validate (ตัวอย่าง: บังคับกรอกเบอร์โทร)
add_action( 'woocommerce_save_account_details_errors', 'gustabe_validate_extra_fields', 10, 1 );

function gustabe_validate_extra_fields( &$args ) {
    if ( isset( $_POST['account_phone'] ) && empty( $_POST['account_phone'] ) ) {
        $args->add( 'error', __( 'กรุณากรอกเบอร์โทรศัพท์', 'woocommerce' ) );
    }
}