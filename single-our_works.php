<?php
/**
 * file: single-our_works.php
 * Template สำหรับแสดงผลงานเดี่ยว (Single Portfolio) 
 * Style: The Minimalist Terminal (Dev/IDE Style)
 */

get_header();

// 1. กวาดข้อมูล Meta Data ทั้งหมดที่เราทำฟอร์มรับค่าไว้ (Zone Builder Logic)
$post_id = get_the_ID();
$project_data = [
    'client'       => get_post_meta( $post_id, '_client_name', true ),
    'year'         => get_post_meta( $post_id, '_project_year', true ),
    'role'         => get_post_meta( $post_id, '_project_role', true ),
    'live_url'     => get_post_meta( $post_id, '_live_url', true ),
    'github_url'   => get_post_meta( $post_id, '_github_url', true ),
    'app_store'    => get_post_meta( $post_id, '_app_store_url', true ),
    'play_store'   => get_post_meta( $post_id, '_play_store_url', true ),
    'gallery_ids'  => get_post_meta( $post_id, '_project_gallery', true ),
];
?>

<!-- 2. วางโครงสร้าง Semantic HTML และ Tailwind สำหรับ Dark/Terminal Mode -->
<!-- Main tag removed, handled by header.php Window Shell -->
    <?php while ( have_posts() ) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'max-w-4xl mx-auto px-4 sm:px-6 lg:px-8' ); ?>>
            
            <!-- ชิ้นส่วนที่ 1: Terminal Header (ข้อมูลหลักและลิงก์) -->
            <?php get_template_part( 'template-parts/portfolio/terminal', 'header', $project_data ); ?>

            <!-- ชิ้นส่วนที่ 2: Editor Content (เนื้อหาที่พิมพ์จาก Classic Editor) -->
            <div class="mt-12 prose prose-invert prose-emerald max-w-none 
                        prose-headings:font-mono prose-headings:font-bold prose-headings:text-emerald-400
                        prose-a:text-emerald-400 prose-a:underline-offset-4 hover:prose-a:text-emerald-300
                        prose-img:rounded-none prose-img:border-2 prose-img:border-slate-700">
                <?php the_content(); ?>
            </div>

            <!-- ชิ้นส่วนที่ 3: Brutalism Gallery (ถ้าระบบมีรูปภาพ) -->
            <?php 
            if ( ! empty( $project_data['gallery_ids'] ) ) {
                get_template_part( 'template-parts/portfolio/terminal', 'gallery', $project_data ); 
            }
            ?>

        </article>

    <?php endwhile; ?>
<!-- Main tag removed, handled by header.php Window Shell -->

<?php
get_footer();