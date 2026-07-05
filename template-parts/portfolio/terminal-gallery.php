<?php
/**
 * file: template-parts/portfolio/terminal-gallery.php
 * หน้าที่: แสดงแกลเลอรีแบบ Grid และระบบ Lightbox ด้วย Vanilla JS (Zero Plugin)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ถ้าระบบไม่ได้ส่ง ID แกลเลอรีมา หรือไม่มีรูป ให้หยุดการทำงานทันที (Performance First)
if ( empty( $args['gallery_ids'] ) ) return;

$gallery_ids = explode( ',', $args['gallery_ids'] );
?>

<section class="mt-16 border-t border-slate-700 pt-10" id="project-gallery">
    
    <!-- Title สไตล์ Terminal -->
    <div class="mb-6 flex items-center gap-2 font-mono text-slate-400 select-none">
        <span class="text-emerald-500">##</span> <h3>Project_Gallery.js</h3>
    </div>

    <!-- 1. Grid แสดงรูป Thumbnail -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="gustabe-gallery-grid">
        <?php 
        foreach ( $gallery_ids as $index => $id ) : 
            // ดึงรูปขนาดกลางมาทำปก (โหลดไว) และรูปเต็มเก็บไว้ใส่ Data Attribute
            $thumb_url = wp_get_attachment_image_url( $id, 'medium_large' );
            $full_url  = wp_get_attachment_image_url( $id, 'full' );
            $alt_text  = get_post_meta( $id, '_wp_attachment_image_alt', true ) ?: 'Project Screenshot ' . ($index + 1);
            
            if ( $thumb_url ) :
        ?>
            <div class="relative group cursor-pointer border-2 border-slate-700 hover:border-emerald-500 transition-colors" 
                 data-gallery-item 
                 data-full-src="<?php echo esc_url( $full_url ); ?>"
                 data-index="<?php echo esc_attr( $index ); ?>">
                
                <!-- Overlay สไตล์ Code Editor ตอน Hover -->
                <div class="absolute inset-0 bg-slate-900/70 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-10">
                     <span class="text-emerald-400 font-mono text-sm border border-emerald-400 px-3 py-1 bg-slate-900/80 backdrop-blur-sm">View()</span>
                </div>

                <!-- รูปภาพ (Lazy Load อัตโนมัติ + Grayscale Effect) -->
                <img src="<?php echo esc_url( $thumb_url ); ?>" 
                     alt="<?php echo esc_attr( $alt_text ); ?>"
                     loading="lazy"
                     class="w-full h-48 md:h-64 object-cover filter grayscale hover:grayscale-0 transition-all duration-300">
            </div>
        <?php 
            endif;
        endforeach; 
        ?>
    </div>

    <!-- 2. Modal Lightbox (ซ่อนไว้เป็นค่าเริ่มต้น) -->
    <div id="gustabe-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-950/95 backdrop-blur-md opacity-0 transition-opacity duration-300">
        
        <!-- ปุ่มปิด (มุมขวาบน) -->
        <button id="lightbox-close" class="absolute top-6 right-6 text-slate-500 hover:text-red-400 p-2 focus:outline-none transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- ปุ่มย้อนกลับ (ซ้าย) -->
        <button id="lightbox-prev" class="absolute left-4 md:left-10 text-slate-500 hover:text-emerald-400 p-2 focus:outline-none transition-colors hidden md:block">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <!-- รูปภาพ Full Size -->
        <div class="relative max-w-6xl w-full px-4 md:px-20 flex justify-center">
            <img id="lightbox-image" src="" alt="Full Screen Project Image" class="max-h-[85vh] object-contain border border-slate-700 shadow-[0_0_50px_rgba(16,185,129,0.1)]">
        </div>

        <!-- ปุ่มถัดไป (ขวา) -->
        <button id="lightbox-next" class="absolute right-4 md:right-10 text-slate-500 hover:text-emerald-400 p-2 focus:outline-none transition-colors hidden md:block">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        
        <!-- ตัวนับภาพ Array Index -->
        <div id="lightbox-counter" class="absolute bottom-6 font-mono text-slate-500 text-sm tracking-widest"></div>
    </div>

    <!-- 3. The Vanilla JS Engine (Lightbox Logic) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const galleryItems = document.querySelectorAll('[data-gallery-item]');
            const lightbox = document.getElementById('gustabe-lightbox');
            const lightboxImg = document.getElementById('lightbox-image');
            const closeBtn = document.getElementById('lightbox-close');
            const prevBtn = document.getElementById('lightbox-prev');
            const nextBtn = document.getElementById('lightbox-next');
            const counter = document.getElementById('lightbox-counter');
            
            let currentIndex = 0;
            const totalImages = galleryItems.length;

            if(totalImages === 0) return;

            // ฟังก์ชันเปิด Lightbox
            const openLightbox = (index) => {
                currentIndex = index;
                updateLightboxImage();
                
                lightbox.classList.remove('hidden');
                lightbox.classList.add('flex');
                
                // ใช้ setTimeout ให้ Browser ทัน render display:flex ก่อนใส่ opacity
                setTimeout(() => lightbox.classList.remove('opacity-0'), 10);
                document.body.style.overflow = 'hidden'; // ล็อก Scroll หน้าเว็บ
            };

            // ฟังก์ชันปิด Lightbox
            const closeLightbox = () => {
                lightbox.classList.add('opacity-0');
                setTimeout(() => {
                    lightbox.classList.add('hidden');
                    lightbox.classList.remove('flex');
                    document.body.style.overflow = ''; // คืนค่า Scroll
                }, 300); // ดีเลย์ให้ตรงกับ duration-300 ในคลาส Tailwind
            };

            // อัปเดตรูปภาพ
            const updateLightboxImage = () => {
                const item = galleryItems[currentIndex];
                lightboxImg.src = item.getAttribute('data-full-src');
                // โชว์เลขนับแบบ Array index (เช่น item[0] / 5)
                counter.textContent = `item[${currentIndex}] // ${totalImages} total`;
            };

            const showNext = () => {
                currentIndex = (currentIndex + 1) % totalImages;
                updateLightboxImage();
            };

            const showPrev = () => {
                currentIndex = (currentIndex - 1 + totalImages) % totalImages;
                updateLightboxImage();
            };

            // ผูก Event Click ให้กับรูปภาพทั้งหมด
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', () => openLightbox(index));
            });

            // ผูก Event ให้ปุ่มต่างๆ
            closeBtn.addEventListener('click', closeLightbox);
            nextBtn.addEventListener('click', showNext);
            prevBtn.addEventListener('click', showPrev);

            // กดที่พื้นหลังสีดำเพื่อปิด
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeLightbox();
            });

            // Keyboard Navigation (UX ขั้นสุด)
            document.addEventListener('keydown', (e) => {
                if (lightbox.classList.contains('hidden')) return;
                
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') showNext();
                if (e.key === 'ArrowLeft') showPrev();
            });
        });
    </script>
</section>