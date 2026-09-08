<?php
/**
 * file: template-parts/services/archive-header.php
 * หน้าที่: ส่วน Header ของหน้ารวมบริการ
 */

global $wp_query;
?>
<section class="relative pt-24 pb-16 bg-black border-b border-slate-900 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-emerald-600/5 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="font-mono mb-6">
            <div class="flex items-center gap-2 text-emerald-500 text-sm mb-2">
                <span class="animate-pulse">▶</span>
                <span>gustabe@system:~$ ./list_services.sh --display=grid</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-slate-200 tracking-tighter">
                Available <span class="text-emerald-500">Modules</span>
            </h1>
            <p class="mt-4 text-slate-400 max-w-2xl text-sm md:text-base italic">
                // [SYSTEM_LOG] Scanning active service nodes... Found <?php echo $wp_query->found_posts; ?> modules ready for deployment.
            </p>
        </div>
    </div>
</section>
