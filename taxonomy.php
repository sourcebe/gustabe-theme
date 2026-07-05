<?php
/**
 * file: taxonomy.php
 * หน้าที่: หน้าแสดงผลเวลากดคลิกหมวดหมู่ (Taxonomy Archive)
 * Logic: ดึงชื่อหมวดหมู่ปัจจุบันมาโชว์บน Header แล้วเรียกใช้ card-ide.php ตัวเดิม
 */

get_header(); 

// 1. ดึงออบเจ็กต์ของ Taxonomy ที่เรากำลังเปิดอยู่ (เช่น กำลังดูแท็ก React)
$current_term = get_queried_object();
$taxonomy_name = '';

// 2. ลอจิกปรับคำนำหน้าให้เท่ตามประเภท Taxonomy
if ( $current_term->taxonomy === 'tech_stack' ) {
    $taxonomy_name = 'Stack: #' . $current_term->name;
} elseif ( $current_term->taxonomy === 'project_platform' ) {
    $taxonomy_name = 'Platform: ' . $current_term->name;
} else {
    $taxonomy_name = 'Type: ' . $current_term->name;
}
?>

<main class="min-h-screen bg-black text-slate-300 font-mono selection:bg-emerald-500 selection:text-white">
    
    <!-- Header สไตล์ Terminal (ปรับ Dynamic ตาม Taxonomy) -->
    <header class="relative bg-black border-b border-slate-800 pt-32 pb-20 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-blue-900/20 via-black to-black opacity-50"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                
                <div class="flex items-center gap-2 text-emerald-500 text-sm font-bold mb-6">
                    <i class="huge huge-terminal"></i>
                    <span class="opacity-80">~/gustabe/filter/</span>
                    <span class="text-slate-500">$</span>
                    <span class="typing-effect animate-pulse">grep "<?php echo esc_html( $current_term->slug ); ?>"</span>
                </div>

                <!-- แสดงชื่อหมวดหมู่ที่คลิกมา -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-sans font-bold text-white tracking-tight mb-6">
                    <?php echo esc_html( $taxonomy_name ); ?>
                </h1>
                
                <p class="text-lg text-slate-400 max-w-2xl leading-relaxed">
                    ผลงานทั้งหมดที่ถูกจัดอยู่ในหมวดหมู่ <strong><?php echo esc_html( $current_term->name ); ?></strong>
                </p>

            </div>
        </div>
    </header>

    <!-- โซน Grid สำหรับแสดงผลงาน (ดึง Card Component ตัวเดิมมาใช้ซ้ำ!) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <?php if ( have_posts() ) : ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ( have_posts() ) : the_post(); ?>
                    
                    <!-- ⚡ DRY: เรียกใช้การ์ดหน้าต่างโปรแกรมที่เราปั้นไว้แล้ว -->
                    <?php get_template_part( 'template-parts/portfolio/card', 'ide' ); ?>

                <?php endwhile; ?>
            </div>

            <div class="mt-16 flex justify-center border-t border-slate-800 pt-8">
                <?php the_posts_pagination(['class' => 'gustabe-pagination prose-a:text-emerald-400 hover:prose-a:text-emerald-300']); ?>
            </div>

        <?php else : ?>
            <div class="text-center py-20 border border-dashed border-slate-800 rounded-xl bg-black/20">
                <i class="huge huge-folder-not-found text-5xl text-slate-600 mb-4 block"></i>
                <p class="text-slate-500">// Error 404: No projects found matching this term.</p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php 
get_footer();