<?php
/**
 * dir: template-parts/portfolio/
 * file: archive-header.php
 * หน้าที่: ส่วน Hero Banner ของหน้า Archive ผลงาน
 */
?>

<header class="relative bg-black border-b border-slate-800 pt-32 pb-20 overflow-hidden">
    <!-- Background Effect เล็กน้อยให้ดูมีมิติ -->
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-900/20 via-black to-black opacity-50"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            
            <!-- Terminal Command อุ่นเครื่อง -->
            <div class="flex items-center gap-2 text-emerald-500 text-sm font-bold mb-6">
                <i class="huge huge-command-line"></i>
                <span class="opacity-80">~/gustabe/agency</span>
                <span class="text-slate-500">$</span>
                <span class="typing-effect animate-pulse">ls -la portfolio/</span>
            </div>

            <!-- SEO H1: แท็กสำคัญที่สุดที่ Google ใช้จัดอันดับ -->
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-sans font-bold text-slate-200 tracking-tight mb-6">
                Crafting <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-500">Digital</span> Experiences.
            </h1>
            
            <!-- Subtitle -->
            <p class="text-lg text-slate-400 max-w-2xl leading-relaxed">
                คลังรวบรวมผลงานการพัฒนาระบบซอฟต์แวร์ เว็บไซต์ และโซลูชันเทคโนโลยีที่เราได้ร่วมสร้างสรรค์เพื่อยกระดับธุรกิจให้กับพาร์ทเนอร์ของเรา
            </p>

            <!-- Status Bar -->
            <div class="mt-8 flex items-center gap-4 text-xs text-slate-500 border border-slate-800 bg-slate-900/50 inline-flex px-4 py-2 rounded-md">
                <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Systems Online</span>
                <span class="border-l border-slate-700 pl-4">Total Projects: <?php echo wp_count_posts('our_works')->publish; ?></span>
            </div>

        </div>
    </div>
</header>