<?php
/**
 * file: template-parts/portfolio/software-specs.php
 * แสดงตาราง System Specs และสถาปัตยกรรมของโปรแกรม Desktop / Script / Tool
 * Style: IDE Property Inspector (รองรับทั้ง 5 ธีม data-theme)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ตรวจสอบว่ามีข้อมูลซอฟต์แวร์หรือไม่
$has_specs = ! empty( $args['target_os'] ) || 
             ! empty( $args['app_version'] ) || 
             ! empty( $args['prerequisites'] ) || 
             ! empty( $args['database_engine'] ) || 
             ! empty( $args['file_size'] );

if ( ! $has_specs ) return;
?>

<section class="mt-12 border border-slate-700 bg-slate-900/60 rounded-lg overflow-hidden shadow-xl" id="software-specs">
    <!-- Header Bar สไตล์ Windows Properties / IDE Tool Window -->
    <div class="bg-slate-950/90 px-4 py-3 border-b border-slate-800 flex items-center justify-between select-none">
        <div class="flex items-center gap-2">
            <span class="inline-block w-2.5 h-2.5 rounded-full bg-blue-500"></span>
            <span class="font-mono text-xs font-semibold text-slate-300 uppercase tracking-wider">
                System_Specifications.ini
            </span>
        </div>
        <div class="text-[11px] font-mono text-slate-500">
            [x86_64 / Win32 Architecture]
        </div>
    </div>

    <!-- Specs Grid Inspector -->
    <div class="p-5 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 font-mono text-sm">
        
        <?php if ( ! empty( $args['app_version'] ) ) : ?>
            <div class="flex items-baseline justify-between border-b border-slate-800/80 pb-2">
                <span class="text-slate-400 text-xs uppercase tracking-wider">Build Version:</span>
                <span class="text-emerald-400 font-semibold"><?php echo esc_html( $args['app_version'] ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['file_size'] ) ) : ?>
            <div class="flex items-baseline justify-between border-b border-slate-800/80 pb-2">
                <span class="text-slate-400 text-xs uppercase tracking-wider">Binary Size:</span>
                <span class="text-slate-200"><?php echo esc_html( $args['file_size'] ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['target_os'] ) ) : ?>
            <div class="flex items-baseline justify-between border-b border-slate-800/80 pb-2">
                <span class="text-slate-400 text-xs uppercase tracking-wider">Target OS:</span>
                <span class="text-cyan-400 font-semibold"><?php echo esc_html( $args['target_os'] ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['prerequisites'] ) ) : ?>
            <div class="flex items-baseline justify-between border-b border-slate-800/80 pb-2">
                <span class="text-slate-400 text-xs uppercase tracking-wider">Runtime / Pre-req:</span>
                <span class="text-amber-400 font-medium truncate max-w-[200px]" title="<?php echo esc_attr( $args['prerequisites'] ); ?>"><?php echo esc_html( $args['prerequisites'] ); ?></span>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['database_engine'] ) ) : ?>
            <div class="flex items-baseline justify-between border-b border-slate-800/80 pb-2 md:col-span-2">
                <span class="text-slate-400 text-xs uppercase tracking-wider">Database / Storage:</span>
                <span class="text-purple-400 font-semibold"><?php echo esc_html( $args['database_engine'] ); ?></span>
            </div>
        <?php endif; ?>

    </div>

    <?php if ( ! empty( $args['download_url'] ) ) : ?>
        <div class="bg-slate-950/40 px-5 py-4 border-t border-slate-800/80 flex flex-wrap items-center justify-between gap-4">
            <div class="text-xs text-slate-400 font-mono">
                Ready to deploy on local workstation
            </div>
            <a href="<?php echo esc_url( $args['download_url'] ); ?>" target="_blank" rel="noopener noreferrer" 
               class="px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-mono text-xs font-bold rounded flex items-center gap-2 transition-all shadow-md hover:shadow-blue-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                <span><?php echo esc_html( $args['download_label'] ); ?></span>
            </a>
        </div>
    <?php endif; ?>
</section>
