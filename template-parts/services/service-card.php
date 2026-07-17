<?php
/**
 * Template Part: Service Card
 * Description: การ์ดแสดงผลบริการสไตล์ System Module / IDE
 */

$post_id = get_the_ID();
$price_value = get_post_meta($post_id, '_starting_price_value', true);
$price_display = !empty($price_value) ? number_format($price_value) . ' THB' : 'CONSULT';

// ดึง Description จาก The SEO Framework (TSF) มาโชว์ในการ์ด
$service_desc = '';
if (function_exists('the_seo_framework')) {
    $service_desc = the_seo_framework()->get_description(['id' => $post_id]);
}
if (empty($service_desc)) {
    $service_desc = get_the_excerpt($post_id);
}
?>

<div class="group relative bg-slate-900 border border-slate-800 hover:border-green-500/50 transition-all duration-500 rounded-xl overflow-hidden flex flex-col h-full shadow-2xl">
    
    <div class="flex items-center justify-between px-4 py-2 bg-slate-950 border-b border-slate-800">
        <div class="flex gap-1.5">
            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#FF5F56] transition-colors duration-500"></div>
            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#FFBD2E] transition-colors duration-500"></div>
            <div class="w-2.5 h-2.5 rounded-full bg-slate-800 group-hover:bg-[#27C93F] transition-colors duration-500"></div>
        </div>
        <span class="text-[10px] font-mono text-slate-600 uppercase tracking-widest italic">module_v<?php echo GUSTABE_THEME_VERSION; ?></span>
    </div>

    <div class="p-6 md:p-8 flex-grow font-mono">
        <div class="mb-6 text-slate-500 group-hover:text-green-400 transition-colors duration-300">
            <i class="huge huge-code-square text-4xl"></i>
        </div>

        <div class="space-y-4">
            <div>
                <span class="text-pink-500 text-xs">// service_title</span>
                <h3 class="text-xl font-bold text-white group-hover:text-green-400 transition-colors">
                    <?php the_title(); ?><span class="inline-block w-2 h-5 bg-green-500 ml-2 opacity-0 group-hover:animate-pulse group-hover:opacity-100"></span>
                </h3>
            </div>

            <div class="text-slate-400 text-sm leading-relaxed line-clamp-3">
                <span class="text-slate-600 text-xs block mb-1">/** description */</span>
                <?php echo wp_strip_all_tags($service_desc); ?>
            </div>
        </div>
    </div>

    <div class="p-6 pt-0 mt-auto font-mono">
        <div class="flex items-center justify-between border-t border-slate-800 pt-6">
            <div class="flex flex-col">
                <span class="text-[10px] text-slate-500 uppercase tracking-tighter">Base_Fee</span>
                <span class="text-green-500 font-bold"><?php echo $price_display; ?></span>
            </div>
            
            <a href="<?php the_permalink(); ?>" class="px-4 py-2 bg-slate-900 border border-slate-700 text-slate-300 text-xs hover:bg-green-500 hover:text-slate-950 hover:border-green-500 transition-all rounded uppercase font-bold">
                Execute()
            </a>
        </div>
    </div>

    <div class="absolute inset-0 bg-green-500/5 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
</div>