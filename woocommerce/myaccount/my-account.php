<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="flex flex-col lg:flex-row gap-8 bg-slate-900/40 border border-slate-800/80 rounded-2xl p-6 lg:p-10 mb-12 shadow-xl backdrop-blur-xl">
    <div class="w-full lg:w-1/4">
        <?php
        /**
         * My Account navigation.
         *
         * @since 2.6.0
         */
        do_action( 'woocommerce_account_navigation' ); ?>
    </div>

    <div class="w-full lg:w-3/4">
        <div class="woocommerce-MyAccount-content">
            <?php
                /**
                 * My Account content.
                 *
                 * @since 2.6.0
                 */
                do_action( 'woocommerce_account_content' );
            ?>
        </div>
    </div>
</div>
