<?php get_header(); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-24">
    <!-- The Terminal Window -->
    <div class="bg-slate-900/50 backdrop-blur-md rounded-xl border border-slate-800 shadow-[0_0_40px_rgba(16,185,129,0.05)] overflow-hidden">
        
        <!-- Terminal Header -->
        <div class="flex items-center justify-between px-4 py-2 bg-slate-950 border-b border-slate-800">
            <div class="text-xs text-slate-500 font-mono flex items-center">
                <i class="huge huge-command-line mr-2"></i> root@gustabe-server:~/archive
            </div>
            <div class="flex gap-2">
                <div class="w-3 h-3 rounded-full bg-slate-800"></div>
                <div class="w-3 h-3 rounded-full bg-slate-800"></div>
                <div class="w-3 h-3 rounded-full bg-slate-800"></div>
            </div>
        </div>

        <!-- Terminal Body -->
        <div class="p-6 md:p-8 font-mono">
            <!-- Command Prompt -->
            <div class="mb-8">
                <div class="text-sm md:text-base break-words">
                    <span class="text-emerald-500 font-bold">></span> 
                    <span class="text-slate-300">ls -la --filter="</span><span class="text-emerald-400 font-bold"><?php echo wp_strip_all_tags( get_the_archive_title() ); ?></span><span class="text-slate-300">"</span>
                </div>
                <?php if ( get_the_archive_description() ) : ?>
                <div class="text-slate-500 mt-2 text-sm italic">
                    // <?php echo wp_strip_all_tags( get_the_archive_description() ); ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Directory Listing -->
            <div class="flex flex-col">
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="group flex flex-col md:flex-row gap-2 md:gap-4 border-b border-slate-800/50 py-4 hover:bg-slate-800/10 px-2 rounded transition-colors relative overflow-hidden">
                        
                        <!-- File Permissions & Date -->
                        <div class="text-slate-500 text-xs md:text-sm shrink-0 flex items-center gap-2">
                            <span class="text-emerald-500/50 hidden sm:inline-block">-rw-r--r--</span>
                            <span><?php echo get_the_date('Y-m-d H:i'); ?></span>
                        </div>
                        
                        <!-- File Name & Meta -->
                        <div class="flex-1 min-w-0 flex items-center gap-3">
                            <div class="text-emerald-400 group-hover:text-emerald-300 font-bold truncate">
                                ./<?php echo urldecode( get_post_field( 'post_name', get_post() ) ); ?>.md
                            </div>
                            <div class="hidden md:flex text-slate-400 text-xs truncate opacity-70">
                                <?php echo wp_trim_words( get_the_excerpt(), 10, '...' ); ?>
                            </div>
                        </div>
                        
                        <!-- Author & Size (mock) -->
                        <div class="shrink-0 text-slate-500 text-xs flex items-center gap-3">
                            <span class="hidden sm:inline-block"><?php echo rand(10, 99); ?>KB</span>
                            <span class="text-slate-400"><?php the_author(); ?></span>
                        </div>
                    </a>
                <?php endwhile; else: ?>
                    <div class="text-slate-500 py-4">// Directory empty or not found.</div>
                <?php endif; ?>
            </div>
            
            <div class="mt-8 border-t border-slate-800/50 pt-4 text-emerald-500">
                <?php the_posts_pagination( array(
                    'prev_text' => '<span class="px-2 py-1 border border-slate-700 rounded hover:bg-slate-800 transition">< Prev</span>',
                    'next_text' => '<span class="px-2 py-1 border border-slate-700 rounded hover:bg-slate-800 transition">Next ></span>',
                    'class'     => 'font-mono text-sm flex gap-2'
                ) ); ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
