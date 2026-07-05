<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 11.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook - woocommerce_before_edit_account_form.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_before_edit_account_form' );
?>


<div class="bg-slate-900/40 border border-slate-800 rounded-xl p-6 lg:p-10 max-w-3xl">
<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

	<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first m-0">
			<label for="account_first_name" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'First name', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" aria-required="true" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last m-0">
			<label for="account_last_name" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'Last name', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" aria-required="true" />
		</p>
	</div>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-6">
		<label for="account_display_name" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'Display name', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span></label>
		<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" aria-describedby="account_display_name_description" value="<?php echo esc_attr( $user->display_name ); ?>" aria-required="true" />
		<span id="account_display_name_description" class="block text-xs text-slate-500 mt-2"><em><?php echo wc_reviews_enabled() ? esc_html__( 'This will be how your name will be displayed in the account section and in reviews', 'woocommerce' ) : esc_html__( 'This will be how your name will be displayed in the account section', 'woocommerce' ); ?></em></span>
	</p>

	<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-8">
		<label for="account_email" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="text-emerald-500" aria-hidden="true">*</span></label>
		<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" aria-required="true" />
	</p>

	<?php
		/**
		 * Hook where additional fields should be rendered.
		 *
		 * @since 8.7.0
		 */
		do_action( 'woocommerce_edit_account_form_fields' );
	?>

	<fieldset class="border border-slate-800 rounded-lg p-6 bg-slate-900/50 mb-8">
		<legend class="text-lg font-semibold text-white px-3 bg-slate-900/80 rounded border border-slate-800 tracking-wide"><?php esc_html_e( 'Password change', 'woocommerce' ); ?></legend>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-6">
			<label for="password_current" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'Current password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
			<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="current-password" />
		</p>
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0">
				<label for="password_1" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'New password (leave blank to leave unchanged)', 'woocommerce' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="new-password" />
			</p>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide m-0">
				<label for="password_2" class="block text-sm font-medium text-slate-400 mb-2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="new-password" />
			</p>
		</div>
	</fieldset>

	<?php
		/**
		 * My Account edit account form.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_edit_account_form' );
	?>

	<p class="mt-8 text-right">
		<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
		<button type="submit" class="woocommerce-Button button w-full md:w-auto <?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
		<input type="hidden" name="action" value="save_account_details" />
	</p>

	<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
</form>
</div>

<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
