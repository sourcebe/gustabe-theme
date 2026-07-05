<?php
/**
 * dir: template-parts/portfolio/
 * file: card-ide.php
 * หน้าที่: การ์ดแสดงผลงาน 1 ชิ้น สไตล์ The Visual IDE (รองรับ Taxonomy)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id = get_the_ID();

// 1. ดึงข้อมูล Meta Data พื้นฐาน
$client_name = get_post_meta( $post_id, '_client_name', true );
$year        = get_post_meta( $post_id, '_project_year', true );

// 2. ดึงข้อมูล Taxonomy: Platform (เอามาแทนที่ Logic เดาลิงก์แบบเก่า)
$platforms     = get_the_terms( $post_id, 'project_platform' );
$platform_name = 'Application'; // ค่าเริ่มต้นกรณีไม่ได้ติ๊กเลือก
if ( $platforms && ! is_wp_error( $platforms ) ) {
    $platform_name = $platforms[0]->name; // ดึงชื่อแพลตฟอร์มแรกมาแสดง
}

// 3. ดึงข้อมูล Taxonomy: Tech Stack
$tech_stacks = get_the_terms( $post_id, 'tech_stack' );

// ⚡ 4. (เพิ่มใหม่) เตรียม Data Attributes สำหรับ JS Filter
$type_slugs = wp_list_pluck( get_the_terms( $post_id, 'project_type' ) ?: [], 'slug' );
$platform_slugs = wp_list_pluck( get_the_terms( $post_id, 'project_platform' ) ?: [], 'slug' );
$tech_slugs = wp_list_pluck( $tech_stacks ?: [], 'slug' );
?>

<article 
    data-type="<?php echo esc_attr( implode(',', $type_slugs) ); ?>"
    data-platform="<?php echo esc_attr( implode(',', $platform_slugs) ); ?>"
    data-tech="<?php echo esc_attr( implode(',', $tech_slugs) ); ?>"
    <?php post_class( 'portfolio-card relative group flex flex-col bg-slate-900 rounded-xl border border-slate-800 overflow-hidden hover:border-emerald-500/50 transition-all duration-500 shadow-lg hover:shadow-[0_0_50px_rgba(16,185,129,0.1)]' ); ?>>
    
    <!-- ส่วนที่ 1: แถบหัวหน้าต่าง (Window Title Bar) -->
    <div class="flex items-center px-4 py-3 bg-slate-950 border-b border-slate-800">
        <div class="flex gap-2">
            <div class="w-3 h-3 rounded-full bg-red-500/80"></div>
            <div class="w-3 h-3 rounded-full bg-yellow-500/80"></div>
            <div class="w-3 h-3 rounded-full bg-green-500/80"></div>
        </div>
        <div class="ml-4 text-xs text-slate-500 font-mono flex-1 text-center truncate">
            <?php echo esc_html( get_post_field( 'post_name', $post_id ) ); ?>.exe
        </div>
    </div>

    <!-- ส่วนที่ 2: Thumbnail รูปภาพ -->
    <a href="<?php the_permalink(); ?>" class="relative block aspect-[4/3] overflow-hidden bg-slate-800">
        <?php if ( has_post_thumbnail() ) : ?>
            <?php 
            the_post_thumbnail( 'large', [
                'class'   => 'w-full h-full object-cover grayscale opacity-80 transition-all duration-500 group-hover:grayscale-0 group-hover:opacity-100 group-hover:scale-105',
                'loading' => 'lazy',
                'alt'     => get_the_title()
            ] ); 
            ?>
        <?php else : ?>
            <div class="w-full h-full flex items-center justify-center text-slate-600 font-mono text-xs">
                // ERR: img_not_found.webp
            </div>
        <?php endif; ?>
        <div class="absolute inset-0 bg-slate-900/20 group-hover:bg-transparent transition-colors duration-500"></div>
    </a>

    <!-- ส่วนที่ 3: Terminal Data -->
    <div class="p-5 flex flex-col flex-grow">
        
        <!-- ป้ายกำกับ Platform (Dynamic 100%) -->
        <div class="mb-3">
            <span class="inline-block px-2 py-1 text-[10px] font-mono font-bold text-emerald-400 border border-emerald-500/30 bg-emerald-500/10 rounded">
                [<?php echo esc_html( $platform_name ); ?>]
            </span>
        </div>

        <!-- ชื่อโปรเจกต์ (Semantic H2) -->
        <h2 class="text-xl font-bold text-white mb-4 line-clamp-2 group-hover:text-emerald-400 transition-colors">
            <a href="<?php the_permalink(); ?>" class="focus:outline-none before:absolute before:inset-0">
                <?php the_title(); ?>
            </a>
        </h2>

        <!-- Terminal Metadata (Client & Year) -->
        <div class="mt-auto pt-4 border-t border-slate-800 font-mono text-xs flex flex-col gap-1.5">
            <?php if ( $client_name ) : ?>
                <div class="flex items-start">
                    <span class="text-emerald-500 mr-2">></span>
                    <span class="text-slate-500 mr-2">client:</span>
                    <span class="text-slate-300 line-clamp-1">"<?php echo esc_html( $client_name ); ?>"</span>
                </div>
            <?php endif; ?>
            
            <?php if ( $year ) : ?>
                <div class="flex items-start">
                    <span class="text-emerald-500 mr-2">></span>
                    <span class="text-slate-500 mr-2">year:&nbsp;&nbsp;</span>
                    <span class="text-emerald-400"><?php echo esc_html( $year ); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- ⚡ Tech Stack Tags (เพิ่มใหม่!) -->
        <?php if ( $tech_stacks && ! is_wp_error( $tech_stacks ) ) : ?>
            <div class="mt-4 flex flex-wrap gap-1.5 pt-3">
                <?php foreach ( $tech_stacks as $tech ) : ?>
                    <span class="px-2 py-0.5 text-[9px] font-mono text-slate-400 bg-slate-950 border border-slate-700 rounded-sm">
                        #<?php echo esc_html( $tech->name ); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</article>