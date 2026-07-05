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
    $client_name  = get_post_meta( $post->ID, '_client_name', true );
    $project_year = get_post_meta( $post->ID, '_project_year', true );
    $project_role = get_post_meta( $post->ID, '_project_role', true );
    $live_url     = get_post_meta( $post->ID, '_live_url', true );
    $app_store    = get_post_meta( $post->ID, '_app_store_url', true );
    $play_store   = get_post_meta( $post->ID, '_play_store_url', true );
    $github_url   = get_post_meta( $post->ID, '_github_url', true );
    $gallery_ids  = get_post_meta( $post->ID, '_project_gallery', true );

    // [เพิ่ม CSS สำหรับปุ่มลบรายภาพ]
    echo '<style>
        .gustabe-meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .gustabe-meta-field { display: flex; flex-direction: column; gap: 5px; }
        .gustabe-meta-field label { font-weight: 600; color: #1d2327; }
        .gustabe-meta-field input { width: 100%; padding: 5px 10px; border: 1px solid #8c8f94; border-radius: 4px; }
        .gustabe-gallery-preview { display: flex; flex-wrap: wrap; gap: 15px; margin-top: 15px; }
        
        /* สไตล์ใหม่สำหรับกล่องรูปและปุ่ม X */
        .gustabe-gallery-item { position: relative; display: inline-block; border: 1px solid #ccc; border-radius: 4px; padding: 2px; background: #fff; }
        .gustabe-gallery-item img { width: 100px; height: 100px; object-fit: cover; display: block; border-radius: 2px; }
        .gustabe-gallery-remove { position: absolute; top: -8px; right: -8px; background: #d63638; color: #fff; width: 20px; height: 20px; line-height: 18px; text-align: center; border-radius: 50%; font-size: 14px; font-weight: bold; cursor: pointer; border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.3); transition: background 0.2s; }
        .gustabe-gallery-remove:hover { background: #b32d2e; }
        .gustabe-section-title { font-size: 14px; font-weight: bold; padding-bottom: 5px; border-bottom: 1px solid #ddd; margin: 20px 0 15px; }
    </style>';

    echo '<div class="gustabe-meta-wrapper">';

    // --- โซน 1: ข้อมูลโครงการ ---
    echo '<div class="gustabe-section-title">📍 ข้อมูลพื้นฐานโครงการ</div>';
    echo '<div class="gustabe-meta-grid">';
    echo '<div class="gustabe-meta-field"><label>ชื่อลูกค้า (Client Name)</label><input type="text" name="client_name" value="' . esc_attr( $client_name ) . '"></div>';
    echo '<div class="gustabe-meta-field"><label>ปีที่พัฒนา (Project Year)</label><input type="text" name="project_year" value="' . esc_attr( $project_year ) . '" placeholder="e.g. 2024"></div>';
    echo '<div class="gustabe-meta-field" style="grid-column: 1 / -1;"><label>บทบาท (Project Role)</label><input type="text" name="project_role" value="' . esc_attr( $project_role ) . '" placeholder="e.g. UI/UX Design, Custom WordPress Development"></div>';
    echo '</div>';

    // --- โซน 2: ลิงก์ช่องทางต่างๆ ---
    echo '<div class="gustabe-section-title">🔗 ลิงก์เชื่อมโยง (External Links) *เว้นว่างไว้ถ้าไม่มี</div>';
    echo '<div class="gustabe-meta-grid">';
    // เปลี่ยน type="url" เป็น type="text"
    echo '<div class="gustabe-meta-field"><label>Website (Live URL)</label><input type="text" name="live_url" value="' . esc_url( $live_url ) . '" placeholder="https://..."></div>';
    echo '<div class="gustabe-meta-field"><label>GitHub / Figma Link</label><input type="text" name="github_url" value="' . esc_url( $github_url ) . '" placeholder="https://..."></div>';
    echo '<div class="gustabe-meta-field"><label>App Store Link (iOS)</label><input type="text" name="app_store_url" value="' . esc_url( $app_store ) . '" placeholder="https://..."></div>';
    echo '<div class="gustabe-meta-field"><label>Google Play Link (Android)</label><input type="text" name="play_store_url" value="' . esc_url( $play_store ) . '" placeholder="https://..."></div>';
    echo '</div>';

    // --- โซน 3: แกลเลอรีรูปภาพ ---
    echo '<div class="gustabe-section-title">🖼 อัลบั้มภาพผลงาน (Project Gallery)</div>';
    echo '<div class="gustabe-meta-field">';
    echo '<input type="hidden" id="gustabe_project_gallery" name="project_gallery" value="' . esc_attr( $gallery_ids ) . '">';
    echo '<div><button type="button" class="button button-primary" id="gustabe_gallery_upload_btn">จัดการรูปภาพผลงาน</button> <button type="button" class="button" id="gustabe_gallery_clear_btn">ลบทั้งหมด</button></div>';
    
    // พรีวิวรูปภาพ (อัปเดตใหม่ ให้มีกล่องห่อหุ้มและปุ่มลบ)
    echo '<div id="gustabe_gallery_preview_container" class="gustabe-gallery-preview">';
    if ( ! empty( $gallery_ids ) ) {
        $ids = explode( ',', $gallery_ids );
        foreach ( $ids as $id ) {
            $img_html = wp_get_attachment_image( $id, 'thumbnail' );
            if ( $img_html ) { // ป้องกันบั๊กกรณีที่รูปโดนลบจาก Media ไปแล้วแต่ไอดีค้าง
                echo '<div class="gustabe-gallery-item" data-id="' . esc_attr( $id ) . '">';
                echo $img_html;
                echo '<span class="gustabe-gallery-remove" title="ลบรูปนี้">&times;</span>'; // ปุ่ม X
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
        'client_name'    => 'sanitize_text_field',
        'project_year'   => 'sanitize_text_field',
        'project_role'   => 'sanitize_text_field',
        'live_url'       => 'sanitize_url',
        'app_store_url'  => 'sanitize_url',
        'play_store_url' => 'sanitize_url',
        'github_url'     => 'sanitize_url',
        'project_gallery'=> 'sanitize_text_field', // ไอดีรูปจะเป็น string แบบ "101,102,103"
    ];

    foreach ( $fields as $field => $sanitize_func ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, '_' . $field, call_user_func( $sanitize_func, $_POST[ $field ] ) );
        } else {
            delete_post_meta( $post_id, '_' . $field );
        }
    }
}
add_action( 'save_post_our_works', 'gustabe_save_our_works_meta' );