<?php
/**
 * file: front-page.php
 * หน้าที่: หน้าแรกของเว็บไซต์ (The IDE Workspace)
 */

get_header(); 
?>

<!-- ⚡ Wrapper หลัก คลุมโทนสี Dark Mode สไตล์ IDE ตลอดทั้งหน้า -->
<main class="bg-black min-h-screen text-slate-300 font-mono selection:bg-emerald-500 selection:text-white">
    
    <!-- ชิ้นส่วนที่ 1: The Hero Workspace (Code Editor) -->
    <?php get_template_part( 'template-parts/home/hero', 'editor' ); ?>

    <!-- 💡 ชิ้นส่วนต่อไปที่เราจะทำ (โครงสร้าง) -->
    <?php 
    get_template_part( 'template-parts/home/core', 'services' );  // Bento Box
    get_template_part( 'template-parts/home/recent', 'works' );     // Portfolio Grid
    get_template_part( 'template-parts/home/cli', 'terminal' );     // The Terminal Logs
    ?>

</main>

<?php 
get_footer();