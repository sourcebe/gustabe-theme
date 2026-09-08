<?php
/**
 * dir:  template-parts/
 * file: search-palette.php
 * UI สำหรับระบบ Command Palette Search (⌘K) สไตล์ The Dark IDE
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<!-- เพิ่มคลาส 'group' และใช้ Tailwind จัดการ state '.is-open' -->
<div id="gustabe-search-palette" 
     class="group fixed inset-0 z-[9999] flex items-start justify-center p-4 sm:p-6 md:p-20 opacity-0 pointer-events-none transition-all duration-300 [&.is-open]:opacity-100 [&.is-open]:pointer-events-auto"
     role="dialog" aria-modal="true" aria-label="Search Command Palette">
    
    <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" id="search-backdrop"></div>

    <!-- ดึง group-[.is-open]:scale-100 มาทำแอนิเมชันซูมเข้า -->
    <div class="relative w-full max-w-2xl transform scale-95 group-[.is-open]:scale-100 divide-y divide-slate-800 overflow-hidden rounded-xl bg-slate-900 shadow-2xl ring-1 ring-slate-800 transition-all duration-300 border border-emerald-500/20">
        
        <div class="relative flex items-center px-4 py-2">
            <i class="huge huge-search-02 text-slate-400 text-xl"></i>
            <input type="text" id="search-input"
                   class="h-12 w-full border-0 bg-transparent pl-4 pr-4 text-slate-200 placeholder-slate-400 focus:ring-0 sm:text-sm outline-none" 
                   placeholder="Search projects, products, or code... (ESC to close)"
                   autocomplete="off">
            <kbd class="hidden sm:inline-flex items-center rounded border border-slate-700 px-2 font-sans text-xs font-medium text-slate-400">
                ESC
            </kbd>
        </div>

        <div id="search-results-container" class="max-h-96 scroll-py-3 overflow-y-auto p-3">
            <!-- ค้นหาส่วน #search-suggestions แล้วอัปเดตโค้ดด้านในตามนี้ครับ -->
            <div id="search-suggestions">
                <p class="px-3 py-2 text-xs font-semibold text-slate-400 uppercase tracking-wider">Quick Suggestions</p>
                <div class="space-y-1">
                    <!-- ⚡ เพิ่ม data-prefix ให้ปุ่ม -->
                    <a href="#" data-prefix="/products " class="suggestion-btn group/link flex items-center rounded-md px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                        <i class="huge huge-shopping-basket-01 text-emerald-500 mr-3"></i>
                        <span>Browse Products</span>
                    </a>
                    <a href="#" data-prefix="/portfolio " class="suggestion-btn group/link flex items-center rounded-md px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                        <!-- ⚡ แก้เป็น huge-code-folder -->
                        <i class="huge huge-code-folder text-red-500 mr-3"></i>
                        <span>View Portfolio</span>
                    </a>
                    <a href="#" data-prefix="/blog " class="suggestion-btn group/link flex items-center rounded-md px-3 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                        <!-- ⚡ แก้เป็น huge-book-open-01 -->
                        <i class="huge huge-book-open-01 text-cyan-500 mr-3"></i>
                        <span>Read Articles</span>
                    </a>
                </div>
            </div>

            <div id="search-loading" class="hidden py-14 text-center">
                <div class="inline-block h-6 w-6 animate-spin rounded-full border-2 border-solid border-emerald-500 border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"></div>
                <p class="mt-4 text-sm text-slate-400 font-mono">Executing query...</p>
            </div>

            <div id="search-results-list" class="space-y-1"></div>

            <div id="search-empty" class="hidden py-14 px-6 text-center sm:px-14">
                <i class="huge huge-search-minus text-5xl mb-4 text-slate-700"></i>
                <p class="text-sm text-slate-400">No results found for "<span id="search-query-text" class="text-slate-200"></span>"</p>
            </div>
        </div>

        <div class="flex items-center justify-between bg-slate-950/50 px-4 py-2.5 text-[10px] text-slate-500 font-mono uppercase tracking-widest border-t border-slate-800">
            <div class="flex gap-4">
                <span><b class="text-slate-300">ENTER</b> select</span>
                <span><b class="text-slate-300">↑↓</b> navigate</span>
            </div>
            <div>
                <span>Gustabe Engine v1.0</span>
            </div>
        </div>
    </div>
</div>