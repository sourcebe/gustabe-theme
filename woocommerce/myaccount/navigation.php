<?php
/**
 * My Account navigation (Advanced Hide Logic)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// เช็คว่าตอนนี้อยู่ที่หน้า Dashboard หรือเปล่า
$is_dashboard = is_account_page() && !is_wc_endpoint_url();
?>

<?php if ( ! $is_dashboard ) : // ถ้าไม่ใช่หน้า Dashboard ค่อยโชว์เมนูบน ?>
<nav class="gustabe-myaccount-navigation mb-5">
	<ul class="nav nav-pills justify-content-center border-bottom pb-3">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
			<li class="nav-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="nav-link px-4 py-2 small text-dark fw-bold">
                    <?php echo esc_html( function_exists('pll__') ? pll__($label) : $label ); ?>
                </a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php endif; ?>

<style>
    /* บังคับซ่อน Sidebar เดิมของธีมในทุกๆ หน้า My Account */
    .woocommerce-MyAccount-navigation { display: none !important; }
    .woocommerce-MyAccount-content { width: 100% !important; float: none !important; border: none !important; }

    /* ตกแต่งเมนูบนให้ดูแพง */
    .gustabe-myaccount-navigation .nav-link {
        border-radius: 0;
        border-bottom: 2px solid transparent;
        transition: all 0.3s ease;
        opacity: 0.6;
    }
    .gustabe-myaccount-navigation .nav-item.is-active .nav-link {
        background: transparent !important;
        color: #04a39c !important;
        border-bottom-color: #04a39c !important;
        opacity: 1;
    }
    .gustabe-myaccount-navigation .nav-link:hover {
        color: #04a39c;
        opacity: 1;
    }
</style>