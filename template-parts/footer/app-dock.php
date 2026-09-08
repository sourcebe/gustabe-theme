<?php
/**
 * file: template-parts/footer/app-dock.php
 * หน้าที่: แถบเมนูด้านล่าง (Mobile App Dock) สไตล์ VS Code Status Bar
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. ดึง URL ของแต่ละระบบย่อย (รองรับ WooCommerce และ Polylang)
$home_url      = function_exists('pll_home_url') ? pll_home_url() : home_url( '/' );
$shop_url      = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : $home_url;
$myaccount_url = class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
$cart_url      = class_exists( 'WooCommerce' ) ? wc_get_cart_url() : $home_url;
$line_link     = gustabe_get_line_url();

// 2. ดึงจำนวนสินค้าในตะกร้า
$cart_count = ( class_exists( 'WooCommerce' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;

// 3. Logic เช็คหน้าปัจจุบัน (Active State)
$is_home    = is_front_page();
$is_shop    = function_exists('is_shop') && is_shop();
$is_cart    = function_exists('is_cart') && is_cart();
$is_account = function_exists('is_account_page') && is_account_page();
?>

<!-- ⚡ Mobile App Dock (แสดงเฉพาะ lg ลงมา) -->
<div class="fixed bottom-0 left-0 w-full h-[65px] bg-slate-950/80 backdrop-blur-xl border-t border-white/10 z-[9000] flex font-sans lg:hidden shadow-[0_-5px_20px_rgba(0,0,0,0.5)]">

    <!-- 1. ปุ่ม Home -->
    <a href="<?php echo esc_url($home_url); ?>" class="flex-1 flex flex-col items-center justify-center transition-colors <?php echo $is_home ? 'text-blue-400' : 'text-slate-400 hover:text-blue-300 hover:bg-white/5'; ?>">
        <i class="huge huge-home-01 text-[22px] mb-1"></i>
        <span class="text-[9px] tracking-widest font-medium uppercase"><?php echo esc_html(my_pll('Home')); ?></span>
    </a>

    <!-- 2. ปุ่ม Shop -->
    <a href="<?php echo esc_url($shop_url); ?>" class="flex-1 flex flex-col items-center justify-center transition-colors border-l border-white/5 <?php echo $is_shop ? 'text-blue-400' : 'text-slate-400 hover:text-blue-300 hover:bg-white/5'; ?>">
        <i class="huge huge-store-01 text-[22px] mb-1"></i>
        <span class="text-[9px] tracking-widest font-medium uppercase"><?php echo esc_html(my_pll('Shop')); ?></span>
    </a>

    <!-- 3. ปุ่ม Cart (พร้อม Badge แจ้งเตือนสไตล์ Terminal) -->
    <a href="<?php echo esc_url($cart_url); ?>" class="flex-1 flex flex-col items-center justify-center transition-colors border-l border-white/5 <?php echo $is_cart ? 'text-blue-400' : 'text-slate-400 hover:text-blue-300 hover:bg-white/5'; ?>">
        <div class="relative">
            <i class="huge huge-shopping-cart-01 text-[22px] mb-1"></i>
            <?php if ( $cart_count > 0 ) : ?>
                <!-- ตัวเลขตะกร้า (Cart Badge) -->
                <div class="absolute -top-1.5 -right-3 bg-blue-500 border border-blue-400 text-white text-[9px] px-1.5 py-0.5 rounded-full font-bold shadow-[0_0_8px_rgba(59,130,246,0.6)]">
                    <?php echo esc_html($cart_count); ?>
                </div>
            <?php endif; ?>
        </div>
        <span class="text-[9px] tracking-widest font-medium uppercase"><?php echo esc_html(my_pll('Cart')); ?></span>
    </a>

    <!-- 4. ปุ่ม Chat (Line) -->
    <a href="<?php echo esc_url($line_link); ?>" target="_blank" rel="noopener noreferrer" class="flex-1 flex flex-col items-center justify-center text-slate-400 hover:text-blue-300 hover:bg-white/5 transition-colors border-l border-white/5">
        <i class="huge huge-bubble-chat text-[22px] mb-1"></i>
        <span class="text-[9px] tracking-widest font-medium uppercase"><?php echo esc_html(my_pll('Chat')); ?></span>
    </a>

    <!-- 5. ปุ่ม Account -->
    <a href="<?php echo esc_url($myaccount_url); ?>" class="flex-1 flex flex-col items-center justify-center transition-colors border-l border-white/5 <?php echo $is_account ? 'text-blue-400' : 'text-slate-400 hover:text-blue-300 hover:bg-white/5'; ?>">
        <i class="huge huge-user-circle text-[22px] mb-1"></i>
        <span class="text-[9px] tracking-widest font-medium uppercase"><?php echo esc_html(my_pll('Account')); ?></span>
    </a>

</div>