/**
 * file: assets/js/admin/portfolio-gallery.js
 * จัดการ Media Uploader สำหรับ Gallery ของ CPT (อัปเดตรองรับการลบรายภาพ)
 */
document.addEventListener('DOMContentLoaded', function() {
    // ==========================================
    // 1. จัดการ Media Uploader สำหรับ Gallery ของ CPT
    // ==========================================
    const uploadBtn = document.getElementById('gustabe_gallery_upload_btn');
    const clearBtn = document.getElementById('gustabe_gallery_clear_btn');
    const hiddenInput = document.getElementById('gustabe_project_gallery');
    const previewContainer = document.getElementById('gustabe_gallery_preview_container');

    if (uploadBtn && hiddenInput && previewContainer) {
        let mediaFrame;

        // จัดการตอนกดปุ่ม "เลือกรูปภาพจากเครื่อง" (อัปเดตให้เป็นแบบ Append ไม่ลบรูปเก่าทิ้ง)
        uploadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            if (mediaFrame) {
                mediaFrame.open();
                return;
            }

            mediaFrame = wp.media({
                title: 'เลือกรูปภาพผลงาน',
                button: { text: 'เพิ่มรูปภาพเหล่านี้' },
                multiple: true
            });

            mediaFrame.on('select', function() {
                const selection = mediaFrame.state().get('selection');
                let currentIds = hiddenInput.value ? hiddenInput.value.split(',').filter(Boolean) : [];

                selection.map(function(attachment) {
                    attachment = attachment.toJSON();
                    const attachIdStr = String(attachment.id);

                    // ถ้ายังไม่มีรูปนี้ ให้เพิ่มเข้าไป
                    if (!currentIds.includes(attachIdStr)) {
                        currentIds.push(attachIdStr);

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
                    }
                });

                // เอา ID อัปเดตกลับไปใน input hidden
                hiddenInput.value = currentIds.join(',');
            });

            mediaFrame.open();
        });

        // จัดการตอนกด "ปุ่มกากบาท" (ลบรายภาพ) - ใช้ Event Delegation
        previewContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('gustabe-gallery-remove')) {
                e.preventDefault();
                
                const itemWrapper = e.target.closest('.gustabe-gallery-item');
                const idToRemove = itemWrapper.getAttribute('data-id');

                // เตะกล่องรูปนี้ออกจากหน้าจอ (DOM)
                itemWrapper.remove();

                // ค้นหาและลบ ID ออกจาก Input Hidden
                if (hiddenInput.value) {
                    let currentIds = hiddenInput.value.split(',');
                    currentIds = currentIds.filter(function(id) {
                        return id !== idToRemove;
                    });
                    hiddenInput.value = currentIds.join(',');
                }
            }
        });

        // จัดการตอนกด "ลบทั้งหมด"
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพทั้งหมดออกจากอัลบั้มนี้?')) {
                    hiddenInput.value = '';
                    previewContainer.innerHTML = '';
                }
            });
        }
    }

    // ==========================================
    // 1.1 Responsive Multi-Device Capture Engine
    // ==========================================
    const respCaptureBtn = document.getElementById('gustabe_responsive_capture_btn');
    const galleryStatus = document.getElementById('gustabe_gallery_capture_status');

    if (respCaptureBtn) {
        respCaptureBtn.addEventListener('click', async function(e) {
            e.preventDefault();

            const liveUrlInput = document.getElementById('gustabe_live_url');
            const targetUrl = liveUrlInput ? liveUrlInput.value.trim() : '';
            if (!targetUrl) {
                alert('กรุณากรอก Website (Live URL) ในช่องด้านบนก่อนกดดึงภาพหน้าจอ');
                showGalleryStatus('กรุณากรอก Website (Live URL) ด้านบนก่อนกดดึงภาพหน้าจอ', 'error');
                if (liveUrlInput) {
                    liveUrlInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    liveUrlInput.focus();
                }
                return;
            }

            if (!/^https?:\/\//i.test(targetUrl)) {
                alert('URL ต้องขึ้นต้นด้วย http:// หรือ https:// (เช่น https://example.com)');
                showGalleryStatus('URL ต้องขึ้นต้นด้วย http:// หรือ https:// (เช่น https://example.com)', 'error');
                if (liveUrlInput) liveUrlInput.focus();
                return;
            }

            let postId = (typeof gustabeAdminData !== 'undefined' && gustabeAdminData.post_id) ? gustabeAdminData.post_id : 0;
            if (!postId) {
                const postInput = document.getElementById('post_ID');
                if (postInput) postId = parseInt(postInput.value, 10);
            }

            if (!postId) {
                alert('ไม่พบ Post ID กรุณากดปุ่ม "บันทึกฉบับร่าง" (Save Draft) ทางขวามือก่อนหนึ่งครั้ง');
                showGalleryStatus('ไม่พบ Post ID กรุณากดบันทึกฉบับร่าง (Save Draft) ก่อนหนึ่งครั้ง', 'error');
                return;
            }

            // รายการอุปกรณ์ที่จะแคป
            const devices = [
                { key: 'desktop', name: 'Desktop (1440x900)' },
                { key: 'tablet',  name: 'Tablet (768x1024)' },
                { key: 'mobile',  name: 'Mobile (375x812)' }
            ];

            setRespLoading(true);
            let successCount = 0;

            for (let i = 0; i < devices.length; i++) {
                const dev = devices[i];
                showGalleryStatus(`[${i + 1}/${devices.length}] กำลังบันทึกภาพหน้าจอ ${dev.name}...`, 'info');

                try {
                    const formData = new FormData();
                    formData.append('action', 'gustabe_capture_responsive_device');
                    formData.append('nonce', typeof gustabeAdminData !== 'undefined' ? gustabeAdminData.nonce : '');
                    formData.append('post_id', postId);
                    formData.append('target_url', targetUrl);
                    formData.append('device', dev.key);

                    const res = await fetch(typeof gustabeAdminData !== 'undefined' ? gustabeAdminData.ajax_url : '/wp-admin/admin-ajax.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success && data.data) {
                        successCount++;
                        // อัปเดต Hidden input
                        if (hiddenInput && data.data.gallery_str) {
                            hiddenInput.value = data.data.gallery_str;
                        }

                        // แทรกกล่องรูปใหม่เข้าไปในแกลเลอรีพรีวิว
                        if (previewContainer && data.data.thumbnail_url) {
                            const itemDiv = document.createElement('div');
                            itemDiv.className = 'gustabe-gallery-item';
                            itemDiv.setAttribute('data-id', data.data.attach_id);

                            itemDiv.innerHTML = `
                                <img src="${data.data.thumbnail_url}?t=${Date.now()}" alt="">
                                <span class="gustabe-device-badge">${data.data.badge || dev.name}</span>
                                <span class="gustabe-gallery-remove" title="ลบรูปนี้">&times;</span>
                            `;
                            previewContainer.appendChild(itemDiv);
                        }
                    } else {
                        console.warn(`Error capturing ${dev.key}:`, data.data ? data.data.message : 'Unknown error');
                    }
                } catch (err) {
                    console.error(`Fetch error capturing ${dev.key}:`, err);
                }
            }

            setRespLoading(false);

            if (successCount === devices.length) {
                showGalleryStatus(`✅ บันทึกภาพหน้าจอครบทั้ง 3 อุปกรณ์ (Desktop, Tablet, Mobile) สำเร็จ!`, 'success');
            } else if (successCount > 0) {
                showGalleryStatus(`⚠️ บันทึกสำเร็จ ${successCount} จาก ${devices.length} อุปกรณ์ (สามารถลองกดอีกครั้งเพื่อเก็บขนาดที่เหลือได้)`, 'info');
            } else {
                showGalleryStatus(`❌ ไม่สามารถดึงภาพหน้าจอได้ กรุณาตรวจสอบว่าเว็บไซต์เปิดออนไลน์ปกติ`, 'error');
            }
        });

        function setRespLoading(isLoading) {
            respCaptureBtn.disabled = isLoading;
            const btnText = respCaptureBtn.querySelector('.btn-text');
            const btnIcon = respCaptureBtn.querySelector('.dashicons');

            if (isLoading) {
                respCaptureBtn.style.opacity = '0.7';
                respCaptureBtn.style.cursor = 'wait';
                if (btnText) {
                    btnText.textContent = 'กำลังเรนเดอร์ภาพอุปกรณ์...';
                } else {
                    respCaptureBtn.textContent = '⏳ กำลังเรนเดอร์ภาพอุปกรณ์...';
                }
                if (btnIcon) {
                    btnIcon.className = 'dashicons dashicons-update';
                    btnIcon.style.animation = 'gustabe-spin 1s linear infinite';
                }
            } else {
                respCaptureBtn.style.opacity = '1';
                respCaptureBtn.style.cursor = 'pointer';
                if (btnText) {
                    btnText.textContent = '⚡ ดึงภาพ Responsive อัตโนมัติ (Desktop + Tablet + Mobile)';
                } else {
                    respCaptureBtn.textContent = '⚡ ดึงภาพ Responsive อัตโนมัติ (Desktop + Tablet + Mobile)';
                }
                if (btnIcon) {
                    btnIcon.className = 'dashicons dashicons-devices';
                    btnIcon.style.animation = 'none';
                }
            }
        }

        function showGalleryStatus(msg, type) {
            if (!galleryStatus) return;
            galleryStatus.style.display = 'block';
            galleryStatus.textContent = msg;
            if (type === 'error') {
                galleryStatus.style.color = '#d63638';
                galleryStatus.style.fontWeight = '600';
            } else if (type === 'success') {
                galleryStatus.style.color = '#008a20';
                galleryStatus.style.fontWeight = '600';
            } else {
                galleryStatus.style.color = '#2271b1';
                galleryStatus.style.fontWeight = 'normal';
            }
        }
    }

    // ==========================================
    // 2. Auto Capture Screenshot เป็นภาพปก (Cover)
    // ==========================================
    const captureBtn = document.getElementById('gustabe_auto_capture_btn');
    const liveUrlInput = document.getElementById('gustabe_live_url');
    const captureStatus = document.getElementById('gustabe_capture_status');
    const previewWrapper = document.getElementById('gustabe_cover_preview_wrapper');
    const previewImg = document.getElementById('gustabe_cover_preview_img');

    if (captureBtn && liveUrlInput) {
        captureBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const targetUrl = liveUrlInput.value.trim();
            if (!targetUrl) {
                showStatus('กรุณากรอก Website (Live URL) ก่อนกดดึงภาพหน้าจอ', 'error');
                liveUrlInput.focus();
                return;
            }

            // ตรวจสอบโครงสร้าง URL คร่าวๆ
            if (!/^https?:\/\//i.test(targetUrl)) {
                showStatus('URL ต้องขึ้นต้นด้วย http:// หรือ https:// (เช่น https://example.com)', 'error');
                liveUrlInput.focus();
                return;
            }

            // ค้นหา Post ID
            let postId = (typeof gustabeAdminData !== 'undefined' && gustabeAdminData.post_id) ? gustabeAdminData.post_id : 0;
            if (!postId) {
                const postInput = document.getElementById('post_ID');
                if (postInput) postId = parseInt(postInput.value, 10);
            }

            if (!postId) {
                showStatus('ไม่พบ Post ID กรุณากดบันทึกฉบับร่าง (Save Draft) ก่อนหนึ่งครั้ง', 'error');
                return;
            }

            // ตั้งค่าสถานะกำลังทำงาน
            setLoading(true);
            showStatus('กำลังบันทึกภาพหน้าจอจากเว็บไซต์ กรุณารอสักครู่ (ประมาณ 5-10 วินาที)...', 'info');

            const formData = new FormData();
            formData.append('action', 'gustabe_auto_capture_cover');
            formData.append('nonce', typeof gustabeAdminData !== 'undefined' ? gustabeAdminData.nonce : '');
            formData.append('post_id', postId);
            formData.append('target_url', targetUrl);

            fetch(typeof gustabeAdminData !== 'undefined' ? gustabeAdminData.ajax_url : '/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(res) {
                setLoading(false);
                if (res.success && res.data) {
                    showStatus('✅ ' + (res.data.message || 'บันทึกภาพหน้าจอและตั้งเป็นภาพปกสำเร็จ!'), 'success');

                    // 1. อัปเดตกล่องพรีวิวใน Meta Box (พร้อม Cache Busting เพื่อไม่ให้ติดภาพ Loading เดิม)
                    const freshThumbUrl = res.data.thumbnail_url + (res.data.thumbnail_url.indexOf('?') !== -1 ? '&' : '?') + 't=' + Date.now();
                    if (previewImg && res.data.thumbnail_url) {
                        previewImg.src = freshThumbUrl;
                        if (previewWrapper) previewWrapper.style.display = 'inline-block';
                    }

                    // 2. อัปเดต Featured Image Box ของ WordPress (Gutenberg / Block Editor)
                    if (window.wp && wp.data && wp.data.dispatch && wp.data.dispatch('core/editor')) {
                        try {
                            wp.data.dispatch('core/editor').editPost({ featured_media: res.data.attach_id });
                        } catch (err) {
                            console.warn('Block editor featured_media update note:', err);
                        }
                    }

                    // 3. อัปเดต Featured Image Box ของ WordPress (Classic Editor)
                    const classicThumbInput = document.getElementById('_thumbnail_id');
                    if (classicThumbInput) {
                        classicThumbInput.value = res.data.attach_id;
                    }
                    const classicInside = document.querySelector('#postimagediv .inside');
                    if (classicInside && res.data.thumbnail_url) {
                        let existingImg = classicInside.querySelector('img');
                        if (existingImg) {
                            existingImg.src = freshThumbUrl;
                        } else {
                            // ถ้าเดิมยังไม่มีรูป ให้แทรกเข้าไป
                            classicInside.innerHTML = '<img src="' + freshThumbUrl + '" style="max-width:100%;height:auto;" />';
                        }
                    }
                } else {
                    showStatus('❌ ' + (res.data && res.data.message ? res.data.message : 'เกิดข้อผิดพลาดในการดึงภาพหน้าจอ'), 'error');
                }
            })
            .catch(function(err) {
                setLoading(false);
                showStatus('❌ เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + err.message, 'error');
            });
        });

        function setLoading(isLoading) {
            captureBtn.disabled = isLoading;
            const btnText = captureBtn.querySelector('.btn-text');
            const btnIcon = captureBtn.querySelector('.dashicons');

            if (isLoading) {
                if (btnText) btnText.textContent = 'กำลังแคปภาพหน้าจอ...';
                if (btnIcon) {
                    btnIcon.className = 'dashicons dashicons-update';
                    btnIcon.style.animation = 'gustabe-spin 1s linear infinite';
                }
            } else {
                if (btnText) btnText.textContent = '⚡ ดึงภาพหน้าจอเป็นภาพปก';
                if (btnIcon) {
                    btnIcon.className = 'dashicons dashicons-camera';
                    btnIcon.style.animation = 'none';
                }
            }
        }

                function showStatus(msg, type) {
            if (!captureStatus) return;
            captureStatus.style.display = 'block';
            captureStatus.textContent = msg;
            if (type === 'error') {
                captureStatus.style.color = '#d63638';
                captureStatus.style.fontWeight = '600';
            } else if (type === 'success') {
                captureStatus.style.color = '#008a20';
                captureStatus.style.fontWeight = '600';
            } else {
                captureStatus.style.color = '#2271b1';
                captureStatus.style.fontWeight = 'normal';
            }
        }
    }

    // ==========================================
    // 3. จัดการสลับโหมดฟิลด์ตามประเภทงาน (Project Mode Switcher)
    // ==========================================
    const modeSelect = document.getElementById('gustabe_project_mode');
    const softwareBox = document.getElementById('gustabe_software_fields_box');
    const webLiveField = document.getElementById('gustabe_web_live_field');
    const responsiveCaptureBtn = document.getElementById('gustabe_responsive_capture_btn');

    if (modeSelect) {
        function updateFieldsVisibility() {
            const currentMode = modeSelect.value;
            
            // ถ้าเป็นงานประเภท Software (.exe) หรือ CLI
            if (currentMode === 'software' || currentMode === 'cli') {
                if (softwareBox) softwareBox.style.display = 'block';
            } else {
                if (softwareBox) softwareBox.style.display = 'none';
            }

            // ถ้าเป็น Graphic ไม่จำเป็นต้องมีปุ่มแคป Responsive อัตโนมัติ
            if (responsiveCaptureBtn) {
                if (currentMode === 'graphic' || currentMode === 'software' || currentMode === 'cli') {
                    responsiveCaptureBtn.style.display = 'none';
                } else {
                    responsiveCaptureBtn.style.display = 'inline-flex';
                }
            }
        }

        modeSelect.addEventListener('change', updateFieldsVisibility);
        // เรียกทำงานทันทีตอนโหลดหน้า
        updateFieldsVisibility();
    }
});