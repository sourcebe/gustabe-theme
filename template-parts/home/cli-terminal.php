<?php
/**
 * file: template-parts/home/cli-terminal.php
 * หน้าที่: แสดงจุดแข็ง 4 ข้อ (ทำไมต้องเลือกเรา) สไตล์ Terminal พร้อม Scroll Trigger Animation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<section id="cli-terminal-section" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24 mb-16 relative z-20">
    
    <!-- ⚡ SEO: ซ่อน H2 ไว้ให้ Google Bot เก็บ Keyword เรื่องความน่าเชื่อถือ -->
    <h2 class="sr-only">ทำไมต้องเลือก Gustabe รับทำเว็บไซต์ - จุดแข็งและบริการหลังการขายของเรา</h2>

    <!-- The Terminal Window -->
    <div class="bg-black rounded-xl border border-slate-800 shadow-[0_0_40px_rgba(16,185,129,0.05)] overflow-hidden">
        
        <!-- Terminal Header -->
        <div class="flex items-center justify-between px-4 py-2 bg-slate-900 border-b border-slate-800">
            <div class="text-xs text-slate-500 font-mono flex items-center">
                <i class="huge huge-command-line mr-2"></i> root@gustabe-server:~
            </div>
            <div class="text-xs text-slate-600 font-mono">
                bash - 80x24
            </div>
        </div>

        <!-- Terminal Body (The Output Space) -->
        <div class="p-6 md:p-8 font-mono text-sm md:text-base leading-relaxed text-emerald-400 min-h-[300px]">
            <div id="cli-output" class="flex flex-col gap-2">
                <!-- JS จะทำการ Inject บรรทัดคำสั่งลงมาตรงนี้เมื่อ Scroll มาถึง -->
            </div>
            <span id="cli-cursor" class="inline-block w-2.5 h-5 bg-emerald-400 animate-pulse mt-2 hidden"></span>
        </div>

    </div>
</section>

<!-- ⚡ Vanilla JS: Scroll Trigger & CLI Animation Engine -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const terminalSection = document.getElementById('cli-terminal-section');
    const outputContainer = document.getElementById('cli-output');
    const cursor = document.getElementById('cli-cursor');
    let hasRun = false; // ป้องกันการรันแอนิเมชันซ้ำเมื่อเลื่อนขึ้นลง

    // 1. เตรียมข้อมูล Log (จุดแข็ง 4 ข้อ)
    const cliLines = [
        { text: '> ./run-system-check --target="Gustabe_Core_Values"', delay: 800, type: 'command' },
        { text: '[SYSTEM] Initializing core value analysis...', delay: 600, type: 'info' },
        { text: '[ OK ] Experience_&_Expertise : ประสบการณ์สูง เชี่ยวชาญการทำเว็บและ SEO', delay: 800, type: 'success' },
        { text: '[ OK ] Professional_Support   : บริการเป็นมิตร ใส่ใจทุกความต้องการของลูกค้า', delay: 700, type: 'success' },
        { text: '[ OK ] Result_Oriented_Focus  : เน้นคุณภาพและสร้างผลลัพธ์ที่วัดผลได้จริง', delay: 700, type: 'success' },
        { text: '[ OK ] After-Sales_Service    : ดูแลหลังการขายอย่างครบครัน มั่นใจได้ 100%', delay: 900, type: 'success' },
        { text: ' ', delay: 200, type: 'info' },
        { text: '> System ready. Waiting for new project initialization_ ', delay: 0, type: 'command' }
    ];

    // 2. ฟังก์ชันรันคำสั่งทีละบรรทัด
    const runCLI = async () => {
        cursor.classList.remove('hidden');
        
        for (let i = 0; i < cliLines.length; i++) {
            const lineData = cliLines[i];
            
            // สร้างบรรทัดใหม่
            const lineEl = document.createElement('div');
            
            // กำหนดสีตามประเภทของ Log
            if (lineData.type === 'command') lineEl.className = 'text-slate-300 font-bold';
            else if (lineData.type === 'info') lineEl.className = 'text-slate-500 italic';
            else if (lineData.type === 'success') lineEl.className = 'text-emerald-400';

            lineEl.innerText = lineData.text;
            outputContainer.appendChild(lineEl);

            // หน่วงเวลา (Delay) ก่อนจะรันบรรทัดถัดไป ให้ดูเหมือนระบบกำลังประมวลผลจริงๆ
            await new Promise(resolve => setTimeout(resolve, lineData.delay));
        }
    };

    // 3. Intersection Observer (The Scroll Trigger)
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            // ถ้าเลื่อนมาเจอ (isIntersecting) และยังไม่เคยรัน (hasRun)
            if (entry.isIntersecting && !hasRun) {
                hasRun = true;
                setTimeout(runCLI, 500); // หน่วง 0.5 วิให้ลูกค้าโฟกัสจอ ก่อนเริ่มรัน
                observer.unobserve(terminalSection); // ถอดตัวจับตาออก ประหยัดทรัพยากรเครื่อง
            }
        });
    }, {
        root: null,
        threshold: 0.3 // ต้องเลื่อนให้เห็น Terminal อย่างน้อย 30% ถึงจะทำงาน
    });

    // เริ่มจับตาดู Section Terminal
    if (terminalSection) {
        observer.observe(terminalSection);
    }
});
</script>