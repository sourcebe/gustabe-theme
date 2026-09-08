<?php
/**
 * file: template-parts/portfolio/terminal-gallery.php
 * หน้าที่: แสดงแกลเลอรีแบบ Grid และระบบ Lightbox ด้วย Vanilla JS (Zero Plugin)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ถ้าระบบไม่ได้ส่ง ID แกลเลอรีมา หรือไม่มีรูป ให้หยุดการทำงานทันที (Performance First)
if ( empty( $args['gallery_ids'] ) ) return;

$gallery_ids  = explode( ',', $args['gallery_ids'] );
$project_mode = ! empty( $args['project_mode'] ) ? $args['project_mode'] : 'web';

$gallery_title = 'Project_Gallery.js';
if ( $project_mode === 'software' ) {
    $gallery_title = 'Software_Screenshots.exe';
} elseif ( $project_mode === 'cli' ) {
    $gallery_title = 'Terminal_Outputs.log';
} elseif ( $project_mode === 'graphic' ) {
    $gallery_title = 'Design_Assets.fig';
}
?>

<section class="mt-16 border-t border-slate-700 pt-10" id="project-gallery">
    
    <!-- Title สไตล์ Terminal -->
    <div class="mb-6 flex items-center gap-2 font-mono text-slate-400 select-none">
        <span class="text-emerald-500">##</span> <h3><?php echo esc_html( $gallery_title ); ?></h3>
    </div>

    <!-- 1. Grid แสดงรูป Thumbnail (กรอบแนวนอนเท่ากัน 100% ทุกใบ ล็อกความสูงแน่นอน) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="gustabe-gallery-grid">
        <?php 
        foreach ( $gallery_ids as $index => $id ) : 
            $thumb_url   = wp_get_attachment_image_url( $id, 'large' ) ?: wp_get_attachment_image_url( $id, 'medium_large' );
            $full_url    = wp_get_attachment_image_url( $id, 'full' );
            $alt_text    = get_post_meta( $id, '_wp_attachment_image_alt', true ) ?: 'Project Screenshot ' . ($index + 1);
            $device_type = get_post_meta( $id, '_gustabe_device_type', true );

            $device_badge = '🖼 SCREENSHOT';
            if ( $project_mode === 'software' ) {
                $device_badge = '💻 WIN_APP // ' . ($index + 1);
            } elseif ( $project_mode === 'cli' ) {
                $device_badge = '⚡ CONSOLE_VIEW';
            } elseif ( $device_type === 'desktop' ) {
                $device_badge = '💻 DESKTOP // 1440px';
            } elseif ( $device_type === 'tablet' ) {
                $device_badge = '📱 TABLET // 768px';
            } elseif ( $device_type === 'mobile' ) {
                $device_badge = '📲 MOBILE // 375px';
            }
            
            if ( $thumb_url ) :
        ?>
            <!-- กรอบการ์ดแนวนอนขนาด 250px เท่ากันเป๊ะ 100% ทุกใบ ไม่มีย้อยเป็นแนวตั้งเด็ดขาด -->
            <div class="relative group cursor-pointer border border-slate-800 hover:border-emerald-500/80 transition-all duration-300 bg-slate-950 rounded-xl overflow-hidden flex flex-col shadow-lg shadow-black/40 hover:shadow-emerald-950/20" 
                 style="height: 250px; width: 100%; min-height: 250px; max-height: 250px;"
                 data-gallery-item 
                 data-full-src="<?php echo esc_url( $full_url ); ?>"
                 data-device-type="<?php echo esc_attr( $device_type ?: 'desktop' ); ?>"
                 data-device-label="<?php echo esc_attr( $device_badge ); ?>"
                 data-index="<?php echo esc_attr( $index ); ?>">
                
                <!-- Terminal Card Bar ด้านบน -->
                <div class="h-8 bg-slate-900/90 border-b border-slate-800 px-3 flex items-center justify-between text-xs select-none z-10 shrink-0" style="height: 32px;">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500/70 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500/70 inline-block"></span>
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500/70 inline-block"></span>
                    </div>
                    <span class="font-mono text-[11px] text-slate-400 tracking-wider font-medium">
                        <?php echo esc_html( $device_badge ); ?>
                    </span>
                </div>

                <!-- พื้นที่จัดแสดงภาพในกรอบแนวนอน (รูปอุปกรณ์อยู่ตรงกลาง) -->
                <div class="w-full relative flex items-center justify-center p-3 overflow-hidden bg-gradient-to-b from-slate-950 to-slate-900/40" style="height: 218px;">
                    <?php if ( $device_type === 'mobile' ) : ?>
                        <!-- จอ Mobile: สัดส่วนมือถือแนวตั้งอยู่ตรงกลางกรอบแนวนอน -->
                        <div class="rounded-lg border-2 border-slate-700/80 overflow-hidden shadow-[0_4px_25px_rgba(0,0,0,0.8)] relative bg-black flex items-center justify-center" style="height: 100%; aspect-ratio: 9 / 19.5;">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" 
                                 alt="<?php echo esc_attr( $alt_text ); ?>" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                    <?php elseif ( $device_type === 'tablet' ) : ?>
                        <!-- จอ Tablet: สัดส่วนแท็บเล็ตแนวตั้งอยู่ตรงกลางกรอบแนวนอน -->
                        <div class="rounded-lg border-2 border-slate-700/80 overflow-hidden shadow-[0_4px_25px_rgba(0,0,0,0.8)] relative bg-black flex items-center justify-center" style="height: 100%; aspect-ratio: 3 / 4;">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" 
                                 alt="<?php echo esc_attr( $alt_text ); ?>" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                    <?php else : ?>
                        <!-- จอ Desktop: แนวนอนเต็มพื้นที่กรอบ -->
                        <div class="w-full h-full rounded-lg overflow-hidden border border-slate-700/80 shadow-md relative bg-black flex items-center justify-center">
                            <img src="<?php echo esc_url( $thumb_url ); ?>" 
                                 alt="<?php echo esc_attr( $alt_text ); ?>" 
                                 loading="lazy" 
                                 class="w-full h-full object-cover object-top filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        </div>
                    <?php endif; ?>

                    <!-- Overlay ปุ่ม View() สไตล์ IDE -->
                    <div class="absolute inset-0 bg-slate-950/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center z-20 backdrop-blur-[2px]">
                        <span class="text-emerald-400 font-mono text-xs border border-emerald-400/80 px-3 py-1.5 rounded-md bg-slate-950/90 shadow-lg tracking-wider">
                            ⚡ View()
                        </span>
                    </div>
                </div>
            </div>
        <?php 
            endif;
        endforeach; 
        ?>
    </div>

    <!-- 2. Modal Lightbox Stage (เฟรมสไลด์ขนาดเท่ากันคงที่ 100% ไม่มียืดหดตามรูปเด็ดขาด) -->
    <div id="gustabe-lightbox" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/90 backdrop-blur-md opacity-0 transition-opacity duration-300 select-none p-2 sm:p-6">
        
        <!-- ปุ่มย้อนกลับ ลอยข้างนอกหน้าต่าง (Desktop/Tablet) ไม่บังรูปภาพ 100% -->
        <button id="lightbox-prev" class="hidden md:flex items-center justify-center z-[110] w-12 h-12 rounded-full bg-slate-900/90 hover:bg-emerald-500 text-slate-300 hover:text-slate-950 border border-slate-700 hover:border-emerald-400 shadow-2xl transition-all cursor-pointer backdrop-blur-sm focus:outline-none" style="position: fixed; top: 50%; transform: translateY(-50%); left: 24px;" title="Previous [Left Arrow]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <!-- กรอบ Stage Window ขนาดกว้าง-สูงล็อกคงที่ 100% แน่นอน ไม่ว่าจะสไลด์รูปไหนก็ตาม -->
        <div id="lightbox-stage-window" 
             class="relative bg-slate-950 border border-slate-800 rounded-2xl shadow-[0_0_80px_rgba(0,0,0,0.9)] flex flex-col overflow-hidden" 
             style="width: min(1080px, 94vw); height: min(720px, 84vh); min-width: min(1080px, 94vw); max-width: 1080px;">
            
            <!-- Window Titlebar ด้านบน -->
            <div class="h-11 bg-slate-900/90 border-b border-slate-800 px-4 flex items-center justify-between z-30 shrink-0" style="height: 44px;">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-3 font-mono text-xs text-slate-400 hidden sm:inline-block">Project_Screen_Viewer.exe</span>
                </div>

                <!-- ป้ายอุปกรณ์ตรงกลาง Titlebar -->
                <div id="lightbox-device-tag" class="font-mono text-xs font-semibold text-emerald-400 bg-slate-950 border border-emerald-500/40 px-3 py-1 rounded-full shadow-sm">
                    💻 DESKTOP // 1440px
                </div>

                <!-- ปุ่มปิด (มุมขวาบน) -->
                <button id="lightbox-close" class="text-slate-400 hover:text-red-400 p-1.5 rounded-lg hover:bg-slate-800 transition-colors" title="Close [Esc]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- กล่อง Canvas ตรงกลาง (สะอาดตา 100% ไม่มีปุ่มลอยมาเกะกะหรือทับภาพเด็ดขาด) -->
            <div class="flex-1 w-full relative flex items-center justify-center p-4 sm:p-6 overflow-hidden bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-900/40 via-slate-950 to-black" style="height: calc(100% - 88px);">
                <!-- พื้นที่จัดแสดงภาพอุปกรณ์ตรงกลาง Canvas (รูปเปลี่ยนข้างใน แต่กรอบ Canvas นิ่งสนิท) -->
                <div id="lightbox-inner-wrapper" class="h-full flex items-center justify-center transition-all duration-300" style="height: 100%;">
                    <img id="lightbox-image" src="" alt="Project Device View" class="transition-all duration-300">
                </div>
            </div>

            <!-- Window Statusbar ด้านล่าง (รวมชุดปุ่มควบคุม Prev / Next ในตัว ชัดเจน ใช้งานง่ายทุกอุปกรณ์) -->
            <div class="h-11 bg-slate-900/90 border-t border-slate-800 px-4 flex items-center justify-between text-xs font-mono text-slate-400 shrink-0 select-none" style="height: 44px;">
                <!-- กลุ่มปุ่มเปลี่ยนสไลด์ Statusbar -->
                <div class="flex items-center gap-1.5">
                    <button id="lightbox-prev-bar" class="px-3 py-1.5 bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-slate-300 rounded border border-slate-700 transition-all flex items-center gap-1 cursor-pointer" title="Previous [Left Arrow]">
                        <span>◀</span> <span class="hidden xs:inline">Prev</span>
                    </button>
                    <span id="lightbox-counter" class="text-emerald-400 px-3 py-1 bg-slate-950 rounded border border-slate-800 tracking-wider">
                        1 / 3
                    </span>
                    <button id="lightbox-next-bar" class="px-3 py-1.5 bg-slate-800 hover:bg-emerald-500 hover:text-slate-950 text-slate-300 rounded border border-slate-700 transition-all flex items-center gap-1 cursor-pointer" title="Next [Right Arrow]">
                        <span class="hidden xs:inline">Next</span> <span>▶</span>
                    </button>
                </div>

                <div class="hidden sm:flex items-center gap-4 text-[11px] text-slate-500">
                    <span>Use <kbd class="px-1.5 py-0.5 bg-slate-800 rounded border border-slate-700 text-slate-300">←</kbd> <kbd class="px-1.5 py-0.5 bg-slate-800 rounded border border-slate-700 text-slate-300">→</kbd> to navigate</span>
                    <span><kbd class="px-1.5 py-0.5 bg-slate-800 rounded border border-slate-700 text-slate-300">ESC</kbd> to exit</span>
                </div>
            </div>

        </div>

        <!-- ปุ่มถัดไป ลอยข้างนอกหน้าต่าง (Desktop/Tablet) ไม่บังรูปภาพ 100% -->
        <button id="lightbox-next" class="hidden md:flex items-center justify-center z-[110] w-12 h-12 rounded-full bg-slate-900/90 hover:bg-emerald-500 text-slate-300 hover:text-slate-950 border border-slate-700 hover:border-emerald-400 shadow-2xl transition-all cursor-pointer backdrop-blur-sm focus:outline-none" style="position: fixed; top: 50%; transform: translateY(-50%); right: 24px;" title="Next [Right Arrow]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>

    <!-- 3. The Vanilla JS Engine (Fixed Stage Lightbox) -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const galleryItems = document.querySelectorAll('[data-gallery-item]');
            const lightbox = document.getElementById('gustabe-lightbox');
            const innerWrapper = document.getElementById('lightbox-inner-wrapper');
            const lightboxImg = document.getElementById('lightbox-image');
            const deviceTag = document.getElementById('lightbox-device-tag');
            const closeBtn = document.getElementById('lightbox-close');
            const prevBtn = document.getElementById('lightbox-prev');
            const nextBtn = document.getElementById('lightbox-next');
            const prevBar = document.getElementById('lightbox-prev-bar');
            const nextBar = document.getElementById('lightbox-next-bar');
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
                }, 300);
            };

            // อัปเดตรูปภาพข้างใน Stage คงที่
            const updateLightboxImage = () => {
                const item = galleryItems[currentIndex];
                const fullSrc = item.getAttribute('data-full-src');
                const devType = item.getAttribute('data-device-type') || 'desktop';
                const devLabel = item.getAttribute('data-device-label');
                
                lightboxImg.src = fullSrc;

                // รีเซ็ตการตกแต่งรูปภาพข้างใน Stage
                if (devType === 'mobile') {
                    // มือถือ: ตั้งอยู่ตรงกลาง Canvas สัดส่วน iPhone
                    innerWrapper.style.cssText = 'height: 100%; aspect-ratio: 9 / 19.5; max-width: 100%;';
                    innerWrapper.className = 'rounded-3xl border-4 border-slate-700 shadow-[0_0_50px_rgba(0,0,0,0.8)] overflow-hidden bg-black flex items-center justify-center relative';
                    lightboxImg.className = 'w-full h-full object-cover object-top rounded-[1.4rem]';
                } else if (devType === 'tablet') {
                    // แท็บเล็ต: ตั้งอยู่ตรงกลาง Canvas สัดส่วน iPad
                    innerWrapper.style.cssText = 'height: 100%; aspect-ratio: 3 / 4; max-width: 100%;';
                    innerWrapper.className = 'rounded-2xl border-4 border-slate-700 shadow-2xl overflow-hidden bg-black flex items-center justify-center relative';
                    lightboxImg.className = 'w-full h-full object-cover object-top rounded-xl';
                } else {
                    // เดสก์ท็อป: กางเต็มความกว้างแนวนอนใน Canvas
                    innerWrapper.style.cssText = 'width: 100%; height: 100%; max-width: 100%;';
                    innerWrapper.className = 'rounded-lg border border-slate-700 shadow-2xl overflow-hidden bg-black flex items-center justify-center relative';
                    lightboxImg.className = 'w-full h-full object-contain rounded-lg';
                }
                
                if (deviceTag) {
                    deviceTag.textContent = devLabel || 'DESKTOP // 1440px';
                }
                if (counter) {
                    counter.textContent = `${currentIndex + 1} / ${totalImages}`;
                }
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

            if (closeBtn) closeBtn.addEventListener('click', closeLightbox);
            if (nextBtn) nextBtn.addEventListener('click', showNext);
            if (prevBtn) prevBtn.addEventListener('click', showPrev);
            if (nextBar) nextBar.addEventListener('click', showNext);
            if (prevBar) prevBar.addEventListener('click', showPrev);


            // กดที่พื้นหลังสีดำเพื่อปิด
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) closeLightbox();
            });

            // Keyboard Navigation
            document.addEventListener('keydown', (e) => {
                if (lightbox.classList.contains('hidden')) return;
                
                if (e.key === 'Escape') closeLightbox();
                if (e.key === 'ArrowRight') showNext();
                if (e.key === 'ArrowLeft') showPrev();
            });

            // Mobile Touch Swipe
            let touchStartX = 0;
            let touchEndX = 0;

            lightbox.addEventListener('touchstart', (e) => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });

            lightbox.addEventListener('touchend', (e) => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                if (Math.abs(diff) > 50) {
                    if (diff < 0) {
                        showNext();
                    } else {
                        showPrev();
                    }
                }
            }, { passive: true });
        });
    </script>
</section>