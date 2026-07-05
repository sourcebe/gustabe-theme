<?php get_header(); ?>
<div class="max-w-4xl mx-auto">
    <header class="mb-12">
        <h1 class="text-4xl font-bold text-white mb-2">
            > <?php the_archive_title(); ?>
        </h1>
        <div class="text-slate-500 font-mono">
            <?php the_archive_description(); ?>
        </div>
    </header>

    <div class="space-y-8">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="p-6 border border-slate-800 rounded-lg bg-slate-900/50 hover:border-emerald-500/50 transition-colors">
                <h2 class="text-2xl text-emerald-400 font-bold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div class="text-slate-400 text-sm mb-4"><?php echo get_the_date(); ?></div>
                <div class="text-slate-300">
                    <?php the_excerpt(); ?>
                </div>
                <a href="<?php the_permalink(); ?>" class="inline-block mt-4 text-emerald-500 hover:text-emerald-400 font-mono">Read_More()</a>
            </article>
        <?php endwhile; ?>
        
        <div class="mt-8 font-mono">
            <?php the_posts_pagination(); ?>
        </div>
        
        <?php else: ?>
            <p class="text-slate-500">// Directory empty or not found.</p>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
