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
    'project_mode'    => get_post_meta( $post_id, '_project_mode', true ) ?: 'web',
    'client'          => get_post_meta( $post_id, '_client_name', true ),
    'year'            => get_post_meta( $post_id, '_project_year', true ),
    'role'            => get_post_meta( $post_id, '_project_role', true ),
    'project_status'  => get_post_meta( $post_id, '_project_status', true ) ?: 'completed',
    'key_metric'      => get_post_meta( $post_id, '_key_metric', true ),
    // Software / EXE Fields
    'app_version'     => get_post_meta( $post_id, '_app_version', true ),
    'download_url'    => get_post_meta( $post_id, '_download_url', true ),
    'download_label'  => get_post_meta( $post_id, '_download_label', true ) ?: 'Download .exe',
    'file_size'       => get_post_meta( $post_id, '_file_size', true ),
    'target_os'       => get_post_meta( $post_id, '_target_os', true ),
    'prerequisites'   => get_post_meta( $post_id, '_prerequisites', true ),
    'database_engine' => get_post_meta( $post_id, '_database_engine', true ),
    'docs_url'        => get_post_meta( $post_id, '_docs_url', true ),
    'cli_command'     => get_post_meta( $post_id, '_cli_command', true ),
    // Web & App Links
    'live_url'        => get_post_meta( $post_id, '_live_url', true ),
    'github_url'      => get_post_meta( $post_id, '_github_url', true ),
    'app_store'       => get_post_meta( $post_id, '_app_store_url', true ),
    'play_store'      => get_post_meta( $post_id, '_play_store_url', true ),
    'gallery_ids'     => get_post_meta( $post_id, '_project_gallery', true ),
];
?>

<!-- 2. วางโครงสร้าง Semantic HTML และ Tailwind สำหรับ Dark/Terminal Mode -->
<!-- Main tag removed, handled by header.php Window Shell -->
    <?php while ( have_posts() ) : the_post(); ?>
        
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 md:pt-32 lg:pt-36 pb-20 md:pb-28' ); ?>>
            
            <!-- ชิ้นส่วนที่ 1: Terminal Header (ข้อมูลหลักและ Bento Cards) -->
            <?php get_template_part( 'template-parts/portfolio/terminal', 'header', $project_data ); ?>

            <!-- ชิ้นส่วนที่ 2: The Adaptive Hero Frame (หน้ากากจำลองตามประเภทงาน เช่น Windows .exe, Web, Terminal CLI) -->
            <?php get_template_part( 'template-parts/portfolio/hero', 'showcase', $project_data ); ?>

            <!-- ชิ้นส่วนที่ 3: Editor Content (เนื้อหาที่พิมพ์จาก Classic Editor) -->
            <div class="mt-12 prose prose-invert prose-emerald max-w-none 
                        prose-headings:font-mono prose-headings:font-bold prose-headings:text-emerald-400
                        prose-a:text-emerald-400 prose-a:underline-offset-4 hover:prose-a:text-emerald-300
                        prose-img:rounded-none prose-img:border-2 prose-img:border-slate-700">
                <?php the_content(); ?>
            </div>

            <!-- ชิ้นส่วนที่ 3: Software Specifications (สำหรับงาน Desktop .exe / Tools / Scripts) -->
            <?php get_template_part( 'template-parts/portfolio/software', 'specs', $project_data ); ?>

            <!-- ชิ้นส่วนที่ 4: Brutalism Gallery (ถ้าระบบมีรูปภาพ) -->
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