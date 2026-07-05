/**
 * file: assets/js/search-engine.js
 * Optimized for Ctrl+K Interception, Command Parser (Hybrid Logic)
 */

document.addEventListener('DOMContentLoaded', () => {
    const palette = document.getElementById('gustabe-search-palette');
    const input = document.getElementById('search-input');
    const resultsList = document.getElementById('search-results-list');
    const suggestions = document.getElementById('search-suggestions');
    const loading = document.getElementById('search-loading');
    const emptyState = document.getElementById('search-empty');
    const queryText = document.getElementById('search-query-text');

    // ดึงปุ่ม Quick Suggestions ทั้งหมด (ที่เพิ่งเติมคลาส suggestion-btn เข้าไป)
    const suggestionBtns = document.querySelectorAll('.suggestion-btn');

    // ตรวจสอบความพร้อมของ Element สำคัญ
    if (!palette || !input) {
        console.warn('Gustabe Search: Required elements not found in DOM.');
        return;
    }

    let debounceTimer;

    // 1. ฟังก์ชันเปิด/ปิด Modal
    const togglePalette = (show) => {
        if (show) {
            palette.classList.add('is-open');
            setTimeout(() => {
                input.value = '';
                input.focus();
            }, 200);
            document.body.style.overflow = 'hidden';
        } else {
            palette.classList.remove('is-open');
            document.body.style.overflow = '';
            resetSearch();
        }
    };

    const resetSearch = () => {
        if (resultsList) resultsList.innerHTML = '';
        suggestions?.classList.remove('hidden');
        loading?.classList.add('hidden');
        emptyState?.classList.add('hidden');
    };

    // ---------------------------------------------------
    // ⚡ 2. ระบบ Keyboard Shortcuts (The Interceptor)
    // ---------------------------------------------------
    window.addEventListener('keydown', (e) => {
        const isK = e.key?.toLowerCase() === 'k' || e.code === 'KeyK';
        const isModifier = e.ctrlKey || e.metaKey;

        if (isModifier && isK) {
            e.preventDefault();
            e.stopImmediatePropagation();
            togglePalette(true);
            console.log('Command Palette: Intercepted successfully.');
        }
        
        if (e.key === 'Escape' && palette.classList.contains('is-open')) {
            togglePalette(false);
        }
    }, true);

    // 3. ระบบคลิก (Event Delegation)
    document.addEventListener('click', (e) => {
        if (e.target.closest('#open-search-btn') || e.target.closest('#open-search-mobile')) {
            e.preventDefault();
            togglePalette(true);
        }
        if (e.target.id === 'search-backdrop' || e.target.closest('#search-backdrop')) {
            togglePalette(false);
        }
    });

    // ---------------------------------------------------
    // 🤖 4. Hybrid Logic: ควบคุมปุ่ม Quick Suggestions
    // ---------------------------------------------------
    if (suggestionBtns.length > 0) {
        suggestionBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const prefix = btn.getAttribute('data-prefix'); // ดึงค่าเช่น "/products "
                if (prefix) {
                    input.value = prefix;
                    input.focus();
                    // บังคับให้ระบบคิดว่า User กำลังพิมพ์ เพื่อสั่งรัน Event 'input' ด้านล่าง
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    }

    // ---------------------------------------------------
    // 🧠 5. The Command Parser: ชำแหละคำสั่งจาก Input
    // ---------------------------------------------------
    input.addEventListener('input', (e) => {
        let rawText = e.target.value.trimLeft(); // ใช้ trimLeft เพื่อเก็บสเปซบาร์ด้านหลังไว้เผื่อพิมพ์ต่อ
        clearTimeout(debounceTimer);
        
        let searchType = 'all';
        let searchKeyword = rawText;

        // ดักจับ Prefix สไตล์ Terminal
        const prefixes = ['/products', '/portfolio', '/blog'];
        for (let p of prefixes) {
            if (rawText.toLowerCase().startsWith(p)) {
                searchType = p.replace('/', ''); // ตัดเครื่องหมาย / ออก เหลือแค่ชื่อ Type
                searchKeyword = rawText.substring(p.length).trim(); // หั่นเอาเฉพาะคำค้นหาด้านหลัง
                break;
            }
        }

        // ถ้าคำค้นหาสั้นเกินไป และไม่ได้ระบุ Type ให้โชว์หน้า Suggestion ปกติ
        if (searchKeyword.length < 2 && searchType === 'all') {
            resetSearch();
            return;
        }

        debounceTimer = setTimeout(() => {
            performSearch(searchKeyword, searchType);
        }, 300);
    });

    // ---------------------------------------------------
    // 📡 6. The API Fetcher (เชื่อมต่อสมองกลหลังบ้าน)
    // ---------------------------------------------------
    async function performSearch(keyword, type) {
        if (!suggestions || !loading || !resultsList) return;

        suggestions.classList.add('hidden');
        loading.classList.remove('hidden');
        emptyState?.classList.add('hidden');
        resultsList.innerHTML = '';

        try {
            // ดึงค่า URL จากตัวแปรที่ส่งมาจาก PHP (enqueue-scripts.php)
            if (typeof gustabeData === 'undefined' || !gustabeData.root_url) {
                console.error('Gustabe Search: gustabeData.root_url is not defined.');
                return;
            }

            // ประกอบ URL ใหม่ โดยเพิ่ม Parameter "type" เข้าไป
            const url = `${gustabeData.root_url}gustabe/v1/search?keyword=${encodeURIComponent(keyword)}&type=${encodeURIComponent(type)}`;
            
            const response = await fetch(url);
            const results = await response.json();

            loading.classList.add('hidden');

            if (results && results.length > 0) {
                renderResults(results);
            } else {
                // อัปเดตข้อความหาไม่เจอ ให้โชว์คำสั่งที่เราพิมพ์เข้าไปด้วย
                if (queryText) {
                    const typeDisplay = type !== 'all' ? `<span class="text-emerald-500">[${type.toUpperCase()}]</span> ` : '';
                    queryText.innerHTML = typeDisplay + keyword;
                }
                emptyState?.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Search Error:', error);
            loading.classList.add('hidden');
        }
    }

    // ---------------------------------------------------
    // 🎨 7. Render UI (วาดผลลัพธ์ลงจอ)
    // ---------------------------------------------------
    function renderResults(results) {
        const typeLabels = {
            'product': { label: 'Product', color: 'text-green-400 border-green-500/30 bg-green-500/10' },
            'portfolio': { label: 'Project', color: 'text-red-400 border-red-500/30 bg-red-500/10' },
            'post': { label: 'Article', color: 'text-blue-400 border-blue-500/30 bg-blue-500/10' }
        };

        resultsList.innerHTML = results.map(item => {
            const type = typeLabels[item.type] || { label: item.type, color: 'text-gray-400 border-gray-500/30 bg-gray-500/10' };
            const priceHtml = item.price ? `<div class="text-xs text-emerald-400 mt-1 font-mono">${item.price}</div>` : '';
            const imgHtml = item.image 
                ? `<img src="${item.image}" class="w-full h-full object-cover">` 
                : `<div class="w-full h-full flex items-center justify-center text-zinc-600 text-[10px]">N/A</div>`;

            return `
                <a href="${item.url}" class="flex items-center gap-4 p-3 rounded-lg hover:bg-white/5 transition-all group border border-transparent hover:border-white/10 no-underline">
                    <div class="w-12 h-12 rounded bg-zinc-900 overflow-hidden flex-shrink-0 border border-zinc-800">
                        ${imgHtml}
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-zinc-200 group-hover:text-red-500 transition-colors">${item.title}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border ${type.color} font-mono uppercase">${type.label}</span>
                        </div>
                        ${priceHtml}
                    </div>
                    <i class="huge huge-arrow-right-01 text-zinc-600 group-hover:text-white transition-all transform group-hover:translate-x-1"></i>
                </a>
            `;
        }).join('');
    }
});