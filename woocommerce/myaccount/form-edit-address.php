<?php
/**
 * GUSTABE CUSTOM ADDRESS FORM (V8: Polylang Ready)
 * รองรับการแปลภาษา 100% (ไทย/อังกฤษ/จีน) ผ่าน Polylang
 */

defined( 'ABSPATH' ) || exit;

$page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );
$user_id    = get_current_user_id();
$prefix     = $load_address . '_'; 

// ฟังก์ชันช่วยแปล (Helper Function) เพื่อลดความรกของโค้ด
if ( ! function_exists( 'g_trans' ) ) {
    function g_trans( $text ) {
        return function_exists( 'pll__' ) ? pll__( $text ) : $text;
    }
}

// ดึงค่าจาก DB
$data = [
    'first_name' => get_user_meta( $user_id, $prefix . 'first_name', true ),
    'last_name'  => get_user_meta( $user_id, $prefix . 'last_name', true ),
    'company'    => get_user_meta( $user_id, $prefix . 'company', true ),
    'phone'      => get_user_meta( $user_id, $prefix . 'phone', true ),
    'email'      => get_user_meta( $user_id, $prefix . 'email', true ),
    'address_1'  => get_user_meta( $user_id, $prefix . 'address_1', true ),
    'address_2'  => get_user_meta( $user_id, $prefix . 'address_2', true ),
    'city'       => get_user_meta( $user_id, $prefix . 'city', true ),
    'postcode'   => get_user_meta( $user_id, $prefix . 'postcode', true ),
    'country'    => get_user_meta( $user_id, $prefix . 'country', true ),
    'state'      => get_user_meta( $user_id, $prefix . 'state', true ),
];

do_action( 'woocommerce_before_edit_account_address_form' ); 
?>

<?php if ( ! $load_address ) : ?>
    <?php wc_get_template( 'myaccount/my-address.php' ); ?>
<?php else : ?>

    <form method="post" action="" class="gustabe-auto-form">

        <div class="form-head-card">
            <div class="head-icon">
                <i class="huge <?php echo ('billing' === $load_address) ? 'huge-invoice-01' : 'huge-delivery-truck-02'; ?>"></i>
            </div>
            <div class="head-info">
                <h3 class="head-title"><?php echo esc_html($page_title); ?></h3>
                <p class="head-subtitle"><?php echo g_trans('พิมพ์รหัสไปรษณีย์ในช่องค้นหา ระบบจะกรอกข้อมูลให้'); ?></p>
            </div>
        </div>

        <div class="form-body-wrapper">
            <?php do_action( "woocommerce_before_edit_address_form_{$load_address}" ); ?>

            <div class="form-section">
                <div class="section-badge"><i class="huge huge-user-circle"></i> <?php echo g_trans('ข้อมูลผู้ติดต่อ'); ?></div>
                <div class="form-grid-2">
                    <div class="input-box">
                        <label><?php echo g_trans('ชื่อจริง'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-user"></i></span>
                            <input type="text" name="<?php echo $prefix; ?>first_name" value="<?php echo isset($_POST[$prefix.'first_name']) ? esc_attr(wp_unslash($_POST[$prefix.'first_name'])) : esc_attr($data['first_name']); ?>" placeholder="<?php echo g_trans('ชื่อจริง'); ?>">
                        </div>
                    </div>
                    <div class="input-box">
                        <label><?php echo g_trans('นามสกุล'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-user"></i></span>
                            <input type="text" name="<?php echo $prefix; ?>last_name" value="<?php echo isset($_POST[$prefix.'last_name']) ? esc_attr(wp_unslash($_POST[$prefix.'last_name'])) : esc_attr($data['last_name']); ?>" placeholder="<?php echo g_trans('นามสกุล'); ?>">
                        </div>
                    </div>
                    
                    <?php if( $load_address === 'billing' ) : ?>
                    <div class="input-box">
                        <label><?php echo g_trans('เบอร์โทรศัพท์'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-smart-phone-01"></i></span>
                            <input type="tel" name="<?php echo $prefix; ?>phone" value="<?php echo isset($_POST[$prefix.'phone']) ? esc_attr(wp_unslash($_POST[$prefix.'phone'])) : esc_attr($data['phone']); ?>" placeholder="08x-xxx-xxxx">
                        </div>
                    </div>
                    <div class="input-box"> 
                        <label><?php echo g_trans('อีเมล'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-mail-02"></i></span>
                            <input type="email" name="<?php echo $prefix; ?>email" value="<?php echo isset($_POST[$prefix.'email']) ? esc_attr(wp_unslash($_POST[$prefix.'email'])) : esc_attr($data['email']); ?>" placeholder="example@email.com">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-section mt-big">
                <div class="section-badge badge-auto"><i class="huge huge-location-01"></i> <?php echo g_trans('ระบบค้นหาที่อยู่อัตโนมัติ'); ?></div>
                <div class="form-grid-2">

                    <div class="input-box full-width highlight-box">
                        <label>🔍 <?php echo g_trans('ค้นหาที่อยู่ (พิมพ์รหัสไปรษณีย์ที่นี่)'); ?></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-search-02"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>address_search" placeholder="<?php echo g_trans('พิมพ์เพื่อค้นหา เช่น 10900...'); ?>" autocomplete="off">
                        </div>
                        <small style="color:#04a39c; display:block; margin-top:5px;">* <?php echo g_trans('พิมพ์รหัสไปรษณีย์ แล้วเลือกรายการ ระบบจะเติมข้อมูลด้านล่างให้อัตโนมัติ'); ?></small>
                    </div>

                    <div class="input-box">
                        <label><?php echo g_trans('รหัสไปรษณีย์'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-mail-01"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>postcode" name="<?php echo $prefix; ?>postcode" value="<?php echo isset($_POST[$prefix.'postcode']) ? esc_attr(wp_unslash($_POST[$prefix.'postcode'])) : esc_attr($data['postcode']); ?>" placeholder="<?php echo g_trans('รหัสไปรษณีย์'); ?>">
                        </div>
                    </div>

                    <div class="input-box">
                        <label><?php echo g_trans('จังหวัด'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-map"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>state" name="<?php echo $prefix; ?>state" value="<?php echo isset($_POST[$prefix.'state']) ? esc_attr(wp_unslash($_POST[$prefix.'state'])) : esc_attr($data['state']); ?>" placeholder="<?php echo g_trans('จังหวัด'); ?>">
                        </div>
                    </div>

                    <div class="input-box">
                        <label><?php echo g_trans('เขต / อำเภอ'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-building-04"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>city" name="<?php echo $prefix; ?>city" value="<?php echo isset($_POST[$prefix.'city']) ? esc_attr(wp_unslash($_POST[$prefix.'city'])) : esc_attr($data['city']); ?>" placeholder="<?php echo g_trans('เขต / อำเภอ'); ?>">
                        </div>
                    </div>

                    <div class="input-box">
                        <label><?php echo g_trans('ตำบล / แขวง'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-map-pin"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>address_2" name="<?php echo $prefix; ?>address_2" value="<?php echo isset($_POST[$prefix.'address_2']) ? esc_attr(wp_unslash($_POST[$prefix.'address_2'])) : esc_attr($data['address_2']); ?>" placeholder="<?php echo g_trans('ตำบล / แขวง'); ?>">
                        </div>
                    </div>

                    <div class="input-box full-width">
                        <label><?php echo g_trans('บ้านเลขที่ / หมู่บ้าน / ซอย'); ?> <span class="req">*</span></label>
                        <div class="input-flex-wrapper">
                            <span class="icon-side"><i class="huge huge-home-01"></i></span>
                            <input type="text" id="<?php echo $prefix; ?>address_1" name="<?php echo $prefix; ?>address_1" value="<?php echo isset($_POST[$prefix.'address_1']) ? esc_attr(wp_unslash($_POST[$prefix.'address_1'])) : esc_attr($data['address_1']); ?>" placeholder="<?php echo g_trans('ระบุบ้านเลขที่...'); ?>">
                        </div>
                    </div>
                    
                    <input type="hidden" name="<?php echo $prefix; ?>country" value="TH">

                </div>
            </div>

            <?php do_action( "woocommerce_after_edit_address_form_{$load_address}" ); ?>

            <div class="form-footer-action">
                <button type="submit" class="gustabe-save-btn" name="save_address" value="<?php esc_attr_e( 'Save address', 'woocommerce' ); ?>">
                    <i class="huge huge-checkmark-circle-02"></i> <?php echo g_trans('บันทึกที่อยู่'); ?>
                </button>
                <?php wp_nonce_field( 'woocommerce-edit_address', 'woocommerce-edit-address-nonce' ); ?>
                <input type="hidden" name="action" value="edit_address" />
            </div>

        </div>
    </form>

<?php endif; ?>

<?php do_action( 'woocommerce_after_edit_account_address_form' ); ?>

<style>
    /* CSS เดิมของคุณ */
    .gustabe-auto-form { max-width: 850px; margin: 0 auto; padding-bottom: 50px; font-family: 'Noto Sans Thai', sans-serif; }
    .form-head-card { display: flex; align-items: center; gap: 20px; background: #fff; padding: 25px; border-radius: 16px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); margin-bottom: 30px; border: 1px solid #f0f0f0; }
    .head-icon { width: 56px; height: 56px; background: linear-gradient(135deg, #e0f2f1, #b2dfdb); color: #04a39c; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 26px; }
    .head-title { margin: 0; font-size: 22px; color: #333; font-weight: 700; }
    .head-subtitle { margin: 5px 0 0; font-size: 14px; color: #888; }
    .form-section { position: relative; margin-bottom: 30px; background: #fff; border-radius: 16px; padding: 30px; border: 1px solid #eee; }
    .mt-big { margin-top: 30px; }
    .section-badge { display: inline-flex; align-items: center; gap: 8px; background: #f5f5f5; padding: 6px 15px; border-radius: 20px; font-size: 14px; font-weight: 700; color: #555; margin-bottom: 25px; }
    .section-badge i { color: #04a39c; }
    .section-badge.badge-auto { background: #e0f7fa; color: #006064; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .input-box.full-width { grid-column: span 2; }
    @media (max-width: 768px) { .form-grid-2 { grid-template-columns: 1fr; } .input-box.full-width { grid-column: span 1; } }
    .input-box label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; }
    .input-box .req { color: #ff4757; }
    .input-flex-wrapper { display: flex; align-items: center; background: #fcfcfc; border: 1px solid #e0e0e0; border-radius: 10px; transition: all 0.3s ease; height: 50px; }
    .icon-side { width: 45px; height: 100%; display: flex; align-items: center; justify-content: center; background: #f5f5f5; border-right: 1px solid #eee; color: #999; font-size: 18px; }
    .input-flex-wrapper input { flex: 1; border: none; background: transparent; height: 100%; padding: 0 15px; font-size: 15px; color: #333; outline: none; width: 100%; }
    .input-flex-wrapper:focus-within { border-color: #04a39c; box-shadow: 0 0 0 4px rgba(4, 163, 156, 0.1); background: #fff; }
    .input-flex-wrapper:focus-within .icon-side { background: #04a39c; color: #fff; border-right-color: #04a39c; }
    .highlight-box .input-flex-wrapper { border: 2px solid #b2dfdb; }
    .highlight-box .input-flex-wrapper:focus-within { border-color: #009688; }
    .form-footer-action { text-align: right; margin-top: 20px; border-top: 1px solid #f0f0f0; padding-top: 20px; }
    .gustabe-save-btn { background: #04a39c !important; color: #fff !important; padding: 14px 40px !important; border-radius: 50px !important; font-weight: bold; font-size: 16px; border: none; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; transition: 0.3s; }
    .gustabe-save-btn:hover { background: #03857f !important; transform: translateY(-3px); }
    .tt-menu { background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-top: 5px; width: 100%; }
    .tt-suggestion { padding: 10px 15px; border-bottom: 1px solid #f9f9f9; font-size: 14px; color: #333; cursor: pointer; }
    .tt-suggestion:hover, .tt-cursor { background: #e0f2f1; color: #04a39c; }
</style>