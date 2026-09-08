/**
 * file: assets/js/modules/gustabe-header.js
 * หน้าที่: ควบคุม Mobile Menu และ Smart Sticky Header (Zero Plugin)
 */
document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. Mobile Menu Controller
    // ==========================================
    const openBtn = document.getElementById('open-mobile-menu');
    const closeBtn = document.getElementById('close-mobile-menu');
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (openBtn && closeBtn && menu && overlay) {

        const openMobileMenu = () => {
            // 1. เปิด Overlay
            overlay.classList.remove('hidden');
            // ใช้ setTimeout เพื่อให้เบราว์เซอร์ Render display:block ก่อนใส่ Opacity (ทำให้มีแอนิเมชัน Fade)
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            }, 10);

            // 2. สไลด์เมนูเข้ามา
            menu.classList.remove('translate-x-full');
            menu.classList.add('translate-x-0');

            // 3. ล็อก Scroll หน้าเว็บหลัก
            document.body.style.overflow = 'hidden';
        };

        const closeMobileMenu = () => {
            // 1. เฟด Overlay ออก
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');

            // 2. สไลด์เมนูกลับไปซ่อน
            menu.classList.remove('translate-x-0');
            menu.classList.add('translate-x-full');

            // 3. รอให้แอนิเมชันจบ (300ms ตาม Tailwind duration-300) แล้วค่อยซ่อน Overlay และปลดล็อก Scroll
            setTimeout(() => {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        };

        // ผูก Event Listeners
        openBtn.addEventListener('click', openMobileMenu);
        closeBtn.addEventListener('click', closeMobileMenu);
        overlay.addEventListener('click', closeMobileMenu);

        // รองรับการกดปุ่ม ESC เพื่อปิดเมนู (Accessibility - A11y)
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !menu.classList.contains('translate-x-full')) {
                closeMobileMenu();
            }
        });
    }

    // ==========================================
    // 2. Smart Sticky Header Controller
    // ==========================================
    const header = document.getElementById('site-header');

    if (header) {
        let lastScrollY = window.scrollY;

        // เพิ่ม Transition ให้ Header ขยับแบบสมูทๆ
        header.style.transition = 'transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out';

        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                window.requestAnimationFrame(() => {
                    const currentScrollY = window.scrollY;

                    // ใส่เงาให้ Header เมื่อเริ่มไถหน้าจอลงมา
                    if (currentScrollY > 50) {
                        header.classList.add('shadow-lg', 'shadow-black/50');
                    } else {
                        header.classList.remove('shadow-lg', 'shadow-black/50');
                    }

                    // ซ่อน/แสดง Header ตามทิศทางการ Scroll
                    if (currentScrollY > lastScrollY && currentScrollY > 150) {
                        // ไถลง (Scroll Down) -> ซ่อน Header ดันขึ้นไป 100%
                        header.style.transform = 'translateY(-100%)';
                    } else {
                        // ไถขึ้น (Scroll Up) หรืออยู่บนสุด -> ดึง Header กลับลงมา
                        header.style.transform = 'translateY(0)';
                    }

                    lastScrollY = currentScrollY;
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true }); // passive: true ช่วยให้ Performance การไถหน้าจอลื่นขึ้น (ไม่บล็อก Main Thread)
    }
});
