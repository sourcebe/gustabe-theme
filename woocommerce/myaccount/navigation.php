<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation bg-slate-900/60 border border-slate-800 rounded-xl overflow-hidden" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
	<ul class="flex flex-col m-0 p-0 list-none">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
			$is_active = wc_is_current_account_menu_item( $endpoint );
		?>
			<li class="m-0 <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" 
				   class="block px-6 py-4 border-b border-slate-800 transition-colors no-underline <?php echo $is_active ? 'bg-slate-800 text-emerald-400 font-semibold border-l-4 border-l-emerald-500' : 'text-slate-400 hover:bg-slate-800/50 hover:text-slate-200 border-l-4 border-l-transparent'; ?>"
				   <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
					<?php echo esc_html( $label ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
