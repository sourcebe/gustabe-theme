<?php
/**
 * file: archive-our_works.php
 * หน้าที่: หน้าแคตตาล็อกรวมผลงานทั้งหมด (Archive)
 * Style: The IDE Grid (Bento Box)
 */

get_header();
?>

<!-- Main tag removed, handled by header.php Window Shell -->

    <!-- ชิ้นส่วนที่ 1: The Hero Header (ดึงไฟล์แยกมาเพื่อความคลีน) -->
    <?php get_template_part( 'template-parts/portfolio/archive', 'header' ); ?>

    <!-- โซน Grid สำหรับแสดงผลงาน -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- ⚡ ชิ้นส่วนใหม่: The Hacker Radio Buttons (Filter UI) -->
        <!-- ⚡ แผงควบคุม The IDE Control Panel -->
        <?php
        // ดึงข้อมูล Taxonomy ทั้ง 3 แกน
        $tax_types = get_terms(['taxonomy' => 'project_type', 'hide_empty' => true]);
        $tax_platforms = get_terms(['taxonomy' => 'project_platform', 'hide_empty' => true]);
        $tax_techs = get_terms(['taxonomy' => 'tech_stack', 'hide_empty' => true]);
        ?>

        <div class="mb-12 bg-slate-900/50 border border-slate-800 rounded-xl p-4 lg:p-6 font-mono text-sm">
            <div class="text-slate-500 mb-4 pb-2 border-b border-slate-800/50">// Filter_Configuration</div>

            <!-- 📱 Mobile UI: The Terminal Dropdowns (แสดงเฉพาะจอมือถือ/แท็บเล็ต) -->
            <div class="flex flex-col gap-4 lg:hidden">
                <select data-filter-group="type" class="filter-select w-full bg-slate-950 border border-slate-700 text-slate-300 rounded px-3 py-2 outline-none focus:border-emerald-500 transition-colors appearance-none">
                    <option value="all">📁 Type: All_Projects</option>
                    <?php foreach($tax_types as $t) echo '<option value="' . esc_attr($t->slug) . '">- ' . esc_html($t->name) . '</option>'; ?>
                </select>
                <select data-filter-group="platform" class="filter-select w-full bg-slate-950 border border-slate-700 text-slate-300 rounded px-3 py-2 outline-none focus:border-emerald-500 transition-colors appearance-none">
                    <option value="all">💻 Platform: All_Platforms</option>
                    <?php foreach($tax_platforms as $t) echo '<option value="' . esc_attr($t->slug) . '">- ' . esc_html($t->name) . '</option>'; ?>
                </select>
                <select data-filter-group="tech" class="filter-select w-full bg-slate-950 border border-slate-700 text-slate-300 rounded px-3 py-2 outline-none focus:border-emerald-500 transition-colors appearance-none">
                    <option value="all">⚙️ Tech: All_Stacks</option>
                    <?php foreach($tax_techs as $t) echo '<option value="' . esc_attr($t->slug) . '">- ' . esc_html($t->name) . '</option>'; ?>
                </select>
            </div>


            <!-- 💻 Desktop UI: The Expanded Control Panel (แสดงเฉพาะจอใหญ่) -->
            <div class="hidden lg:flex flex-col gap-6">
                <!-- Group 1: Type -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-slate-600 w-24">Type:</span>
                    <button data-filter-group="type" data-filter-val="all" class="filter-btn active px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded transition-all">[x] All</button>
                    <?php foreach($tax_types as $t) : ?>
                        <button data-filter-group="type" data-filter-val="<?php echo esc_attr($t->slug); ?>" class="filter-btn px-3 py-1 text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-700 rounded transition-all">[ ] <?php echo esc_html($t->name); ?></button>
                    <?php endforeach; ?>
                </div>
                <!-- Group 2: Platform -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-slate-600 w-24">Platform:</span>
                    <button data-filter-group="platform" data-filter-val="all" class="filter-btn active px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded transition-all">[x] All</button>
                    <?php foreach($tax_platforms as $t) : ?>
                        <button data-filter-group="platform" data-filter-val="<?php echo esc_attr($t->slug); ?>" class="filter-btn px-3 py-1 text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-700 rounded transition-all">[ ] <?php echo esc_html($t->name); ?></button>
                    <?php endforeach; ?>
                </div>
                <!-- Group 3: Tech Stack -->
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-slate-600 w-24">Tech:</span>
                    <button data-filter-group="tech" data-filter-val="all" class="filter-btn active px-3 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded transition-all">[x] All</button>
                    <?php foreach($tax_techs as $t) : ?>
                        <button data-filter-group="tech" data-filter-val="<?php echo esc_attr($t->slug); ?>" class="filter-btn px-3 py-1 text-slate-400 hover:text-slate-200 border border-transparent hover:border-slate-700 rounded transition-all">[ ] <?php echo esc_html($t->name); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- ⚡ สร้างกล่อง ID ให้ JS ใช้ DOM Parser สับเปลี่ยนข้อมูล -->
        <div id="gustabe-portfolio-engine" class="transition-opacity duration-300">

            <?php if ( have_posts() ) : ?>
                <!-- ตะแกรง Grid: มือถือ 1 แถว, แท็บเล็ต 2 แถว, จอใหญ่ 3 แถว -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <!-- เรียกไฟล์การ์ดมาแสดงซ้ำๆ -->
                        <?php get_template_part( 'template-parts/portfolio/card', 'ide' ); ?>
                    <?php endwhile; ?>
                </div>

                <!-- P2: พื้นที่แสดงผลเมื่อ Filter แล้วไม่เจอผลงาน (JS จะสลับให้แสดงถ้ากรองไม่เจอ) -->
                <div id="no-results-state" class="hidden text-center py-20 border border-dashed border-slate-800 rounded-xl bg-black/20 mt-8">
                    <i class="huge huge-search-minus text-6xl text-slate-600 mb-4 inline-block"></i>
                    <p class="text-slate-500 font-mono">// Error 404: No items match your filter criteria.</p>
                </div>

                <!-- ระบบแบ่งหน้า (Pagination) สไตล์ Terminal -->
                <!-- ⚡ พื้นที่สำหรับปุ่มแบ่งหน้าจำลอง (JS Generated Pagination) -->
                <div id="js-pagination-container" class="mt-16 flex justify-center items-center gap-2 border-t border-slate-800 pt-8 font-mono text-sm">
                    <!-- ปุ่ม 1, 2, 3 จะถูกวาดลงตรงนี้ด้วย Vanilla JS -->
                </div>

            <?php else : ?>
                <!-- กรณีไม่มีผลงาน -->
                <div class="text-center py-20 border border-dashed border-slate-800 rounded-xl bg-black/20">
                    <i class="huge huge-search-minus text-6xl text-slate-600 mb-4 inline-block"></i>
                    <p class="text-slate-500">// Error 404: No projects found in this directory.</p>
                </div>
            <?php endif; ?>

        </div> <!-- ปิด #gustabe-portfolio-engine -->

    </section>
<!-- Main tag removed, handled by header.php Window Shell -->

<?php
get_footer();
