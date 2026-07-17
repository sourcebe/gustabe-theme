<?php
/**
 * file: template-parts/home/core-services.php
 * หน้าที่: แสดงบริการหลัก 3 อย่าง รูปแบบ Bento Box (Option B: เน้น Web Dev)
 */
?>
<section id="core-services" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 relative z-20">
    
    <!-- ⚡ SEO Section Header (GitHub Style) -->
    <div class="mb-8 flex items-end justify-between border-b border-white/10 pb-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <i class="huge huge-folder-open text-blue-400"></i>
                <span class="text-blue-400 font-mono text-sm">gustabe / <span class="font-bold">core-modules</span></span>
                <span class="px-2 py-0.5 text-[10px] font-mono border border-white/10 text-slate-400 rounded-full">Public</span>
            </div>
            <h2 class="text-3xl font-bold text-slate-200">บริการ สร้างเว็บไซต์ และการตลาดออนไลน์</h2>
            <p class="mt-2 text-slate-400 max-w-2xl text-sm">
                เราไม่ใช่แค่สร้างหน้าเว็บ แต่เราสร้างเครื่องมือทางธุรกิจที่เพิ่มยอดขายและสร้างการจดจำให้กับแบรนด์ของคุณ
            </p>
        </div>
        <div class="hidden md:block">
            <button class="bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20 text-slate-300 px-3 py-1.5 rounded-md text-xs font-medium transition-colors flex items-center gap-2">
                <i class="huge huge-star"></i> Star <span class="bg-white/10 px-1.5 rounded-full text-[10px]">1.2k</span>
            </button>
        </div>
    </div>

    <!-- 🍱 The GitHub Repo Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- ⭐️ Box 1: Web Development -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 hover:border-blue-500/50 hover:shadow-[0_0_15px_rgba(59,130,246,0.1)] transition-all duration-300 group flex flex-col h-full">
            <div class="flex justify-between items-start mb-3">
                <a href="#" class="text-blue-400 text-lg font-semibold font-sans hover:underline flex items-center gap-2">
                    <i class="huge huge-laptop-programming"></i> Web-Development
                </a>
                <span class="px-2 py-0.5 border border-[#30363d] rounded-full text-slate-400 text-[10px] font-mono">MVP</span>
            </div>
            
            <!-- ⚡ SEO: H3 พร้อม Keyword Tier 1 (Hidden visually or integrated) -->
            <h3 class="sr-only">รับพัฒนาเว็บไซต์ (Web Development)</h3>
            
            <p class="text-slate-400 text-sm leading-relaxed mb-6 flex-grow">
                บริการรับทำเว็บไซต์ธุรกิจอีคอมเมิร์ซ, บริษัทที่ต้องการความน่าเชื่อถือ, ระบบจองทัวร์ หรืออสังหาริมทรัพย์ เราสร้างเว็บไซต์ในฝันที่มีประสิทธิภาพสูงสุด (High Performance & SEO Friendly)
            </p>
            
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> TypeScript</span>
                <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-star text-[14px]"></i> 845</span>
                <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-git-fork text-[14px]"></i> 128</span>
                <span class="ml-auto">Updated 2h ago</span>
            </div>
        </div>

        <!-- 🎯 Box 2: SEO Optimization -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 hover:border-blue-500/50 hover:shadow-[0_0_15px_rgba(59,130,246,0.1)] transition-all duration-300 group flex flex-col h-full">
            <div class="flex justify-between items-start mb-3">
                <a href="#" class="text-blue-400 text-lg font-semibold font-sans hover:underline flex items-center gap-2">
                    <i class="huge huge-seo"></i> SEO-Optimization
                </a>
                <span class="px-2 py-0.5 border border-[#30363d] rounded-full text-slate-400 text-[10px] font-mono">Algorithm</span>
            </div>
            
            <h3 class="sr-only">บริการ SEO ครบวงจร</h3>
            
            <p class="text-slate-400 text-sm leading-relaxed mb-6 flex-grow">
                วิเคราะห์คีย์เวิร์ด ปรับปรุงโครงสร้างเว็บ (Technical SEO) และดันอันดับให้ลูกค้าพบคุณก่อนใครบน Google ด้วยกลยุทธ์ที่ยั่งยืน
            </p>
            
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span> Python</span>
                <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-star text-[14px]"></i> 512</span>
                <span class="ml-auto">Updated 5h ago</span>
            </div>
        </div>

        <!-- 🚀 Box 3: Digital Marketing -->
        <div class="bg-slate-900 border border-slate-800 rounded-xl p-5 hover:border-blue-500/50 hover:shadow-[0_0_15px_rgba(59,130,246,0.1)] transition-all duration-300 group flex flex-col h-full md:col-span-2 lg:col-span-1">
            <div class="flex justify-between items-start mb-3">
                <a href="#" class="text-blue-400 text-lg font-semibold font-sans hover:underline flex items-center gap-2">
                    <i class="huge huge-target-02"></i> Digital-Marketing
                </a>
                <span class="px-2 py-0.5 border border-[#30363d] rounded-full text-slate-400 text-[10px] font-mono">Growth</span>
            </div>
            
            <h3 class="sr-only">การตลาดออนไลน์ (Marketing)</h3>
            
            <p class="text-slate-400 text-sm leading-relaxed mb-6 flex-grow">
                เปลี่ยนทุกโอกาสเป็นยอดขาย ด้วยการยิงโฆษณาตรงกลุ่มเป้าหมาย (Facebook, Google Ads) และ Content Marketing ที่ทรงพลังแบบ Data-Driven
            </p>
            
            <div class="flex items-center gap-4 text-xs font-mono text-slate-500">
                <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-orange-500"></span> HTML</span>
                <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-star text-[14px]"></i> 390</span>
                <span class="ml-auto">Updated 1d ago</span>
            </div>
        </div>

    </div>
</section>