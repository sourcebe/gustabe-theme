<?php
/**
 * dir:  inc/meta-boxes/
 * file: meta-our-works.php
 * สร้าง Custom Meta Box สำหรับ CPT 'our_works'
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// 1. ลงทะเบียน Meta Box
function gustabe_add_our_works_meta_boxes() {
    add_meta_box(
        'gustabe_our_works_details',       // ID ของกล่อง
        'รายละเอียดโปรเจกต์ (Project Data)', // ชื่อหัวกล่อง
        'gustabe_render_our_works_meta_box', // ฟังก์ชันวาดฟอร์ม HTML
        'our_works',                       // CPT ที่จะให้แสดง
        'normal',                          // ตำแหน่ง (normal = ใต้ Editor)
        'high'                             // ความสำคัญ
    );
}
add_action( 'add_meta_boxes', 'gustabe_add_our_works_meta_boxes' );

// 2. วาดฟอร์ม HTML (UI หลังบ้าน)
function gustabe_render_our_works_meta_box( $post ) {
    // สร้าง Nonce สำหรับความปลอดภัย (ป้องกัน CSRF)
    wp_nonce_field( 'gustabe_save_our_works_data', 'gustabe_our_works_meta_nonce' );

    // ดึงค่าเก่าจาก Database (ถ้ามี)
    $project_mode = get_post_meta( $post->ID, '_project_mode', true ) ?: 'web';
    $client_name  = get_post_meta( $post->ID, '_client_name', true );
    $project_year = get_post_meta( $post->ID, '_project_year', true );
    $project_role = get_post_meta( $post->ID, '_project_role', true );
    $project_status = get_post_meta( $post->ID, '_project_status', true ) ?: 'completed';
    $key_metric   = get_post_meta( $post->ID, '_key_metric', true );
    
    // Software / EXE Specific Meta
    $app_version  = get_post_meta( $post->ID, '_app_version', true );
    $download_url = get_post_meta( $post->ID, '_download_url', true );
    $download_label = get_post_meta( $post->ID, '_download_label', true ) ?: 'Download .exe';
    $file_size    = get_post_meta( $post->ID, '_file_size', true );
    $target_os    = get_post_meta( $post->ID, '_target_os', true );
    $prerequisites = get_post_meta( $post->ID, '_prerequisites', true );
    $database_engine = get_post_meta( $post->ID, '_database_engine', true );
    $docs_url     = get_post_meta( $post->ID, '_docs_url', true );
    $cli_command  = get_post_meta( $post->ID, '_cli_command', true );

    // Web & App Links
    $live_url     = get_post_meta( $post->ID, '_live_url', true );
    $app_store    = get_post_meta( $post->ID, '_app_store_url', true );
    $play_store   = get_post_meta( $post->ID, '_play_store_url', true );
    $github_url   = get_post_meta( $post->ID, '_github_url', true );
    $gallery_ids  = get_post_meta( $post->ID, '_project_gallery', true );

    // [เพิ่ม CSS สำหรับการจัดระเบียบ Meta Box]
    echo '<style>
        .gustabe-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .gustabe-meta-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .gustabe-meta-field { display: flex; flex-direction: column; gap: 5px; }
        .gustabe-meta-field label { font-weight: 600; color: #1d2327; font-size: 12px; }
        .gustabe-meta-field input, .gustabe-meta-field select { width: 100%; padding: 6px 10px; border: 1px solid #8c8f94; border-radius: 4px; font-size: 13px; }
        .gustabe-gallery-preview { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; }
        .gustabe-type-selector-banner { background: #f0f6fc; border: 1px solid #c8d9e8; border-left: 4px solid #2271b1; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }
        .gustabe-type-selector-banner label { font-weight: 700; color: #0c2d48; font-size: 13px; }
        .gustabe-type-selector-banner select { font-weight: 600; padding: 6px 12px; min-width: 280px; border-radius: 4px; border-color: #2271b1; }
        
        /* สไตล์สำหรับกล่องรูปและปุ่ม X */
        .gustabe-gallery-item { position: relative; display: inline-block; border: 1px solid #ccc; border-radius: 4px; padding: 2px; background: #fff; }
        .gustabe-gallery-item img { width: 100px; height: 100px; object-fit: cover; display: block; border-radius: 2px; }
        .gustabe-device-badge { position: absolute; bottom: 4px; left: 4px; right: 4px; background: rgba(0,0,0,0.75); color: #fff; font-size: 10px; padding: 2px 4px; border-radius: 3px; text-align: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: monospace; }
        .gustabe-gallery-remove { position: absolute; top: -8px; right: -8px; background: #d63638; color: #fff; width: 20px; height: 20px; line-height: 18px; text-align: center; border-radius: 50%; font-size: 14px; font-weight: bold; cursor: pointer; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.3); transition: background 0.2s; }
        .gustabe-gallery-remove:hover { background: #b32d2e; }
        .gustabe-section-title { font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; padding-bottom: 6px; border-bottom: 2px solid #e2e4e7; margin: 24px 0 16px; color: #1e293b; display: flex; align-items: center; gap: 6px; }
        .gustabe-pill-badge { font-size: 10px; background: #0f172a; color: #38bdf8; padding: 2px 8px; border-radius: 10px; font-family: monospace; }
        .gustabe-conditional-box { transition: opacity 0.2s ease; }
        @keyframes gustabe-spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>';

    echo '<div class="gustabe-meta-wrapper">';

    // --- โซน 0: เลือกประเภทผลงานหลัก (Mode Switcher) ---
    echo '<div class="gustabe-type-selector-banner">';
    echo '<div>';
    echo '<label for="gustabe_project_mode">🎯 ประเภทผลงาน (Project Architecture / Mode):</label>';
    echo '<div style="font-size: 11px; color: #50575e; margin-top: 3px;">เลือกประเภทเพื่อเปิดใช้ชุดฟิลด์และหน้าต่างพรีวิวที่ตรงกับงานชิ้นนี้</div>';
    echo '</div>';
    echo '<select id="gustabe_project_mode" name="project_mode">';
    $modes = [
        'web'      => '🌐 Web Application / Website (รองรับ Responsive Device Preview)',
        'software' => '💻 Desktop Software (.exe / Windows App / VB / C# / Electron)',
        'cli'      => '⚡ CLI Tool / Automation Script / Bot (Terminal Output View)',
        'mobile'   => '📱 Mobile Application (iOS / Android Only)',
        'graphic'  => '🎨 UI/UX & Graphic Artwork / Branding (Artwork View)'
    ];
    foreach ( $modes as $key => $label ) {
        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $project_mode, $key, false ) . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
    echo '</div>';

    // --- โซน 1: ข้อมูลพื้นฐานโครงการ ---
    echo '<div class="gustabe-section-title">📍 ข้อมูลพื้นฐานโครงการ (Core Metadata)</div>';
    echo '<div class="gustabe-meta-grid-3">';
    echo '<div class="gustabe-meta-field"><label>ชื่อลูกค้า / องค์กร (Client / Org)</label><input type="text" name="client_name" value="' . esc_attr( $client_name ) . '" placeholder="e.g. Internal / ACME Corp"></div>';
    echo '<div class="gustabe-meta-field"><label>ปีหรือระยะเวลา (Year / Duration)</label><input type="text" name="project_year" value="' . esc_attr( $project_year ) . '" placeholder="e.g. 2024 (3 months)"></div>';
    echo '<div class="gustabe-meta-field"><label>สถานะโครงการ (Project Status)</label>';
    echo '<select name="project_status">';
    echo '<option value="completed" ' . selected( $project_status, 'completed', false ) . '>✅ Completed / Production</option>';
    echo '<option value="active" ' . selected( $project_status, 'active', false ) . '>⚡ Active Development / v2.0</option>';
    echo '<option value="archived" ' . selected( $project_status, 'archived', false ) . '>📦 Archived / Legacy Code</option>';
    echo '</select>';
    echo '</div>';
    echo '<div class="gustabe-meta-field" style="grid-column: 1 / 3;"><label>บทบาทหน้าที่ (Role)</label><input type="text" name="project_role" value="' . esc_attr( $project_role ) . '" placeholder="e.g. Lead Software Architect, Full-stack Developer, UI/UX"></div>';
    echo '<div class="gustabe-meta-field"><label>ผลลัพธ์เด่น / Key Metric (Impact)</label><input type="text" name="key_metric" value="' . esc_attr( $key_metric ) . '" placeholder="e.g. ลดเวลาประมวลผล 60%, 10k+ Users"></div>';
    echo '</div>';

    // --- โซน 2: ฟิลด์เฉพาะสำหรับ Desktop Software & Script (.exe / VB / C# / CLI) ---
    echo '<div id="gustabe_software_fields_box" class="gustabe-conditional-box">';
    echo '<div class="gustabe-section-title">💾 ข้อมูลสเปกโปรแกรม Desktop Software &amp; Tools <span class="gustabe-pill-badge">.EXE / SCRIPT</span></div>';
    echo '<div class="gustabe-meta-grid">';
    
    echo '<div class="gustabe-meta-field"><label>เวอร์ชันโปรแกรม (Version / Build)</label><input type="text" name="app_version" value="' . esc_attr( $app_version ) . '" placeholder="e.g. v2.4.1 (Build 2409)"></div>';
    echo '<div class="gustabe-meta-field"><label>ขนาดไฟล์ติดตั้ง (File Size)</label><input type="text" name="file_size" value="' . esc_attr( $file_size ) . '" placeholder="e.g. 45.8 MB หรือ Portable .zip (12 MB)"></div>';
    
    echo '<div class="gustabe-meta-field"><label>ระบบปฏิบัติการที่รองรับ (Target OS / Platform)</label><input type="text" name="target_os" value="' . esc_attr( $target_os ) . '" placeholder="e.g. Windows 11 / 10 (x64, x86) / Windows 7 SP1"></div>';
    echo '<div class="gustabe-meta-field"><label>สิ่งที่ต้องมีก่อนรัน (Runtime / Prerequisites)</label><input type="text" name="prerequisites" value="' . esc_attr( $prerequisites ) . '" placeholder="e.g. .NET Framework 4.8 / MSVBVM60.DLL / Python 3.10+"></div>';
    
    echo '<div class="gustabe-meta-field"><label>ฐานข้อมูลหรือ Storage ที่เชื่อมต่อ (Database Engine)</label><input type="text" name="database_engine" value="' . esc_attr( $database_engine ) . '" placeholder="e.g. Microsoft SQL Server / SQLite / MS Access (.mdb)"></div>';
    echo '<div class="gustabe-meta-field"><label>คำสั่งติดตั้ง / รันโปรแกรม (CLI Command)</label><input type="text" name="cli_command" value="' . esc_attr( $cli_command ) . '" placeholder="e.g. npm install -g @gustabe/tool หรือ pip install bot-runner"></div>';
    echo '<div class="gustabe-meta-field"><label>ลิงก์คู่มือ / Release Notes / Documentation</label><input type="text" name="docs_url" value="' . esc_url( $docs_url ) . '" placeholder="https://docs... หรือ ลิงก์ PDF"></div>';
    
    echo '<div class="gustabe-meta-field"><label>ปุ่มดาวน์โหลด: ข้อความบนปุ่ม (Download Button Label)</label><input type="text" name="download_label" value="' . esc_attr( $download_label ) . '" placeholder="Download .exe / ดาวน์โหลดโปรแกรม"></div>';
    echo '<div class="gustabe-meta-field" style="grid-column: 1 / -1;"><label>ลิงก์ดาวน์โหลดไฟล์ (Direct Download URL)</label><input type="text" name="download_url" value="' . esc_url( $download_url ) . '" placeholder="https://domain.com/downloads/setup.exe หรือ Google Drive link"></div>';
    echo '</div>';
    echo '</div>';

    // ดึงค่า Featured Image ปัจจุบันเพื่อแสดงพรีวิว
    $current_thumbnail_id  = get_post_thumbnail_id( $post->ID );
    $current_thumbnail_url = $current_thumbnail_id ? wp_get_attachment_image_url( $current_thumbnail_id, 'medium' ) : '';

    // --- โซน 3: ลิงก์ช่องทางออนไลน์ (Online & Repo Links) ---
    echo '<div class="gustabe-section-title">🔗 ลิงก์เชื่อมโยง (External Links) <span class="gustabe-pill-badge">WEB / REPO / STORES</span></div>';
    echo '<div class="gustabe-meta-grid">';
    
    // Website (Live URL) พร้อมปุ่ม Auto Capture Screenshot
    echo '<div id="gustabe_web_live_field" class="gustabe-meta-field" style="grid-column: 1 / -1;">';
    echo '<label for="gustabe_live_url">Website (Live URL) &amp; ภาพหน้าปก</label>';
    echo '<div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">';
    echo '<input type="text" id="gustabe_live_url" name="live_url" value="' . esc_url( $live_url ) . '" placeholder="https://example.com" style="flex: 1; min-width: 250px;">';
    echo '<button type="button" class="button button-secondary" id="gustabe_auto_capture_btn" style="white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; height: 32px; font-weight: 500;">';
    echo '<span class="dashicons dashicons-camera" style="font-size: 16px; margin-top: 2px;"></span>';
    echo '<span class="btn-text">⚡ ดึงภาพหน้าจอเป็นภาพปก</span>';
    echo '</button>';
    echo '</div>';
    echo '<div id="gustabe_capture_status" style="margin-top: 6px; font-size: 12px; display: none;"></div>';
    echo '<div id="gustabe_cover_preview_wrapper" style="margin-top: 10px; display: ' . ( $current_thumbnail_url ? 'inline-block' : 'none' ) . ';">';
    echo '<div style="font-size: 11px; color: #646970; margin-bottom: 4px; font-weight: 600;">🖼 ภาพปกปัจจุบัน (Featured Image):</div>';
    echo '<img id="gustabe_cover_preview_img" src="' . esc_url( $current_thumbnail_url ) . '" alt="Cover Preview" style="max-width: 220px; max-height: 140px; object-fit: cover; border: 1px solid #c3c4c7; border-radius: 4px; display: block; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">';
    echo '</div>';
    echo '</div>';

    echo '<div class="gustabe-meta-field"><label>GitHub / GitLab / Source Repo</label><input type="text" name="github_url" value="' . esc_url( $github_url ) . '" placeholder="https://github.com/username/repo"></div>';
    echo '<div class="gustabe-meta-field"><label>App Store Link (iOS)</label><input type="text" name="app_store_url" value="' . esc_url( $app_store ) . '" placeholder="https://apps.apple.com/..."></div>';
    echo '<div class="gustabe-meta-field"><label>Google Play Link (Android)</label><input type="text" name="play_store_url" value="' . esc_url( $play_store ) . '" placeholder="https://play.google.com/..."></div>';
    echo '</div>';

    // --- โซน 4: แกลเลอรีรูปภาพ ---
    echo '<div class="gustabe-section-title">🖼 ภาพหน้าจอโปรแกรม &amp; แกลเลอรี (Project Screenshots)</div>';
    echo '<div class="gustabe-meta-field">';
    echo '<input type="hidden" id="gustabe_project_gallery" name="project_gallery" value="' . esc_attr( $gallery_ids ) . '">';
    
    // ปุ่มสั่งการ 2 ระบบ
    echo '<div style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center; margin-bottom: 8px;">';
    echo '<button type="button" class="button button-primary" id="gustabe_gallery_upload_btn"><span class="dashicons dashicons-images-alt2" style="font-size: 16px; margin-top: 3px; margin-right: 4px;"></span>เลือกรูปภาพจากเครื่อง / Screenshot</button>';
    echo '<button type="button" class="button button-secondary" id="gustabe_responsive_capture_btn" style="display: inline-flex; align-items: center; gap: 4px; font-weight: 600; color: #007017; border-color: #007017;">';
    echo '<span class="dashicons dashicons-devices" style="font-size: 16px; margin-top: 2px;"></span>';
    echo '<span class="btn-text">⚡ ดึงภาพ Responsive อัตโนมัติ (เฉพาะเว็บ URL)</span>';
    echo '</button>';
    echo '<button type="button" class="button" id="gustabe_gallery_clear_btn" style="color: #d63638;">ลบทั้งหมด</button>';
    echo '</div>';

    echo '<div id="gustabe_gallery_capture_status" style="margin-top: 6px; margin-bottom: 8px; font-size: 12px; display: none;"></div>';
    
    // พรีวิวรูปภาพ
    echo '<div id="gustabe_gallery_preview_container" class="gustabe-gallery-preview">';
    if ( ! empty( $gallery_ids ) ) {
        $ids = explode( ',', $gallery_ids );
        foreach ( $ids as $id ) {
            $img_html    = wp_get_attachment_image( $id, 'thumbnail' );
            $device_meta = get_post_meta( $id, '_gustabe_device_type', true );
            $badge_text  = '';
            if ( $device_meta === 'desktop' ) $badge_text = '💻 Desktop';
            elseif ( $device_meta === 'tablet' ) $badge_text = '📱 Tablet';
            elseif ( $device_meta === 'mobile' ) $badge_text = '📲 Mobile';

            if ( $img_html ) {
                echo '<div class="gustabe-gallery-item" data-id="' . esc_attr( $id ) . '">';
                echo $img_html;
                if ( $badge_text ) {
                    echo '<span class="gustabe-device-badge">' . esc_html( $badge_text ) . '</span>';
                }
                echo '<span class="gustabe-gallery-remove" title="ลบรูปนี้">&times;</span>';
                echo '</div>';
            }
        }
    }
    echo '</div>';
    echo '</div>'; // จบโซนแกลเลอรี

    echo '</div>'; // จบ wrapper
}

// 3. บันทึกข้อมูลลง Database
function gustabe_save_our_works_meta( $post_id ) {
    // เช็ค Nonce
    if ( ! isset( $_POST['gustabe_our_works_meta_nonce'] ) || ! wp_verify_nonce( $_POST['gustabe_our_works_meta_nonce'], 'gustabe_save_our_works_data' ) ) {
        return;
    }
    // เช็ค Auto Save
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    // เช็คสิทธิ์ User
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // กวาดข้อมูลและ Sanitize ก่อน Save ลงตาราง wp_postmeta
    $fields = [
        'project_mode'    => 'sanitize_text_field',
        'client_name'     => 'sanitize_text_field',
        'project_year'    => 'sanitize_text_field',
        'project_role'    => 'sanitize_text_field',
        'project_status'  => 'sanitize_text_field',
        'key_metric'      => 'sanitize_text_field',
        'app_version'     => 'sanitize_text_field',
        'download_url'    => 'sanitize_url',
        'download_label'  => 'sanitize_text_field',
        'file_size'       => 'sanitize_text_field',
        'target_os'       => 'sanitize_text_field',
        'prerequisites'   => 'sanitize_text_field',
        'database_engine' => 'sanitize_text_field',
        'docs_url'        => 'sanitize_url',
        'cli_command'     => 'sanitize_text_field',
        'live_url'        => 'sanitize_url',
        'app_store_url'   => 'sanitize_url',
        'play_store_url'  => 'sanitize_url',
        'github_url'      => 'sanitize_url',
        'project_gallery' => 'sanitize_text_field',
    ];

    foreach ( $fields as $field => $sanitize_func ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize_func, wp_unslash( $_POST[ $field ] ) ) );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
}
add_action( 'save_post_our_works', 'gustabe_save_our_works_meta' );

// 4. AJAX Handler: แคปภาพหน้าจอจาก Website URL อัตโนมัติและตั้งเป็นภาพปก
function gustabe_ajax_auto_capture_cover() {
    check_ajax_referer( 'gustabe_auto_screenshot_nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
    if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
        wp_send_json_error( [ 'message' => 'คุณไม่มีสิทธิ์แก้ไขโปรเจกต์นี้' ] );
    }

    $target_url = isset( $_POST['target_url'] ) ? esc_url_raw( trim( wp_unslash( $_POST['target_url'] ) ) ) : '';
    if ( empty( $target_url ) || ! filter_var( $target_url, FILTER_VALIDATE_URL ) ) {
        wp_send_json_error( [ 'message' => 'กรุณาระบุ URL ที่ถูกต้อง (เช่น https://example.com)' ] );
    }

    // 1. บริการหลัก: Microlink API (ใช้ Headless Chromium เรนเดอร์ CSS/JS จริง ภาพคมชัดสูง ไม่มีลายน้ำ)
    $image_data = null;
    $microlink_api = 'https://api.microlink.io/?url=' . rawurlencode( $target_url ) . '&screenshot=true&meta=false&waitForTimeout=2500';
    $ml_response   = wp_remote_get( $microlink_api, [
        'timeout'    => 25,
        'sslverify'  => false,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ] );

    if ( ! is_wp_error( $ml_response ) && wp_remote_retrieve_response_code( $ml_response ) === 200 ) {
        $ml_json = json_decode( wp_remote_retrieve_body( $ml_response ), true );
        if ( ! empty( $ml_json['data']['screenshot']['url'] ) ) {
            $img_res = wp_remote_get( $ml_json['data']['screenshot']['url'], [
                'timeout'   => 25,
                'sslverify' => false,
            ] );
            if ( ! is_wp_error( $img_res ) && wp_remote_retrieve_response_code( $img_res ) === 200 ) {
                $body = wp_remote_retrieve_body( $img_res );
                // ตรวจสอบว่าเป็นรูปภาพจริงและขนาดไฟล์สมเหตุสมผล (> 15KB)
                if ( strlen( $body ) > 15360 ) {
                    $image_data = $body;
                }
            }
        }
    }

    // 2. หาก Microlink ไม่สำเร็จ ให้สำรองด้วย Thum.io (โดยใส่ wait/5 และ noanimate เพื่อบังคับให้รอหน้าเว็บโหลด ไม่คืนรูปหมุนโหลดดิ้ง)
    if ( empty( $image_data ) ) {
        $thum_url = 'https://image.thum.io/get/wait/5/noanimate/width/1280/crop/800/' . $target_url;
        $response = wp_remote_get( $thum_url, [
            'timeout'    => 30,
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'sslverify'  => false,
        ] );

        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $content_type = wp_remote_retrieve_header( $response, 'content-type' );
            $body         = wp_remote_retrieve_body( $response );
            // รูป placeholder หมุนๆ มักมีขนาดเล็กมาก และมี header หรือขนาดต่ำกว่า 30KB
            if ( strpos( $content_type, 'image' ) !== false && strlen( $body ) > 30720 ) {
                $image_data = $body;
            }
        }
    }

    if ( empty( $image_data ) ) {
        wp_send_json_error( [ 'message' => 'ไม่สามารถแคปภาพจาก URL ที่ระบุได้ กรุณาตรวจสอบว่าเว็บไซต์เปิดออนไลน์ปกติ หรือลองอีกครั้งใน 1-2 นาที' ] );
    }

    // 3. เซฟไฟล์ลงโฟลเดอร์ uploads ของโฮสต์
    $domain       = parse_url( $target_url, PHP_URL_HOST );
    $clean_domain = preg_replace( '/[^a-zA-Z0-9_-]/', '-', $domain ?: 'screenshot' );
    $filename     = sanitize_file_name( 'screenshot-' . $clean_domain . '-' . time() . '.png' );

    $upload = wp_upload_bits( $filename, null, $image_data );
    if ( ! empty( $upload['error'] ) ) {
        wp_send_json_error( [ 'message' => 'เกิดข้อผิดพลาดในการบันทึกไฟล์ภาพ: ' . $upload['error'] ] );
    }

    // 4. สร้าง Media Attachment ใน WordPress
    $attachment = [
        'post_mime_type' => 'image/png',
        'post_title'     => 'Screenshot ' . ( $domain ?: $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
    if ( ! $attach_id || is_wp_error( $attach_id ) ) {
        wp_send_json_error( [ 'message' => 'ไม่สามารถสร้าง Media Attachment ในระบบได้' ] );
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    // 5. ตั้งค่าเป็น Featured Image และอัปเดต live_url
    set_post_thumbnail( $post_id, $attach_id );
    update_post_meta( $post_id, '_live_url', $target_url );

    $thumb_url = wp_get_attachment_image_url( $attach_id, 'medium' );
    $full_url  = wp_get_attachment_image_url( $attach_id, 'full' );

    wp_send_json_success( [
        'message'       => 'แคปภาพหน้าจอและตั้งเป็นภาพปกสำเร็จ!',
        'attach_id'     => $attach_id,
        'thumbnail_url' => $thumb_url ?: $upload['url'],
        'full_url'      => $full_url ?: $upload['url'],
    ] );
}
add_action( 'wp_ajax_gustabe_auto_capture_cover', 'gustabe_ajax_auto_capture_cover' );

// 5. AJAX Handler: แคปภาพหน้าจอตามขนาดอุปกรณ์ (Desktop / Tablet / Mobile) เข้า Project Gallery
function gustabe_ajax_capture_responsive_device() {
    check_ajax_referer( 'gustabe_auto_screenshot_nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
    if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
        wp_send_json_error( [ 'message' => 'คุณไม่มีสิทธิ์แก้ไขโปรเจกต์นี้' ] );
    }

    $target_url = isset( $_POST['target_url'] ) ? esc_url_raw( trim( wp_unslash( $_POST['target_url'] ) ) ) : '';
    if ( empty( $target_url ) || ! filter_var( $target_url, FILTER_VALIDATE_URL ) ) {
        wp_send_json_error( [ 'message' => 'กรุณาระบุ URL ที่ถูกต้อง' ] );
    }

    $device = isset( $_POST['device'] ) ? sanitize_key( $_POST['device'] ) : 'desktop';
    $device_configs = [
        'desktop' => [
            'label'   => 'Desktop',
            'width'   => 1440,
            'height'  => 900,
            'mobile'  => false,
            'badge'   => '💻 Desktop',
        ],
        'tablet'  => [
            'label'   => 'Tablet',
            'width'   => 768,
            'height'  => 1024,
            'mobile'  => true,
            'badge'   => '📱 Tablet',
        ],
        'mobile'  => [
            'label'   => 'Mobile',
            'width'   => 375,
            'height'  => 812,
            'mobile'  => true,
            'badge'   => '📲 Mobile',
        ],
    ];

    if ( ! isset( $device_configs[ $device ] ) ) {
        $device = 'desktop';
    }

    $cfg = $device_configs[ $device ];

    // สร้าง Microlink URL พร้อม Viewport parameters
    $query_params = [
        'url'                    => $target_url,
        'screenshot'             => 'true',
        'meta'                   => 'false',
        'viewport.width'         => $cfg['width'],
        'viewport.height'        => $cfg['height'],
        'viewport.deviceScaleFactor' => 2,
        'waitForTimeout'         => 2500,
    ];
    if ( $cfg['mobile'] ) {
        $query_params['viewport.isMobile'] = 'true';
        $query_params['viewport.hasTouch'] = 'true';
    }

    $microlink_api = 'https://api.microlink.io/?' . http_build_query( $query_params );
    $ml_response   = wp_remote_get( $microlink_api, [
        'timeout'    => 35,
        'sslverify'  => false,
        'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ] );

    $image_data = null;
    if ( ! is_wp_error( $ml_response ) && wp_remote_retrieve_response_code( $ml_response ) === 200 ) {
        $ml_json = json_decode( wp_remote_retrieve_body( $ml_response ), true );
        if ( ! empty( $ml_json['data']['screenshot']['url'] ) ) {
            $img_res = wp_remote_get( $ml_json['data']['screenshot']['url'], [
                'timeout'   => 30,
                'sslverify' => false,
            ] );
            if ( ! is_wp_error( $img_res ) && wp_remote_retrieve_response_code( $img_res ) === 200 ) {
                $body = wp_remote_retrieve_body( $img_res );
                if ( strlen( $body ) > 15360 ) {
                    $image_data = $body;
                }
            }
        }
    }

    if ( empty( $image_data ) ) {
        wp_send_json_error( [ 'message' => "ไม่สามารถบันทึกภาพขนาด {$cfg['label']} ได้ กรุณาลองใหม่อีกครั้ง" ] );
    }

    // เซฟไฟล์ลงโฟลเดอร์ uploads ของโฮสต์
    $domain       = parse_url( $target_url, PHP_URL_HOST );
    $clean_domain = preg_replace( '/[^a-zA-Z0-9_-]/', '-', $domain ?: 'screenshot' );
    $filename     = sanitize_file_name( 'responsive-' . $device . '-' . $clean_domain . '-' . time() . '.png' );

    $upload = wp_upload_bits( $filename, null, $image_data );
    if ( ! empty( $upload['error'] ) ) {
        wp_send_json_error( [ 'message' => 'เกิดข้อผิดพลาดในการบันทึกไฟล์ภาพ: ' . $upload['error'] ] );
    }

    // สร้าง Media Attachment ใน WordPress
    $attachment = [
        'post_mime_type' => 'image/png',
        'post_title'     => 'Mockup [' . $cfg['label'] . ' ' . $cfg['width'] . 'x' . $cfg['height'] . '] ' . ( $domain ?: $filename ),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];

    $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
    if ( ! $attach_id || is_wp_error( $attach_id ) ) {
        wp_send_json_error( [ 'message' => 'ไม่สามารถสร้าง Media Attachment ในระบบได้' ] );
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
    wp_update_attachment_metadata( $attach_id, $attach_data );

    // บันทึก Meta Device Type ลงใน Attachment เพื่อใช้อ้างอิงแสดง Badge
    update_post_meta( $attach_id, '_gustabe_device_type', $device );
    update_post_meta( $attach_id, '_wp_attachment_image_alt', 'Mockup ' . $cfg['label'] . ' View - ' . ( $domain ?: '' ) );

    // เพิ่ม Attachment ID ต่อท้ายเข้าไปใน _project_gallery (Append Logic)
    $current_gallery = get_post_meta( $post_id, '_project_gallery', true );
    $gallery_array   = ! empty( $current_gallery ) ? array_filter( array_map( 'trim', explode( ',', $current_gallery ) ) ) : [];
    if ( ! in_array( (string) $attach_id, $gallery_array, true ) ) {
        $gallery_array[] = (string) $attach_id;
    }
    $updated_gallery_str = implode( ',', $gallery_array );
    update_post_meta( $post_id, '_project_gallery', $updated_gallery_str );

    $thumb_url = wp_get_attachment_image_url( $attach_id, 'thumbnail' );
    $full_url  = wp_get_attachment_image_url( $attach_id, 'full' );

    wp_send_json_success( [
        'message'       => "แคปภาพหน้าจอขนาด {$cfg['label']} สำเร็จ!",
        'attach_id'     => $attach_id,
        'device'        => $device,
        'badge'         => $cfg['badge'],
        'gallery_str'   => $updated_gallery_str,
        'thumbnail_url' => $thumb_url ?: $upload['url'],
        'full_url'      => $full_url ?: $upload['url'],
    ] );
}
add_action( 'wp_ajax_gustabe_capture_responsive_device', 'gustabe_ajax_capture_responsive_device' );