<?php
/**
 * GUSTABE AVATAR ENGINE (ULTIMATE EDITION - FIXED CSS)
 * แก้ไขปัญหา CSS ชนกับ Header Bar
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================================
// 1. FRONTEND: สร้าง UI และ Script
// =========================================================================

add_action( 'woocommerce_edit_account_form_start', 'gustabe_render_avatar_ui' );

function gustabe_render_avatar_ui() {
    $user_id = get_current_user_id();
    $custom_avatar_id = get_user_meta( $user_id, '_gustabe_custom_avatar', true );
    
    // ดึง URL รูปปัจจุบัน (Custom หรือ Default)
    $avatar_url = $custom_avatar_id ? wp_get_attachment_image_url( $custom_avatar_id, 'thumbnail' ) : get_avatar_url( $user_id );
    
    // เช็คว่ามีรูป Custom ไหม (เพื่อโชว์/ซ่อนปุ่มลบ)
    $has_custom = ! empty( $custom_avatar_id );
    
    // สร้าง Nonce เพื่อความปลอดภัย
    $nonce = wp_create_nonce( 'gustabe-avatar-nonce' );
    ?>

    <div class="gustabe-avatar-section">
        <div class="gustabe-upload-wrapper">
            
            <div class="avatar-loading" id="gustabe-avatar-loading">
                <div class="spinner"></div>
            </div>

            <img src="<?php echo esc_url( $avatar_url ); ?>" id="gustabe-avatar-img" alt="Profile Picture">
            
            <label for="gustabe_avatar_input" class="action-btn edit-btn" title="เปลี่ยนรูป">
                <i class="huge huge-camera-01"></i>
            </label>

            <div class="action-btn remove-btn <?php echo $has_custom ? '' : 'hidden'; ?>" id="gustabe-avatar-remove" title="ลบรูป">
                <i class="huge huge-delete-02"></i>
            </div>
        </div>

        <input type="file" id="gustabe_avatar_input" accept="image/jpeg,image/png,image/webp" hidden>
        
        <p class="helper-text">
            <?php echo (function_exists('pll__') ? pll__('แตะไอคอนกล้องเพื่อเปลี่ยนรูป') : 'Click camera icon to change'); ?>
        </p>
        <div id="gustabe-msg" class="msg-box"></div>
    </div>

    <style>
        /* CSS เฉพาะส่วน Avatar Upload (ใช้ Class ใหม่) */
        .gustabe-avatar-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px dashed #e0e0e0;
        }
        
        /* แก้ชื่อ Class ตรงนี้ให้ตรงกับ HTML */
        .gustabe-upload-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            margin-bottom: 15px;
        }
        
        #gustabe-avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            background: #f9f9f9;
            transition: 0.3s;
        }
        
        /* Buttons */
        .action-btn {
            position: absolute;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid #fff;
            font-size: 18px;
            z-index: 5; /* เพิ่ม z-index ให้กดง่าย */
        }
        .action-btn:hover { transform: scale(1.1); }
        
        .edit-btn {
            bottom: 5px; right: 5px;
            background: #04a39c; color: #fff;
        }
        .remove-btn {
            top: 5px; right: -5px;
            background: #ff4757; color: #fff;
        }
        .remove-btn.hidden { display: none; }

        /* Loading Overlay */
        .avatar-loading {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.7);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            z-index: 10;
            opacity: 0; visibility: hidden;
            transition: 0.3s;
        }
        .avatar-loading.active { opacity: 1; visibility: visible; }
        .spinner {
            width: 30px; height: 30px;
            border: 3px solid #ddd;
            border-top-color: #04a39c;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .helper-text { font-size: 14px; color: #888; margin-top: 5px; }
        .msg-box { font-size: 13px; margin-top: 8px; height: 20px; font-weight: 500; }
        .msg-success { color: #04a39c; }
        .msg-error { color: #ff4757; }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('gustabe_avatar_input');
        const img = document.getElementById('gustabe-avatar-img');
        const removeBtn = document.getElementById('gustabe-avatar-remove');
        const loader = document.getElementById('gustabe-avatar-loading');
        const msgBox = document.getElementById('gustabe-msg');
        
        // Define AJAX URL
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        const nonce = '<?php echo $nonce; ?>';

        // Helper: Show Message
        function showMsg(text, type) {
            msgBox.textContent = text;
            msgBox.className = 'msg-box ' + (type === 'success' ? 'msg-success' : 'msg-error');
            setTimeout(() => { msgBox.textContent = ''; }, 3000);
        }

        // Helper: Update All Avatars on Page (Optional)
        function updateGlobalAvatars(url) {
            // ค้นหารูป avatar อื่นๆ ในหน้าและเปลี่ยน source
            document.querySelectorAll('img.avatar, .custom-logo-link-mobile img, .my-account-link-logged-in-split-text img').forEach(el => {
                // ข้ามรูปตัวมันเองในกล่องอัปโหลด (เพราะเราอัปเดตแยกต่างหากแล้ว)
                if(el.id === 'gustabe-avatar-img') return;
                
                el.src = url;
                el.removeAttribute('srcset'); 
            });
        }

        // 1. Handle Upload
        input.addEventListener('change', function() {
            if (!this.files || !this.files[0]) return;
            
            const file = this.files[0];
            
            if (file.size > 2 * 1024 * 1024) {
                showMsg('ไฟล์ใหญ่เกินไป (Max 2MB)', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('action', 'gustabe_avatar_upload');
            formData.append('security', nonce);
            formData.append('avatar_file', file);

            loader.classList.add('active');

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loader.classList.remove('active');
                if (data.success) {
                    img.src = data.data.url; // Update Preview
                    updateGlobalAvatars(data.data.url); // Update Global
                    removeBtn.classList.remove('hidden'); // Show Remove Btn
                    showMsg('อัปโหลดเรียบร้อย!', 'success');
                } else {
                    showMsg(data.data || 'เกิดข้อผิดพลาด', 'error');
                }
            })
            .catch(err => {
                loader.classList.remove('active');
                showMsg('Server Error', 'error');
            });
        });

        // 2. Handle Remove
        removeBtn.addEventListener('click', function() {
            if(!confirm('ต้องการลบรูปโปรไฟล์ใช่หรือไม่?')) return;

            loader.classList.add('active');
            
            const formData = new FormData();
            formData.append('action', 'gustabe_avatar_remove');
            formData.append('security', nonce);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                loader.classList.remove('active');
                if (data.success) {
                    img.src = data.data.url; 
                    updateGlobalAvatars(data.data.url);
                    removeBtn.classList.add('hidden'); 
                    input.value = ''; 
                    showMsg('ลบรูปเรียบร้อย', 'success');
                } else {
                    showMsg(data.data, 'error');
                }
            })
            .catch(err => {
                loader.classList.remove('active');
                showMsg('Server Error', 'error');
            });
        });
    });
    </script>
    <?php
}

// =========================================================================
// 2. BACKEND: AJAX HANDLERS
// =========================================================================

// --- Upload Handler ---
add_action( 'wp_ajax_gustabe_avatar_upload', 'gustabe_handle_avatar_upload' );
function gustabe_handle_avatar_upload() {
    check_ajax_referer( 'gustabe-avatar-nonce', 'security' );

    if ( ! isset( $_FILES['avatar_file'] ) ) {
        wp_send_json_error( 'ไม่พบไฟล์' );
    }

    $file = $_FILES['avatar_file'];
    $user_id = get_current_user_id();

    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/media.php' );

    // Auto Cleanup
    $old_avatar_id = get_user_meta( $user_id, '_gustabe_custom_avatar', true );
    if ( $old_avatar_id ) {
        wp_delete_attachment( $old_avatar_id, true );
    }

    $attachment_id = media_handle_upload( 'avatar_file', 0 );

    if ( is_wp_error( $attachment_id ) ) {
        wp_send_json_error( $attachment_id->get_error_message() );
    } else {
        update_user_meta( $user_id, '_gustabe_custom_avatar', $attachment_id );
        $new_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
        wp_send_json_success( [ 'url' => $new_url ] );
    }
}

// --- Remove Handler ---
add_action( 'wp_ajax_gustabe_avatar_remove', 'gustabe_handle_avatar_remove' );
function gustabe_handle_avatar_remove() {
    check_ajax_referer( 'gustabe-avatar-nonce', 'security' );
    
    $user_id = get_current_user_id();
    $old_avatar_id = get_user_meta( $user_id, '_gustabe_custom_avatar', true );

    if ( $old_avatar_id ) {
        wp_delete_attachment( $old_avatar_id, true );
    }

    delete_user_meta( $user_id, '_gustabe_custom_avatar' );

    $default_url = get_avatar_url( $user_id );
    wp_send_json_success( [ 'url' => $default_url ] );
}

// =========================================================================
// 3. GLOBAL: Override WordPress Avatar
// =========================================================================

add_filter( 'get_avatar', 'gustabe_avatar_override_global', 10, 5 );
function gustabe_avatar_override_global( $avatar, $id_or_email, $size, $default, $alt ) {
    $user_id = 0;

    if ( is_numeric( $id_or_email ) ) {
        $user_id = (int) $id_or_email;
    } elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) ) {
        $user_id = $user->ID;
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user_id = (int) $id_or_email->user_id;
    }

    if ( $user_id > 0 ) {
        $custom_avatar_id = get_user_meta( $user_id, '_gustabe_custom_avatar', true );
        if ( $custom_avatar_id ) {
            $img_size = ( $size > 150 ) ? 'medium' : 'thumbnail';
            $custom_avatar_url = wp_get_attachment_image_url( $custom_avatar_id, $img_size );
            
            if ( $custom_avatar_url ) {
                $avatar = "<img alt='" . esc_attr( $alt ) . "' src='" . esc_url( $custom_avatar_url ) . "' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' loading='lazy' />";
            }
        }
    }

    return $avatar;
}