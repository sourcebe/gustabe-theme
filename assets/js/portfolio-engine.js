/**
 * file: assets/js/portfolio-engine.js
 * หน้าที่: Pure Client-Side Filter (AND Logic) + Fake Pagination
 */

document.addEventListener('DOMContentLoaded', () => {
    // แปลง NodeList เป็น Array เพื่อให้ใช้เมธอด .filter() และ .slice() ได้สะดวก
    const allCards = Array.from(document.querySelectorAll('.portfolio-card'));
    const filterBtns = document.querySelectorAll('.filter-btn');
    const filterSelects = document.querySelectorAll('.filter-select');
    const paginationContainer = document.getElementById('js-pagination-container');
    const noResultsState = document.getElementById('no-results-state'); // ถ้านายทำกล่อง "ไม่พบผลงาน" ไว้
    
    // ตั้งค่าตัวแปรหลัก
    const itemsPerPage = 9; // ⚡ จำนวนผลงานต่อ 1 หน้า (ปรับเลขได้ตามชอบ)
    let currentPage = 1;
    let activeFilters = { type: 'all', platform: 'all', tech: 'all' };
    let filteredCards = [...allCards]; // เก็บการ์ดที่ผ่านการกรอง

    // -----------------------------------------------------------------
    // 1. ฟังก์ชันตัวกรอง (The Filter Engine)
    // -----------------------------------------------------------------
    const executeFilterAndPagination = () => {
        // กรองการ์ดด้วยกฎ AND (Strict Mode)
        filteredCards = allCards.filter(card => {
            const types = card.getAttribute('data-type').split(',');
            const platforms = card.getAttribute('data-platform').split(',');
            const techs = card.getAttribute('data-tech').split(',');

            const matchType = activeFilters.type === 'all' || types.includes(activeFilters.type);
            const matchPlatform = activeFilters.platform === 'all' || platforms.includes(activeFilters.platform);
            const matchTech = activeFilters.tech === 'all' || techs.includes(activeFilters.tech);

            return matchType && matchPlatform && matchTech;
        });

        // ซ่อนการ์ด "ทั้งหมด" ก่อนจะดึงเฉพาะส่วนที่ต้องการมาโชว์
        allCards.forEach(card => {
            card.style.display = 'none';
            card.style.opacity = '0';
        });

        // คำนวณจำนวนหน้า
        const totalPages = Math.ceil(filteredCards.length / itemsPerPage);
        
        // ถ้าผลการกรองทำให้หน้าน้อยลงกว่าหน้าที่อยู่ปัจจุบัน ให้กลับไปหน้า 1
        if (currentPage > totalPages) currentPage = 1;

        // หั่น Array (Slice) เอาเฉพาะการ์ดที่จะโชว์ในหน้านั้นๆ
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const cardsToShow = filteredCards.slice(startIndex, endIndex);

        // แสดงผลการ์ดที่ถูกหั่นมา
        cardsToShow.forEach(card => {
            card.style.display = 'flex';
            setTimeout(() => { card.style.opacity = '1'; }, 50); // Animation นุ่มๆ
        });

        // วาดปุ่ม Pagination ใหม่
        renderPagination(totalPages);
    };

    // -----------------------------------------------------------------
    // 2. ฟังก์ชันวาดปุ่มเปลี่ยนหน้า (The Pagination Builder)
    // -----------------------------------------------------------------
    const renderPagination = (totalPages) => {
        if (!paginationContainer) return;
        paginationContainer.innerHTML = '';

        if (totalPages <= 1) return; // ถ้ามีหน้าเดียว ไม่ต้องโชว์ปุ่ม

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            
            // สไตล์ The Dark IDE
            let baseClasses = 'px-3 py-1.5 rounded transition-all focus:outline-none ';
            if (i === currentPage) {
                baseClasses += 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50 font-bold';
            } else {
                baseClasses += 'text-slate-500 hover:text-emerald-400 border border-transparent hover:border-slate-700';
            }
            
            btn.className = baseClasses;
            
            // เมื่อกดปุ่มเปลี่ยนหน้า
            btn.addEventListener('click', () => {
                currentPage = i;
                executeFilterAndPagination();
                // เลื่อนจอกลับมาตรงหัวตะแกรงผลงาน
                const filterSection = document.getElementById('gustabe-portfolio-engine');
                if (filterSection) {
                    window.scrollTo({ top: filterSection.offsetTop - 100, behavior: 'smooth' });
                }
            });

            paginationContainer.appendChild(btn);
        }
    };

    // -----------------------------------------------------------------
    // 3. ฟังก์ชันซิงก์ UI (Desktop & Mobile)
    // -----------------------------------------------------------------
    const updateUI = () => {
        // อัปเดตปุ่ม Desktop
        filterBtns.forEach(btn => {
            const group = btn.getAttribute('data-filter-group');
            const val = btn.getAttribute('data-filter-val');
            if (activeFilters[group] === val) {
                btn.classList.add('bg-emerald-500/10', 'text-emerald-400', 'border-emerald-500/30');
                btn.classList.remove('text-slate-400', 'border-transparent');
                btn.innerHTML = btn.innerHTML.replace('[ ]', '[x]');
            } else {
                btn.classList.remove('bg-emerald-500/10', 'text-emerald-400', 'border-emerald-500/30');
                btn.classList.add('text-slate-400', 'border-transparent');
                btn.innerHTML = btn.innerHTML.replace('[x]', '[ ]');
            }
        });

        // อัปเดต Dropdown Mobile
        filterSelects.forEach(select => {
            const group = select.getAttribute('data-filter-group');
            select.value = activeFilters[group];
        });
    };

    // -----------------------------------------------------------------
    // 4. ดักจับ Event ผู้ใช้งาน
    // -----------------------------------------------------------------
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            activeFilters[btn.getAttribute('data-filter-group')] = btn.getAttribute('data-filter-val');
            currentPage = 1; // เมื่อเปลี่ยน Filter ต้องกลับไปหน้าแรกเสมอ
            updateUI();
            executeFilterAndPagination();
        });
    });

    filterSelects.forEach(select => {
        select.addEventListener('change', () => {
            activeFilters[select.getAttribute('data-filter-group')] = select.value;
            currentPage = 1;
            updateUI();
            executeFilterAndPagination();
        });
    });

    // รันการกรองครั้งแรกตอนโหลดหน้าเว็บ
    executeFilterAndPagination();
});