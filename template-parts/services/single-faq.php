<?php
/**
 * Template Part: Service FAQ Section (ด่านที่ 4: The Debug Console FAQ)
 * Description: เปลี่ยน FAQ เป็นหน้าต่าง Log Viewer เพื่อความ Geek ขั้นสุด
 */

$faqs = get_post_meta( get_the_ID(), '_service_faqs', true );

if ( empty( $faqs ) || ! is_array( $faqs ) ) {
    return;
}
?>

<section id="faq-section" class="py-24 bg-black border-t border-slate-900 relative">
    
    <!-- ⚡ Subtle Scanline Effect -->
    <div class="absolute inset-0 bg-[linear-gradient(rgba(18,16,16,0)_50%,rgba(0,0,0,0.25)_50%),linear-gradient(90deg,rgba(255,0,0,0.02),rgba(0,255,0,0.01),rgba(0,0,255,0.02))] bg-[length:100%_4px,3px_100%] pointer-events-none z-10"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20">
        
        <!-- Console Header -->
        <div class="mb-12 flex items-center justify-between border-b border-green-900/30 pb-4">
            <div class="font-mono text-sm">
                <span class="text-green-500 font-bold">STDOUT:</span> 
                <span class="text-slate-400 italic">// resolving_client_uncertainties...</span>
            </div>
            <div class="flex gap-1.5">
                <div class="w-2.5 h-2.5 rounded-full bg-red-500/20"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-yellow-500/20"></div>
                <div class="w-2.5 h-2.5 rounded-full bg-green-500/20"></div>
            </div>
        </div>

        <div class="space-y-6" id="gustabe-debug-accordion">
            
            <?php foreach ( $faqs as $index => $faq ) : 
                if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) continue;
                $num = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
            ?>
                
                <div class="faq-item group">
                    
                    <!-- 🟢 The Query Command -->
                    <button class="faq-toggle w-full flex items-start gap-4 text-left focus:outline-none py-2" aria-expanded="false">
                        <span class="text-pink-500 font-mono font-bold shrink-0 mt-1">[QUERY]</span>
                        <h3 class="text-lg md:text-xl font-mono text-slate-200 group-hover:text-green-400 transition-colors leading-snug">
                            <?php echo esc_html( $faq['question'] ); ?>
                        </h3>
                        <span class="faq-icon ml-auto text-slate-700 group-hover:text-green-500 transition-all duration-300 mt-1">
                            <i class="huge huge-plus-sign text-xl"></i>
                        </span>
                    </button>

                    <!-- 📝 The Log Output (Answer) -->
                    <div class="faq-content max-h-0 overflow-hidden transition-all duration-500 ease-in-out">
                        <div class="mt-4 ml-4 md:ml-8 border-l border-green-900/50 bg-green-500/[0.01] relative">
                            <!-- Line Numbers decoration -->
                            <div class="absolute left-[-2rem] top-4 flex flex-col gap-1 font-mono text-[10px] text-slate-800 select-none text-right w-6 hidden md:flex">
                                <span>1</span><span>2</span><span>3</span>
                            </div>
                            
                            <div class="p-6 pt-4 text-slate-400 font-mono text-sm md:text-base leading-relaxed">
                                <span class="text-blue-400 block mb-2">>> RESPONSE_BODY:</span>
                                <span class="text-amber-200/90">"<?php echo wp_kses_post( nl2br( $faq['answer'] ) ); ?>"</span>
                                <div class="mt-4 text-green-500/40 text-xs">✔ Trace: Success (0.0ms)</div>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="mt-4 h-px bg-slate-900 group-last:hidden"></div>
                </div>

            <?php endforeach; ?>

        </div>

        <!-- Final System Cursor -->
        <div class="mt-12 font-mono text-green-500/50 flex items-center gap-2">
            <span>Gustabe_OS:</span>
            <span class="w-2 h-5 bg-green-500 animate-pulse"></span>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.faq-item');
    
    items.forEach(item => {
        const toggle = item.querySelector('.faq-toggle');
        const content = item.querySelector('.faq-content');
        const icon = item.querySelector('.huge');

        toggle.addEventListener('click', () => {
            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
            
            // Toggle State
            toggle.setAttribute('aria-expanded', !isExpanded);
            content.style.maxHeight = isExpanded ? null : content.scrollHeight + "px";
            
            // Icon Class Switch
            if (!isExpanded) {
                icon.classList.replace('huge-plus-sign', 'huge-minus-sign');
                icon.classList.add('text-green-500');
            } else {
                icon.classList.replace('huge-minus-sign', 'huge-plus-sign');
                icon.classList.remove('text-green-500');
            }
        });
    });
});
</script>