<?php
/**
 * My Account dashboard
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$current_user = wp_get_current_user();
?>

<div class="gustabe-myaccount-dashboard">
    
    <div class="welcome-banner mb-5 p-4 d-flex align-items-center justify-content-between bg-light rounded-4">
        <div>
            <h2 class="h4 mb-1 fw-bold">
                <?php echo (function_exists('pll__') ? pll__('สวัสดี') : 'Hello') . ', ' . esc_html( $current_user->display_name ); ?>
            </h2>
            <p class="text-muted small mb-0">
                <?php echo (function_exists('pll__') ? pll__('ยินดีต้อนรับสู่หน้าจัดการบัญชีของคุณ') : 'Welcome to your account dashboard.'); ?>
            </p>
        </div>
        <div class="date-badge text-end d-none d-md-block">
            <span class="text-muted small"><?php echo date_i18n( get_option( 'date_format' ) ); ?></span>
        </div>
    </div>

    <div class="row g-3">
        <?php
        $menu_items = array(
            'orders'    => array('label' => 'คำสั่งซื้อ', 'icon' => 'huge-shopping-basket-01', 'desc' => 'เช็คสถานะและประวัติ'),
            'edit-address' => array('label' => 'ที่อยู่', 'icon' => 'huge-location-01', 'desc' => 'จัดการที่อยู่จัดส่ง'),
            'edit-account' => array('label' => 'ข้อมูลส่วนตัว', 'icon' => 'huge-user-edit-01', 'desc' => 'เปลี่ยนรหัสผ่านและชื่อ'),
            'customer-logout' => array('label' => 'ออกจากระบบ', 'icon' => 'huge-logout-01', 'desc' => 'ลงชื่อออกจากเครื่องนี้'),
        );

        foreach ( $menu_items as $endpoint => $item ) : ?>
            <div class="col-6 col-md-3">
                <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" class="dashboard-card text-decoration-none h-100 d-flex flex-column align-items-center text-center p-4 rounded-4 border transition-all">
                    <div class="icon-wrap mb-3 d-flex align-items-center justify-content-center">
                        <i class="huge <?php echo $item['icon']; ?>"></i>
                    </div>
                    <h3 class="h6 fw-bold mb-1 text-dark"><?php echo function_exists('pll__') ? pll__($item['label']) : $item['label']; ?></h3>
                    <span class="text-muted small d-none d-md-block"><?php echo function_exists('pll__') ? pll__($item['desc']) : $item['desc']; ?></span>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    /* CSS มินิมอลสำหรับ Dashboard */
    .gustabe-myaccount-dashboard .dashboard-card {
        background: #fff;
        border-color: #f0f0f0 !important;
        transition: 0.3s ease;
    }
    .gustabe-myaccount-dashboard .dashboard-card:hover {
        border-color: #04a39c !important;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(4, 163, 156, 0.08);
    }
    .gustabe-myaccount-dashboard .icon-wrap {
        width: 60px;
        height: 60px;
        background: #f8fdfd;
        border-radius: 50%;
        color: #04a39c;
        font-size: 24px;
        transition: 0.3s;
    }
    .gustabe-myaccount-dashboard .dashboard-card:hover .icon-wrap {
        background: #04a39c;
        color: #fff;
    }
    .welcome-banner {
        border-left: 5px solid #04a39c;
    }
</style>