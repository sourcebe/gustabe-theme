/**
 * ⚡ GUSTABE ENGINE: Absolute Smooth Scroll (Bypass Elementor)
 * Description: ดักจับการคลิกในจังหวะ Capture Phase ก่อนที่สคริปต์ของ Elementor จะรู้ตัว
 */
document.addEventListener('click', function(e) {
    // 1. หาว่าสิ่งที่คลิกคือแท็ก <a> ที่มี href ขึ้นต้นด้วย '#' หรือไม่
    const anchor = e.target.closest('a[href^="#"]');
    
    if (anchor && anchor.getAttribute('href') !== '#') {
        // 2. หยุด! ห้ามส่ง Event นี้ไปให้ Elementor หรือสคริปต์อื่นทำงานต่อเด็ดขาด (หัวใจหลักอยู่ตรงนี้)
        e.preventDefault(); 
        e.stopPropagation(); 

        const targetId = anchor.getAttribute('href');
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
            // 3. สั่งให้เบราว์เซอร์ไถลไปหาเป้าหมาย (ซึ่งเป้าหมายมี CSS scroll-margin-top ดักรอเบรกอยู่แล้ว)
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // 4. (Optional) อัปเดต URL สวยๆ
            history.pushState(null, null, targetId);
        }
    }
}, true); // ⚡ ใส่ 'true' เพื่อเปิดโหมด Capture Phase (ดักหน้าสคริปต์ทั้งเว็บ)