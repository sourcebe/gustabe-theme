<?php
/**
 * file: template-parts/footer/bottom-sheet.php
 * หน้าที่: แผ่นสไลด์สั่งซื้อสินค้า (Hacker Modal) พร้อม Vanilla JS Engine
 * โหลดเฉพาะหน้า Single Product เท่านั้น
 */
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;
if ( ! $product ) return; // ป้องกัน Error ถ้าหาข้อมูลสินค้าไม่เจอ

// 1. ดึงข้อมูลพื้นฐานสินค้า
$prod_img = wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) ?: wc_placeholder_img_src();
$prod_price = $product->get_price_html(); // ดึง HTML ราคาดั้งเดิม

// 2. Logic ตรวจสอบสถานะสต็อกสินค้า เพื่อเปลี่ยนข้อความปุ่ม
$btn_text = 'EXECUTE // ORDER';
$btn_class = 'bg-emerald-600 hover:bg-emerald-500 text-slate-950 border-emerald-400';
$btn_disabled = '';
$pulse_effect = '<span class="animate-pulse">_</span>';

if ( ! $product->is_in_stock() ) {
    $btn_text = 'ERR: OUT_OF_STOCK';
    $btn_class = 'bg-slate-800 text-slate-500 border-slate-700 cursor-not-allowed';
    $btn_disabled = 'disabled';
    $pulse_effect = '';
}
?>

<!-- ⚡ 1. ปุ่ม Trigger ด้านล่าง (แสดงเฉพาะมือถือ) -->
<div class="fixed bottom-0 left-0 w-full h-[65px] bg-slate-950 border-t border-slate-800 z-[9000] flex items-center justify-center lg:hidden px-4 shadow-[0_-5px_20px_rgba(0,0,0,0.5)]">
    <button id="terminal-trigger-sheet" class="w-full h-[45px] font-mono font-bold text-sm tracking-widest uppercase transition-all duration-300 border <?php echo $btn_class; ?>" <?php echo $btn_disabled; ?>>
        > <?php echo esc_html(my_pll($btn_text)); ?><?php echo $pulse_effect; ?>
    </button>
</div>

<!-- ⚡ 2. Hacker Modal (Bottom Sheet) -->
<div id="terminal-bottom-sheet" class="fixed inset-0 z-[9999] hidden flex-col justify-end pointer-events-none font-mono">

    <!-- พื้นหลังสีดำเบลอ (Overlay) -->
    <div id="sheet-overlay" class="absolute inset-0 bg-black/80 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-auto"></div>

    <!-- ตัวแผ่น Modal -->
    <div id="sheet-panel" class="relative w-full bg-slate-900 border-t-2 border-emerald-500 rounded-t-xl transform translate-y-full transition-transform duration-300 pointer-events-auto max-h-[85vh] flex flex-col shadow-[0_-10px_40px_rgba(16,185,129,0.15)]">

        <!-- ส่วนหัว (Header) -->
        <div class="flex items-start gap-4 p-5 border-b border-slate-800">
            <div class="relative group">
                <img src="<?php echo esc_url($prod_img); ?>" id="sheet-thumb" class="w-16 h-16 object-cover rounded border border-slate-700 grayscale transition-all duration-300">
                <div class="absolute inset-0 border border-emerald-500 opacity-0 group-hover:opacity-100 transition-opacity rounded"></div>
            </div>
            <div class="flex-1">
                <div class="text-[10px] text-emerald-500 mb-1 tracking-widest">// TARGET_ITEM:</div>
                <h4 class="text-sm font-bold text-slate-200 line-clamp-2 leading-snug"><?php the_title(); ?></h4>
                <div class="text-lg font-bold text-cyan-400 mt-2" id="sheet-price"><?php echo $prod_price; ?></div>
            </div>
            <button id="sheet-close" class="text-slate-500 hover:text-red-400 transition-colors focus:outline-none">
                <i class="huge huge-cancel-01 text-2xl"></i>
            </button>
        </div>

        <!-- บริเวณเนื้อหาฟอร์ม (Body) -->
        <div class="p-5 overflow-y-auto flex-1 text-slate-300" id="sheet-content-area">
            <div class="text-xs text-slate-500 mb-4">// SELECT_PARAMETERS:</div>
            <!-- 🎯 โค้ด JS จะดึงฟอร์ม WooCommerce มาใส่ตรงนี้แบบปลอดภัย -->
        </div>

        <!-- ส่วนท้าย (Footer) -->
        <div class="p-5 border-t border-slate-800 bg-slate-950 flex items-center justify-between gap-4">
            <div>
                <div class="text-[10px] text-slate-500 uppercase tracking-widest mb-1">Total_Value</div>
                <div class="text-xl font-bold text-emerald-400" id="sheet-total"><?php echo $prod_price; ?></div>
            </div>
            <button id="sheet-confirm-btn" class="flex-1 h-[50px] bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold tracking-widest uppercase transition-colors <?php echo $btn_disabled ? 'opacity-50 cursor-not-allowed' : ''; ?>" <?php echo $btn_disabled; ?>>
                [ CONFIRM ]
            </button>
        </div>
    </div>
</div>

<!-- ⚡ 3. The Vanilla JS Engine -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const triggerBtn = document.getElementById('terminal-trigger-sheet');
    const sheet = document.getElementById('terminal-bottom-sheet');
    const overlay = document.getElementById('sheet-overlay');
    const panel = document.getElementById('sheet-panel');
    const closeBtn = document.getElementById('sheet-close');
    const contentArea = document.getElementById('sheet-content-area');
    const confirmBtn = document.getElementById('sheet-confirm-btn');

    // ค้นหาฟอร์มตะกร้าของ WooCommerce ที่อยู่ในหน้าเว็บ
    const originalForm = document.querySelector('form.cart');
    let isSheetOpen = false;

    if (!triggerBtn || !sheet || !originalForm) {
        // P2: ซ่อนปุ่ม Trigger หากไม่มีฟอร์มตะกร้า (เช่น External Product)
        if (triggerBtn) triggerBtn.parentElement.style.display = 'none';
        return;
    }

    // [Safe Relocation] ย้ายฟอร์มเข้ามาใน Bottom Sheet เฉพาะ Mobile (lg:hidden)
    const originalParent = originalForm.parentElement;
    const originalNextSibling = originalForm.nextElementSibling;
    const mql = window.matchMedia('(max-width: 1023px)'); // < 1024px (lg breakpoint in Tailwind)

    // --- ควบคุมการเปิด/ปิด (Vanilla JS Animation) ---
    const openSheet = () => {
        isSheetOpen = true;
        sheet.classList.remove('hidden');
        sheet.classList.add('flex');
        // ใช้ requestAnimationFrame ให้ Browser กระตุก DOM ก่อนค่อยสั่ง Animation
        requestAnimationFrame(() => {
            overlay.classList.remove('opacity-0');
            panel.classList.remove('translate-y-full');
        });
        document.body.style.overflow = 'hidden'; // ล็อกการเลื่อนหน้าจอ
    };

    const closeSheet = () => {
        isSheetOpen = false;
        overlay.classList.add('opacity-0');
        panel.classList.add('translate-y-full');
        setTimeout(() => {
            sheet.classList.add('hidden');
            sheet.classList.remove('flex');
            document.body.style.overflow = ''; // ปลดล็อกการเลื่อน
        }, 300); // 300ms ตรงกับค่า duration-300 ของ Tailwind
    };

    const handleFormRelocation = (e) => {
        if (e.matches) {
            contentArea.appendChild(originalForm);
        } else {
            if (originalNextSibling) {
                originalParent.insertBefore(originalForm, originalNextSibling);
            } else {
                originalParent.appendChild(originalForm);
            }
            if (isSheetOpen) closeSheet();
        }
    };

    mql.addEventListener('change', handleFormRelocation);
    handleFormRelocation(mql); // ทำงานครั้งแรกตอนโหลดหน้าเว็บ



    triggerBtn.addEventListener('click', openSheet);
    overlay.addEventListener('click', closeSheet);
    closeBtn.addEventListener('click', closeSheet);

    // --- ระบบ Remote Control สั่งซื้อ ---
    confirmBtn.addEventListener('click', () => {
        const realSubmitBtn = originalForm.querySelector('button[type="submit"]');
        if (realSubmitBtn && !confirmBtn.hasAttribute('disabled')) {
            realSubmitBtn.click(); // สั่งกดปุ่มจริงๆ ของ WooCommerce ที่ซ่อนอยู่
            confirmBtn.innerHTML = '> PROCESSING...';
            confirmBtn.classList.add('animate-pulse');
        }
    });

    // --- ฟังเสียงกระซิบจาก WooCommerce (ต้องใช้ jQuery แค่ตรงนี้) ---
    // WooCommerce Core ปล่อย Event การเปลี่ยน Variation ผ่าน jQuery เราจึงต้องดักจับเพื่ออัปเดตราคา/รูปภาพ
    if (typeof jQuery !== 'undefined') {
        const $form = jQuery(originalForm);
        const priceDisplay = document.getElementById('sheet-price');
        const totalDisplay = document.getElementById('sheet-total');
        const thumbDisplay = document.getElementById('sheet-thumb');

        // ฟังก์ชันอัปเดตสถานะปุ่ม CONFIRM ให้ตรงกับปุ่ม Submit ของจริง (P2)
        const realSubmit = originalForm.querySelector('button[type="submit"]');
        const syncConfirmButton = () => {
            if (realSubmit && (realSubmit.disabled || realSubmit.classList.contains('disabled'))) {
                confirmBtn.setAttribute('disabled', 'disabled');
                confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                confirmBtn.removeAttribute('disabled');
                confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        };

        // เมื่อลูกค้าเลือกตัวเลือกสี/ไซส์สำเร็จ
        $form.on('found_variation', (e, variation) => {
            if (variation.price_html) {
                priceDisplay.innerHTML = variation.price_html;
                totalDisplay.innerHTML = variation.price_html;
            }
            if (variation.image && variation.image.src) {
                thumbDisplay.src = variation.image.src;
                thumbDisplay.classList.remove('grayscale'); // เอฟเฟกต์ปลดล็อกสีรูป
            }
            syncConfirmButton();
        });

        // เมื่อลูกค้ากดล้างค่าตัวเลือก (Clear)
        $form.on('reset_data', () => {
            priceDisplay.innerHTML = '<?php echo wp_kses_post($prod_price); ?>';
            totalDisplay.innerHTML = '<?php echo wp_kses_post($prod_price); ?>';
            thumbDisplay.src = '<?php echo esc_url($prod_img); ?>';
            thumbDisplay.classList.add('grayscale');
            syncConfirmButton();
        });

        // Initial sync ตอนโหลดครั้งแรก
        setTimeout(syncConfirmButton, 100);
    }
});
</script>

<style>
/* CSS ปรับแต่งพิเศษเพื่อซ่อนปุ่มเดิมของ Woo และปรับดีไซน์ช่องกรอกจำนวน (Quantity) */
@media (max-width: 1023px) { /* lg breakpoint ใน Tailwind */
    /* ซ่อนฟอร์มตะกร้าในเนื้อหาปกติ (เพราะเราย้ายไปใน Modal แล้ว) */
    .single-product div.product form.cart { display: none !important; }
    /* แต่ให้แสดงใน Modal ของเรา */
    #terminal-bottom-sheet form.cart { display: block !important; }
    /* ซ่อนปุ่ม Submit จริงๆ เอาไว้ เพราะเราใช้ปุ่ม [ CONFIRM ] ด้านล่างแทน */
    #terminal-bottom-sheet button[type="submit"] { display: none !important; }

    /* ปรับหน้าตาปุ่มเพิ่มลดจำนวน (Quantity) ให้เข้ากับตีม Hacker */
    #terminal-bottom-sheet .quantity {
        display: flex !important; align-items: center; border: 1px solid #334155;
        background: #020617; border-radius: 4px; overflow: hidden; width: max-content;
    }
    #terminal-bottom-sheet input.qty {
        width: 50px; height: 35px; background: transparent; border: none;
        text-align: center; color: #34d399; font-weight: bold; font-family: monospace;
    }
}
</style>
