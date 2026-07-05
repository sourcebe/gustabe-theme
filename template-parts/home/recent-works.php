<?php
/**
 * file: template-parts/home/recent-works.php
 * หน้าที่: แสดงผลงานล่าสุด 6 ชิ้น (เรียกใช้การ์ดผลงานซ้ำตามหลัก DRY)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<section id="recent-works" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 border-t border-slate-800/50">
    
    <!-- ⚡ SEO Section Header -->
    <div class="mb-12 text-center md:text-left flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-emerald-500 font-mono tracking-wider uppercase text-sm mb-2 block">// latest_commits</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-100">
                ผลงาน <span class="text-emerald-400">รับทำเว็บไซต์</span> และออกแบบธุรกิจออนไลน์
            </h2>
        </div>
        <p class="text-slate-400 font-mono text-sm hidden md:block">
            Found <?php echo wp_count_posts('our_works')->publish; ?> projects in database.
        </p>
    </div>

    <!-- 🧠 WP_Query: ดึงผลงานล่าสุด 6 ชิ้น -->
    <?php
    $args = [
        'post_type'      => 'our_works',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ];
    $recent_works = new WP_Query( $args );
    ?>

    <!-- 🍱 The Portfolio Grid -->
    <?php if ( $recent_works->have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php while ( $recent_works->have_posts() ) : $recent_works->the_post(); ?>
                
                <!-- ⚡ DRY Principle: เรียกใช้ Card IDE ที่ทำไว้แล้วมาใช้เลย -->
                <?php get_template_part( 'template-parts/portfolio/card', 'ide' ); ?>

            <?php endwhile; ?>
        </div>
        
        <!-- เคลียร์ Query เพื่อไม่ให้กระทบ Loop อื่นๆ ในหน้าเว็บ -->
        <?php wp_reset_postdata(); ?>
        
    <?php else : ?>
        <!-- กรณีไม่มีผลงานในระบบ (Fallback) -->
        <div class="text-center py-20 border border-dashed border-slate-800 rounded-xl bg-slate-900/50">
            <i class="huge huge-folder-not-found text-4xl text-slate-600 mb-4 block"></i>
            <p class="text-slate-500 font-mono">// ERR_NO_COMMITS_FOUND: ยังไม่มีผลงานในระบบ</p>
        </div>
    <?php endif; ?>

    <!-- 🚀 Call To Action: The Terminal Command Button (Option A) -->
    <div class="mt-16 flex justify-center">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'our_works' ) ); ?>" class="inline-flex items-center px-6 py-4 bg-slate-950 border border-slate-700 rounded-lg text-slate-300 font-mono hover:bg-emerald-500 hover:text-slate-950 hover:border-emerald-500 transition-all duration-300 group shadow-lg">
            <span class="text-emerald-500 font-bold mr-3 group-hover:text-slate-950 transition-colors">></span> 
            npm run show_all_projects
            <span class="inline-block w-2.5 h-5 bg-emerald-400 animate-pulse ml-2 group-hover:bg-slate-950 transition-colors"></span>
        </a>
    </div>

</section>