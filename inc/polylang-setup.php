<?php
/**
 * Polylang Setup & Compatibility Bridge
 * ไฟล์นี้มีหน้าที่:
 * 1. ลงทะเบียนคำศัพท์ให้ Polylang รู้จัก (Register)
 * 2. ทำหน้าที่เป็นล่าม (Bridge) แปลภาษาเมื่อธีมเรียกใช้งาน
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ==============================================================
// 1. ส่วนลงทะเบียนคำศัพท์ (Register Strings)
// ==============================================================
function hello_child_register_customizer_strings() {
    if ( function_exists( 'pll_register_string' ) ) {
        
        // --- 1. ข้อมูลติดต่อ ---
        $address   = get_theme_mod( 'footer_contact_address', 'Address' );
        $phone     = get_theme_mod( 'footer_contact_phone', '02-XXX-XXXX' );
        $phone2    = get_theme_mod( 'footer_contact_phone_2', '' );
        $email     = get_theme_mod( 'footer_contact_email', 'info@example.com' );
        $line      = get_theme_mod( 'footer_contact_line', '@lineid' );
        $copyright = get_theme_mod( 'footer_copyright_text', '© 2024 All Rights Reserved.' );
        
        pll_register_string( 'Footer Address', $address, 'Hello Child Footer' );
        pll_register_string( 'Footer Phone', $phone, 'Hello Child Footer' );
        if ( ! empty( $phone2 ) ) pll_register_string( 'Footer Phone 2', $phone2, 'Hello Child Footer' );
        pll_register_string( 'Footer Email', $email, 'Hello Child Footer' );
        pll_register_string( 'Footer Line', $line, 'Hello Child Footer' );
        pll_register_string( 'Footer Copyright', $copyright, 'Hello Child Footer' );

        $fb_url    = get_theme_mod( 'footer_social_facebook', '#' );
        $ig_url    = get_theme_mod( 'footer_social_instagram', '#' );
        $line_url  = get_theme_mod( 'footer_social_line_url', '#' );
        pll_register_string( 'Social Facebook', $fb_url, 'Hello Child Footer' );
        pll_register_string( 'Social Instagram', $ig_url, 'Hello Child Footer' );
        pll_register_string( 'Social Line URL', $line_url, 'Hello Child Footer' );

        // --- 2. หัวข้อ Footer/Menu ---
        pll_register_string( 'Footer Title Follow', 'Follow Us', 'Hello Child Footer' );
        pll_register_string( 'Footer Title Links', 'Quick Links', 'Hello Child Footer' );
        pll_register_string( 'Footer Title Service', 'Customer Service', 'Hello Child Footer' );
        pll_register_string( 'Footer Title Contact', 'Contact Us', 'Hello Child Footer' );
        pll_register_string( 'Footer Credit Prefix', 'Dev & Design by', 'Hello Child Footer' );
        
        // Search & Greeting
        pll_register_string( 'Announcement Text', get_theme_mod( 'announcement_text', 'Free shipping!' ), 'Hello Elementor Child' );
        pll_register_string( 'Search Title', 'Search', 'Hello Elementor Child' );
        pll_register_string( 'Search Placeholder', 'Search...', 'Hello Elementor Child' );
        pll_register_string( 'Search Filter Label', 'Filter by:', 'Hello Elementor Child' );
        pll_register_string( 'Search All', 'Everything', 'Hello Elementor Child' );
        pll_register_string( 'Search Post', 'Article', 'Hello Elementor Child' );
        pll_register_string( 'Search Product', 'Product', 'Hello Elementor Child' );
        pll_register_string( 'Greeting Hello', 'Hello', 'Hello Elementor Child' );
        pll_register_string( 'Greeting Welcome', 'Welcome', 'Hello Elementor Child' );
        pll_register_string( 'Greeting Login Register', 'Login / Register', 'Hello Elementor Child' );
        pll_register_string( 'Menu My Account', 'My Account', 'Hello Elementor Child' );
        pll_register_string( 'Menu Orders', 'Orders', 'Hello Elementor Child' );
        pll_register_string( 'Menu Logout', 'Logout', 'Hello Elementor Child' );
        pll_register_string( 'Page Login Title', 'Login / Register', 'Hello Elementor Child' );
        pll_register_string( 'Tab Login', 'Login', 'Hello Elementor Child' );
        pll_register_string( 'Tab Register', 'Register', 'Hello Elementor Child' );

        // --- 3. Mobile App UI ---
        pll_register_string( 'Mobile Menu Home', 'Home', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Menu Shop', 'Shop', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Menu Cart', 'Cart', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Menu Account', 'Account', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Menu Chat', 'Chat', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Menu Line', 'Line', 'Hello Child Mobile App' );
        
        pll_register_string( 'Mobile Product Buy', 'Buy Now', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Sheet Title', 'Select Options', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Sheet Total', 'Total', 'Hello Child Mobile App' );
        pll_register_string( 'Mobile Sheet Confirm', 'Confirm Order', 'Hello Child Mobile App' );

        // --- 4. Shop Page (Filter & Sort) ---
        $shop_group = 'Hello Child Shop Page';
        
        pll_register_string( 'Filter Status On Sale', 'ลดราคา', $shop_group );
        pll_register_string( 'Filter Status In Stock', 'พร้อมส่ง', $shop_group );

        pll_register_string( 'Shop All', 'ทั้งหมด', $shop_group );
        pll_register_string( 'Shop Items Count', 'รายการ', $shop_group );
        pll_register_string( 'Shop Filter Btn', 'ตัวกรอง', $shop_group );
        pll_register_string( 'Shop Sort Btn', 'เรียงลำดับ', $shop_group );

        pll_register_string( 'Filter Header', 'ตัวกรองสินค้า', $shop_group );
        pll_register_string( 'Filter Price', 'ช่วงราคา', $shop_group );
        pll_register_string( 'Filter Min', 'ต่ำสุด', $shop_group );
        pll_register_string( 'Filter Max', 'สูงสุด', $shop_group );
        pll_register_string( 'Filter Reset', 'ล้างค่า', $shop_group );
        pll_register_string( 'Filter Apply', 'ดูผลลัพธ์', $shop_group );
        
        pll_register_string( 'Sort Header', 'เรียงลำดับตาม', $shop_group );
        pll_register_string( 'Sort Default', 'ค่าเริ่มต้น', $shop_group );
        pll_register_string( 'Sort Popularity', 'ได้รับความนิยมสูงสุด', $shop_group );
        pll_register_string( 'Sort Rating', 'คะแนนเฉลี่ยสูงสุด', $shop_group );
        pll_register_string( 'Sort Date', 'สินค้าใหม่ล่าสุด', $shop_group );
        pll_register_string( 'Sort Price Low', 'ราคาน้อยไปมาก', $shop_group );
        pll_register_string( 'Sort Price High', 'ราคามากไปน้อย', $shop_group );

        // --- 5. Search Modal ---
        $group_search = 'Hello Child Search';

        pll_register_string( 'Search Type Product', 'Product', $group_search );
        pll_register_string( 'Search Type Article', 'Article', $group_search );
        pll_register_string( 'Search Type All', 'Everything', $group_search );
        
        pll_register_string( 'Search Rec Label', 'แนะนำสำหรับคุณ', $group_search );
        pll_register_string( 'Search Tag Best Seller', '🔥 สินค้าขายดี', $group_search );
        pll_register_string( 'Search Tag Promotion', '💰 ลดราคา', $group_search );
        pll_register_string( 'Search Tag New', '🆕 มาใหม่', $group_search );

        pll_register_string( 'JS View All', 'ดูผลลัพธ์ทั้งหมด', 'Hello Child JS' );
        pll_register_string( 'JS No Results', 'ไม่พบข้อมูลที่ค้นหา', 'Hello Child JS' );
        pll_register_string( 'JS Error', 'เกิดข้อผิดพลาด โปรดลองใหม่', 'Hello Child JS' );
        pll_register_string( 'JS Sale Badge', 'ลดราคา', 'Hello Child JS' );

        pll_register_string( 'Search Again Btn', 'ค้นหาอีกครั้ง', 'Hello Child Search' );
        pll_register_string( 'Search Try Again', 'ลองค้นหาคำอื่นดูไหม?', 'Hello Child Search' );
        pll_register_string( 'Search Recommended', 'สินค้าแนะนำ', 'Hello Child Search' );

        pll_register_string( 'Review Logged In', 'เข้าสู่ระบบในชื่อ', 'Hello Child Review' );
        pll_register_string( 'Review Logout', 'ออกจากระบบ', 'Hello Child Review' );
        pll_register_string( 'Review Submit Btn', 'Submit', 'Hello Child Review' );

        // --- 6. My Account & Address Form ---
        $acc_group = 'Hello Child My Account';

        pll_register_string( 'Avatar Help', 'แตะไอคอนกล้องเพื่อเปลี่ยนรูป', $acc_group );
        pll_register_string( 'Avatar Change', 'เปลี่ยนรูป', $acc_group );
        pll_register_string( 'Avatar Remove', 'ลบรูป', $acc_group );
        pll_register_string( 'Avatar Upload Success', 'อัปโหลดเรียบร้อย!', $acc_group );
        pll_register_string( 'Avatar Remove Success', 'ลบรูปเรียบร้อย', $acc_group );
        pll_register_string( 'Avatar File Too Large', 'ไฟล์ใหญ่เกินไป (Max 2MB)', $acc_group );
        pll_register_string( 'Avatar Server Error', 'Server Error', $acc_group );

        pll_register_string( 'Acc Info Title', 'ข้อมูลส่วนตัว', $acc_group );
        pll_register_string( 'Acc Contact Title', 'ช่องทางติดต่อ', $acc_group );
        pll_register_string( 'Acc Social Title', 'โซเชียลมีเดีย', $acc_group );
        pll_register_string( 'Acc Password Title', 'Password change', $acc_group );

        pll_register_string( 'Acc Label Birth', 'วันเกิด (DD/MM/YYYY)', $acc_group );
        pll_register_string( 'Acc Label Phone', 'เบอร์โทรศัพท์', $acc_group );
        pll_register_string( 'Acc Label Line', 'Line ID', $acc_group );
        pll_register_string( 'Acc Label FB', 'Facebook Profile URL', $acc_group );
        pll_register_string( 'Acc Label IG', 'Instagram (IG)', $acc_group );
        pll_register_string( 'Acc Label X', 'X (Twitter)', $acc_group );
        pll_register_string( 'Acc Label TikTok', 'TikTok', $acc_group );
        pll_register_string( 'Acc Label WeChat', 'WeChat ID (微信)', $acc_group );

        pll_register_string( 'Dash Hello', 'สวัสดี', $acc_group );
        pll_register_string( 'Dash Orders', 'คำสั่งซื้อ', $acc_group );
        pll_register_string( 'Dash Address', 'ที่อยู่', $acc_group );
        pll_register_string( 'Dash Account', 'ข้อมูลส่วนตัว', $acc_group );
        pll_register_string( 'Dash Logout', 'ออกจากระบบ', $acc_group );
        pll_register_string( 'Dash Downloads', 'ดาวน์โหลด', $acc_group );
        pll_register_string( 'Dash Payment', 'วิธีการชำระเงิน', $acc_group );
        
        pll_register_string( 'Gustabe Theme', 'Address saved successfully', 'WooCommerce Notices' );

        // Address Form
        $addr_group = 'Address Form';
        pll_register_string( 'Gustabe Theme', 'พิมพ์รหัสไปรษณีย์ในช่องค้นหา ระบบจะกรอกข้อมูลให้', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ข้อมูลผู้ติดต่อ', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ชื่อจริง', $addr_group );
        pll_register_string( 'Gustabe Theme', 'นามสกุล', $addr_group );
        pll_register_string( 'Gustabe Theme', 'เบอร์โทรศัพท์', $addr_group );
        pll_register_string( 'Gustabe Theme', 'อีเมล', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ระบบค้นหาที่อยู่อัตโนมัติ', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ค้นหาที่อยู่ (พิมพ์รหัสไปรษณีย์ที่นี่)', $addr_group );
        pll_register_string( 'Gustabe Theme', 'พิมพ์เพื่อค้นหา เช่น 10900...', $addr_group );
        pll_register_string( 'Gustabe Theme', 'พิมพ์รหัสไปรษณีย์ แล้วเลือกรายการ ระบบจะเติมข้อมูลด้านล่างให้อัตโนมัติ', $addr_group );
        pll_register_string( 'Gustabe Theme', 'รหัสไปรษณีย์', $addr_group );
        pll_register_string( 'Gustabe Theme', 'จังหวัด', $addr_group );
        pll_register_string( 'Gustabe Theme', 'เขต / อำเภอ', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ตำบล / แขวง', $addr_group );
        pll_register_string( 'Gustabe Theme', 'บ้านเลขที่ / หมู่บ้าน / ซอย', $addr_group );
        pll_register_string( 'Gustabe Theme', 'ระบุบ้านเลขที่...', $addr_group );
        pll_register_string( 'Gustabe Theme', 'บันทึกที่อยู่', $addr_group );

        // --- 7. Order History (ใหม่!) ---
        $group_order = 'Gustabe Custom Order';
        
        pll_register_string( 'timeline_step_1', 'รับออเดอร์', $group_order );
        pll_register_string( 'timeline_step_2', 'เตรียมของ', $group_order );
        pll_register_string( 'timeline_step_3', 'ขนส่ง', $group_order );
        pll_register_string( 'timeline_step_4', 'สำเร็จ', $group_order );
        
        pll_register_string( 'btn_view_items', 'ดูรายการ', $group_order );
        pll_register_string( 'label_total', 'ยอดรวม:', $group_order );
        pll_register_string( 'btn_received', 'ได้รับของแล้ว', $group_order );
        
        pll_register_string( 'popup_loading_title', 'Loading...', $group_order );
        pll_register_string( 'popup_loading_text', 'กำลังโหลดข้อมูล...', $group_order );
        pll_register_string( 'popup_header', 'รายการสินค้า', $group_order );
        pll_register_string( 'popup_order_no', 'Order #%s', $group_order );
        pll_register_string( 'popup_net_total', 'ยอดสุทธิ', $group_order );
        pll_register_string( 'popup_btn_full', 'ดูรายละเอียดเต็ม', $group_order );
        
        pll_register_string( 'status_shipped', '🚚 อยู่ระหว่างขนส่ง', $group_order );

                // --- Downloads Page ---
        pll_register_string( 'dl_remaining', 'เหลือ %s ครั้ง', $group_order ); // %s คือตัวเลข
        pll_register_string( 'dl_lifetime', 'ตลอดชีพ', $group_order );
        pll_register_string( 'dl_btn', 'ดาวน์โหลด', $group_order );
        pll_register_string( 'dl_empty_title', 'ยังไม่มีรายการดาวน์โหลด', $group_order );
        pll_register_string( 'dl_empty_desc', 'ไฟล์คู่มือ หรือเอกสารดิจิทัลจากการสั่งซื้อ จะปรากฏที่นี่', $group_order );
        pll_register_string( 'dl_btn_shop', 'เลือกซื้อสินค้า', $group_order );

    }
}
add_action( 'init', 'hello_child_register_customizer_strings' );


// ==============================================================
// 2. ★ ส่วนสะพานเชื่อม (Bridge) - สำคัญมาก! ต้องมีถึงจะแปลได้ ★
// ==============================================================
add_filter( 'gettext', 'gustabe_polylang_bridge', 10, 3 );
function gustabe_polylang_bridge( $translated_text, $text, $domain ) {
    // 1. ดักจับข้อความที่มาจาก domain 'gustabe' หรือ 'Gustabe Theme'
    // 2. ตรวจสอบว่ามี Polylang ทำงานอยู่ไหม
    if ( ( 'gustabe' === $domain || 'Gustabe Theme' === $domain ) && function_exists( 'pll__' ) ) {
        
        // ส่งข้อความไปถาม Polylang ว่ามีคำแปลไหม
        $polylang_translation = pll__( $text );
        
        // ถ้า Polylang ตอบกลับมา (และคำแปลไม่เหมือนเดิม) ให้ใช้คำแปลนั้น
        if ( $polylang_translation !== $text ) {
            return $polylang_translation;
        }
    }
    // ถ้าไม่มีคำแปล ให้คืนค่าเดิมกลับไป
    return $translated_text;
}