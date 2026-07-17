<?php
/**
 * theme name: gustabe
 * dir: template-parts/
 * file: gustabe-header.php
 * Concept: The Dark IDE (VS Code Vibe) - Refined
 */
if ( ! defined( 'ABSPATH' ) ) exit;

$site_name = get_bloginfo( 'name' );
$announcement_text = get_theme_mod( 'announcement_text', 'system.log("ให้บริการ 24 ชั่วโมง");' );
?>

<script>
    (function() {
        try {
            var theme = localStorage.getItem('gustabeTheme') || 'normal';
            document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {}
    })();
</script>

<header id="site-header" class="w-full bg-slate-950/80 backdrop-blur-xl text-slate-300 border-b border-white/10 sticky top-0 z-50 transition-all duration-300 ease-in-out font-sans">
    
    <!-- Top Announcement Bar (AI Processing Vibe) -->
    <div class="bg-blue-600/10 border-b border-blue-500/20 text-[11px] font-mono tracking-widest hidden md:block">
        <div class="max-w-7xl mx-auto px-6 py-1.5 flex justify-between items-center">
            
            <div class="flex items-center gap-2 text-blue-400">
                <div class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-pulse shadow-[0_0_8px_rgba(96,165,250,0.8)]"></div>
                <span class="select-none text-blue-300/80">
                    <?php echo esc_html( function_exists('pll__') ? pll__( $announcement_text ) : $announcement_text ); ?>
                </span>
            </div>

            <div class="flex items-center gap-4 text-slate-500">
                <?php if ( shortcode_exists( 'gustab_currency_switcher' ) ) : ?>
                    <div class="hover:text-blue-400 transition-colors cursor-pointer select-none flex items-center gap-1">
                        <i class="huge huge-bitcoin-circle"></i> <?php echo do_shortcode( '[gustab_currency_switcher]' ); ?>
                    </div>
                <?php endif; ?>

                <?php if ( function_exists( 'pll_the_languages' ) ) : ?>
                    <div class="flex gap-2 select-none items-center">
                        <i class="huge huge-global"></i> 
                        <ul class="flex gap-2 m-0 p-0 list-none text-slate-400">
                            <?php pll_the_languages( [ 'show_flags' => 0, 'show_names' => 1, 'display_names_as' => 'slug' ] ); ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="max-w-7xl mx-auto px-4 md:px-6 h-16 flex justify-between items-center">
        
        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex items-center gap-3 group no-underline" aria-label="Go to homepage">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080" class="w-10 h-10 rounded-2xl transition-transform duration-300 group-hover:scale-110" style="filter: drop-shadow(0 0 8px var(--color-logo-shadow));">
                <path fill="var(--color-logo-primary)" d="M0,0v1080l306.6,0.8c0.1-118,0.4-292.5,1.1-417.7c0-82.2,19.6-155.8,58.8-220.6c39.2-64.8,93.9-115.2,164.1-151.3c53.6-27.5,112.5-44.5,176.8-51.1c3.3-0.3,6.5-0.6,9.8-0.9h105.2h259.5V0H0z M845.8,468.1H716.4c-30.5,8.7-56.6,24-78.1,46c-35.7,36.5-53.5,86.1-53.5,149c0,61.3,17.6,110.6,53,147.8c35.3,37.2,82.4,55.9,141.4,55.9c34.9,0,68.3-6.6,100.1-19.8V641h202.7V468.1h-24.6H845.8z"/>
                <path fill="var(--color-logo-secondary)" d="M717.2,239.3c-3.3,0.3-6.6,0.6-9.8,0.9c-64.2,6.5-123.2,23.5-176.8,51.1c-70.2,36.1-124.9,86.5-164.1,151.3c-39.2,64.8-58.3,138.3-58.8,220.6c-0.7,125.1-1,299.7-1.1,417.7H684h397.9V641H879.3v206c-31.8,13.2-65.2,19.8-100.1,19.8c-59,0-106.1-18.6-141.4-55.9c-35.3-37.2-53-86.5-53-147.8c0-62.9,17.8-112.5,53.5-149c21.5-22,47.5-37.3,78.1-46h129.4h211.5h24.6V239.3H822.4H717.2z"/>
            </svg>
            <span class="text-xl font-bold text-white tracking-tight group-hover:text-red-400 transition-colors font-mono">
                <?php echo esc_html( $site_name ); ?>
            </span>
        </a>

        <!-- Tailwind Menu Styler -->
        <nav class="hidden lg:block">
            <?php 
            wp_nav_menu([
                'theme_location' => 'menu-1',
                'container'      => false,
                'menu_class'     => 'flex gap-6 m-0 p-0 list-none text-[14px] font-medium items-center text-slate-400 [&_a]:no-underline [&_a]:transition-colors hover:[&_a]:text-white',
                'fallback_cb'    => false, // ป้องกันการดึงหน้าทั้งหมดมาแสดงอัตโนมัติ
            ]); 
            ?>
        </nav>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-4">
            
            <!-- Jules/GitHub Style Search -->
            <button id="open-search-btn" aria-label="Search" class="hidden md:flex items-center gap-3 bg-white/5 border border-white/10 hover:border-blue-500/50 hover:bg-white/10 px-3 py-1.5 rounded-full text-sm text-slate-400 transition-all group w-48 lg:w-64">
                <i class="huge huge-search-02 text-slate-500 group-hover:text-blue-400 transition-colors"></i>
                <span class="group-hover:text-slate-200">Ask Jules...</span>
                <span class="ml-auto bg-black/30 border border-white/10 text-slate-500 text-[10px] px-1.5 py-0.5 rounded font-mono">⌘K</span>
            </button>

            <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="Cart" class="relative flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/10 text-slate-400 hover:text-white transition-colors group no-underline">
                    <i class="huge huge-shopping-cart-01 text-lg group-hover:text-blue-400 transition-colors"></i>
                    <?php if (WC()->cart && WC()->cart->get_cart_contents_count() > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-blue-500 text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center rounded-full shadow-[0_0_10px_rgba(59,130,246,0.6)]">
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" aria-label="Account" class="flex items-center justify-center w-8 h-8 rounded-full hover:bg-white/10 text-slate-400 hover:text-white transition-colors no-underline">
                <i class="huge huge-user-circle text-xl hover:text-blue-400 transition-colors"></i>
            </a>

            <button id="open-mobile-menu" aria-label="Menu" aria-expanded="false" class="lg:hidden text-slate-400 focus:outline-none hover:text-white transition-colors p-1">
                <i class="huge huge-menu-01 text-2xl"></i>
            </button>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[60] hidden opacity-0 transition-opacity duration-300" aria-hidden="true"></div>

<!-- Mobile Menu Panel -->
<div id="mobile-menu" class="fixed top-0 right-0 h-full w-4/5 max-w-[320px] bg-slate-900 border-l border-white/10 z-[9999] transform translate-x-full transition-transform duration-500 font-sans flex flex-col shadow-2xl" role="dialog" aria-modal="true" aria-label="Mobile Navigation">
    <div class="p-6 border-b border-white/10 flex justify-between items-center bg-black/20">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white">
                <i class="huge huge-code text-sm"></i>
            </div>
            <span class="text-white font-semibold text-sm">Menu</span>
        </div>
        <button id="close-mobile-menu" aria-label="Close Mobile Menu" class="text-slate-500 hover:text-white transition-colors w-8 h-8 rounded hover:bg-white/10 flex items-center justify-center focus:outline-none">
            <i class="huge huge-cancel"></i>
        </button>
    </div>
    
    <div class="p-6 overflow-y-auto flex-grow space-y-8 [&_a]:no-underline [&_a]:transition-colors hover:[&_a]:text-white">
        <div class="space-y-4">
            <span class="text-slate-500 text-[10px] block uppercase tracking-widest border-b border-white/5 pb-2 font-mono">Navigation</span>
            <?php 
            wp_nav_menu([ 
                'theme_location' => 'menu-1', 
                'menu_class' => 'flex flex-col gap-6 list-none p-0 m-0 text-base font-medium text-slate-300 text-inherit',
                'fallback_cb' => false,
            ]); 
            ?>
        </div>
    </div>

    <!-- The Jules Command Line (Mobile Search Trigger) -->
    <div class="p-6 border-t border-white/10 bg-black/20">
        <button id="open-search-mobile" aria-label="Open Search Command Palette" class="w-full text-left focus:outline-none group active:scale-[0.98] transition-transform">
            <div class="flex items-center bg-black/50 border border-white/10 rounded-full py-3 px-4 transition-all duration-300 group-hover:border-blue-500/50 group-hover:shadow-[0_0_15px_rgba(59,130,246,0.15)] group-hover:bg-black">
                <i class="huge huge-sparkles text-blue-500 mr-2 text-lg"></i>
                <span class="text-slate-400 text-sm group-hover:text-slate-200 transition-colors">
                    Ask Jules...<span class="text-blue-500 animate-pulse ml-0.5 font-bold">|</span>
                </span>
            </div>
        </button>
    </div>
</div>