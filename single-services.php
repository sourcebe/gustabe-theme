<?php
/**
 * Template Name: Single Service (Custom Post Type)
 * Description: หน้าแสดงรายละเอียดบริการแบบ Dark Mode (Cybernetics Vibe)
 */

get_header(); ?>

<!-- ⚡ พื้นหลังหลักแบบ Dark Mode (สี Slate-950) -->
<!-- Main tag removed, handled by header.php Window Shell -->
    
    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <?php 
            // 🚀 ด่านที่ 1: Hero Section (The Hook)
            get_template_part( 'template-parts/services/single', 'hero' ); 
            
            // 🍱 ด่านที่ 2: Features Bento Box (เตรียมสร้างในสเต็ปถัดไป)
            get_template_part( 'template-parts/services/single', 'features' ); 
            
            // 💰 ด่านที่ 3: Pricing & CTA (เตรียมสร้างในสเต็ปถัดไป)
            get_template_part( 'template-parts/services/single', 'pricing' ); 

            // ❓ ด่านที่ 4: FAQ Accordion (เตรียมสร้างในสเต็ปถัดไป)
            get_template_part( 'template-parts/services/single', 'faq' ); 
            ?>

        </article>

    <?php endwhile; ?>

<!-- Main tag removed, handled by header.php Window Shell -->

<?php get_footer(); ?>