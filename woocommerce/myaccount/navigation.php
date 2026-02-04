<?php
/**
 * My Account navigation (DevGustabe Pro Version - With Icons)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// เช็คว่าตอนนี้อยู่ที่หน้า Dashboard หรือเปล่า
$is_dashboard = is_account_page() && !is_wc_endpoint_url();

// Mapping ไอคอนให้ตรงกับเมนู (ใช้ Huge Icons)
$menu_icons = [
    'dashboard'       => 'huge-dashboard-square-01',
    'orders'          => 'huge-shopping-bag-01',
    'downloads'       => 'huge-download-circle-01',
    'edit-address'    => 'huge-location-01',
    'edit-account'    => 'huge-user-circle',
    'customer-logout' => 'huge-logout-03',
    'payment-methods' => 'huge-credit-card',
];
?>

<?php if ( ! $is_dashboard ) : // ถ้าไม่ใช่หน้า Dashboard ค่อยโชว์เมนูบน ?>
<nav class="gustabe-myaccount-navigation mb-4">
	<ul class="nav nav-pills gustabe-nav-scroll">
		<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : 
            // หาไอคอน ถ้าไม่มีให้ใช้รูปดาวเป็นค่าเริ่มต้น
            $icon_class = isset($menu_icons[$endpoint]) ? $menu_icons[$endpoint] : 'huge-star';
        ?>
			<li class="nav-item <?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
				<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="nav-link">
                    <i class="huge <?php echo esc_attr( $icon_class ); ?>"></i>
                    <span><?php echo esc_html( function_exists('pll__') ? pll__($label) : $label ); ?></span>
                </a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php endif; ?>

<style>
    /* บังคับซ่อน Sidebar เดิม */
    .woocommerce-MyAccount-navigation { display: none !important; }
    .woocommerce-MyAccount-content { width: 100% !important; float: none !important; border: none !important; }

    /* Container เมนู */
    .gustabe-myaccount-navigation {
        background: #fff;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        margin-left: -15px; /* ชดเชย padding ของ container หลัก เพื่อให้เต็มจอ */
        margin-right: -15px;
        position: sticky;
        top: 0;
        z-index: 99; /* ลอยเหนือ content */
    }

    /* ทำให้เลื่อนแนวนอนได้ (Scrollable) */
    .gustabe-nav-scroll {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        white-space: nowrap;
        padding: 0 15px;
        gap: 10px;
        -webkit-overflow-scrolling: touch; /* ลื่นๆ บน iOS */
        scrollbar-width: none; /* ซ่อน Scrollbar (Firefox) */
    }
    .gustabe-nav-scroll::-webkit-scrollbar { display: none; /* ซ่อน Scrollbar (Chrome/Safari) */ }

    /* ดีไซน์ปุ่ม */
    .gustabe-myaccount-navigation .nav-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        background: #f5f5f5;
        border-radius: 50px; /* ปุ่มมน */
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    /* ปุ่มตอนเลือกอยู่ (Active) */
    .gustabe-myaccount-navigation .nav-item.is-active .nav-link {
        background: #04a39c !important; /* สีธีม */
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(4, 163, 156, 0.3);
    }

    .gustabe-myaccount-navigation .nav-link:hover {
        background: #e0e0e0;
        color: #333;
    }
    
    .gustabe-myaccount-navigation i {
        font-size: 18px;
    }
</style>