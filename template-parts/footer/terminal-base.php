<?php
/**
 * file: template-parts/footer/terminal-base.php
 * หน้าที่: โครงสร้างหลัก Footer (Desktop & Mobile) สไตล์ Terminal Dark Mode
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. ดึงข้อมูลพื้นฐาน
$site_name = get_bloginfo( 'name' );
$home_url  = function_exists('pll_home_url') ? pll_home_url() : home_url( '/' );

// 2. ดึงข้อมูล Social & Contact (จาก Customizer)
$fb_link   = get_theme_mod( 'footer_social_facebook', '#' );
$ig_link   = get_theme_mod( 'footer_social_instagram', '#' );
$line_link = get_theme_mod( 'footer_social_line_url', '#' );
if( empty($line_link) || $line_link == '#' ) $line_link = $home_url;

$addr  = get_theme_mod('footer_contact_address','Address');
$phone = get_theme_mod('footer_contact_phone','02-XXX-XXXX');
$email = get_theme_mod('footer_contact_email','mail@ex.com');

// 3. Polylang Helper
if (!function_exists('my_pll')) {
    function my_pll($text) { return function_exists('pll__') ? pll__($text) : $text; }
}
?>

<!-- ⚡ โครงสร้าง Tailwind CSS 100% (Zero Bootstrap) -->
<footer class="bg-slate-950/80 backdrop-blur-xl text-slate-400 font-sans border-t border-white/10 pt-16 pb-28 lg:pb-12 selection:bg-blue-500 selection:text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 📱 Desktop & Mobile Grid (1 คอลัมน์บนมือถือ, 4 คอลัมน์บนจอใหญ่) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <!-- Col 1: Brand & Status -->
            <div class="flex flex-col">
                <div class="mb-4 text-2xl font-bold tracking-tighter flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080" class="w-8 h-8 rounded-xl transition-transform duration-300 hover:scale-110" style="filter: drop-shadow(0 0 8px var(--color-logo-shadow));">
                        <path fill="var(--color-logo-primary)" d="M0,0v1080l306.6,0.8c0.1-118,0.4-292.5,1.1-417.7c0-82.2,19.6-155.8,58.8-220.6c39.2-64.8,93.9-115.2,164.1-151.3c53.6-27.5,112.5-44.5,176.8-51.1c3.3-0.3,6.5-0.6,9.8-0.9h105.2h259.5V0H0z M845.8,468.1H716.4c-30.5,8.7-56.6,24-78.1,46c-35.7,36.5-53.5,86.1-53.5,149c0,61.3,17.6,110.6,53,147.8c35.3,37.2,82.4,55.9,141.4,55.9c34.9,0,68.3-6.6,100.1-19.8V641h202.7V468.1h-24.6H845.8z"/>
                        <path fill="var(--color-logo-secondary)" d="M717.2,239.3c-3.3,0.3-6.6,0.6-9.8,0.9c-64.2,6.5-123.2,23.5-176.8,51.1c-70.2,36.1-124.9,86.5-164.1,151.3c-39.2,64.8-58.3,138.3-58.8,220.6c-0.7,125.1-1,299.7-1.1,417.7H684h397.9V641H879.3v206c-31.8,13.2-65.2,19.8-100.1,19.8c-59,0-106.1-18.6-141.4-55.9c-35.3-37.2-53-86.5-53-147.8c0-62.9,17.8-112.5,53.5-149c21.5-22,47.5-37.3,78.1-46h129.4h211.5h24.6V239.3H822.4H717.2z"/>
                    </svg>
                    <span class="text-white"><?php echo esc_html($site_name); ?></span>
                </div>
                <div class="text-xs text-slate-500 mb-6 leading-relaxed border-l border-white/10 pl-3 font-mono">
                    System.AI.ready = true;<br>
                    Connected to global network.
                </div>
                <div class="flex gap-4">
                    <a href="<?php echo esc_url($fb_link); ?>" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-blue-400 hover:-translate-y-1 transition-all duration-300">
                        <i class="huge huge-facebook-02 text-2xl"></i>
                    </a>
                    <a href="<?php echo esc_url($ig_link); ?>" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-blue-400 hover:-translate-y-1 transition-all duration-300">
                        <i class="huge huge-instagram text-2xl"></i>
                    </a>
                    <a href="<?php echo esc_url($line_link); ?>" target="_blank" rel="noopener noreferrer" class="text-slate-500 hover:text-blue-400 hover:-translate-y-1 transition-all duration-300">
                        <i class="huge huge-bubble-chat text-2xl"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h4 class="text-slate-300 text-sm font-semibold mb-6 border-b border-white/10 pb-2">
                    <?php echo esc_html(my_pll('Quick Links')); ?>
                </h4>
                <div class="prose prose-invert prose-sm prose-a:text-slate-500 hover:prose-a:text-blue-400 prose-a:no-underline prose-li:my-2 marker:text-slate-700">
                    <?php wp_nav_menu(['theme_location'=>'footer-quick-links','container'=>false, 'menu_class' => 'list-none pl-0', 'fallback_cb' => false]); ?>
                </div>
            </div>

            <!-- Col 3: Customer Service -->
            <div>
                <h4 class="text-slate-300 text-sm font-semibold mb-6 border-b border-white/10 pb-2">
                    <?php echo esc_html(my_pll('Support')); ?>
                </h4>
                <div class="prose prose-invert prose-sm prose-a:text-slate-500 hover:prose-a:text-blue-400 prose-a:no-underline prose-li:my-2 marker:text-slate-700">
                    <?php wp_nav_menu(['theme_location'=>'footer-customer-service','container'=>false, 'menu_class' => 'list-none pl-0', 'fallback_cb' => false]); ?>
                </div>
            </div>

            <!-- Col 4: Contact Info -->
            <div>
                <h4 class="text-slate-300 text-sm font-semibold mb-6 border-b border-white/10 pb-2">
                    <?php echo esc_html(my_pll('Contact')); ?>
                </h4>
                <div class="flex flex-col gap-5 text-sm text-slate-500">
                    <div class="flex items-start gap-3 group">
                        <i class="huge huge-location-01 text-slate-600 group-hover:text-blue-400 transition-colors text-xl mt-0.5"></i>
                        <span class="leading-relaxed"><?php echo nl2br(esc_html(my_pll($addr))); ?></span>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <i class="huge huge-telephone text-slate-600 group-hover:text-blue-400 transition-colors text-xl"></i>
                        <a href="tel:<?php echo esc_attr($phone); ?>" class="hover:text-blue-400 transition-colors"><?php echo esc_html(my_pll($phone)); ?></a>
                    </div>
                    <div class="flex items-center gap-3 group">
                        <i class="huge huge-mail-02 text-slate-600 group-hover:text-blue-400 transition-colors text-xl"></i>
                        <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-blue-400 transition-colors"><?php echo esc_html(my_pll($email)); ?></a>
                    </div>
                </div>
            </div>

        </div>

        <!-- 🛡️ Copyright Bar -->
        <div class="border-t border-white/10 pt-6 flex flex-col md:flex-row justify-between items-center text-[11px] text-slate-600 font-mono">
            <div class="mb-4 md:mb-0">
                <?php echo esc_html(my_pll(get_theme_mod('footer_copyright_text','© 2024 All Rights Reserved.'))); ?>
            </div>
            <div class="flex items-center gap-1">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500 shadow-[0_0_8px_rgba(59,130,246,0.8)]"></span> API Status: Operational</span>
                <span class="ml-2 border-l border-white/10 pl-3">
                    <?php echo esc_html(my_pll('Developed by')); ?> 
                    <a href="https://gustabe.com/" target="_blank" rel="noopener noreferrer" class="text-blue-500 hover:text-blue-400 font-bold transition-colors">Gustabe</a>
                </span>
            </div>
        </div>

    </div>
</footer>