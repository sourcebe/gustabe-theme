<?php
/**
 * file: template-parts/home/hero-editor.php
 * หน้าที่: Hero Section สไตล์ VS Code (Split-Pane) + Safe Geek Tracking
 * รองรับ: Mobile, Tablet, Desktop (Tailwind Responsive)
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<section class="relative min-h-[100dvh] flex flex-col justify-start md:justify-center items-center px-4 sm:px-6 lg:px-8 pt-32 md:pt-24 pb-12 overflow-hidden bg-black">
    
    <!-- ⚡ 1. The Hidden H1 for Semantic SEO -->
    <h1 class="sr-only">รับทำเว็บไซต์และออกแบบเว็บไซต์ธุรกิจ การตลาดออนไลน์ครบวงจร โดยทีมงานมืออาชีพ</h1>

    <!-- Glow Effect in Background -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[60vh] max-w-[1000px] bg-gradient-to-r from-blue-600/20 to-indigo-600/20 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="w-full max-w-5xl flex flex-col items-center z-10 space-y-8">
        
        <!-- Jules Style Prompt Interface -->
        <div class="w-full md:w-3/4 bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-2xl p-4 shadow-[0_0_30px_rgba(0,0,0,0.5)] relative">
            <div class="flex items-start gap-4">
                <!-- User Avatar (Simulation) -->
                <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center shrink-0">
                    <i class="huge huge-user text-slate-400"></i>
                </div>
                <div class="flex-1 space-y-2">
                    <div class="text-slate-300 font-sans text-lg font-medium leading-relaxed">
                        <span id="prompt-text" class="text-white"></span>
                        <span class="inline-block w-1.5 h-5 bg-blue-500 animate-pulse align-middle ml-1" id="prompt-cursor"></span>
                    </div>
                </div>
            </div>
            
            <!-- Gustabe Action Indicators -->
            <div id="gustabe-actions" class="mt-6 flex items-center gap-3 text-sm text-slate-500 font-mono opacity-0 transition-opacity duration-500">
                <i class="huge huge-sparkles text-blue-500 animate-spin-slow"></i>
                <span id="gustabe-status">GUSTABE is thinking...</span>
            </div>
        </div>

        <!-- GitHub / Editor Result Box (Appears after prompt) -->
        <div id="result-box" class="w-full bg-slate-950 border border-white/10 rounded-xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.8)] opacity-0 transform translate-y-8 transition-all duration-1000 ease-out">
            <!-- Top Bar -->
            <div class="bg-slate-900 px-4 py-3 flex items-center justify-between border-b border-white/10">
                <div class="flex items-center gap-3">
                    <i class="huge huge-git-branch text-slate-400"></i>
                    <span class="text-slate-300 text-sm font-sans font-medium">gustabe / agency-website</span>
                </div>
                <div class="flex gap-2">
                    <span class="px-2 py-1 text-[10px] font-mono border border-white/10 text-slate-400 rounded-md bg-white/5">Public</span>
                    <span class="px-2 py-1 text-[10px] font-mono border border-white/10 text-blue-400 rounded-md bg-blue-500/10"><i class="huge huge-sparkles mr-1"></i>GUSTABE Generated</span>
                </div>
            </div>
            
            <!-- Code Editor Area -->
            <div class="p-5 font-mono text-[13px] md:text-[14px] overflow-x-auto">
                <div class="flex text-slate-500 leading-relaxed">
                    <div class="flex flex-col text-right pr-4 select-none border-r border-white/10 mr-4 shrink-0">
                        <span>1</span><span>2</span><span>3</span><span>4</span><span>5</span><span>6</span><span>7</span><span>8</span><span>9</span><span>10</span><span>11</span><span>12</span>
                    </div>
                    <div class="flex-1 text-slate-300" id="code-target">
                        <!-- Code injected by JS -->
                    </div>
                </div>
            </div>
            
            <!-- GitHub Style Bottom Bar -->
            <div class="bg-slate-900 px-4 py-2 flex items-center justify-between border-t border-white/10 text-[11px] font-mono text-slate-500">
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-code"></i> TypeScript</span>
                    <span class="flex items-center gap-1 hover:text-blue-400 cursor-pointer transition-colors"><i class="huge huge-check-circle"></i> Compiled</span>
                </div>
                <div>Ready in <span id="compile-time" class="text-emerald-400">0.00ms</span></div>
            </div>
        </div>
    </div>
</section>

<!-- Vanilla JS Engine -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Prompt Typing
    const promptText = "สร้างเว็บไซต์ธุรกิจที่ล้ำสมัยให้หน่อย ขอแบบโหลดเร็วและดึงดูดลูกค้าได้จริง";
    const promptEl = document.getElementById('prompt-text');
    const promptCursor = document.getElementById('prompt-cursor');
    const gustabeActions = document.getElementById('gustabe-actions');
    const gustabeStatus = document.getElementById('gustabe-status');
    const resultBox = document.getElementById('result-box');
    const codeTarget = document.getElementById('code-target');
    const compileTime = document.getElementById('compile-time');

    let pIndex = 0;
    
    function typePrompt() {
        if (pIndex < promptText.length) {
            promptEl.innerHTML += promptText.charAt(pIndex);
            pIndex++;
            setTimeout(typePrompt, Math.random() * 50 + 20); // Random typing speed
        } else {
            promptCursor.classList.add('hidden');
            gustabeActions.classList.remove('opacity-0');
            setTimeout(startGustabeProcess, 800);
        }
    }

    // 2. GUSTABE Processing States
    const states = [
        "GUSTABE is analyzing requirements...",
        "Generating robust architecture...",
        "Applying GitHub-grade security...",
        "Compiling Next-gen UI..."
    ];

    function startGustabeProcess() {
        let stateIndex = 0;
        const stateInterval = setInterval(() => {
            if (stateIndex < states.length) {
                gustabeStatus.innerText = states[stateIndex];
                stateIndex++;
            } else {
                clearInterval(stateInterval);
                gustabeStatus.innerHTML = '<span class="text-emerald-400">Generation Complete!</span>';
                gustabeActions.querySelector('i').classList.replace('huge-sparkles', 'huge-check-circle');
                gustabeActions.querySelector('i').classList.replace('text-blue-500', 'text-emerald-400');
                gustabeActions.querySelector('i').classList.remove('animate-spin-slow');
                
                showResultBox();
            }
        }, 600);
    }

    // 3. Show Result Box and Type Code
    function showResultBox() {
        resultBox.classList.remove('opacity-0', 'translate-y-8');
        setTimeout(typeCode, 800);
    }

    const codeLines = [
        { text: 'import { GustabeEngine } from "@gustabe/core";', color: 'text-pink-400' },
        { text: 'import { ModernUI } from "@gustabe/ui";', color: 'text-pink-400' },
        { text: ' ', color: '' },
        { text: '// We focus on High-Performance Web Development', color: 'text-slate-500 italic' },
        { text: 'const Platform = new GustabeEngine({', color: 'text-blue-400' },
        { text: '  quality: "100% Custom Build",', color: 'text-green-300 ml-4' },
        { text: '  services: ["Web App", "E-Commerce", "SEO"],', color: 'text-green-300 ml-4' },
        { text: '  ui: ModernUI.init({ theme: "github-dark" })', color: 'text-purple-400 ml-4' },
        { text: '});', color: 'text-blue-400' },
        { text: ' ', color: '' },
        { text: 'await Platform.deploy();', color: 'text-yellow-300' },
        { text: 'console.log("Your business is now unstoppable. 🚀");', color: 'text-green-400' }
    ];

    let lineIndex = 0;
    let charIndex = 0;
    let startTime = Date.now();
    
    function typeCode() {
        if (lineIndex < codeLines.length) {
            const currentLine = codeLines[lineIndex];
            
            if (charIndex === 0) {
                const span = document.createElement('div');
                span.className = currentLine.color + ' whitespace-pre-wrap';
                codeTarget.appendChild(span);
            }

            const currentSpan = codeTarget.lastElementChild;
            
            if (charIndex < currentLine.text.length) {
                currentSpan.innerHTML += currentLine.text.charAt(charIndex);
                charIndex++;
                setTimeout(typeCode, 15);
            } else {
                lineIndex++;
                charIndex = 0;
                setTimeout(typeCode, 100);
            }
        } else {
            const cursor = document.createElement('span');
            cursor.className = 'inline-block w-2.5 h-5 bg-white/50 animate-pulse align-middle ml-1';
            codeTarget.lastElementChild.appendChild(cursor);
            
            // Update compile time
            compileTime.innerText = (Date.now() - startTime) + "ms";
        }
    }

    // Start everything
    setTimeout(typePrompt, 1000);
});
</script>