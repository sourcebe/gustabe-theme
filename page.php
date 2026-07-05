<?php get_header(); ?>
<?php 
// Check if it's a WooCommerce page
$is_woo_page = ( function_exists('is_cart') && is_cart() ) || ( function_exists('is_checkout') && is_checkout() ) || ( function_exists('is_account_page') && is_account_page() );
$container_class = $is_woo_page ? 'max-w-7xl' : 'max-w-4xl';

// Dynamic Title & Icon Logic
$page_title = get_the_title();
$page_icon = 'huge-shopping-cart-01'; // Default woo icon
$icon_color = 'text-emerald-400';

if ( function_exists('is_account_page') && is_account_page() ) {
    if ( ! is_user_logged_in() ) {
        $page_title = __( 'เข้าสู่ระบบ / สมัครสมาชิก', 'gustabe' );
        $page_icon = 'huge-user-circle';
        $icon_color = 'text-blue-400';
    } else {
        $page_icon = 'huge-user-status';
    }
} elseif ( function_exists('is_cart') && is_cart() ) {
    $page_icon = 'huge-shopping-cart-01';
} elseif ( function_exists('is_checkout') && is_checkout() ) {
    $page_icon = 'huge-wallet-02';
}
?>
<div class="<?php echo esc_attr($container_class); ?> mx-auto pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <?php while ( have_posts() ) : the_post(); ?>
        <h1 class="text-4xl md:text-5xl font-sans font-bold text-white tracking-tight mb-8 border-b border-slate-800 pb-8 flex items-center gap-3">
            <?php if ( $is_woo_page ) : ?><i class="huge <?php echo esc_attr($page_icon . ' ' . $icon_color); ?>"></i><?php endif; ?>
            <?php echo esc_html( $page_title ); ?>
        </h1>
        <div class="<?php echo $is_woo_page ? 'w-full' : 'prose prose-invert prose-emerald max-w-none'; ?>">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
