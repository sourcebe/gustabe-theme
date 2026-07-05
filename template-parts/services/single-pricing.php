<?php
/**
 * Template Part: Service Pricing Section (ด่านที่ 3: The Deployment Summary)
 * Description: ส่วนแสดงราคาในรูปแบบผลลัพธ์การ Deploy ระบบ (Geek Style)
 */

if ( ! get_the_ID() ) return;

// ดึงค่าจาก Zone 3
$price_label = get_post_meta( get_the_ID(), '_starting_price_label', true );
$price_value = get_post_meta( get_the_ID(), '_starting_price_value', true );

$display_price = ! empty( $price_label ) ? $price_label : 'ประเมินราคาตาม Scope งาน';
?>

<section id="pricing-section" class="py-24 bg-[#050505] relative overflow-hidden font-sans border-b border-slate-900">
    
    <!-- Matrix Glow (สีเขียวตามธีมหลัก) -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-green-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header: สไตล์ Status Report -->
        <div class="mb-12 text-center md:text-left">
            <div class="inline-flex items-center gap-2 text-green-500 font-mono text-xs mb-4">
                <span>[LOG]</span> <span>Finalizing_Investment_Plan...</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight">Project <span class="text-green-500">Deployment</span> Cost</h2>
        </div>

        <!-- 🚀 Investment Card: สไตล์ System Module -->
        <div class="bg-[#0D0D0D] border border-slate-800 rounded-2xl overflow-hidden shadow-2xl flex flex-col lg:flex-row relative">
            
            <!-- ✅ ฝั่งซ้าย: Price & CTA (The "Execute" Zone) -->
            <div class="p-8 md:p-12 lg:w-2/5 flex flex-col justify-center border-b lg:border-b-0 lg:border-r border-slate-800 bg-[#0A0A0A]">
                <div class="font-mono text-xs text-slate-500 mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_#22c55e]"></span>
                    INSTANCE_READY
                </div>

                <div class="mb-10">
                    <span class="block text-slate-400 font-mono text-sm mb-2 uppercase tracking-widest">// STARTING_FROM</span>
                    <div class="text-4xl md:text-5xl font-extrabold text-white">
                        <span class="text-green-500"><?php echo esc_html( $display_price ); ?></span>
                    </div>
                    <?php if ( ! empty( $price_value ) ) : ?>
                        <p class="mt-4 text-xs text-slate-600 font-mono italic">* Dynamic_Pricing_Variable: True</p>
                    <?php endif; ?>
                </div>

                <!-- ปุ่ม Call to Action: สไตล์ EXECUTE -->
                <a href="#contact-form" class="group relative inline-flex justify-center items-center px-8 py-4 rounded bg-green-500 hover:bg-green-400 text-slate-950 font-bold text-lg transition-all shadow-[0_0_20px_rgba(34,197,94,0.3)] hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] w-full font-mono overflow-hidden uppercase tracking-wider">
                    <i class="huge huge-bubble-chat-done mr-3 text-xl"></i> 
                    Initialize_Contact()
                </a>
            </div>

            <!-- 📝 ฝั่งขวา: Deployment Specs (Value Breakdown) -->
            <div class="p-8 md:p-12 lg:w-3/5 bg-slate-900/10">
                <h3 class="text-lg font-mono text-slate-300 mb-8 border-b border-slate-800 pb-4 flex items-center gap-3">
                    <span class="text-green-500">>></span> System_Included_Packages
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Item 1 -->
                    <div class="flex gap-4">
                        <i class="huge huge-tick-04 text-green-500 text-xl mt-1"></i>
                        <div>
                            <strong class="text-slate-100 block text-sm mb-1 uppercase tracking-tight">Custom_Engine</strong>
                            <p class="text-slate-500 text-xs leading-relaxed">สถาปัตยกรรมเขียนมือ 100% ไร้ปลั๊กอินส่วนเกิน เพื่อความปลอดภัยสูงสุด</p>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex gap-4">
                        <i class="huge huge-tick-04 text-green-500 text-xl mt-1"></i>
                        <div>
                            <strong class="text-slate-100 block text-sm mb-1 uppercase tracking-tight">Performance_Boost</strong>
                            <p class="text-slate-500 text-xs leading-relaxed">Optimization ระดับ Code-base โหลดเร็วระดับมิลลิวินาที (LCP Focus)</p>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex gap-4">
                        <i class="huge huge-tick-04 text-green-500 text-xl mt-1"></i>
                        <div>
                            <strong class="text-slate-100 block text-sm mb-1 uppercase tracking-tight">Enterprise_Security</strong>
                            <p class="text-slate-500 text-xs leading-relaxed">รับประกันระบบ 1 ปีเต็ม พร้อมทีม Support ดูแลความปลอดภัยเซิร์ฟเวอร์</p>
                        </div>
                    </div>
                    <!-- Item 4 -->
                    <div class="flex gap-4">
                        <i class="huge huge-tick-04 text-green-500 text-xl mt-1"></i>
                        <div>
                            <strong class="text-slate-100 block text-sm mb-1 uppercase tracking-tight">Scalable_API</strong>
                            <p class="text-slate-500 text-xs leading-relaxed">วางโครงสร้างพร้อมเชื่อมต่อทุก API ในอนาคต (Payment, CRM, LINE)</p>
                        </div>
                    </div>
                </div>

                <!-- Build Log Footer -->
                <div class="mt-12 pt-6 border-t border-slate-800/50 flex justify-between items-center text-[10px] font-mono text-slate-700 uppercase tracking-widest">
                    <span>Build_Status: Stable</span>
                    <span>Env: Production</span>
                    <span>Ver: 2026.05</span>
                </div>
            </div>

        </div>
    </div>
</section>