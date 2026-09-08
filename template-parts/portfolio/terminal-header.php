<?php
/**
 * file: template-parts/portfolio/terminal-header.php
 * หน้าที่: แสดงผลส่วนหัว (Title, Meta, Links, Tags) พร้อมระบบ Clickable Taxonomy
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id = get_the_ID();

// 1. ดึงข้อมูล Taxonomy ทั้ง 3 แกน
$tech_stacks = get_the_terms( $post_id, 'tech_stack' );
$platforms   = get_the_terms( $post_id, 'project_platform' );
$types       = get_the_terms( $post_id, 'project_type' );

// 2. แกะชื่อและดึง URL ของ Platform และ Type (ดึงค่าแรกสุด)
$platform_name = '-'; $platform_url = '';
if ( $platforms && ! is_wp_error( $platforms ) ) {
    $platform_name = $platforms[0]->name;
    $platform_url  = get_term_link( $platforms[0] );
}

$type_name = '-'; $type_url = '';
if ( $types && ! is_wp_error( $types ) ) {
    $type_name = $types[0]->name;
    $type_url  = get_term_link( $types[0] );
}

// โหมดของโปรเจกต์
$project_mode = ! empty( $args['project_mode'] ) ? $args['project_mode'] : 'web';
$path_prefix  = '~/gustabe/web/';
if ( $project_mode === 'software' ) {
    $path_prefix = '~/gustabe/software/win32/';
} elseif ( $project_mode === 'cli' ) {
    $path_prefix = '~/gustabe/bin/cli/';
} elseif ( $project_mode === 'mobile' ) {
    $path_prefix = '~/gustabe/mobile/';
} elseif ( $project_mode === 'graphic' ) {
    $path_prefix = '~/gustabe/design/';
}
?>

<header class="mb-12 border-b-2 border-slate-700 pb-10">
    
    <!-- 1. ส่วนจำลองหน้าจอ Terminal & H1 -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-4 select-none">
        <div class="flex gap-1.5">
            <span class="w-3 h-3 rounded-full bg-red-500/80"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-500/80"></span>
            <span class="w-3 h-3 rounded-full bg-green-500/80"></span>
        </div>
        <span class="ml-2 font-mono text-slate-400"><?php echo esc_html( $path_prefix ); ?></span>
        
        <?php if ( ! empty( $args['app_version'] ) ) : ?>
            <span class="px-2 py-0.5 text-xs font-mono bg-blue-950/80 text-blue-400 border border-blue-800 rounded-sm">
                <?php echo esc_html( $args['app_version'] ); ?>
            </span>
        <?php endif; ?>

        <?php if ( ! empty( $args['project_status'] ) ) : ?>
            <span class="ml-auto text-xs font-mono px-2 py-0.5 rounded-sm <?php echo $args['project_status'] === 'completed' ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-800' : 'bg-amber-950/80 text-amber-400 border border-amber-800'; ?>">
                ● <?php echo strtoupper( esc_html( $args['project_status'] ) ); ?>
            </span>
        <?php endif; ?>
    </div>
    
    <div class="mb-8 font-mono">
        <span class="text-emerald-500 font-bold">const</span> <span class="text-blue-400"><?php echo $project_mode === 'software' ? 'binary_target' : 'project_name'; ?></span> = 
        <h1 class="inline text-3xl md:text-4xl lg:text-5xl font-bold text-slate-100 tracking-tight">
            "<?php the_title(); ?><?php echo $project_mode === 'software' && !str_ends_with(strtolower(get_the_title()), '.exe') ? '.exe' : ''; ?>"
        </h1><span class="text-slate-400">;</span>
    </div>

    <!-- 2. ส่วนข้อมูล Meta: แยกเป็นการ์ดเดี่ยว (Modular Bento Cards สไตล์ IDE Dashboard) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        
        <!-- การ์ด 1: Client & Timeline -->
        <div class="p-5 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col justify-between hover:border-slate-700 transition-colors shadow-md">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 uppercase font-mono tracking-wider mb-2 select-none">
                    <span class="w-2 h-2 rounded-full bg-emerald-500/70 inline-block"></span>
                    <span>Client // Org</span>
                </div>
                <div class="text-base font-semibold text-slate-100 leading-snug break-words">
                    <?php echo !empty($args['client']) ? esc_html($args['client']) : 'Internal / Gustabe Lab'; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs font-mono">
                <span class="text-slate-500">TIMELINE</span>
                <span class="text-slate-300 font-medium"><?php echo !empty($args['year']) ? esc_html($args['year']) : 'Ongoing'; ?></span>
            </div>
        </div>

        <!-- การ์ด 2: Role & Responsibilities (รองรับข้อความยาวได้เต็มที่ ไม่โดนตัดไข่ปลา) -->
        <div class="p-5 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col justify-between hover:border-slate-700 transition-colors shadow-md sm:col-span-1 lg:col-span-1">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 uppercase font-mono tracking-wider mb-2 select-none">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block"></span>
                    <span>My Role &amp; Scope</span>
                </div>
                <div class="text-sm font-medium text-emerald-400 leading-relaxed break-words">
                    <?php echo !empty($args['role']) ? nl2br(esc_html($args['role'])) : 'Lead Architecture & Full Development'; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs font-mono">
                <span class="text-slate-500">STATUS</span>
                <span class="text-slate-300 uppercase"><?php echo !empty($args['project_status']) ? esc_html($args['project_status']) : 'Completed'; ?></span>
            </div>
        </div>

        <!-- การ์ด 3: Platform / Architecture -->
        <div class="p-5 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col justify-between hover:border-slate-700 transition-colors shadow-md">
            <div>
                <div class="flex items-center gap-1.5 text-xs text-slate-500 uppercase font-mono tracking-wider mb-2 select-none">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 inline-block"></span>
                    <span>Platform // Target</span>
                </div>
                <div class="text-base font-semibold text-cyan-400 break-words">
                    <?php if ( !empty($args['target_os']) ) : ?>
                        <?php echo esc_html( $args['target_os'] ); ?>
                    <?php elseif ( $platform_url && ! is_wp_error( $platform_url ) ) : ?>
                        <a href="<?php echo esc_url( $platform_url ); ?>" class="hover:text-cyan-300 hover:underline underline-offset-4 transition-colors">
                            <?php echo esc_html( $platform_name ); ?>
                        </a>
                    <?php else: echo esc_html( $platform_name ); endif; ?>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-800/80 flex items-center justify-between text-xs font-mono">
                <span class="text-slate-500">TYPE</span>
                <span class="text-slate-300 truncate max-w-[140px]" title="<?php echo esc_attr($type_name); ?>">
                    <?php if ( $type_url && ! is_wp_error( $type_url ) ) : ?>
                        <a href="<?php echo esc_url( $type_url ); ?>" class="hover:underline"><?php echo esc_html( $type_name ); ?></a>
                    <?php else: echo esc_html( $type_name ); endif; ?>
                </span>
            </div>
        </div>

        <?php if ( ! empty( $args['key_metric'] ) ) : ?>
            <!-- การ์ด 4: Key Impact & Result (การ์ดไฮไลท์ผลลัพธ์แบบเด่นเป็นพิเศษ) -->
            <div class="p-5 bg-gradient-to-br from-slate-900/90 to-amber-950/20 border border-amber-500/30 rounded-xl flex flex-col justify-between shadow-md sm:col-span-2 lg:col-span-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-500/20 text-amber-400 border border-amber-500/40 text-sm">
                            ⚡
                        </span>
                        <div>
                            <div class="text-[11px] font-mono uppercase tracking-wider text-amber-500/90 font-bold">Key Project Impact</div>
                            <div class="text-base sm:text-lg font-bold text-amber-300 font-mono">
                                <?php echo esc_html( $args['key_metric'] ); ?>
                            </div>
                        </div>
                    </div>
                    <?php if ( ! empty( $args['app_version'] ) ) : ?>
                        <div class="text-xs font-mono px-3 py-1 bg-slate-950/80 border border-slate-700 text-slate-300 rounded self-start sm:self-auto">
                            Version: <span class="text-emerald-400"><?php echo esc_html( $args['app_version'] ); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        
        <!-- 3. แท็ก Tech Stack (ทำเป็นลิงก์แบบปุ่ม) -->
        <div class="flex-1">
            <div class="text-xs text-slate-500 uppercase tracking-wider mb-2">Tech Stack &amp; Dependencies</div>
            <div class="flex flex-wrap gap-2">
                <?php if ( $tech_stacks && ! is_wp_error( $tech_stacks ) ) : ?>
                    <?php foreach ( $tech_stacks as $stack ) : 
                        $stack_url = get_term_link( $stack );
                        if ( is_wp_error( $stack_url ) ) continue;
                    ?>
                        <a href="<?php echo esc_url( $stack_url ); ?>" class="px-2.5 py-1 text-xs font-mono bg-slate-900 text-blue-300 border border-slate-700 rounded-sm hover:border-emerald-500 hover:text-emerald-400 transition-colors group">
                            <span class="text-slate-500 group-hover:text-emerald-500 transition-colors">#</span><?php echo esc_html( $stack->name ); ?>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-sm text-slate-600">// No stack defined</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- 4. ลิงก์เชื่อมโยง & ปุ่มดาวน์โหลดโปรแกรม (Download & External Links) -->
        <div class="flex flex-wrap gap-3">
            
            <!-- ปุ่ม Download .exe สำหรับงาน Desktop Software -->
            <?php if ( ! empty( $args['download_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['download_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold border border-blue-400 transition-colors flex items-center gap-2 shadow-lg shadow-blue-900/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span><?php echo esc_html( $args['download_label'] ); ?></span>
                    <?php if ( ! empty( $args['file_size'] ) ) : ?>
                        <span class="text-xs opacity-80 font-mono font-normal">(<?php echo esc_html( $args['file_size'] ); ?>)</span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>

            <!-- ปุ่มเปิดเว็บจริง -->
            <?php if ( ! empty( $args['live_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['live_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold border border-emerald-400 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Visit Live Site
                </a>
            <?php endif; ?>

            <!-- เอกสาร / Documentation -->
            <?php if ( ! empty( $args['docs_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['docs_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-bold border border-slate-700 hover:border-slate-600 transition-colors flex items-center gap-2">
                    <i class="huge huge-file-01 text-base"></i>
                    Documentation
                </a>
            <?php endif; ?>

            <!-- ลิงก์ GitHub Repo -->
            <?php if ( ! empty( $args['github_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['github_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-bold border border-slate-700 hover:border-slate-600 transition-colors flex items-center gap-2">
                    <i class="huge huge-git-branch text-base"></i>
                    Repository
                </a>
            <?php endif; ?>

            <!-- App Store -->
            <?php if ( ! empty( $args['app_store'] ) ) : ?>
                <a href="<?php echo esc_url( $args['app_store'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-bold border border-slate-700 hover:border-slate-600 transition-colors flex items-center gap-2">
                    <i class="huge huge-apple text-base"></i>
                    App Store
                </a>
            <?php endif; ?>

            <!-- Play Store -->
            <?php if ( ! empty( $args['play_store'] ) ) : ?>
                <a href="<?php echo esc_url( $args['play_store'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-slate-300 text-sm font-bold border border-slate-700 hover:border-slate-600 transition-colors flex items-center gap-2">
                    <i class="huge huge-play-store text-base"></i>
                    Google Play
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>