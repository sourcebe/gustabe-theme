<?php
/**
 * The template for displaying product content within loops
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( 'group flex flex-col bg-slate-900/40 border border-slate-800/80 rounded-2xl overflow-hidden hover:border-emerald-500/50 hover:shadow-[0_0_20px_rgba(52,211,153,0.1)] transition-all duration-300', $product ); ?>>

    <!-- Product Image (with hover zoom effect) -->
    <a href="<?php echo esc_url( get_permalink() ); ?>" class="relative block overflow-hidden aspect-square bg-slate-800/50">
        <?php
        // Override default image wrapper
        $image_id  = $product->get_image_id();
        $image_url = wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' );
        if ( $image_url ) {
            echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $product->get_name() ) . '" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500 ease-out" />';
        } else {
            echo '<div class="w-full h-full flex items-center justify-center text-slate-600"><i class="huge huge-image-01 text-4xl"></i></div>';
        }
        ?>

        <!-- Badges (On Sale, Out of Stock) -->
        <div class="absolute top-3 left-3 flex flex-col gap-2">
            <?php if ( $product->is_on_sale() ) : ?>
                <span class="bg-emerald-500/90 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md backdrop-blur-md">
                    <?php esc_html_e( 'Sale', 'gustabe' ); ?>
                </span>
            <?php endif; ?>
            <?php if ( ! $product->is_in_stock() ) : ?>
                <span class="bg-red-500/90 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md backdrop-blur-md">
                    <?php esc_html_e( 'Sold Out', 'gustabe' ); ?>
                </span>
            <?php endif; ?>
        </div>
    </a>

    <!-- Product Details -->
    <div class="p-5 flex flex-col flex-1">
        
        <!-- Category -->
        <div class="text-xs text-slate-500 font-mono mb-2 uppercase tracking-wide">
            <?php echo wc_get_product_category_list( $product->get_id(), ', ' ); ?>
        </div>

        <!-- Title -->
        <a href="<?php echo esc_url( get_permalink() ); ?>" class="block mb-2">
            <h2 class="text-lg font-bold text-slate-200 group-hover:text-emerald-400 transition-colors line-clamp-2 leading-snug">
                <?php echo esc_html( $product->get_name() ); ?>
            </h2>
        </a>

        <!-- Star Rating -->
        <?php if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' && $product->get_review_count() > 0 ) : ?>
            <div class="mb-3 text-emerald-500 text-xs flex items-center gap-1">
                <?php
                $rating = $product->get_average_rating();
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $rating ) {
                        echo '<i class="huge huge-star"></i>';
                    } else {
                        echo '<i class="huge huge-star text-slate-700"></i>';
                    }
                }
                ?>
                <span class="text-slate-500 ml-1 font-mono">(<?php echo $product->get_review_count(); ?>)</span>
            </div>
        <?php else: ?>
            <div class="mb-3 h-4"></div> <!-- Spacer if no rating -->
        <?php endif; ?>

        <!-- Price -->
        <div class="mt-auto mb-4 font-mono">
            <?php 
            $price_html = $product->get_price_html();
            // We can replace standard Woo classes with Tailwind if needed, but let's keep it simple
            // Price usually wraps inside <span class="price">
            echo '<div class="text-emerald-400 font-bold text-lg">' . $price_html . '</div>';
            ?>
        </div>

        <!-- Add to Cart Button -->
        <div class="mt-2">
            <?php
            // P1: ใช้ฟังก์ชันของ WooCommerce เพื่อให้แนบ data-product_id, SKU, etc. มาด้วยอย่างถูกต้อง
            woocommerce_template_loop_add_to_cart( array(
                'class' => implode( ' ', array_filter( array(
                    'button',
                    'w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-emerald-500 text-slate-300 hover:text-white text-sm font-semibold py-2.5 px-4 rounded-lg transition-all duration-300',
                    $product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
                    $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
                ) ) )
            ) );
            ?>
        </div>
        
    </div>
</li>
