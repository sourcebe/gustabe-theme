<?php
/**
 * The template for displaying footer.
 * Update: FIXED Mobile Quantity Styling (Capsule Style)
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$site_name = get_bloginfo( 'name' );
$home_url = function_exists('pll_home_url') ? pll_home_url() : home_url( '/' );
$shop_url      = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : $home_url;
$myaccount_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$cart_url      = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : $home_url;
$cart_count    = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$is_product    = class_exists( 'WooCommerce' ) && is_product();

// Social Links
$fb_link = get_theme_mod( 'footer_social_facebook', '#' );
$ig_link = get_theme_mod( 'footer_social_instagram', '#' );
$line_link = get_theme_mod( 'footer_social_line_url', '#' );
if( empty($line_link) || $line_link == '#' ) $line_link = $home_url;

// Product Data
$prod_img = ''; $prod_price = 0; $prod_currency = get_woocommerce_currency_symbol();
$btn_text = 'Buy Now'; $btn_icon = 'huge-shopping-basket-add-01';
$btn_class_extra = ''; $btn_is_disabled = ''; 

if ( $is_product ) {
    global $product;
    $prod_img = wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' );
    $prod_price = $product->get_price();
    if(empty($prod_img)) $prod_img = wc_placeholder_img_src();

    // Check Stock Logic
    if ( $product->is_type( 'variable' ) ) {
        if ( $product->get_stock_status() == 'outofstock' ) {
            $btn_text = 'Sold Out'; $btn_icon = 'huge-unavailable';
            $btn_class_extra = 'status-soldout'; $btn_is_disabled = 'disabled';
        } else {
            $btn_text = 'Select Options'; $btn_icon = 'huge-view';
        }
    } else {
        if ( ! $product->is_in_stock() ) {
            $btn_text = 'Sold Out'; $btn_icon = 'huge-unavailable';
            $btn_class_extra = 'status-soldout'; $btn_is_disabled = 'disabled';
        } elseif ( $product->is_on_backorder( 1 ) ) {
            $btn_text = 'Pre-Order'; $btn_icon = 'huge-time-02'; $btn_class_extra = 'status-preorder';
        }
    }
}

function my_pll($text) { return function_exists('pll__') ? pll__($text) : $text; }
?>



<style>
    /* =========================================
       1. GLOBAL FOOTER RESET & BASE
       ========================================= */
    .site-footer { 
        background-color: #ffffff; /* เปลี่ยนเป็นขาว หรือเทาอ่อน #f9f9f9 ตามชอบ */
        padding-top: 70px; 
        width: 100%; 
        border-top: 1px solid #eee;
        font-size: 15px; /* ขนาดตัวหนังสือมาตรฐาน */
        color: #555;
    }

    /* ล้างค่า List เดิมของ Theme (สำคัญมาก!) */
    .site-footer ul { 
        list-style: none !important; 
        padding-left: 0 !important; 
        margin: 0 !important; 
    }
    .site-footer li { 
        margin-bottom: 10px; 
        line-height: 1.6;
    }
    .site-footer a { 
        text-decoration: none !important; 
        color: inherit; 
        transition: all 0.3s ease;
    }

    /* =========================================
       2. DESKTOP LAYOUT (@media min-width 992px+)
       ครอบคลุมทั้ง Laptop และ PC จอใหญ่
       ========================================= */
    @media (min-width: 992px) {
        
        /* จัดระยะห่างภายใน Container ไม่ให้กว้างเกินไปบนจอใหญ่มาก */
        .site-footer .container-fluid {
            max-width: 1320px;
            margin: 0 auto;
        }

        /* --- คอลัมน์ --- */
        .footer-col-20 { flex: 0 0 20%; max-width: 20%; }
        .footer-col-25 { flex: 0 0 25%; max-width: 25%; }
        .footer-col-30 { flex: 0 0 30%; max-width: 30%; }

        /* --- หัวข้อ (Heading) --- */
        .footer-heading { 
            font-size: 18px;
            font-weight: 700; 
            color: #2c3e50; /* สีเข้มขึ้นให้อ่านง่าย */
            text-transform: uppercase; 
            margin-bottom: 25px; 
            position: relative;
            letter-spacing: 0.5px;
        }
        /* เส้นขีดใต้หัวข้อ (ลูกเล่นเสริม) */
        .footer-heading::after {
            content: '';
            display: block;
            width: 35px;
            height: 3px;
            background-color: #04a39c; /* สีธีม */
            margin-top: 10px;
            border-radius: 2px;
        }

        /* --- ลิงก์เมนู (Quick Links) --- */
        .footer-links a {
            display: inline-block;
            color: #666;
        }
        /* Effect: ชี้แล้วขยับขวา + เปลี่ยนสี */
        .footer-links a:hover {
            color: #04a39c;
            padding-left: 5px; 
        }

        /* --- Social Icons --- */
        .social-icons { 
            display: flex; 
            gap: 12px; 
            margin-top: 20px;
        }
        .social-btn { 
            width: 42px; 
            height: 42px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: #fff; 
            border-radius: 50%; 
            border: 1px solid #e0e0e0; 
            color: #555; 
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .social-btn i { font-size: 20px; }
        /* Effect: ชี้แล้วเด้งขึ้น + สีพื้นเปลี่ยน */
        .social-btn:hover {
            background-color: #04a39c;
            border-color: #04a39c;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(4, 163, 156, 0.3);
        }

        /* --- Contact Info --- */
        .contact-item {
            display: flex;
            align-items: flex-start; /* จัดไอคอนให้ตรงบรรทัดแรก */
            gap: 12px;
            margin-bottom: 15px;
            color: #555;
        }
        .contact-item i.huge {
            color: #04a39c;
            font-size: 20px;
            margin-top: 2px; /* ดันไอคอนลงนิดนึงให้ตรงตัวหนังสือ */
            flex-shrink: 0;
        }
        .contact-item span, 
        .contact-item a {
            font-size: 15px;
            line-height: 1.5;
        }
        .contact-item a:hover {
            text-decoration: underline !important;
            color: #04a39c;
        }
    }

    /* =========================================
       3. COPYRIGHT BAR
       ========================================= */
    .copyright-area {
        border-top: 1px solid #f0f0f0;
        padding: 20px 0;
        margin-top: 40px;
        background-color: #fff;
        font-size: 14px;
        color: #888;
    }
    @media (max-width: 991px) {
        .site-footer { padding-bottom: 90px; } /* เว้นที่ให้ Mobile Menu */
    }

    /* =========================================
       4. MOBILE & STICKY & BOTTOM SHEET (คงเดิมไว้)
       ========================================= */
    .mobile-sticky-bar { position: fixed; bottom: 0; left: 0; width: 100%; height: 65px; background: #fff; box-shadow: 0 -4px 20px rgba(0,0,0,0.1); z-index: 9999; display: flex; border-top: 1px solid #eee; }
    .sticky-item { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #777; text-decoration: none; }
    .sticky-item.active { color: #04a39c; }
    .ps-left { width: 35%; display: flex; }
    .ps-right { width: 65%; }
    .ps-btn-icon { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #555; border-right: 1px solid #f0f0f0; position: relative; }
    .sticky-cart-count { position: absolute; top: 5px; right: 10px; background: #ff4757; color: #fff; font-size: 10px; font-weight: bold; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    
    .ps-buy-btn { width: 100%; height: 100%; border: none; background: linear-gradient(90deg, #04a39c 0%, #03857f 100%); color: #fff; font-size: 18px; font-weight: 800; text-transform: uppercase; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .ps-buy-btn.status-soldout { background: #e0e0e0 !important; color: #999 !important; pointer-events: none; }
    .ps-buy-btn.status-preorder { background: linear-gradient(90deg, #f39c12 0%, #d35400 100%) !important; }

    /* Bottom Sheet */
    .bottom-sheet-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9998; opacity: 0; visibility: hidden; transition: 0.3s; backdrop-filter: blur(2px); }
    .bottom-sheet-panel { position: fixed; bottom: -120%; left: 0; width: 100%; background: #fff; z-index: 9999; border-radius: 24px 24px 0 0; transition: bottom 0.3s cubic-bezier(0.2, 0.8, 0.2, 1); display: flex; flex-direction: column; max-height: 85vh; box-shadow: 0 -10px 40px rgba(0,0,0,0.2); }
    .bottom-sheet-open .bottom-sheet-overlay { opacity: 1; visibility: visible; }
    .bottom-sheet-open .bottom-sheet-panel { bottom: 0; }

    .sheet-header { padding: 15px 20px; border-bottom: 1px solid #f5f5f5; display: flex; gap: 15px; align-items: center; }
    .sheet-thumb { width: 70px; height: 70px; border-radius: 12px; object-fit: cover; border: 1px solid #eee; transition: all 0.3s ease; }
    .sheet-info { flex: 1; }
    .sheet-title { font-size: 14px; margin: 0 0 5px; color: #333; font-weight: 500; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .sheet-price { font-size: 18px; font-weight: 700; color: #04a39c; }
    .sheet-close { font-size: 24px; color: #ccc; padding: 5px; cursor: pointer; }

    .sheet-body { padding: 20px; overflow-y: auto; flex: 1; padding-bottom: 30px; }
    
    .sheet-footer { padding: 15px 20px; border-top: 1px solid #f0f0f0; background: #fff; display: flex; justify-content: space-between; align-items: center; gap: 15px; position: sticky; bottom: 0; z-index: 10; padding-bottom: max(20px, env(safe-area-inset-bottom)); }
    .sheet-total-label { font-size: 12px; color: #888; }
    .sheet-total-price { font-size: 22px; font-weight: 800; color: #04a39c; line-height: 1; }
    
    .sheet-confirm-btn { background-color: #265175 !important; color: #ffffff !important; border: none !important; padding: 0 30px !important; height: 50px !important; border-radius: 12px !important; font-size: 16px !important; font-weight: 700 !important; flex: 1 !important; box-shadow: 0 4px 15px rgba(38, 81, 117, 0.3) !important; cursor: pointer !important; transition: all 0.2s ease !important; outline: none !important; }
    .sheet-confirm-btn:hover { background-color: #1d3e5a !important; color: #ffffff !important; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(38, 81, 117, 0.4) !important; }
    .sheet-confirm-btn:active, .sheet-confirm-btn:focus { background-color: #163045 !important; color: #ffffff !important; transform: translateY(0); box-shadow: none !important; outline: none !important; }

    .bottom-sheet-panel .quantity { display: flex !important; flex-direction: row !important; align-items: center !important; justify-content: space-between !important; width: 120px !important; height: 44px !important; border: 1px solid #e0e0e0 !important; border-radius: 50px !important; background: #fff !important; margin: 0 !important; padding: 0 !important; overflow: hidden !important; }
    .bottom-sheet-panel .quantity label { display: none !important; }
    .bottom-sheet-panel .qty-btn { width: 35px !important; height: 100% !important; border: none !important; background: transparent !important; font-size: 20px !important; color: #555 !important; cursor: pointer !important; display: flex !important; align-items: center !important; justify-content: center !important; padding: 0 !important; margin: 0 !important; }
    .bottom-sheet-panel .qty-btn:active { background: #f5f5f5 !important; }
    .bottom-sheet-panel input.qty { width: 50px !important; height: 100% !important; border: none !important; background: transparent !important; text-align: center !important; font-size: 16px !important; font-weight: 700 !important; color: #333 !important; padding: 0 !important; margin: 0 !important; -moz-appearance: textfield !important; }
    .bottom-sheet-panel input.qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    @media (max-width: 991px) {
        .single-product .product form.cart { display: none !important; }
        .bottom-sheet-panel form.cart { display: block !important; }
        .bottom-sheet-panel button[type="submit"] { display: none !important; }
    }

    /* =========================================
       ★ FIX: FORCED FULL WIDTH FOOTER (Overriding Hello Elementor)
       แก้ปัญหาธีมแม่บังคับความกว้าง 1140px ให้เป็นเต็มจอ 100%
       ========================================= */
    @media (min-width: 1200px) {
        /* บังคับตัวกล่อง Footer ให้กว้างเต็มจอ */
        footer#site-footer.site-footer,
        .site-footer:not(.dynamic-footer) {
            max-width: 100% !important;
            width: 100% !important;
            padding: 5em 1em 0em 1em !important;
        }

        /* จัดการ Container ข้างในให้ยังอยู่กึ่งกลางสวยงาม (ไม่ชิดขอบจอเกินไป) */
        .site-footer .container-fluid {
            width: 100% !important;
            max-width: 1400px !important; /* กำหนดความกว้างเนื้อหาข้างในตามต้องการ */
            margin: 0 auto !important;
            padding-left: 20px !important;
            padding-right: 20px !important;
        }
    }
</style>

<footer id="site-footer" class="site-footer">
    <div class="container-fluid px-4 px-lg-5">
        <div class="footer-desktop d-none d-lg-block">
             <div class="row gx-5">
                <div class="footer-col-20 mb-4">
                    <div class="footer-logo mb-4"><?php if(has_custom_logo()){the_custom_logo();}else{echo '<h2 style="color:#04a39c;">'.esc_html($site_name).'</h2>';} ?></div>
                    <div class="social-icons">
                        <a href="<?php echo esc_url($fb_link); ?>" class="social-btn" target="_blank"><i class="huge huge-facebook-02"></i></a>
                        <a href="<?php echo esc_url($ig_link); ?>" class="social-btn" target="_blank"><i class="huge huge-instagram"></i></a>
                        <a href="<?php echo esc_url($line_link); ?>" class="social-btn" target="_blank"><i class="huge huge-bubble-chat"></i></a>
                    </div>
                </div>
                <div class="footer-col-25 mb-4 ps-lg-4">
                    <h4 class="footer-heading"><?php echo esc_html(my_pll('Quick Links')); ?></h4>
                    <div class="footer-links"><?php wp_nav_menu(['theme_location'=>'footer-quick-links','container'=>false]); ?></div>
                </div>
                <div class="footer-col-25 mb-4">
                    <h4 class="footer-heading"><?php echo esc_html(my_pll('Customer Service')); ?></h4>
                    <div class="footer-links"><?php wp_nav_menu(['theme_location'=>'footer-customer-service','container'=>false]); ?></div>
                </div>
                <div class="footer-col-30 mb-4">
                    <h4 class="footer-heading"><?php echo esc_html(my_pll('Contact Us')); ?></h4>
                    <div class="contact-info">
                        <?php 
                            $addr=get_theme_mod('footer_contact_address','Address');
                            $phone=get_theme_mod('footer_contact_phone','02-XXX-XXXX');
                            $email=get_theme_mod('footer_contact_email','mail@ex.com');
                        ?>
                        <div class="contact-item"><i class="huge huge-location-01"></i><span><?php echo nl2br(esc_html(my_pll($addr))); ?></span></div>
                        <div class="contact-item"><i class="huge huge-telephone"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html(my_pll($phone)); ?></a></div>
                        <div class="contact-item"><i class="huge huge-mail-02"></i><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html(my_pll($email)); ?></a></div>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="footer-mobile d-block d-lg-none mobile-footer-wrapper">
            <div class="mobile-logo mb-3"><?php if(has_custom_logo()){the_custom_logo();} ?></div>
            <div class="footer-accordion-item">
                <div class="footer-accordion-header" onclick="this.parentElement.classList.toggle('active')"><?php echo esc_html(my_pll('Quick Links')); ?></div>
                <div class="footer-accordion-content"><div class="pt-2 pb-3"><?php wp_nav_menu(['theme_location'=>'footer-quick-links','container'=>false]); ?></div></div>
            </div>
            <div class="footer-accordion-item">
                <div class="footer-accordion-header" onclick="this.parentElement.classList.toggle('active')"><?php echo esc_html(my_pll('Customer Service')); ?></div>
                <div class="footer-accordion-content"><div class="pt-2 pb-3"><?php wp_nav_menu(['theme_location'=>'footer-customer-service','container'=>false]); ?></div></div>
            </div>
            <div class="mobile-contact-area">
                <h5 class="mb-3 font-weight-bold"><?php echo esc_html(my_pll('Contact Us')); ?></h5>
                <div class="contact-item"><i class="huge huge-telephone"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html(my_pll($phone)); ?></a></div>
            </div>
        </div>
    </div>
    
    <div class="copyright-area">
        <div class="container-fluid px-4">
             <div class="row">
                <div class="col-6 text-start"><?php echo esc_html(my_pll(get_theme_mod('footer_copyright_text','© 2024 All Rights Reserved.'))); ?></div>
                <div class="col-6 text-end">
                    <span class="footer-credit" style="font-size: 13px; color: #888;">
                        <?php echo esc_html(my_pll('Dev & Design by')); ?> 
                        <a href="https://gustabe.com/" target="_blank" style="color: inherit; font-weight: 600; text-decoration: none;">Gustabe</a>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <?php if ( $is_product ) : global $product; ?>
        
        <div class="mobile-sticky-bar d-lg-none">
            <div class="ps-left">
                <a href="<?php echo esc_url( $line_link ); ?>" class="ps-btn-icon" target="_blank"><i class="huge huge-bubble-chat"></i><span><?php echo esc_html(my_pll('Chat')); ?></span></a>
                <a href="<?php echo esc_url( $cart_url ); ?>" class="ps-btn-icon">
                    <i class="huge huge-shopping-cart-01"></i><span><?php echo esc_html(my_pll('Cart')); ?></span>
                    <?php if ( $cart_count > 0 ) : ?><div class="sticky-cart-count"><?php echo esc_html( $cart_count ); ?></div><?php endif; ?>
                </a>
            </div>
            <div class="ps-right">
                <button id="trigger-bottom-sheet" class="ps-buy-btn <?php echo esc_attr($btn_class_extra); ?>" <?php echo $btn_is_disabled; ?>>
                    <i class="huge <?php echo esc_attr($btn_icon); ?>" style="font-size:22px;"></i><?php echo esc_html(my_pll($btn_text)); ?>
                </button>
            </div>
        </div>

        <div id="product-bottom-sheet" class="d-lg-none" 
             data-currency="<?php echo esc_attr($prod_currency); ?>" 
             data-price="<?php echo esc_attr($prod_price); ?>"
             data-redirect="<?php echo esc_url($cart_url); ?>"> 
             
            <div class="bottom-sheet-overlay" id="sheet-overlay"></div>
            <div class="bottom-sheet-panel">
                <div class="sheet-header">
                    <img src="<?php echo esc_url($prod_img); ?>" class="sheet-thumb">
                    <div class="sheet-info">
                        <h4 class="sheet-title"><?php the_title(); ?></h4>
                        <div class="sheet-price" id="sheet-display-price"><?php echo $product->get_price_html(); ?></div>
                    </div>
                    <div class="sheet-close" id="sheet-close">&times;</div>
                </div>
                <div class="sheet-body" id="sheet-content-area">
                    <div class="text-center" style="margin-bottom:15px; font-weight:700; color:#333; font-size:16px;"><?php echo esc_html(my_pll('Select Options')); ?></div>
                </div>
                <div class="sheet-footer">
                    <div>
                        <div class="sheet-total-label"><?php echo esc_html(my_pll('Total')); ?></div>
                        <div class="sheet-total-price" id="sheet-total-display"><?php echo $product->get_price_html(); ?></div>
                    </div>
                    <button id="sheet-confirm-btn" class="sheet-confirm-btn"><?php echo esc_html(my_pll('Confirm Order')); ?></button>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const triggerBtn = document.getElementById('trigger-bottom-sheet');
            const sheet = document.getElementById('product-bottom-sheet');
            const overlay = document.getElementById('sheet-overlay');
            const closeBtn = document.getElementById('sheet-close');
            const sheetContent = document.getElementById('sheet-content-area');
            const originalForm = document.querySelector('form.cart');
            const sheetThumb = document.querySelector('.sheet-thumb');
            
            let redirectUrl = sheet.getAttribute('data-redirect');
            let currency = sheet.getAttribute('data-currency');
            let basePrice = parseFloat(sheet.getAttribute('data-price')) || 0;
            let currentPrice = basePrice;
            let originalImageSrc = sheetThumb ? sheetThumb.src : '';
            let isBuyNowClicked = false; 

            // Clean Version: ไม่มีการ injectQtyButtons แล้ว (ใช้ของ PC)

            if(originalForm && sheetContent) {
                if(triggerBtn && triggerBtn.hasAttribute('disabled')) return;

                triggerBtn.addEventListener('click', () => {
                    sheetContent.appendChild(originalForm);
                    document.body.classList.add('bottom-sheet-open');
                });
                
                const closeSheet = () => document.body.classList.remove('bottom-sheet-open');
                overlay.addEventListener('click', closeSheet);
                closeBtn.addEventListener('click', closeSheet);

                document.getElementById('sheet-confirm-btn').addEventListener('click', () => {
                    const realBtn = originalForm.querySelector('button[type="submit"]');
                    if(realBtn) { isBuyNowClicked = true; realBtn.click(); }
                });

                const totalDisplay = document.getElementById('sheet-total-display');
                const priceDisplay = document.getElementById('sheet-display-price');

                function updateTotal() {
                    const qtyInput = originalForm.querySelector('input.qty');
                    let qty = qtyInput ? parseInt(qtyInput.value) : 1;
                    if(isNaN(qty) || qty < 1) qty = 1;
                    
                    let total = currentPrice * qty;
                    let formattedTotal = currency + total.toLocaleString('en-US', {minimumFractionDigits: 0});
                    if(totalDisplay) totalDisplay.innerHTML = formattedTotal;
                }

                if(typeof jQuery !== 'undefined') {
                    jQuery(document).on('change input', 'input.qty', function() { updateTotal(); });
                    
                    jQuery('.variations_form').on('found_variation', function(event, variation) {
                        currentPrice = variation.display_price;
                        if(priceDisplay) priceDisplay.innerHTML = variation.price_html;
                        if(variation.image && variation.image.src) {
                            if(sheetThumb) sheetThumb.src = variation.image.src;
                        }
                        updateTotal();
                    });
                    
                    jQuery('.variations_form').on('reset_data', function() {
                        currentPrice = basePrice;
                        if(sheetThumb) sheetThumb.src = originalImageSrc;
                        updateTotal();
                    });

                    jQuery(document.body).on('added_to_cart', function() {
                        if (isBuyNowClicked) window.location.href = redirectUrl;
                    });
                }
            }
        });
        </script>

    <?php else : // APP DOCK ?>
        <div class="mobile-sticky-bar d-lg-none">
            <a href="<?php echo esc_url($home_url); ?>" class="sticky-item <?php echo is_front_page()?'active':''; ?>"><i class="huge huge-home-01"></i><span><?php echo esc_html(my_pll('Home')); ?></span></a>
            <a href="<?php echo esc_url($shop_url); ?>" class="sticky-item <?php echo is_shop()?'active':''; ?>"><i class="huge huge-store-01"></i><span><?php echo esc_html(my_pll('Shop')); ?></span></a>
            <a href="<?php echo esc_url($cart_url); ?>" class="sticky-item <?php echo is_cart()?'active':''; ?>"><i class="huge huge-shopping-cart-01"></i><span><?php echo esc_html(my_pll('Cart')); ?></span><?php if($cart_count>0):?><div class="sticky-cart-count"><?php echo esc_html($cart_count);?></div><?php endif;?></a>
            <a href="<?php echo esc_url($line_link); ?>" class="sticky-item" target="_blank"><i class="huge huge-bubble-chat"></i><span><?php echo esc_html(my_pll('Line')); ?></span></a>
            <a href="<?php echo esc_url($myaccount_url); ?>" class="sticky-item <?php echo is_account_page()?'active':''; ?>"><i class="huge huge-user-circle"></i><span><?php echo esc_html(my_pll('Account')); ?></span></a>
        </div>
    <?php endif; ?>
</footer>