<?php
/**
 * File: archive-services.php
 * Description: หน้ารวมบริการ GUSTABE สไตล์ System Registry Grid
 */

get_header(); ?>

<!-- Main tag removed, handled by header.php Window Shell -->
    
    <!-- ชิ้นส่วนที่ 1: The Hero Header (ดึงไฟล์แยกมาเพื่อความคลีน) -->
    <?php get_template_part( 'template-parts/services/archive', 'header' ); ?>

    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    
                    <article>
                        <?php get_template_part( 'template-parts/services/service-card' ); ?>
                    </article>

                <?php endwhile; endif; ?>

            </div>

            <div class="mt-16 pt-12 border-t border-slate-900 flex justify-between items-center font-mono text-xs uppercase tracking-widest text-slate-600">
                <span>> End of registry</span>
                <div class="flex gap-4">
                    <?php echo get_the_posts_pagination(array(
                        'mid_size'  => 2,
                        'prev_text' => '[ PREV_PAGE ]',
                        'next_text' => '[ NEXT_PAGE ]',
                    )); ?>
                </div>
            </div>
        </div>
    </section>

<!-- Main tag removed, handled by header.php Window Shell -->

<?php get_footer(); ?>