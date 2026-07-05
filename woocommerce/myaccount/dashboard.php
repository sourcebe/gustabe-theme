<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="bg-slate-900/40 border border-slate-800 rounded-2xl shadow-xl backdrop-blur-xl overflow-hidden mb-8 relative font-mono text-sm leading-relaxed">
	<!-- Terminal Header (Mac-like dots) -->
	<div class="bg-slate-950/50 border-b border-slate-800/80 px-4 py-3 flex items-center gap-2">
		<div class="w-3 h-3 rounded-full bg-red-500/20 border border-red-500/50"></div>
		<div class="w-3 h-3 rounded-full bg-yellow-500/20 border border-yellow-500/50"></div>
		<div class="w-3 h-3 rounded-full bg-green-500/20 border border-green-500/50"></div>
		<span class="ml-4 text-xs text-slate-500">root@gustabe:~</span>
	</div>

	<!-- Terminal Body -->
	<div class="p-6 lg:p-8">
		<div class="text-slate-300 mb-8 flex flex-col md:flex-row md:items-center gap-2">
			<span class="text-emerald-500 font-bold">sys.login</span> 
			<span class="text-slate-400">--user</span>
			<?php
			printf(
				/* translators: 1: user display name 2: logout url */
				wp_kses( __( '<strong class="text-white">%1$s</strong> <span class="text-slate-500 ml-2">(not %1$s? <a href="%2$s" class="text-red-400 hover:text-red-300 transition-colors underline">kill_session</a>)</span>', 'woocommerce' ), $allowed_html ),
				esc_html( $current_user->display_name ),
				esc_url( wc_logout_url() )
			);
			?>
		</div>

		<div class="text-slate-400">
			<span class="text-blue-400 font-bold mb-2 block">[SYSTEM_PROMPT] Available endpoints:</span>
			<ul class="list-none m-0 p-0 space-y-4 mt-6">
				<li class="flex items-center gap-4">
					<span class="text-emerald-500/50">$</span>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'orders' ) ); ?>" class="text-slate-300 hover:text-emerald-400 transition-colors group flex items-center gap-2">
						cd <span class="text-blue-400 group-hover:text-emerald-400 transition-colors">/recent-orders</span>
					</a>
				</li>
				<li class="flex items-center gap-4">
					<span class="text-emerald-500/50">$</span>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address' ) ); ?>" class="text-slate-300 hover:text-emerald-400 transition-colors group flex items-center gap-2">
						nano <span class="text-blue-400 group-hover:text-emerald-400 transition-colors">/shipping-billing-address</span>
					</a>
				</li>
				<li class="flex items-center gap-4">
					<span class="text-emerald-500/50">$</span>
					<a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-account' ) ); ?>" class="text-slate-300 hover:text-emerald-400 transition-colors group flex items-center gap-2">
						passwd <span class="text-blue-400 group-hover:text-emerald-400 transition-colors">--update-details</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
