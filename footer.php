<?php
/**
 * The template for displaying the footer.
 * Option A: Pure Custom Engine (Zero Elementor, 100% Performance)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. เรียกใช้งานโครงสร้างหลัก Footer (แสดงทุกหน้า)
get_template_part( 'template-parts/footer/terminal', 'base' );

// อัญเชิญระบบ Search Command Palette
get_template_part( 'template-parts/footer/search-palette' );

// 2. Logic สลับฟีเจอร์ด้านล่างมือถือ (Mobile UI Switcher)
$is_product = class_exists( 'WooCommerce' ) && is_product();

if ( ! $is_product ) {
    // 🟢 ถ้าไม่ใช่หน้าสินค้า -> โชว์ App Dock ปกติ (Status Bar)
    get_template_part( 'template-parts/footer/app', 'dock' );
} else {
    // 🔴 ถ้าเป็นหน้าสินค้า -> โชว์ Product Bottom Sheet พร้อม Vanilla JS
    get_template_part( 'template-parts/footer/bottom', 'sheet' );
}
?>

<?php wp_footer(); ?>

</body>
</html>