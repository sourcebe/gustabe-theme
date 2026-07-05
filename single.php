<?php get_header(); ?>
<div class="max-w-4xl mx-auto">
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="mb-8 border-b border-slate-800 pb-6">
            <h1 class="text-3xl md:text-5xl font-bold text-emerald-400 mb-4"><?php the_title(); ?></h1>
            <div class="flex gap-4 text-slate-500 text-sm font-mono">
                <span>[AUTHOR: <?php the_author(); ?>]</span>
                <span>[DATE: <?php echo get_the_date(); ?>]</span>
            </div>
        </div>
        <div class="prose prose-invert prose-emerald max-w-none">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
