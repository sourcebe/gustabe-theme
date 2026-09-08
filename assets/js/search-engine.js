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

    const suggestionBtns = document.querySelectorAll('.suggestion-btn');

    if (!palette || !input) {
        console.warn('Gustabe Search: Required elements not found in DOM.');
        return;
    }

    let debounceTimer;
    let selectedIndex = -1;
    let currentResults = []; // Store rendered result DOM elements

    // Helper for escaping HTML
    const escapeHTML = (str) => {
        if (!str) return '';
        return str.replace(/[&<>'"]/g,
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    };

    // 1. ฟังก์ชันเปิด/ปิด Modal
    const togglePalette = (show) => {
        if (show) {
            palette.classList.add('is-open');
            setTimeout(() => {
                input.value = '';
                input.focus();
                showRecentSearches();
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
        selectedIndex = -1;
        currentResults = Array.from(suggestionBtns); // นำปุ่ม Suggestion เข้าไปให้ลูกศรเลื่อนมาโดนได้
    };

    // ---------------------------------------------------
    // ⚡ 2. ระบบ Keyboard Shortcuts (The Interceptor & Navigation)
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

        // Keyboard Navigation (Up/Down/Enter)
        if (palette.classList.contains('is-open')) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateResults(1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateResults(-1);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                let link = null;
                if (selectedIndex >= 0 && currentResults[selectedIndex]) {
                    link = currentResults[selectedIndex];
                } else if (currentResults.length > 0 && selectedIndex === -1) {
                    link = currentResults[0];
                }

                if (link) {
                    if (link.classList.contains('suggestion-btn')) {
                        link.click(); // จำลองการคลิกเพื่อรัน Hybrid Logic
                    } else if (link.getAttribute('data-action') === 'set-theme') {
                        const theme = link.getAttribute('data-value');
                        document.documentElement.setAttribute('data-theme', theme);
                        localStorage.setItem('gustabeTheme', theme);
                        togglePalette(false);
                    } else {
                        const url = link.getAttribute('href');
                        const title = link.querySelector('.result-title')?.innerText || 'Link';
                        saveRecentSearch(title, url);
                        window.location.href = url;
                    }
                }
            }
        }
    }, true);

    const navigateResults = (direction) => {
        if (currentResults.length === 0) return;

        if (selectedIndex >= 0) {
            currentResults[selectedIndex].classList.remove('bg-white/10', 'border-white/20');
        }

        selectedIndex += direction;

        if (selectedIndex >= currentResults.length) {
            selectedIndex = 0; // loop back to top
        } else if (selectedIndex < 0) {
            selectedIndex = currentResults.length - 1; // loop to bottom
        }

        const activeItem = currentResults[selectedIndex];
        activeItem.classList.add('bg-white/10', 'border-white/20');
        activeItem.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
    };

    // 3. ระบบคลิก (Event Delegation)
    document.addEventListener('click', (e) => {
        if (e.target.closest('#open-search-btn') || e.target.closest('#open-search-mobile')) {
            e.preventDefault();
            togglePalette(true);
        }
        if (e.target.id === 'search-backdrop' || e.target.closest('#search-backdrop')) {
            togglePalette(false);
        }

        // บันทึกประวัติเมื่อคลิกผลลัพธ์
        const resultLink = e.target.closest('.search-result-item');
        if (resultLink) {
            if (resultLink.getAttribute('data-action') === 'set-theme') {
                e.preventDefault();
                const theme = resultLink.getAttribute('data-value');
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('gustabeTheme', theme);
                togglePalette(false);
                return;
            }

            const url = resultLink.getAttribute('href');
            const title = resultLink.querySelector('.result-title')?.innerText || 'Link';
            saveRecentSearch(title, url);
        }
    });

    // ---------------------------------------------------
    // 🤖 4. Hybrid Logic: ควบคุมปุ่ม Quick Suggestions
    // ---------------------------------------------------
    if (suggestionBtns.length > 0) {
        suggestionBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const prefix = btn.getAttribute('data-prefix');
                if (prefix) {
                    input.value = prefix;
                    input.focus();
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    }

    // ---------------------------------------------------
    // 🧠 5. The Command Parser: ชำแหละคำสั่งจาก Input
    // ---------------------------------------------------
    input.addEventListener('input', (e) => {
        let rawText = e.target.value.trimLeft();
        clearTimeout(debounceTimer);
        selectedIndex = -1; // Reset selection on new input
        currentResults = [];

        // ว่างเปล่า = แสดง Recent
        if (rawText === '') {
            showRecentSearches();
            return;
        }

        // ระบบโหมด System Commands (>)
        if (rawText.startsWith('>')) {
            handleSystemCommands(rawText.substring(1).trim().toLowerCase());
            return;
        }

        let searchType = 'all';
        let searchKeyword = rawText;

        const prefixes = ['/products', '/portfolio', '/blog'];
        for (let p of prefixes) {
            if (rawText.toLowerCase().startsWith(p)) {
                searchType = p.replace('/', '');
                searchKeyword = rawText.substring(p.length).trim();
                break;
            }
        }

        if (searchKeyword.length < 2 && searchType === 'all') {
            resetSearch();
            return;
        }

        debounceTimer = setTimeout(() => {
            performSearch(searchKeyword, searchType);
        }, 300);
    });

    // ---------------------------------------------------
    // ⚙️ System Commands Handler
    // ---------------------------------------------------
    function handleSystemCommands(keyword) {
        suggestions.classList.add('hidden');
        loading.classList.add('hidden');
        emptyState?.classList.add('hidden');

        const commands = [
            // Theme Group
            { id: 'normal', title: 'Theme: Normal Mode', action: 'set-theme', value: 'normal', icon: 'huge-code', color: 'var(--color-zinc-200)' },
            { id: 'darkmode', title: 'Theme: Neon Cyberpunk', action: 'set-theme', value: 'darkmode', icon: 'huge-laptop-programming', color: 'var(--color-zinc-200)' },
            { id: 'whitemode', title: 'Theme: Clean IDE', action: 'set-theme', value: 'whitemode', icon: 'huge-code-square', color: 'var(--color-zinc-200)' },
            { id: 'hackmode', title: 'Theme: Matrix Terminal', action: 'set-theme', value: 'hackmode', icon: 'huge-command-line', color: 'var(--color-zinc-200)' },
            { id: 'funmode', title: 'Theme: Fun Mode', action: 'set-theme', value: 'funmode', icon: 'huge-sparkles', color: '#ea580c' },

            // Navigation Group
            { id: 'works', title: 'Portfolio / ผลงานทั้งหมด', url: '/our-works/', icon: 'huge-folder-code', color: 'var(--color-zinc-200)' },
            { id: 'home', title: 'Go to Homepage', url: '/', icon: 'huge-home-01', color: 'var(--color-zinc-200)' },
            { id: 'contact', title: 'Contact Us', url: '/contact/', icon: 'huge-mail-02', color: 'var(--color-zinc-200)' },
            { id: 'login', title: 'Login / Account', url: '/my-account/', icon: 'huge-user-circle', color: 'var(--color-zinc-200)' },
            { id: 'cart', title: 'View Cart', url: '/cart/', icon: 'huge-shopping-cart-01', color: 'var(--color-zinc-200)' }
        ];

        const matched = keyword === '' ? commands : commands.filter(cmd => cmd.title.toLowerCase().includes(keyword) || cmd.id.includes(keyword));

        if (matched.length > 0) {
            resultsList.innerHTML = matched.map(cmd => {
                const attrs = cmd.action
                    ? `href="#" data-action="${cmd.action}" data-value="${cmd.value}"`
                    : `href="${cmd.url}"`;
                return `
                <a ${attrs} class="search-result-item flex items-center gap-4 p-3 rounded-lg hover:bg-white/5 transition-all group border border-transparent hover:border-white/10 no-underline">
                    <div class="w-12 h-12 rounded bg-zinc-900 flex items-center justify-center flex-shrink-0 border border-zinc-800">
                        <i class="huge ${cmd.icon} text-xl" style="color: ${cmd.color};"></i>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <span class="result-title text-sm font-medium text-slate-200 group-hover:text-zinc-200 transition-colors">${cmd.title}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border border-white/20 bg-white/5 text-slate-400 font-mono uppercase">Command</span>
                        </div>
                    </div>
                </a>
            `}).join('');
            currentResults = Array.from(resultsList.querySelectorAll('.search-result-item'));
        } else {
            resultsList.innerHTML = '';
            if (queryText) {
                const escapedKeyword = keyword.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                queryText.innerHTML = `<span class="text-zinc-200/50">&gt;</span> ${escapedKeyword}`;
            }
            emptyState?.classList.remove('hidden');
        }
    }

    // ---------------------------------------------------
    // 📡 6. The API Fetcher
    // ---------------------------------------------------
    async function performSearch(keyword, type) {
        if (!suggestions || !loading || !resultsList) return;

        suggestions.classList.add('hidden');
        loading.classList.remove('hidden');
        emptyState?.classList.add('hidden');
        resultsList.innerHTML = '';
        currentResults = [];

        try {
            if (typeof gustabeData === 'undefined' || !gustabeData.root_url) {
                console.error('Gustabe Search: gustabeData.root_url is not defined.');
                return;
            }

            const url = `${gustabeData.root_url}gustabe/v1/search?keyword=${encodeURIComponent(keyword)}&type=${encodeURIComponent(type)}`;

            const response = await fetch(url);
            const results = await response.json();

            loading.classList.add('hidden');

            if (results && results.length > 0) {
                renderResults(results);
            } else {
                if (queryText) {
                    const escapedKeyword = keyword.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    const typeDisplay = type !== 'all' ? `<span class="text-emerald-500">[${type.toUpperCase()}]</span> ` : '';
                    queryText.innerHTML = typeDisplay + escapedKeyword;
                }
                emptyState?.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Search Error:', error);
            loading.classList.add('hidden');
        }
    }

    // ---------------------------------------------------
    // 🎨 7. Render UI
    // ---------------------------------------------------
    function renderResults(results) {
        const typeLabels = {
            'product': { label: 'Product', color: 'text-green-400 border-green-500/30 bg-green-500/10' },
            'our_works': { label: 'Project', color: 'text-red-400 border-red-500/30 bg-red-500/10' },
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
                <a href="${item.url}" class="search-result-item flex items-center gap-4 p-3 rounded-lg hover:bg-white/5 transition-all group border border-transparent hover:border-white/10 no-underline">
                    <div class="w-12 h-12 rounded bg-zinc-900 overflow-hidden flex-shrink-0 border border-zinc-800">
                        ${imgHtml}
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center gap-2">
                            <span class="result-title text-sm font-medium text-zinc-200 group-hover:text-red-500 transition-colors">${escapeHTML(item.title)}</span>
                            <span class="text-[10px] px-1.5 py-0.5 rounded border ${type.color} font-mono uppercase">${type.label}</span>
                        </div>
                        ${priceHtml}
                    </div>
                </a>
            `;
        }).join('');

        currentResults = Array.from(resultsList.querySelectorAll('.search-result-item'));
    }

    // ---------------------------------------------------
    // 🕰️ 8. Recent Searches (LocalStorage)
    // ---------------------------------------------------
    function saveRecentSearch(title, url) {
        if (!title || !url) return;
        let recents = JSON.parse(localStorage.getItem('gustabeRecentSearches') || '[]');

        // Remove existing if duplicate url
        recents = recents.filter(item => item.url !== url);

        // Add to front
        recents.unshift({ title, url });

        // Keep only top 5
        if (recents.length > 5) recents.pop();

        localStorage.setItem('gustabeRecentSearches', JSON.stringify(recents));
    }

    function showRecentSearches() {
        let recents = JSON.parse(localStorage.getItem('gustabeRecentSearches') || '[]');
        if (recents.length === 0) {
            resetSearch();
            return;
        }

        suggestions.classList.add('hidden');
        loading.classList.add('hidden');
        emptyState?.classList.add('hidden');

        let html = '<p class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">Recent Searches</p>';
        html += recents.map(item => `
            <a href="${item.url}" class="search-result-item flex items-center gap-4 p-3 rounded-lg hover:bg-white/5 transition-all group border border-transparent hover:border-white/10 no-underline">
                <div class="w-10 h-10 rounded bg-zinc-900 flex items-center justify-center flex-shrink-0 border border-zinc-800">
                    <i class="huge huge-search-02 text-zinc-500 text-lg"></i>
                </div>
                <div class="flex-grow">
                    <div class="flex items-center gap-2">
                        <span class="result-title text-sm font-medium text-zinc-300 group-hover:text-zinc-200 transition-colors">${escapeHTML(item.title)}</span>
                    </div>
                </div>
            </a>
        `).join('');

        resultsList.innerHTML = html;
        currentResults = Array.from(resultsList.querySelectorAll('.search-result-item'));
        selectedIndex = -1;
    }
});
