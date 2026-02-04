<?php
/**
 * Edit account form (Gustabe Pro Version - Fixed Icons)
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' );
?>

<form class="gustabe-edit-account-form" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

    <div class="form-section-group">
        <h3 class="section-title"><i class="huge huge-user-circle"></i> <?php echo function_exists('pll__') ? pll__('ข้อมูลส่วนตัว') : 'Personal Info'; ?></h3>
        
        <div class="row-grid-2">
            <div class="form-input-group">
                <label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="huge huge-user"></i>
                    <input type="text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="huge huge-user"></i>
                    <input type="text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
                </div>
            </div>
        </div>

        <div class="form-input-group">
            <label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?> <span class="required">*</span></label>
            <div class="input-with-icon">
                <i class="huge huge-star"></i>
                <input type="text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" />
            </div>
            <span class="desc-text"><?php esc_html_e( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ); ?></span>
        </div>

        <div class="row-grid-2">
            <div class="form-input-group">
                <label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="huge huge-mail-02"></i>
                    <input type="email" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
                </div>
            </div>
            
            <div class="form-input-group">
                <label for="account_birth_date"><?php echo function_exists('pll__') ? pll__('วันเกิด (DD/MM/YYYY)') : 'Date of Birth'; ?></label>
                <div class="input-with-icon">
                    <i class="huge huge-calendar-03"></i>
                    <input type="date" name="account_birth_date" id="account_birth_date" value="<?php echo esc_attr( get_user_meta( $user->ID, 'date_of_birth', true ) ); ?>" />
                </div>
            </div>
        </div>
    </div>

    <div class="form-section-group">
        <h3 class="section-title"><i class="huge huge-smart-phone-01"></i> <?php echo function_exists('pll__') ? pll__('ช่องทางติดต่อ') : 'Contact'; ?></h3>
        
        <div class="row-grid-2">
            <div class="form-input-group">
                <label for="account_phone"><?php echo function_exists('pll__') ? pll__('เบอร์โทรศัพท์') : 'Mobile Phone'; ?> <span class="required">*</span></label>
                <div class="input-with-icon">
                    <i class="huge huge-smart-phone-01"></i>
                    <input type="tel" name="account_phone" id="account_phone" value="<?php echo esc_attr( get_user_meta( $user->ID, 'billing_phone', true ) ); ?>" placeholder="08x-xxx-xxxx" />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_line_id"><?php echo function_exists('pll__') ? pll__('Line ID') : 'Line ID'; ?></label>
                <div class="input-with-icon line-input">
                    <i class="huge huge-bubble-chat"></i>
                    <input type="text" name="account_line_id" id="account_line_id" value="<?php echo esc_attr( get_user_meta( $user->ID, 'line_id', true ) ); ?>" placeholder="@username" />
                </div>
            </div>
        </div>
    </div>

    <div class="form-section-group">
        <h3 class="section-title"><i class="huge huge-globe"></i> <?php echo function_exists('pll__') ? pll__('โซเชียลมีเดีย') : 'Social Media'; ?></h3>
        
        <div class="row-grid-2">
            <div class="form-input-group">
                <label for="account_facebook_url">Facebook</label>
                <div class="input-with-icon fb-input">
                    <i class="huge huge-facebook-02"></i>
                    <input type="url" name="account_facebook_url" id="account_facebook_url" value="<?php echo esc_attr( get_user_meta( $user->ID, 'facebook_url', true ) ); ?>" placeholder="https://facebook.com/..." />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_instagram">Instagram (IG)</label>
                <div class="input-with-icon ig-input">
                    <i class="huge huge-instagram"></i>
                    <input type="text" name="account_instagram" id="account_instagram" value="<?php echo esc_attr( get_user_meta( $user->ID, 'instagram_handle', true ) ); ?>" placeholder="@username" />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_x_twitter">X (Twitter)</label>
                <div class="input-with-icon x-input">
                    <i class="huge huge-x"></i> 
                    <input type="text" name="account_x_twitter" id="account_x_twitter" value="<?php echo esc_attr( get_user_meta( $user->ID, 'x_handle', true ) ); ?>" placeholder="@username" />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_tiktok">TikTok</label>
                <div class="input-with-icon tiktok-input">
                    <i class="huge huge-tiktok"></i>
                    <input type="text" name="account_tiktok" id="account_tiktok" value="<?php echo esc_attr( get_user_meta( $user->ID, 'tiktok_handle', true ) ); ?>" placeholder="@username" />
                </div>
            </div>

            <div class="form-input-group">
                <label for="account_wechat">WeChat ID (微信)</label>
                <div class="input-with-icon wechat-input">
                    <i class="huge huge-wechat"></i>
                    <input type="text" name="account_wechat" id="account_wechat" value="<?php echo esc_attr( get_user_meta( $user->ID, 'wechat_id', true ) ); ?>" placeholder="WeChat ID" />
                </div>
            </div>
        </div>
    </div>

	<?php do_action( 'woocommerce_edit_account_form_fields' ); ?>

    <fieldset class="form-section-group password-section">
		<h3 class="section-title"><i class="huge huge-security-lock"></i> <?php esc_html_e( 'Password change', 'woocommerce' ); ?></h3>

        <div class="form-input-group">
			<label for="password_current"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
            <div class="input-with-icon">
                <i class="huge huge-lock-key"></i>
			    <input type="password" name="password_current" id="password_current" autocomplete="off" />
            </div>
		</div>
        
        <div class="row-grid-2">
            <div class="form-input-group">
                <label for="password_1"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
                <div class="input-with-icon">
                    <i class="huge huge-circle-password"></i>
                    <input type="password" name="password_1" id="password_1" autocomplete="off" />
                </div>
            </div>
            <div class="form-input-group">
                <label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
                <div class="input-with-icon">
                    <i class="huge huge-circle-lock-check-01"></i>
                    <input type="password" name="password_2" id="password_2" autocomplete="off" />
                </div>
            </div>
        </div>
	</fieldset>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_edit_account_form' ); ?>

	<p class="form-submit-row">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>

<style>
    .gustabe-edit-account-form { max-width: 800px; margin: 0 auto; }
    
    .form-section-group {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
    }
    
    .section-title {
        font-size: 18px; font-weight: 700; color: #333;
        border-bottom: 2px solid #f0f0f0; padding-bottom: 15px; margin-bottom: 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .section-title i { color: #04a39c; }

    .form-input-group { margin-bottom: 20px; }
    .form-input-group label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px; color: #555; }
    .required { color: red; }
    .desc-text { font-size: 12px; color: #999; margin-top: 5px; display: block; }

    /* Input Icon Styling */
    .input-with-icon { position: relative; }
    .input-with-icon i {
        position: absolute; left: 15px; top: 50%; transform: translateY(-50%);
        color: #aaa; font-size: 20px; transition: 0.3s;
    }
    .input-with-icon input {
        width: 100%;
        padding: 12px 15px 12px 45px !important; 
        border: 1px solid #ddd !important;
        border-radius: 8px !important;
        background: #fdfdfd;
        transition: all 0.3s;
    }
    
    /* Focus Effects */
    .input-with-icon input:focus { border-color: #04a39c !important; background: #fff; box-shadow: 0 0 0 3px rgba(4, 163, 156, 0.1); }
    .input-with-icon input:focus + i, 
    .input-with-icon:focus-within i { color: #04a39c; }

    /* Social Colors (Focus State) */
    .line-input:focus-within i { color: #06c755; }
    .fb-input:focus-within i { color: #1877f2; }
    .ig-input:focus-within i { color: #e1306c; }
    .x-input:focus-within i { color: #000; }
    .tiktok-input:focus-within i { color: #000; }
    .wechat-input:focus-within i { color: #7bb32e; }

    /* Grid Layout */
    .row-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 767px) { .row-grid-2 { grid-template-columns: 1fr; gap: 0; } }

    /* Button */
    .form-submit-row { text-align: right; margin-top: 20px; }
    .form-submit-row button {
        background: #04a39c !important; color: #fff !important;
        padding: 15px 40px !important; border-radius: 50px !important;
        font-weight: bold; font-size: 16px;
        box-shadow: 0 4px 15px rgba(4, 163, 156, 0.3);
    }
    .form-submit-row button:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(4, 163, 156, 0.4); }
</style>