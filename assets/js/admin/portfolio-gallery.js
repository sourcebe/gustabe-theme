/**
 * file: assets/js/admin/portfolio-gallery.js
 * จัดการ Media Uploader สำหรับ Gallery ของ CPT (อัปเดตรองรับการลบรายภาพ)
 */
document.addEventListener('DOMContentLoaded', function() {
    const uploadBtn = document.getElementById('gustabe_gallery_upload_btn');
    const clearBtn = document.getElementById('gustabe_gallery_clear_btn');
    const hiddenInput = document.getElementById('gustabe_project_gallery');
    const previewContainer = document.getElementById('gustabe_gallery_preview_container');

    if (!uploadBtn || !hiddenInput || !previewContainer) return;

    let mediaFrame;

    // 1. จัดการตอนกดปุ่ม "เพิ่มรูปภาพ"
    uploadBtn.addEventListener('click', function(e) {
        e.preventDefault();

        if (mediaFrame) {
            mediaFrame.open();
            return;
        }

        mediaFrame = wp.media({
            title: 'เลือกรูปภาพผลงาน',
            button: { text: 'ใช้งานรูปภาพเหล่านี้' },
            multiple: true
        });

        mediaFrame.on('select', function() {
            const selection = mediaFrame.state().get('selection');
            const attachmentIds = [];
            
            previewContainer.innerHTML = ''; // ล้างหน้าจอเก่า

            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                attachmentIds.push(attachment.id);

                const imgUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                
                // สร้างกล่องห่อหุ้มรูปและปุ่ม X
                const itemDiv = document.createElement('div');
                itemDiv.className = 'gustabe-gallery-item';
                itemDiv.setAttribute('data-id', attachment.id);

                itemDiv.innerHTML = `
                    <img src="${imgUrl}" alt="">
                    <span class="gustabe-gallery-remove" title="ลบรูปนี้">&times;</span>
                `;
                
                previewContainer.appendChild(itemDiv);
            });

            // เอา ID อัปเดตกลับไปใน input hidden
            hiddenInput.value = attachmentIds.join(',');
        });

        mediaFrame.open();
    });

    // 2. จัดการตอนกด "ปุ่มกากบาท" (ลบรายภาพ) - ใช้ Event Delegation
    previewContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('gustabe-gallery-remove')) {
            e.preventDefault();
            
            const itemWrapper = e.target.closest('.gustabe-gallery-item');
            const idToRemove = itemWrapper.getAttribute('data-id');

            // 2.1 เตะกล่องรูปนี้ออกจากหน้าจอ (DOM)
            itemWrapper.remove();

            // 2.2 ค้นหาและลบ ID ออกจาก Input Hidden
            if (hiddenInput.value) {
                let currentIds = hiddenInput.value.split(','); // แปลง "101,102" เป็น Array
                
                // กรองเอาเฉพาะ ID ที่ไม่ตรงกับตัวที่ลบ (ทิ้งตัวที่ลบ)
                currentIds = currentIds.filter(function(id) {
                    return id !== idToRemove;
                });
                
                // แปลงกลับเป็น String มีลูกน้ำคั่นแล้วยัดกลับที่เดิม
                hiddenInput.value = currentIds.join(',');
            }
        }
    });

    // 3. จัดการตอนกด "ลบทั้งหมด"
    if (clearBtn) {
        clearBtn.addEventListener('click', function(e) {
            e.preventDefault();
            hiddenInput.value = '';
            previewContainer.innerHTML = '';
        });
    }
});