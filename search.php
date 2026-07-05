<?php get_header(); ?>
<div class="max-w-4xl mx-auto">
    <header class="mb-10 border-b border-slate-800 pb-6">
        <div class="font-mono text-emerald-500 mb-2">> grep -r "<?php echo get_search_query(); ?>" ./</div>
        <h1 class="text-3xl font-bold text-white">
            Search Results
        </h1>
    </header>

    <div class="space-y-6">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="font-mono">
                <a href="<?php the_permalink(); ?>" class="text-blue-400 hover:underline"><?php the_permalink(); ?></a>
                <div class="mt-1 flex items-start gap-4 text-sm">
                    <span class="text-slate-500">line 1:</span>
                    <span class="text-slate-300"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></span>
                </div>
            </article>
        <?php endwhile; ?>
        
        <div class="mt-8 font-mono">
            <?php the_posts_pagination(); ?>
        </div>
        
        <?php else: ?>
            <p class="text-slate-500 font-mono">0 matches found.</p>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
