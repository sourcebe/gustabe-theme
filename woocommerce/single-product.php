<?php
/**
 * The Template for displaying all single products
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' ); ?>

<div class="min-h-screen bg-black pt-32 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        
        <!-- Breadcrumbs Wrapper -->
        <div class="mb-8 bg-slate-900/50 border border-slate-800 rounded-xl px-4 py-3 text-sm font-mono text-slate-400">
            <?php
            /**
             * woocommerce_before_main_content hook.
             *
             * @hooked woocommerce_breadcrumb - 20
             */
            do_action( 'woocommerce_before_main_content' );
            ?>
        </div>

        <!-- Single Product Loop -->
        <?php while ( have_posts() ) : ?>
            <?php the_post(); ?>

            <?php wc_get_template_part( 'content', 'single-product' ); ?>

        <?php endwhile; // end of the loop. ?>

        <?php
        /**
         * woocommerce_after_main_content hook.
         */
        do_action( 'woocommerce_after_main_content' );
        ?>
    </div>
</div>

<?php
get_footer( 'shop' );
