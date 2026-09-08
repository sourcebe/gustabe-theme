<?php get_header(); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
    <?php while ( have_posts() ) : the_post(); ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative">
        
        <!-- Left Column: The Code Editor -->
        <div class="col-span-1 lg:col-span-8 xl:col-span-9 relative">
            
            <!-- Pixel Art Mascot (Attached to the left) -->
            <div class="hidden lg:block absolute -left-8 top-16 w-16 h-16 z-10 text-slate-500 animate-[bounce_4s_infinite]">
                <svg viewBox="0 0 24 24" fill="currentColor" class="w-full h-full drop-shadow-lg">
                    <path d="M12 2C8.686 2 6 4.686 6 8v3.5l-2.5 2.5v2h2v2h2v2h2v-2h2v-2h2v2h2v2h2v-2h2v-2h2v-2h2v-2l-2.5-2.5V8c0-3.314-2.686-6-6-6zm-3 8c-.828 0-1.5-.672-1.5-1.5S8.172 7 9 7s1.5.672 1.5 1.5S9.828 10 9 10zm6 0c-.828 0-1.5-.672-1.5-1.5S14.172 7 15 7s1.5.672 1.5 1.5S15.828 10 15 10zm-4.5 4h3v2h-3v-2z"/>
                </svg>
            </div>

            <!-- The Editor Window -->
            <div class="bg-slate-900/80 backdrop-blur-xl rounded-2xl border-2 border-slate-800 shadow-[8px_8px_0px_#0f204c] overflow-hidden min-h-screen">
                
                <!-- Editor Header -->
                <div class="flex items-center px-4 py-3 bg-slate-950 border-b border-slate-800">
                    <div class="flex gap-2 mr-4">
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                    </div>
                    <div class="text-xs text-slate-500 font-mono">
                        ~/workspace/post_<?php echo get_the_ID(); ?>.md
                    </div>
                </div>

                <!-- Editor Body with Line Numbers -->
                <div class="relative p-6 md:p-10 pl-12 md:pl-16 font-mono border-l-4 border-slate-800/30 ml-4 md:ml-8 my-4 editor-line-numbers">
                    
                    <div class="mb-10">
                        <h1 class="text-3xl md:text-5xl font-bold text-slate-200 mb-4 font-sans leading-tight">
                            <?php the_title(); ?>
                        </h1>
                        <div class="text-slate-500 text-sm">
                            <span class="text-emerald-500/50">//</span> Objective: Read and understand the following module
                        </div>
                    </div>

                    <!-- File Content -->
                    <div class="prose prose-invert prose-emerald max-w-none prose-headings:text-slate-200 text-slate-300 font-sans">
                        <?php the_content(); ?>
                    </div>
                    
                    <div class="mt-12 text-slate-500 text-sm italic border-t border-slate-800/50 pt-6">
                        <span class="text-emerald-500 font-bold">return</span> "done";
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: The "Jules" Sticky Card -->
        <div class="col-span-1 lg:col-span-4 xl:col-span-3">
            <div class="sticky top-32">
                
                <div class="bg-slate-900/90 backdrop-blur-xl rounded-2xl border-2 border-slate-800 shadow-[8px_8px_0px_rgba(0,0,0,0.5)] p-6 relative">
                    <!-- Decorator dots -->
                    <div class="absolute -top-3 -right-3 w-6 h-6 border-2 border-slate-800 bg-slate-950 rounded-full"></div>
                    
                    <div class="flex flex-col items-center text-center mb-6">
                        <!-- Sad Face / AI Avatar -->
                        <div class="w-16 h-16 rounded-full bg-slate-800 border-2 border-slate-700 flex items-center justify-center mb-4 text-3xl">
                            🤖
                        </div>
                        <p class="text-slate-300 text-sm font-mono leading-relaxed">
                            <span class="text-emerald-400 font-bold">Jules</span> analyzes coding tasks you 
                            <span class="underline decoration-emerald-500 decoration-2 underline-offset-4">don't want</span> to do.
                        </p>
                    </div>

                    <!-- Post Meta as "Tasks" -->
                    <div class="flex flex-wrap gap-2 justify-center mb-6 font-mono text-xs font-bold uppercase tracking-widest">
                        <div class="bg-emerald-500 text-slate-950 px-3 py-1.5 rounded-sm border-2 border-transparent hover:border-slate-800 cursor-default transition-all shadow-[2px_2px_0px_#0f204c]">
                            <?php the_author(); ?>
                        </div>
                        <div class="bg-blue-500 text-white px-3 py-1.5 rounded-sm border-2 border-transparent hover:border-slate-800 cursor-default transition-all shadow-[2px_2px_0px_#0f204c]">
                            <?php echo get_the_date('M Y'); ?>
                        </div>
                        <?php 
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            foreach ( $categories as $category ) {
                                echo '<div class="bg-slate-800 text-slate-300 px-3 py-1.5 rounded-sm border-2 border-transparent hover:border-slate-700 transition-all shadow-[2px_2px_0px_#000000]">' . esc_html( $category->name ) . '</div>';
                            }
                        }
                        ?>
                    </div>

                    <!-- Action Button -->
                    <a href="#comments" class="block w-full text-center py-3 bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700 rounded font-mono text-sm transition-colors mt-4">
                        > Run Analysis_
                    </a>
                </div>

            </div>
        </div>

    </div>
    
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
