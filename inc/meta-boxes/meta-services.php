<?php
/**
 * dir: inc/meta-boxes/
 * file: meta-services.php
 * หน้าที่: สร้าง Zone Builder (Custom Meta Boxes) สำหรับ CPT Services (Zone 1-4)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// -----------------------------------------------------------------------------
// 1. ลงทะเบียนกล่อง Meta Box (Register)
// -----------------------------------------------------------------------------
function gustabe_add_services_meta_boxes() {
    
    // Zone 1: Hero Section
    add_meta_box( 'gustabe_service_zone_1', '⚡ Zone 1: Hero & SEO Hook', 'gustabe_render_service_zone_1', 'services', 'normal', 'high' );

    // Zone 2: Features
    add_meta_box( 'gustabe_service_zone_2', '🍱 Zone 2: Core Values (จุดเด่น 3 ข้อ)', 'gustabe_render_service_zone_2', 'services', 'normal', 'high' );

    // Zone 3: Pricing
    add_meta_box( 'gustabe_service_zone_3', '💰 Zone 3: Pricing (ราคาสำหรับทำ Schema SEO)', 'gustabe_render_service_zone_3', 'services', 'normal', 'high' );

    // Zone 4: FAQ Engine
    add_meta_box( 'gustabe_service_zone_4', '❓ Zone 4: FAQ Engine (คำถามที่พบบ่อย)', 'gustabe_render_service_zone_4', 'services', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'gustabe_add_services_meta_boxes' );

// -----------------------------------------------------------------------------
// 2. ออกแบบหน้าตาช่องกรอกข้อมูล (Render UI)
// -----------------------------------------------------------------------------

// ⚡ Render Zone 1: Hero
function gustabe_render_service_zone_1( $post ) {
    wp_nonce_field( 'gustabe_save_services_meta', 'gustabe_services_meta_nonce' );

    $hero_h1       = get_post_meta( $post->ID, '_hero_h1', true );
    $hero_subtitle = get_post_meta( $post->ID, '_hero_subtitle', true );
    $service_icon  = get_post_meta( $post->ID, '_service_icon', true );

    ?>
    <div style="padding: 10px 0;">
        <p><strong><label for="hero_h1">1. หัวข้อหลัก (H1 สำหรับ SEO Override)</label></strong></p>
        <input type="text" id="hero_h1" name="hero_h1" value="<?php echo esc_attr( $hero_h1 ); ?>" style="width: 100%; padding: 8px;" placeholder="เช่น: บริการรับพัฒนาระบบ Web Application และ Custom แพลตฟอร์มธุรกิจ" />
        <p style="color: #666; font-size: 12px; margin-top: 4px;">* หากปล่อยว่างไว้ ระบบจะใช้ชื่อบทความ (Title) เป็น H1 อัตโนมัติ</p>
        
        <p style="margin-top: 15px;"><strong><label for="hero_subtitle">2. คำโปรยย่อย (Subtitle)</label></strong></p>
        <textarea id="hero_subtitle" name="hero_subtitle" style="width: 100%; padding: 8px; height: 80px;" placeholder="กระตุ้นความสนใจของลูกค้าที่นี่..."><?php echo esc_textarea( $hero_subtitle ); ?></textarea>

        <p style="margin-top: 15px;"><strong><label for="service_icon">3. ไอคอนประจำบริการ (Huge Icons Class)</label></strong></p>
        <input type="text" id="service_icon" name="service_icon" value="<?php echo esc_attr( $service_icon ); ?>" style="width: 50%; padding: 8px;" placeholder="เช่น: huge-code-circle" />
    </div>
    <?php
}

// 🍱 Render Zone 2: Features
function gustabe_render_service_zone_2( $post ) {
    ?>
    <div style="padding: 10px 0;">
        <p style="color: #666; margin-bottom: 20px;">กรอกจุดขาย 3 ข้อเพื่อนำไปแสดงเป็นกล่อง Grid เรียงกัน (Bento Box)</p>
        
        <?php for ( $i = 1; $i <= 3; $i++ ) : 
            $f_title = get_post_meta( $post->ID, "_feature_{$i}_title", true );
            $f_desc  = get_post_meta( $post->ID, "_feature_{$i}_desc", true );
            $f_icon  = get_post_meta( $post->ID, "_feature_{$i}_icon", true );
        ?>
            <div style="background: #f9f9f9; border: 1px solid #e5e5e5; padding: 15px; margin-bottom: 15px; border-radius: 4px;">
                <h4 style="margin-top: 0;">🔥 Feature <?php echo $i; ?></h4>
                <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                    <div style="flex: 1;">
                        <label>หัวข้อ:</label><br>
                        <input type="text" name="feature_<?php echo $i; ?>_title" value="<?php echo esc_attr( $f_title ); ?>" style="width: 100%; padding: 5px;" placeholder="เช่น: Tailor-Made System" />
                    </div>
                    <div style="flex: 1;">
                        <label>ไอคอนคลาส:</label><br>
                        <input type="text" name="feature_<?php echo $i; ?>_icon" value="<?php echo esc_attr( $f_icon ); ?>" style="width: 100%; padding: 5px;" placeholder="เช่น: huge-database" />
                    </div>
                </div>
                <div>
                    <label>คำอธิบายย่อย:</label><br>
                    <input type="text" name="feature_<?php echo $i; ?>_desc" value="<?php echo esc_attr( $f_desc ); ?>" style="width: 100%; padding: 5px;" placeholder="อธิบายจุดเด่นสั้นๆ..." />
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <?php
}

// 💰 Render Zone 3: Pricing
function gustabe_render_service_zone_3( $post ) {
    $price_value = get_post_meta( $post->ID, '_starting_price_value', true );
    $price_label = get_post_meta( $post->ID, '_starting_price_label', true );
    ?>
    <div style="padding: 10px 0;">
        <p style="color: #666; margin-bottom: 15px;">ข้อมูลนี้สำคัญมาก! บอท Google จะนำไปทำ Rich Snippets เพื่อโชว์ราคาบนหน้าผลการค้นหา</p>
        <div style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label for="starting_price_value"><strong>ราคาเริ่มต้น (ตัวเลขเท่านั้น) ⚡:</strong></label><br>
                <input type="number" id="starting_price_value" name="starting_price_value" value="<?php echo esc_attr( $price_value ); ?>" style="width: 100%; padding: 8px; margin-top: 5px;" placeholder="เช่น 50000" />
            </div>
            <div style="flex: 1;">
                <label for="starting_price_label"><strong>ข้อความแสดงผลบนเว็บ:</strong></label><br>
                <input type="text" id="starting_price_label" name="starting_price_label" value="<?php echo esc_attr( $price_label ); ?>" style="width: 100%; padding: 8px; margin-top: 5px;" placeholder="เช่น เริ่มต้น 50,000 บาท" />
            </div>
        </div>
    </div>
    <?php
}

// ❓ Render Zone 4: FAQ Engine (Dynamic Fields)
function gustabe_render_service_zone_4( $post ) {
    $faqs = get_post_meta( $post->ID, '_service_faqs', true );
    if ( ! is_array( $faqs ) ) {
        $faqs = []; 
    }
    ?>
    <div style="padding: 10px 0;" id="gustabe-faq-container">
        <p style="color: #666; margin-bottom: 15px;">ระบบจะนำคำถามเหล่านี้ไปสร้าง <code>FAQPage</code> Schema อัตโนมัติ (เพิ่มพื้นที่กวาด Google Search)</p>
        
        <div id="faq-wrapper">
            <?php 
            if ( ! empty( $faqs ) ) {
                foreach ( $faqs as $index => $faq ) { 
                    $q = isset($faq['question']) ? $faq['question'] : '';
                    $a = isset($faq['answer']) ? $faq['answer'] : '';
                    ?>
                    <div class="faq-item" style="background: #fff; border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; position: relative;">
                        <div style="margin-bottom: 10px;">
                            <label><strong>คำถาม (Question):</strong></label>
                            <input type="text" name="service_faqs[<?php echo $index; ?>][question]" value="<?php echo esc_attr( $q ); ?>" style="width: 100%; padding: 6px;" />
                        </div>
                        <div>
                            <label><strong>คำตอบ (Answer):</strong></label>
                            <textarea name="service_faqs[<?php echo $index; ?>][answer]" style="width: 100%; padding: 6px; height: 60px;"><?php echo esc_textarea( $a ); ?></textarea>
                        </div>
                        <button type="button" class="remove-faq-btn" style="position: absolute; top: 15px; right: 15px; color: red; border: none; background: none; cursor: pointer; font-weight: bold;">[X] ลบ</button>
                    </div>
                    <?php 
                }
            }
            ?>
        </div>

        <button type="button" id="add-faq-btn" style="padding: 8px 15px; background: #2271b1; color: #fff; border: none; border-radius: 3px; cursor: pointer; margin-top: 10px;">
            + เพิ่มคำถาม FAQ
        </button>
    </div>

    <!-- ⚡ Vanilla JS จัดการเพิ่ม/ลบคำถาม -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('faq-wrapper');
        const addBtn = document.getElementById('add-faq-btn');
        let faqIndex = <?php echo count( $faqs ); ?>; 

        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const html = `
                <div class="faq-item" style="background: #f0f8ff; border: 1px dashed #2271b1; padding: 15px; margin-bottom: 10px; position: relative;">
                    <div style="margin-bottom: 10px;">
                        <label><strong>คำถาม (Question):</strong></label>
                        <input type="text" name="service_faqs[${faqIndex}][question]" value="" style="width: 100%; padding: 6px;" placeholder="พิมพ์คำถามที่นี่..." />
                    </div>
                    <div>
                        <label><strong>คำตอบ (Answer):</strong></label>
                        <textarea name="service_faqs[${faqIndex}][answer]" style="width: 100%; padding: 6px; height: 60px;" placeholder="พิมพ์คำตอบที่นี่..."></textarea>
                    </div>
                    <button type="button" class="remove-faq-btn" style="position: absolute; top: 15px; right: 15px; color: red; border: none; background: none; cursor: pointer; font-weight: bold;">[X] ลบ</button>
                </div>
            `;
            wrapper.insertAdjacentHTML('beforeend', html);
            faqIndex++;
        });

        wrapper.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-faq-btn')) {
                e.preventDefault();
                if(confirm('ต้องการลบคำถามนี้ใช่หรือไม่?')) {
                    e.target.closest('.faq-item').remove();
                }
            }
        });
    });
    </script>
    <?php
}

// -----------------------------------------------------------------------------
// 3. บันทึกข้อมูลลงฐานข้อมูล (Save Data ทั้ง 4 โซน)
// -----------------------------------------------------------------------------
function gustabe_save_services_meta( $post_id ) {
    
    // Security Checks
    if ( ! isset( $_POST['gustabe_services_meta_nonce'] ) || ! wp_verify_nonce( $_POST['gustabe_services_meta_nonce'], 'gustabe_save_services_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // ⚡ Save Zone 1: Hero
    if ( isset( $_POST['hero_h1'] ) ) update_post_meta( $post_id, '_hero_h1', sanitize_text_field( wp_unslash( $_POST['hero_h1'] ) ) );
    if ( isset( $_POST['hero_subtitle'] ) ) update_post_meta( $post_id, '_hero_subtitle', sanitize_textarea_field( wp_unslash( $_POST['hero_subtitle'] ) ) );
    if ( isset( $_POST['service_icon'] ) ) update_post_meta( $post_id, '_service_icon', sanitize_text_field( wp_unslash( $_POST['service_icon'] ) ) );

    // 🍱 Save Zone 2: Features
    for ( $i = 1; $i <= 3; $i++ ) {
        if ( isset( $_POST["feature_{$i}_title"] ) ) update_post_meta( $post_id, "_feature_{$i}_title", sanitize_text_field( wp_unslash( $_POST["feature_{$i}_title"] ) ) );
        if ( isset( $_POST["feature_{$i}_desc"] ) ) update_post_meta( $post_id, "_feature_{$i}_desc", sanitize_text_field( wp_unslash( $_POST["feature_{$i}_desc"] ) ) );
        if ( isset( $_POST["feature_{$i}_icon"] ) ) update_post_meta( $post_id, "_feature_{$i}_icon", sanitize_text_field( wp_unslash( $_POST["feature_{$i}_icon"] ) ) );
    }

    // 💰 Save Zone 3: Pricing
    if ( isset( $_POST['starting_price_value'] ) ) update_post_meta( $post_id, '_starting_price_value', sanitize_text_field( wp_unslash( $_POST['starting_price_value'] ) ) );
    if ( isset( $_POST['starting_price_label'] ) ) update_post_meta( $post_id, '_starting_price_label', sanitize_text_field( wp_unslash( $_POST['starting_price_label'] ) ) );

    // ❓ Save Zone 4: FAQ Array
    if ( isset( $_POST['service_faqs'] ) && is_array( $_POST['service_faqs'] ) ) {
        $sanitized_faqs = [];
        foreach ( $_POST['service_faqs'] as $faq ) {
            // เซฟเฉพาะช่องที่มีคำถามเท่านั้น (กันช่องว่าง)
            if ( ! empty( trim( $faq['question'] ) ) ) {
                $sanitized_faqs[] = [
                    'question' => sanitize_text_field( wp_unslash( $faq['question'] ) ),
                    'answer'   => sanitize_textarea_field( wp_unslash( $faq['answer'] ) )
                ];
            }
        }
        update_post_meta( $post_id, '_service_faqs', $sanitized_faqs );
    } else {
        // ถ้าถูกลบออกหมด ให้เคลียร์ค่าทิ้ง
        delete_post_meta( $post_id, '_service_faqs' );
    }
}
add_action( 'save_post_services', 'gustabe_save_services_meta' );