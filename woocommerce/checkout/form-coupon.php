<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
}

?>
<div class="woocommerce-form-coupon-toggle bg-black/40 border border-emerald-500/50 p-4 rounded-xl mb-8 flex items-center gap-3 font-mono text-slate-300 shadow-lg">
	<i class="huge huge-ticket-01 text-emerald-400 text-2xl"></i>
	<span class="text-sm">
		<span class="text-emerald-400 font-bold">[!] SYSTEM ALERT:</span> <?php esc_html_e( 'Have a coupon?', 'woocommerce' ); ?> 
		<a href="#" class="showcoupon text-emerald-400 hover:text-emerald-300 hover:underline transition-colors ml-2 font-bold tracking-widest uppercase">
			[ CLICK TO ENTER ]
		</a>
	</span>
</div>

<form class="checkout_coupon woocommerce-form-coupon bg-slate-900/60 border border-slate-700 p-6 rounded-xl mb-8 font-mono shadow-xl" method="post" style="display:none" id="woocommerce-checkout-form-coupon">

	<p class="mb-4 text-emerald-400 font-bold uppercase tracking-widest text-sm">> ENTER_COUPON_CODE_</p>

	<div class="flex flex-col sm:flex-row gap-4 items-stretch">
		<p class="form-row form-row-first w-full sm:w-2/3 m-0">
			<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label>
			<input type="text" name="coupon_code" class="input-text w-full h-full bg-black/50 border border-slate-600 text-emerald-400 font-mono px-4 py-3 focus:border-emerald-400 focus:ring-0 rounded-none outline-none transition-all" placeholder="Enter code here..." id="coupon_code" value="" />
		</p>

		<p class="form-row form-row-last w-full sm:w-1/3 m-0">
			<button type="submit" class="button w-full h-full bg-emerald-500/10 border border-emerald-500 text-emerald-400 hover:bg-emerald-500 hover:text-black font-bold uppercase tracking-widest transition-all px-4 py-3" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">[ EXECUTE ]</button>
		</p>
	</div>

	<div class="clear"></div>
</form>
