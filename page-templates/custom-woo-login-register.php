<?php
/**
 * Template Name: Custom WooCommerce Login/Register
 * Description: แก้ไข Register ซ้ำซ้อน + จัด CSS ให้หัวข้อเท่ากัน
 */

if ( is_user_logged_in() && ! is_admin() ) {
    $my_account_id = get_option( 'woocommerce_myaccount_page_id' );
    if ( function_exists( 'pll_get_post' ) ) {
        $trans_id = pll_get_post( $my_account_id );
        if ( $trans_id ) $my_account_id = $trans_id;
    }
    wp_redirect( get_permalink( $my_account_id ) );
    exit;
}

get_header(); 
?>

<div id="primary" class="content-area custom-login-register-page">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="entry-content">
                <style>
                    /* --- CSS Layout --- */
                    .custom-login-register-page { background-color: #f4f6f8; min-height: 60vh; display: flex; align-items: center; }
                    .forms-container { width: 100%; max-width: 1100px; margin: 60px auto; padding: 0 20px; box-sizing: border-box; }

                    /* Desktop */
                    @media (min-width: 851px) {
                        .forms-container-inner { display: flex; flex-wrap: wrap; justify-content: center; align-items: flex-start; gap: 40px; }
                        .login-form-wrapper, .register-form-wrapper { 
                            flex: 1; min-width: 380px; background: #ffffff; 
                            padding: 40px; border-radius: 12px; 
                            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); border: 1px solid #fff; 
                        }
                        .tab-buttons, .tab-content-mobile { display: none; }
                    }

                    /* Mobile */
                    @media (max-width: 850px) {
                        .custom-login-register-page { background-color: #fff; display: block; }
                        .forms-container { padding: 10px; margin: 20px auto; }
                        .forms-container-inner { display: flex; flex-direction: column; align-items: center; }
                        .login-form-wrapper, .register-form-wrapper { display: none; }
                        
                        /* Tabs */
                        .tab-buttons { display: flex; width: 100%; max-width: 450px; margin-bottom: 0; }
                        .tab-button { flex: 1; padding: 15px; text-align: center; cursor: pointer; background-color: #f1f1f1; border: none; font-size: 16px; font-weight: 600; color: #888; transition: all 0.3s; }
                        .tab-button:first-child { border-radius: 10px 0 0 0; }
                        .tab-button:last-child { border-radius: 0 10px 0 0; }
                        .tab-button.active { background-color: #ffffff; color: #04a39c; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); position: relative; z-index: 2; }
                        .tab-content-mobile { display: block; width: 100%; max-width: 450px; background: #ffffff; padding: 30px 20px; border-radius: 0 0 10px 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); border: 1px solid #f1f1f1; border-top: none; position: relative; z-index: 1; }
                        .tab-pane { display: none; }
                        .tab-pane.active { display: block; animation: fadeIn 0.3s ease; }
                    }
                    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

                    /* Typography (บังคับให้ H2 ทั้งของเรา และของ Woo หน้าตาเหมือนกัน) */
                    .forms-container h2, 
                    .register-form-wrapper h2 { 
                        text-align: center; 
                        margin-bottom: 30px; 
                        color: #333; 
                        font-size: 26px; 
                        font-weight: 700;
                        line-height: 1.2;
                    }

                    /* ปุ่มและ Input */
                    .forms-container .woocommerce-form .button { background-color: #04a39c; color: #fff; border: none; padding: 15px; border-radius: 50px; cursor: pointer; font-size: 16px; font-weight: 600; width: 100%; transition: background 0.3s; }
                    .forms-container .woocommerce-form .button:hover { background-color: #03857f; }
                    .forms-container .woocommerce-form-row label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
                    .forms-container .woocommerce-form-row input.input-text { width: 100%; padding: 12px 15px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; background: #fdfdfd; }
                    .forms-container .woocommerce-form-row input.input-text:focus { border-color: #04a39c; outline: none; background: #fff; }
                </style>

                <div class="forms-container">
                    <?php wc_print_notices(); ?>

                    <div class="forms-container-inner">
                        
                        <div class="login-form-wrapper">
                            <h2><?php echo esc_html( function_exists('pll__') ? pll__('Login') : 'Login' ); ?></h2>
                            <?php if ( function_exists( 'woocommerce_login_form' ) ) { woocommerce_login_form(); } ?>
                        </div>
                        
                        <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                            <div class="register-form-wrapper">
                                <?php if ( function_exists( 'woocommerce_get_template' ) ) { wc_get_template( 'myaccount/form-registration.php' ); } ?>
                            </div>
                        <?php endif; ?>

                        <div class="tab-buttons">
                            <button class="tab-button active" data-tab="login-mobile">
                                <?php echo esc_html( function_exists('pll__') ? pll__('Login') : 'Login' ); ?>
                            </button>
                            <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                                <button class="tab-button" data-tab="register-mobile">
                                    <?php echo esc_html( function_exists('pll__') ? pll__('Register') : 'Register' ); ?>
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="tab-content-mobile">
                            <div id="login-mobile" class="tab-pane active">
                                 <?php if ( function_exists( 'woocommerce_login_form' ) ) { woocommerce_login_form(); } ?>
                            </div>
                            <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                                <div id="register-mobile" class="tab-pane">
                                    <style>#register-mobile h2 { display: none; }</style>
                                    <?php if ( function_exists( 'woocommerce_get_template' ) ) { wc_get_template( 'myaccount/form-registration.php' ); } ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            </div>
        </article>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    if (tabButtons.length > 0 && tabPanes.length > 0) {
        const pageHasError = document.querySelector('.woocommerce-notices-wrapper .woocommerce-error');
        const registrationFormHasError = document.querySelector('#register-mobile .woocommerce-invalid');

        if (pageHasError || registrationFormHasError) {
            document.querySelector('.tab-button[data-tab="login-mobile"]').classList.remove('active');
            document.querySelector('#login-mobile').classList.remove('active');
            document.querySelector('.tab-button[data-tab="register-mobile"]').classList.add('active');
            document.querySelector('#register-mobile').classList.add('active');
        }

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(targetTab).classList.add('active');
            });
        });
    }
});
</script>

<?php get_footer(); ?>