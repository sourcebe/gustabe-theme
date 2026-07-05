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
?>

<header class="mb-12 border-b-2 border-slate-700 pb-10">
    
    <!-- 1. ส่วนจำลองหน้าจอ Terminal & H1 -->
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-4 select-none">
        <div class="flex gap-1.5">
            <span class="w-3 h-3 rounded-full bg-red-500/80"></span>
            <span class="w-3 h-3 rounded-full bg-yellow-500/80"></span>
            <span class="w-3 h-3 rounded-full bg-green-500/80"></span>
        </div>
        <span class="ml-2 text-slate-600">~/gustabe/portfolio/</span>
    </div>
    
    <div class="mb-8 font-mono">
        <span class="text-emerald-500 font-bold">const</span> <span class="text-blue-400">project_name</span> = 
        <h1 class="inline text-3xl md:text-4xl lg:text-5xl font-bold text-slate-100 tracking-tight">
            "<?php the_title(); ?>"
        </h1><span class="text-slate-400">;</span>
    </div>

    <!-- 2. ส่วนข้อมูล Meta (Grid 5 ช่อง) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 bg-slate-800/50 p-6 border border-slate-700 border-l-4 border-l-emerald-500 mb-8">
        <dl>
            <dt class="text-xs text-slate-500 uppercase tracking-wider mb-1">Client</dt>
            <dd class="text-slate-200 font-semibold truncate" title="<?php echo !empty($args['client']) ? esc_attr($args['client']) : 'Internal Project'; ?>">
                <?php echo !empty($args['client']) ? esc_html($args['client']) : 'Internal Project'; ?>
            </dd>
        </dl>
        <dl>
            <dt class="text-xs text-slate-500 uppercase tracking-wider mb-1">Year</dt>
            <dd class="text-slate-200 font-semibold"><?php echo !empty($args['year']) ? esc_html($args['year']) : '-'; ?></dd>
        </dl>
        <dl>
            <dt class="text-xs text-slate-500 uppercase tracking-wider mb-1">Role</dt>
            <dd class="text-emerald-400 font-semibold"><?php echo !empty($args['role']) ? esc_html($args['role']) : 'Development'; ?></dd>
        </dl>
        
        <!-- ⚡ ทำ Platform เป็นลิงก์ -->
        <dl>
            <dt class="text-xs text-slate-500 uppercase tracking-wider mb-1">Platform</dt>
            <dd class="text-cyan-400 font-semibold">
                <?php if ( $platform_url && ! is_wp_error( $platform_url ) ) : ?>
                    <a href="<?php echo esc_url( $platform_url ); ?>" class="hover:text-emerald-300 hover:underline underline-offset-4 transition-colors">
                        <?php echo esc_html( $platform_name ); ?>
                    </a>
                <?php else: echo esc_html( $platform_name ); endif; ?>
            </dd>
        </dl>

        <!-- ⚡ ทำ Type เป็นลิงก์ -->
        <dl>
            <dt class="text-xs text-slate-500 uppercase tracking-wider mb-1">Type</dt>
            <dd class="text-cyan-400 font-semibold">
                <?php if ( $type_url && ! is_wp_error( $type_url ) ) : ?>
                    <a href="<?php echo esc_url( $type_url ); ?>" class="hover:text-emerald-300 hover:underline underline-offset-4 transition-colors">
                        <?php echo esc_html( $type_name ); ?>
                    </a>
                <?php else: echo esc_html( $type_name ); endif; ?>
            </dd>
        </dl>
    </div>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6">
        
        <!-- 3. แท็ก Tech Stack (ทำเป็นลิงก์แบบปุ่ม) -->
        <div class="flex-1">
            <div class="text-xs text-slate-500 uppercase tracking-wider mb-2">Tech Stack</div>
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

        <!-- 4. ลิงก์เชื่อมโยง (External Links) -->
        <div class="flex flex-wrap gap-3">
            <?php if ( ! empty( $args['live_url'] ) ) : ?>
                <a href="<?php echo esc_url( $args['live_url'] ); ?>" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold border border-emerald-400 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Visit Live Site
                </a>
            <?php endif; ?>
            <!-- GitHub Link ละไว้เพื่อความสั้นกระชับ (ใช้โค้ดเดิมของนายได้เลย) -->
        </div>

    </div>
</header>