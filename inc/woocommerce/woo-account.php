<?php
/**
 * dir  inc/woocommerce/
 * file woo-account.php
 * * ปรับแต่งหน้า My Account และเพิ่มฟิลด์ข้อมูลสมาชิก
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. เพิ่มช่องกรอกข้อมูลในหน้า "รายละเอียดบัญชี" (Edit Account)
 */
add_action( 'woocommerce_edit_account_form', 'gustabe_add_custom_account_fields' );
function gustabe_add_custom_account_fields() {
    $user_id = get_current_user_id();
    // ดึงข้อมูลเดิมมาแสดง
    $line_id = get_user_meta( $user_id, 'gustabe_line_id', true );
    ?>
    <fieldset class="mt-8 pt-6 border-t border-gray-200">
        <legend class="text-lg font-bold text-gray-800 mb-4">
            <?php esc_html_e( 'ข้อมูลติดต่อเพิ่มเติม', 'gustabe' ); ?>
        </legend>
        
        <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
            <label for="gustabe_line_id" class="block text-sm font-medium text-gray-700 mb-1">
                <?php esc_html_e( 'Line ID (ตัวเลือก)', 'gustabe' ); ?>
            </label>
            <input type="text" 
                   class="woocommerce-Input woocommerce-Input--text input-text w-full rounded-xl border-gray-300 focus:ring-2 focus:ring-[#04a39c] focus:border-[#04a39c] px-4 py-3 outline-none transition-all" 
                   name="gustabe_line_id" 
                   id="gustabe_line_id" 
                   value="<?php echo esc_attr( $line_id ); ?>" />
        </p>
    </fieldset>
    <?php
}

/**
 * 2. ดักจับตอนกดปุ่ม "บันทึก" เพื่อเอาข้อมูลลงฐานข้อมูล
 */
add_action( 'woocommerce_save_account_details', 'gustabe_save_custom_account_fields' );
function gustabe_save_custom_account_fields( $user_id ) {
    if ( isset( $_POST['gustabe_line_id'] ) ) {
        // P3: ใช้ wp_unslash() เพื่อป้องกันปัญหา Slash ตกค้าง และ sanitize_text_field เพื่อล้างข้อมูลให้สะอาดก่อนลงฐานข้อมูล (ป้องกันโดนแฮก)
        update_user_meta( $user_id, 'gustabe_line_id', sanitize_text_field( wp_unslash( $_POST['gustabe_line_id'] ) ) );
    }
}