<?php
/**
 * file: template-parts/portfolio/hero-showcase.php
 * หน้าที่: Adaptive Hero Showcase Frame สำหรับ CPT our_works
 * เปลี่ยนหน้ากากพรีวิวตามประเภทงาน:
 * 1. Web Application -> Browser Frame (URL bar, refresh, responsive)
 * 2. Desktop Software -> Windows 11 App Frame (_ □ ✕, running binary title)
 * 3. CLI Tool -> Terminal Console Shell (black, copyable command, prompt)
 * 4. Mobile App -> Smartphone Mockup Frame (dynamic island/notch)
 * 5. Graphic / Artwork -> Frameless Ultra Clean Canvas
 * Style: Geek IDE / Terminal (รองรับทั้ง 5 ธีม data-theme)
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$post_id      = get_the_ID();
$project_mode = ! empty( $args['project_mode'] ) ? $args['project_mode'] : 'web';
$thumb_id     = get_post_thumbnail_id( $post_id );
$thumb_url    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'full' ) : '';
$alt_text     = get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title();
$cli_command  = ! empty( $args['cli_command'] ) ? $args['cli_command'] : '';
$live_url     = ! empty( $args['live_url'] ) ? $args['live_url'] : '';
$domain_host  = $live_url ? ( parse_url( $live_url, PHP_URL_HOST ) ?: $live_url ) : 'localhost:3000';
?>

<div class="mb-14 select-none" id="adaptive-showcase-frame">

    <?php if ( $project_mode === 'cli' ) : ?>
        <!-- ======================================================== -->
        <!-- ⚡ 1. CLI / Terminal Console Frame (ไม่มีรูปก็ได้ เน้นคำสั่ง) -->
        <!-- ======================================================== -->
        <div class="rounded-xl border border-slate-700 bg-slate-950 shadow-2xl overflow-hidden font-mono">
            <!-- Terminal Titlebar -->
            <div class="bg-slate-900 px-4 py-3 border-b border-slate-800 flex items-center justify-between text-xs text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                    <span class="ml-2 text-slate-300 font-semibold">bash — 80x24</span>
                </div>
                <div class="text-[11px] text-slate-500 hidden sm:block">POSIX CLI Engine</div>
            </div>

            <div class="p-6 md:p-8 space-y-4">
                <!-- Command Box with One-Click Copy -->
                <?php if ( $cli_command ) : ?>
                    <div class="flex items-center justify-between gap-4 p-4 rounded-lg bg-slate-900 border border-slate-800">
                        <div class="flex items-center gap-3 overflow-x-auto text-sm md:text-base">
                            <span class="text-emerald-400 font-bold select-none">$</span>
                            <span class="text-slate-200" id="cli-cmd-text"><?php echo esc_html( $cli_command ); ?></span>
                        </div>
                        <button type="button" 
                                onclick="navigator.clipboard.writeText(document.getElementById('cli-cmd-text').innerText); this.innerText='COPIED! ⚡'; setTimeout(()=>this.innerText='COPY', 2000);"
                                class="shrink-0 px-3 py-1 text-xs font-mono font-bold bg-slate-800 hover:bg-emerald-500 text-slate-300 hover:text-slate-950 border border-slate-700 rounded transition-all cursor-pointer">
                            COPY
                        </button>
                    </div>
                <?php endif; ?>

                <?php if ( $thumb_url ) : ?>
                    <!-- ภาพ Capture Terminal Output จริง -->
                    <div class="rounded-lg overflow-hidden border border-slate-800 mt-4">
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="w-full object-cover">
                    </div>
                <?php else : ?>
                    <!-- Output จำลองเมื่อไม่มีรูปภาพ -->
                    <div class="text-xs sm:text-sm text-slate-400 space-y-1.5 pt-2">
                        <div class="text-slate-500">// System diagnostics &amp; execution pipeline</div>
                        <div class="text-emerald-400">✓ Package dependencies verified [OK]</div>
                        <div class="text-cyan-400">ℹ Initializing runtime environment...</div>
                        <div class="text-slate-300">Ready. Listening on standard input / socket stream.</div>
                        <div class="flex items-center gap-1 text-emerald-400 font-bold pt-2">
                            <span>admin@gustabe:~$</span>
                            <span class="inline-block w-2 h-4 bg-emerald-400 animate-pulse"></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ( $project_mode === 'software' ) : ?>
        <!-- ======================================================== -->
        <!-- 💻 2. Desktop Software Frame (Windows 11 / Native App) -->
        <!-- ======================================================== -->
        <div class="rounded-xl border border-slate-700 bg-slate-950 shadow-2xl overflow-hidden font-sans">
            <!-- Windows Titlebar -->
            <div class="bg-slate-900/95 px-4 py-2.5 border-b border-slate-800 flex items-center justify-between text-xs select-none">
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 flex items-center justify-center rounded-sm bg-blue-600 text-[10px] text-white font-bold font-mono">⊞</span>
                    <span class="text-slate-200 font-medium font-mono truncate max-w-[250px] sm:max-w-md">
                        <?php the_title(); ?><?php echo !str_ends_with(strtolower(get_the_title()), '.exe') ? '.exe' : ''; ?>
                    </span>
                    <span class="text-[10px] font-mono text-emerald-400 px-1.5 py-0.5 bg-emerald-950/60 border border-emerald-800 rounded">Running</span>
                </div>
                <!-- Windows Min/Max/Close Buttons -->
                <div class="flex items-center gap-3 text-slate-400">
                    <span class="hover:text-white cursor-default text-sm">─</span>
                    <span class="hover:text-white cursor-default text-xs">□</span>
                    <span class="hover:bg-red-600 hover:text-white px-2 py-0.5 rounded cursor-default text-xs font-bold transition-colors">✕</span>
                </div>
            </div>

            <!-- หน้าต่างแสดง Screenshot โปรแกรม -->
            <div class="relative bg-slate-900/40 p-2 sm:p-4 flex items-center justify-center">
                <?php if ( $thumb_url ) : ?>
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="w-full h-auto rounded shadow-lg border border-slate-800">
                <?php else : ?>
                    <div class="py-16 text-center text-slate-500 font-mono text-xs">
                        [No application screenshot configured. Set Featured Image in WordPress]
                    </div>
                <?php endif; ?>
            </div>

            <!-- Windows Statusbar -->
            <div class="bg-slate-950 px-4 py-2 border-t border-slate-800/80 flex items-center justify-between text-[11px] font-mono text-slate-500">
                <div>Status: Ready</div>
                <div><?php echo !empty($args['target_os']) ? esc_html($args['target_os']) : 'Win32 System Target'; ?></div>
            </div>
        </div>

    <?php elseif ( $project_mode === 'mobile' ) : ?>
        <!-- ======================================================== -->
        <!-- 📱 3. Mobile Device Frame (Smartphone Mockup Frame) -->
        <!-- ======================================================== -->
        <div class="flex justify-center py-4">
            <div class="w-full max-w-[320px] sm:max-w-[360px] rounded-[40px] p-3 bg-slate-800 border-4 border-slate-700 shadow-2xl relative">
                <!-- Speaker / Dynamic Island -->
                <div class="absolute top-6 left-1/2 -translate-x-1/2 w-24 h-4 bg-slate-950 rounded-full z-20 flex items-center justify-end px-2">
                    <span class="w-2 h-2 rounded-full bg-slate-900 border border-slate-700"></span>
                </div>
                
                <!-- จอภาพมือถือ -->
                <div class="rounded-[30px] overflow-hidden bg-black aspect-[9/19.5] relative flex items-center justify-center">
                    <?php if ( $thumb_url ) : ?>
                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="w-full h-full object-cover">
                    <?php else : ?>
                        <div class="text-slate-600 text-xs font-mono">Mobile Screen View</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <?php elseif ( $project_mode === 'graphic' ) : ?>
        <!-- ======================================================== -->
        <!-- 🎨 4. Graphic / UI Artwork Frame (Frameless Clean Canvas) -->
        <!-- ======================================================== -->
        <div class="rounded-2xl overflow-hidden border border-slate-800 bg-slate-950 shadow-2xl">
            <?php if ( $thumb_url ) : ?>
                <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="w-full h-auto object-cover">
            <?php else : ?>
                <div class="py-20 text-center text-slate-600 font-mono text-xs">Artwork Canvas Area</div>
            <?php endif; ?>
        </div>

    <?php else : ?>
        <!-- ======================================================== -->
        <!-- 🌐 5. Default Web Application Frame (Browser Inspector) -->
        <!-- ======================================================== -->
        <div class="rounded-xl border border-slate-700 bg-slate-950 shadow-2xl overflow-hidden">
            <!-- Browser Address Bar -->
            <div class="bg-slate-900 px-4 py-3 border-b border-slate-800 flex items-center gap-3 select-none">
                <div class="flex items-center gap-1.5 shrink-0">
                    <span class="w-3 h-3 rounded-full bg-red-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-yellow-500/80 inline-block"></span>
                    <span class="w-3 h-3 rounded-full bg-emerald-500/80 inline-block"></span>
                </div>

                <!-- URL Bar -->
                <div class="flex-1 bg-slate-950 border border-slate-800 rounded-md px-3 py-1.5 text-xs font-mono text-slate-300 flex items-center justify-between truncate">
                    <div class="flex items-center gap-2 truncate">
                        <span class="text-emerald-500">🔒 https://</span>
                        <span class="truncate"><?php echo esc_html( $domain_host ); ?></span>
                    </div>
                    <?php if ( $live_url ) : ?>
                        <a href="<?php echo esc_url( $live_url ); ?>" target="_blank" rel="noopener noreferrer" 
                           class="text-[10px] text-emerald-400 hover:text-emerald-300 uppercase tracking-wider font-bold ml-2 shrink-0">
                            Launch ↗
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ภาพแคปหน้าจอเว็บ -->
            <div class="relative bg-slate-950">
                <?php if ( $thumb_url ) : ?>
                    <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" class="w-full h-auto object-cover">
                <?php else : ?>
                    <div class="py-20 text-center text-slate-600 font-mono text-xs">
                        [Web preview snapshot will appear here]
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php endif; ?>

</div>
