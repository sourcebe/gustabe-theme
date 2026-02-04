<?php
/**
 * The template for displaying product content in the single-product.php template
 * UPDATE: Fix Sticky Overflow by separating sections.
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'custom-product-layout', $product ); ?>>

    <div class="product-top-section">
        
        <div class="product-gallery-wrapper">
            <?php do_action( 'woocommerce_before_single_product_summary' ); ?>
        </div>

        <div class="summary entry-summary product-info-wrapper">
            <?php do_action( 'woocommerce_single_product_summary' ); ?>
        </div>
        
    </div> 
    <div class="product-tabs-wrapper">
        <?php do_action( 'woocommerce_after_single_product_summary' ); ?>
    </div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>