<?php
/**
 * GUSTABE REWARDS SYSTEM (Final Version)
 * - Premium Dashboard UI (Card Style)
 * - Auto Coupon Logic
 * - Polylang Ready (ผ่าน polylang-setup.php)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Gustabe_Rewards_System {

    public function __construct() {
        // Settings & Save
        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
        add_action( 'woocommerce_settings_tabs_gustabe_rewards', array( $this, 'settings_tab' ) );
        add_action( 'woocommerce_update_options_gustabe_rewards', array( $this, 'update_settings' ) );

        // Custom UI Renderers
        add_action( 'woocommerce_admin_field_gustabe_card', array( $this, 'render_gustabe_card' ) );
        add_action( 'woocommerce_admin_field_gustabe_switch', array( $this, 'render_gustabe_switch' ) );

        // Logic
        add_action( 'woocommerce_before_cart', array( $this, 'check_rewards_logic' ) );
        add_action( 'woocommerce_before_checkout_form', array( $this, 'check_rewards_logic' ) );

        // Styles
        add_action( 'admin_head', array( $this, 'admin_styles' ) );
    }

    /**
     * 🎨 CSS: Premium UI
     */
    public function admin_styles() {
        if ( isset( $_GET['tab'] ) && $_GET['tab'] === 'gustabe_rewards' ) {
            ?>
            <style>
                .gustabe-settings-wrapper { max-width: 1000px; margin: 30px 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
                
                /* Header Switch */
                .gustabe-header-card {
                    background: #fff; padding: 25px 30px; border-radius: 16px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.04); border: 1px solid #f0f0f0;
                    display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;
                }
                .gh-title h2 { margin: 0; font-size: 20px; color: #222; font-weight: 700; }
                .gh-desc { color: #666; margin-top: 6px; font-size: 14px; }

                /* Switch Toggle */
                .switch { position: relative; display: inline-block; width: 56px; height: 30px; }
                .switch input { opacity: 0; width: 0; height: 0; }
                .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e4e4e4; transition: .4s; border-radius: 34px; }
                .slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
                input:checked + .slider { background-color: #04a39c; }
                input:checked + .slider:before { transform: translateX(26px); }

                /* Grid System */
                .gustabe-card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 25px; }

                /* Card Design */
                .gustabe-tier-card {
                    background: #fff; border-radius: 20px; border: 1px solid #f5f5f5;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.02); overflow: hidden; position: relative;
                    transition: all 0.3s ease;
                }
                .gustabe-tier-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px rgba(0,0,0,0.08); border-color: #04a39c; }

                .gt-header {
                    padding: 20px 25px; background: linear-gradient(to right, #fcfcfc, #fff);
                    border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;
                }
                .gt-badge {
                    background: #222; color: #fff; font-size: 12px; font-weight: 700;
                    padding: 6px 12px; border-radius: 30px; text-transform: uppercase; letter-spacing: 0.5px;
                }
                .gt-icon { font-size: 24px; filter: grayscale(1); opacity: 0.3; }
                .gustabe-tier-card:hover .gt-icon { filter: grayscale(0); opacity: 1; transform: scale(1.1); transition: 0.3s; }

                .gt-body { padding: 25px; }

                /* Inputs */
                .gt-form-group { margin-bottom: 20px; }
                .gt-form-group label { display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; }
                .gt-form-group input, .gt-form-group select {
                    width: 100%; padding: 12px 15px; border: 1px solid #e0e0e0; border-radius: 10px;
                    font-size: 14px; background: #fafafa; transition: 0.2s; color: #333; height: 48px;
                }
                .gt-form-group input:focus, .gt-form-group select:focus {
                    background: #fff; border-color: #04a39c; box-shadow: 0 0 0 4px rgba(4,163,156,0.1); outline: none;
                }
                
                .form-table { display: none !important; }
                .select2-container { width: 100% !important; }
            </style>
            <?php
        }
    }

    /**
     * ดึงรายชื่อคูปอง
     */
    public function get_coupon_options() {
        $coupons = get_posts( array( 'post_type' => 'shop_coupon', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
        $options = array( '' => __( '--- เลือกคูปอง (หรือเว้นว่าง) ---', 'gustabe' ) );
        foreach ( $coupons as $coupon ) {
            $wc_coupon = new WC_Coupon( $coupon->ID );
            $desc = ($wc_coupon->get_discount_type()=='percent') ? $wc_coupon->get_amount().'%' : $wc_coupon->get_amount().' บาท';
            $options[ $coupon->post_title ] = $coupon->post_title . ' (ลด ' . $desc . ')';
        }
        return $options;
    }

    /* ==========================================================
       RENDERERS
       ========================================================== */
    public function render_gustabe_switch( $value ) {
        $option_value = get_option( $value['id'], $value['default'] );
        ?>
        <div class="gustabe-settings-wrapper">
            <div class="gustabe-header-card">
                <div class="gh-title">
                    <h2><?php echo esc_html( $value['title'] ); ?></h2>
                    <div class="gh-desc"><?php echo esc_html( $value['desc'] ); ?></div>
                </div>
                <div class="gh-action">
                    <label class="switch">
                        <input type="checkbox" name="<?php echo esc_attr( $value['id'] ); ?>" value="yes" <?php checked( $option_value, 'yes' ); ?>>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            <div class="gustabe-card-grid">
        <?php
    }

    public function render_gustabe_card( $value ) {
        $i = $value['tier_index'];
        $min = get_option( "gustabe_rewards_tier_{$i}_min" );
        $label = get_option( "gustabe_rewards_tier_{$i}_label" );
        $coupon = get_option( "gustabe_rewards_tier_{$i}_coupon" );
        $options = $this->get_coupon_options();
        ?>
            <div class="gustabe-tier-card">
                <div class="gt-header">
                    <span class="gt-badge"><?php printf( esc_html__( 'Level %d', 'gustabe' ), $i ); ?></span>
                    <span class="gt-icon">🏆</span>
                </div>
                <div class="gt-body">
                    <div class="gt-form-group">
                        <label><?php esc_html_e( 'ยอดซื้อเป้าหมาย', 'gustabe' ); ?></label>
                        <input type="number" name="gustabe_rewards_tier_<?php echo $i; ?>_min" value="<?php echo esc_attr($min); ?>" placeholder="0">
                    </div>
                    <div class="gt-form-group">
                        <label><?php esc_html_e( 'ข้อความรางวัล (เช่น ส่งฟรี)', 'gustabe' ); ?></label>
                        <input type="text" name="gustabe_rewards_tier_<?php echo $i; ?>_label" value="<?php echo esc_attr($label); ?>" placeholder="<?php esc_attr_e('ใส่ข้อความที่นี่...', 'gustabe'); ?>">
                    </div>
                    <div class="gt-form-group">
                        <label><?php esc_html_e( 'คูปองรางวัล', 'gustabe' ); ?></label>
                        <select name="gustabe_rewards_tier_<?php echo $i; ?>_coupon" class="wc-enhanced-select">
                            <?php foreach($options as $k => $v): ?>
                                <option value="<?php echo esc_attr($k); ?>" <?php selected($coupon, $k); ?>><?php echo esc_html($v); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php
        if ( $i == 4 ) echo '</div></div>'; 
    }

    /* ==========================================================
       SETTINGS CONFIG
       ========================================================== */
    public function add_settings_tab( $settings_tabs ) {
        $settings_tabs['gustabe_rewards'] = __( 'Rewards (โปรโมชั่น)', 'gustabe' );
        return $settings_tabs;
    }

    public function settings_tab() {
        woocommerce_admin_fields( $this->get_settings() );
    }

    public function get_settings() {
        $settings = array(
            array(
                'type' => 'gustabe_switch',
                'id'   => 'gustabe_rewards_enabled',
                'title' => __( 'เปิดใช้งานระบบ Rewards', 'gustabe' ),
                'desc' => __( 'แสดงแถบสะสมแต้มและแจกคูปองอัตโนมัติ', 'gustabe' ),
                'default' => 'no'
            )
        );
        for ( $i = 1; $i <= 4; $i++ ) {
            $settings[] = array( 'type' => 'gustabe_card', 'tier_index' => $i, 'id' => 'gustabe_tier_' . $i );
        }
        $settings[] = array( 'type' => 'sectionend', 'id' => 'gustabe_end' );
        return $settings;
    }

    /* ==========================================================
       UPDATE SETTINGS (Clean: บันทึกอย่างเดียว)
       ========================================================== */
    public function update_settings() {
        $enabled = isset($_POST['gustabe_rewards_enabled']) ? 'yes' : 'no';
        update_option( 'gustabe_rewards_enabled', $enabled );

        for ( $i = 1; $i <= 4; $i++ ) {
            if ( isset( $_POST["gustabe_rewards_tier_{$i}_min"] ) ) {
                update_option( "gustabe_rewards_tier_{$i}_min", sanitize_text_field( $_POST["gustabe_rewards_tier_{$i}_min"] ) );
            }
            if ( isset( $_POST["gustabe_rewards_tier_{$i}_coupon"] ) ) {
                update_option( "gustabe_rewards_tier_{$i}_coupon", sanitize_text_field( $_POST["gustabe_rewards_tier_{$i}_coupon"] ) );
            }
            if ( isset( $_POST["gustabe_rewards_tier_{$i}_label"] ) ) {
                update_option( "gustabe_rewards_tier_{$i}_label", sanitize_text_field( $_POST["gustabe_rewards_tier_{$i}_label"] ) );
            }
        }
    }

    /* ==========================================================
       LOGIC (Frontend + Translation)
       ========================================================== */
    public function check_rewards_logic() {
        if ( 'yes' !== get_option( 'gustabe_rewards_enabled' ) ) return;
        if ( is_admin() && ! defined( 'DOING_AJAX' ) ) return;
        if ( ! WC()->cart ) return;

        $tiers = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            $min    = get_option( "gustabe_rewards_tier_{$i}_min" );
            $coupon = get_option( "gustabe_rewards_tier_{$i}_coupon" );
            
            if ( ! empty( $min ) && ! empty( $coupon ) ) {
                $tiers[ $i ] = array(
                    'min'    => (float) $min,
                    'coupon' => strtolower( trim( $coupon ) )
                );
            }
        }

        if ( empty( $tiers ) ) return;
        usort( $tiers, function($a, $b) { return $a['min'] - $b['min']; });

        $cart_total = WC()->cart->get_subtotal(); 
        $winner_coupon = null;
        foreach ( $tiers as $tier ) {
            if ( $cart_total >= $tier['min'] ) {
                $winner_coupon = $tier['coupon'];
            }
        }

        $all_reward_coupons = array_column( $tiers, 'coupon' );
        $applied_coupons = array_map('strtolower', WC()->cart->get_applied_coupons());

        if ( $winner_coupon ) {
            if ( ! in_array( $winner_coupon, $applied_coupons ) ) {
                WC()->cart->add_discount( $winner_coupon );
                
                // แปลข้อความ "ยินดีด้วย" (ถ้าต้องการ)
                $msg = sprintf( __( '🎉 ยินดีด้วย! คุณได้รับสิทธิ์: %s', 'gustabe' ), strtoupper($winner_coupon) );
                wc_print_notice( $msg, 'success' );
            }
            foreach ( $all_reward_coupons as $c ) {
                if ( $c !== $winner_coupon && in_array( $c, $applied_coupons ) ) WC()->cart->remove_coupon( $c );
            }
        } else {
            foreach ( $all_reward_coupons as $c ) {
                if ( in_array( $c, $applied_coupons ) ) WC()->cart->remove_coupon( $c );
            }
        }
    }
}

return new Gustabe_Rewards_System();