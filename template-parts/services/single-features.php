<?php
/**
 * Template Part: Service Features Section (ด่านที่ 2: The JSON Snippet Bento)
 * Description: การผสมผสานหน้าจอเขียนโค้ดและโครงสร้าง JSON เพื่อโชว์ความโปร
 */

if ( ! get_the_ID() ) return;
?>

<section id="core-capabilities" class="relative py-24 bg-[#050505] border-b border-slate-900 overflow-hidden font-sans">
    
    <!-- ⚡ Grid Pattern Background -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSIjMTEzMzFmIiBzdHJva2Utd2lkdGg9IjEiIGZpbGw9Im5vbmUiPjxwYXRoIGQ9Ik00MCAwaC00MHY0MGg0MHoiLz48L2c+PC9zdmc+')] opacity-[0.05] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded bg-green-500/10 border border-green-500/20 text-green-400 font-mono text-xs mb-4 uppercase tracking-widest">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                System_Capabilities.json
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-white tracking-tight">
                Architectural <span class="text-green-500">Nodes</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <?php 
            for ( $i = 1; $i <= 3; $i++ ) : 
                $f_title = get_post_meta( get_the_ID(), "_feature_{$i}_title", true );
                $f_desc  = get_post_meta( get_the_ID(), "_feature_{$i}_desc", true );
                $f_icon  = get_post_meta( get_the_ID(), "_feature_{$i}_icon", true );

                if ( empty( $f_title ) ) continue;

                // Bento Logic: กล่องแรกกว้างกว่าในหน้าจอ Tablet
                $bento_class = ( $i === 1 ) ? 'md:col-span-2 lg:col-span-1' : 'col-span-1';
            ?>
                
                <!-- 💻 Snippet Window -->
                <div class="<?php echo esc_attr( $bento_class ); ?> group relative rounded-xl bg-[#0D0D0D] border border-slate-800 hover:border-green-500/50 transition-all duration-500 flex flex-col shadow-2xl">
                    
                    <!-- Header Bar -->
                    <div class="flex items-center justify-between px-4 py-2 border-b border-slate-800 bg-[#141414]">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#FF5F56] transition-colors"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#FFBD2E] transition-colors"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#27C93F] transition-colors"></div>
                        </div>
                        <span class="text-[10px] font-mono text-slate-600 uppercase tracking-tighter">feature_0<?php echo $i; ?>.json</span>
                    </div>

                    <!-- Editor Body -->
                    <div class="p-6 md:p-8 font-mono text-sm leading-relaxed">
                        
                        <!-- Icon Focus -->
                        <div class="mb-6 text-slate-500 group-hover:text-green-400 transition-colors duration-300">
                            <i class="huge <?php echo esc_attr( $f_icon ); ?> text-3xl"></i>
                        </div>

                        <!-- JSON Object Style -->
                        <div class="space-y-1">
                            <div class="text-slate-500">{</div>
                            <div class="pl-4">
                                <span class="text-pink-500">"key"</span>: <span class="text-amber-300">"<?php echo esc_html( $f_title ); ?>"</span>,
                            </div>
                            <div class="pl-4 flex flex-wrap">
                                <span class="text-pink-500">"value"</span>: <span class="text-green-400">"<?php echo esc_html( $f_desc ); ?>"</span>
                            </div>
                            <div class="text-slate-500">}</div>
                        </div>

                    </div>

                    <!-- Glow Effect -->
                    <div class="absolute inset-0 bg-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                </div>

            <?php endfor; ?>

        </div>
    </div>
</section>