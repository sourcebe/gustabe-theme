<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="min-h-screen bg-black pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Shop Header -->
        <header class="mb-12 border-b border-slate-800/50 pb-8">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="text-4xl md:text-5xl font-sans font-bold text-white tracking-tight mb-4 flex items-center gap-3">
                    <i class="huge huge-store-01 text-emerald-400"></i>
                    <?php woocommerce_page_title(); ?>
                </h1>
            <?php endif; ?>

            <div class="text-slate-400 font-mono text-sm max-w-2xl">
                <?php do_action( 'woocommerce_archive_description' ); ?>
            </div>
        </header>

        <!-- Main Content -->
        <?php if ( woocommerce_product_loop() ) : ?>
            
            <!-- Toolbar (Sorting & Results) -->
            <div class="flex flex-col sm:flex-row justify-between items-center bg-slate-900/50 border border-slate-800/80 rounded-xl p-4 mb-8 backdrop-blur-xl">
                <div class="text-slate-400 text-sm font-mono w-full flex flex-col sm:flex-row justify-between items-center gap-4">
                    <?php 
                    // Let WooCommerce render result count and sorting
                    do_action( 'woocommerce_before_shop_loop' ); 
                    ?>
                </div>
            </div>

            <!-- The Grid -->
            <?php woocommerce_product_loop_start(); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                if ( wc_get_loop_prop( 'total' ) ) {
                    while ( have_posts() ) {
                        the_post();
                        
                        /**
                         * Hook: woocommerce_shop_loop.
                         */
                        do_action( 'woocommerce_shop_loop' );

                        wc_get_template_part( 'content', 'product' );
                    }
                }
                ?>
            </div>
            <?php woocommerce_product_loop_end(); ?>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <?php do_action( 'woocommerce_after_shop_loop' ); ?>
            </div>

        <?php else : ?>
            
            <!-- No Products Found -->
            <div class="bg-slate-900/50 border border-slate-800 rounded-2xl p-12 text-center">
                <i class="huge huge-search-minus text-6xl text-slate-600 mb-4 inline-block"></i>
                <h2 class="text-2xl font-bold text-white mb-2"><?php esc_html_e( 'No products found', 'gustabe' ); ?></h2>
                <div class="text-slate-400 font-mono text-sm">
                    <?php do_action( 'woocommerce_no_products_found' ); ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php
get_footer( 'shop' );
