<?php get_header(); ?>
<div class="flex flex-col items-center justify-center h-full text-center">
    <div class="text-red-500 mb-6">
        <i class="huge-alert-02 text-6xl"></i>
    </div>
    <h1 class="text-5xl font-bold text-white mb-4">FATAL_ERROR: 404</h1>
    <p class="text-xl text-slate-400 font-mono mb-8">System cannot locate the requested file.</p>
    
    <div class="bg-black/50 border border-slate-800 rounded p-4 font-mono text-left max-w-lg w-full text-sm text-slate-500 mb-8">
        > TRACEBACK:<br>
        > URL: <?php echo esc_url($_SERVER['REQUEST_URI']); ?><br>
        > STATUS: Module Not Found<br>
        > ACTION: Terminated
    </div>

    <a href="<?php echo esc_url(home_url()); ?>" class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded font-mono border border-slate-700 transition-colors">
        ./restart_system.sh
    </a>
</div>
<?php get_footer(); ?>
