<?php
/**
 * Template Part: Service Hero Section (ด่านที่ 1: The Premium CMD Terminal)
 * Description: UI สไตล์ Command Prompt / Terminal ผสาน Syntax Highlighting เพื่อความ Geek ขั้นสุด
 */

// ดึงข้อมูลจาก Zone 1 (Meta Fields) 
$hero_h1       = get_post_meta( get_the_ID(), '_hero_h1', true );
$hero_subtitle = get_post_meta( get_the_ID(), '_hero_subtitle', true );
$service_icon  = get_post_meta( get_the_ID(), '_service_icon', true );

// Fallback Logic
$display_h1 = ! empty( $hero_h1 ) ? $hero_h1 : get_the_title();
$target_slug = urldecode( get_post_field( 'post_name', get_post() ) );
?>

<header class="relative pt-24 pb-20 lg:pt-36 lg:pb-32 bg-black overflow-hidden border-b border-slate-900 font-sans">
    
    <!-- 🎨 Ambient Background Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-blue-600/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 💻 The CMD Terminal Window -->
        <div class="rounded-xl overflow-hidden border border-slate-800 bg-[#0A0A0A] shadow-2xl shadow-blue-900/20 mb-10">
            
            <!-- Terminal Header Bar (CMD Style) -->
            <div class="bg-slate-900 px-4 py-3 flex items-center justify-between border-b border-slate-800 select-none">
                <div class="flex items-center gap-2 text-slate-400 font-mono text-xs">
                    <i class="huge huge-command-line text-slate-300 text-sm"></i>
                    <span>C:\WINDOWS\system32\cmd.exe - gustabe_engine.exe</span>
                </div>
                <!-- Windows CMD Window Controls -->
                <div class="flex items-center gap-4 text-slate-500">
                    <span class="hover:text-white cursor-pointer block h-[2px] w-3 bg-current mt-2"></span>
                    <span class="hover:text-white cursor-pointer block h-3 w-3 border border-current"></span>
                    <span class="hover:text-red-500 cursor-pointer text-lg leading-none">&times;</span>
                </div>
            </div>

            <!-- Terminal Body (Syntax Highlighting & Line Numbers) -->
            <div class="p-4 md:p-6 flex font-mono text-sm md:text-base overflow-x-auto">
                
                <!-- Line Numbers (The Modern IDE Feel) -->
                <div class="flex flex-col text-slate-700 pr-4 md:pr-6 border-r border-slate-800/50 select-none text-right">
                    <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span>
                    <span>6</span><span>7</span><span>8</span><span>9</span><span>10</span>
                </div>

                <!-- The Code Content -->
                <div class="pl-4 md:pl-6 flex-grow whitespace-pre">
<div class="text-slate-300"><span class="text-green-500">C:\Gustabe></span> node deploy_solution.js --target="<span class="text-amber-300"><?php echo esc_attr( $target_slug ); ?></span>"</div>
<div class="text-slate-500 mt-2">// 🚀 INITIALIZING SYSTEM ARCHITECTURE...</div>
<div><span class="text-pink-500">const</span> <span class="text-blue-400">serviceData</span> <span class="text-pink-500">=</span> <span class="text-pink-500">await</span> <span class="text-blue-300">fetchCoreModules</span>();</div>
<div class="mt-2"><span class="text-pink-500">export default function</span> <span class="text-blue-400">ExecuteService</span>() {</div>
<div>  <span class="text-pink-500">return</span> {</div>

<!-- 🎯 SEO Hook (H1) ซ่อนอยู่ใน Syntax -->
<h1 class="inline-block m-0 font-mono text-sm md:text-base font-normal">
    <span class="text-cyan-400 pl-8">title:</span> <span class="text-amber-300">"<?php echo esc_html( $display_h1 ); ?>"</span><span class="text-slate-300">,</span>
</h1>

<!-- Subtitle -->
<?php if ( ! empty( $hero_subtitle ) ) : ?>
<div class="pl-8"><span class="text-cyan-400">description:</span> <span class="text-amber-300">"<?php echo esc_html( $hero_subtitle ); ?>"</span><span class="text-slate-300">,</span></div>
<?php endif; ?>

<!-- Icon Variable -->
<?php if ( ! empty( $service_icon ) ) : ?>
<div class="pl-8"><span class="text-cyan-400">module_icon:</span> <span class="text-amber-300">"<?php echo esc_html( $service_icon ); ?>"</span><span class="text-slate-300">,</span></div>
<?php endif; ?>

<div class="pl-8"><span class="text-cyan-400">status:</span> <span class="text-green-400">"READY_TO_DEPLOY"</span></div>
<div>  };</div>
<div>}</div>
<div class="mt-2"><span class="text-green-500">C:\Gustabe></span> <span class="inline-block w-2.5 h-5 bg-slate-300 animate-pulse align-middle"></span></div>
                </div>
            </div>
        </div>

        <!-- 🚀 CTA Buttons (Business Conversion Zone) -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <!-- Primary Action -->
            <a href="#pricing-section" class="flex items-center gap-2 px-8 py-4 rounded-md bg-blue-600 hover:bg-blue-500 text-white font-mono font-bold tracking-wide transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] w-full sm:w-auto justify-center">
                <i class="huge huge-code-circle text-xl"></i>
                RUN_DEPLOYMENT()
            </a>
            
            <!-- Secondary Action -->
            <a href="#contact" class="flex items-center gap-2 px-8 py-4 rounded-md border border-slate-700 bg-slate-900 hover:bg-slate-800 text-slate-300 font-mono transition-all w-full sm:w-auto justify-center">
                <i class="huge huge-chat-bot text-xl"></i>
                INITIATE_CONSULT
            </a>
        </div>

    </div>
</header>