<?php get_header(); ?>
<div class="max-w-4xl mx-auto">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article class="mb-10 border-b border-slate-800 pb-10">
            <h2 class="text-3xl text-emerald-400 font-bold mb-4"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="prose prose-invert prose-emerald max-w-none">
                <?php the_excerpt(); ?>
            </div>
        </article>
    <?php endwhile; else: ?>
        <p class="text-slate-500">// No data found.</p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
