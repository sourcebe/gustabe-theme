<?php
/**
 * Custom Product Card - Final Fix (Style 2 & 3) + Polylang Support
 */
defined( 'ABSPATH' ) || exit;
global $product;

if ( empty( $product ) || ! $product->is_visible() ) return;

// --- เตรียมคำศัพท์สำหรับแปล (Polylang) ---
$txt_sold_label   = function_exists('pll__') ? pll__('ขายแล้ว') : 'ขายแล้ว';
$txt_sold_unit    = function_exists('pll__') ? pll__('ชิ้น') : 'ชิ้น';
$txt_out_stock    = function_exists('pll__') ? pll__('สินค้าหมด') : 'สินค้าหมด'; // แก้จาก "หมด" ให้เต็มยศหน่อย
$txt_view_details = function_exists('pll__') ? pll__('ดูรายละเอียด') : 'ดูรายละเอียด';
$txt_add_cart     = function_exists('pll__') ? pll__('ใส่ตะกร้า') : 'ใส่ตะกร้า';

$card_style = get_option( 'hello_child_card_style', 'style-1' );
$title      = $product->get_title();
$price_html = $product->get_price_html();
$image_url  = wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_thumbnail' );
$add_to_cart_url = $product->add_to_cart_url();

// Logic ป้าย Sale
$sale_badge = '';
if ( $product->is_on_sale() && $product->get_type() == 'simple' ) {
    $regular = $product->get_regular_price();
    $sale    = $product->get_sale_price();
    if( $regular && $sale ) {
        $percent = round( ( ($regular - $sale) / $regular ) * 100 );
        $sale_badge = '<span class="mp-badge-sale">-' . $percent . '%</span>';
    }
}
?>

<li class="product-item-wrapper <?php echo esc_attr($card_style); ?>">
    <div class="mp-card-inner">
        
        <?php if ( 'style-1' === $card_style ) : ?>
            <div class="mp-img-box">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $sale_badge; ?>
                    <?php if($image_url): ?><img src="<?php echo esc_url($image_url); ?>" loading="lazy"><?php else: ?><img src="<?php echo wc_placeholder_img_src(); ?>"><?php endif; ?>
                </a>
            </div>
            <div class="mp-content">
                <a href="<?php the_permalink(); ?>" class="mp-title-link"><h3 class="mp-title"><?php echo $title; ?></h3></a>
                
                <?php if($product->get_total_sales() > 0): ?>
                    <div class="mp-sales"><?php echo esc_html($txt_sold_label . ' ' . $product->get_total_sales() . ' ' . $txt_sold_unit); ?></div>
                <?php else: ?>
                    <div class="mp-sales" style="opacity:0;">.</div>
                <?php endif; ?>
                
                <div class="mp-footer-row">
                    <div class="mp-price"><?php echo $product->get_price_html(); ?></div>
                    
                    <div class="mp-action-group">
                        <a href="<?php the_permalink(); ?>" class="mp-view-btn circle-btn" aria-label="<?php echo esc_attr($txt_view_details); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </a>
                        
                        <a href="<?php echo esc_url($add_to_cart_url); ?>" class="mp-add-btn circle-btn" aria-label="<?php echo esc_attr($txt_add_cart); ?>">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 6h15l-1.5 9h-13z"/><path d="M6 6H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        <?php elseif ( 'style-2' === $card_style ) : ?>
            <div class="mp-img-box">
                <a href="<?php the_permalink(); ?>">
                    <?php if($image_url): ?><img src="<?php echo esc_url($image_url); ?>" loading="lazy"><?php else: ?><img src="<?php echo wc_placeholder_img_src(); ?>"><?php endif; ?>
                </a>
            </div>
            <div class="mp-content text-center">
                <a href="<?php the_permalink(); ?>" class="mp-title-link"><h3 class="mp-title" style="text-align:left; -webkit-line-clamp: 1; margin-bottom: 2px !important;"><?php echo $title; ?></h3></a>
                
                <div class="mp-price big-price" style="text-align:left; margin-bottom: 8px; font-size:16px;"><?php echo $product->get_price_html(); ?></div>
                
                <div class="style-2-action-row">
                    <?php if ( $product->is_in_stock() && $product->get_type() == 'simple' ) : ?>
                        <div class="qty-selector-compact">
                            <button type="button" class="qty-btn minus">-</button>
                            <input type="number" class="qty-input" value="1" min="1" max="<?php echo ($product->get_stock_quantity()) ? $product->get_stock_quantity() : ''; ?>">
                            <button type="button" class="qty-btn plus">+</button>
                        </div>
                        
                        <a href="?add-to-cart=<?php echo $product->get_id(); ?>" 
                           data-quantity="1" 
                           data-product_id="<?php echo $product->get_id(); ?>" 
                           class="mp-add-btn compact-add-btn ajax_add_to_cart add_to_cart_button"
                           aria-label="<?php echo esc_attr($txt_add_cart); ?>">
                           <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6h15l-1.5 9h-13z"/><path d="M6 6H2"/><circle cx="9" cy="20" r="1"/><circle cx="18" cy="20" r="1"/></svg>
                        </a>
                    <?php else: ?>
                        <a href="<?php the_permalink(); ?>" class="mp-add-btn full-btn" style="background:#eee; color:#999; border:none; width:100%;"><?php echo esc_html($txt_out_stock); ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ( 'style-3' === $card_style ) : ?>
            <div class="mp-img-box portrait-img">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $sale_badge; ?>
                    <?php if($image_url): ?><img src="<?php echo esc_url($image_url); ?>" loading="lazy"><?php else: ?><img src="<?php echo wc_placeholder_img_src(); ?>"><?php endif; ?>
                </a>
            </div>
            <div class="mp-content">
                <div class="fashion-meta">
                    <div class="fashion-cat"><?php echo wc_get_product_category_list( $product->get_id(), ', ', '', '' ); ?></div>
                    <a href="<?php the_permalink(); ?>" class="mp-title-link"><h3 class="mp-title"><?php echo $title; ?></h3></a>
                    <div class="mp-price"><?php echo $product->get_price_html(); ?></div>
                </div>
            </div>

        <?php else : ?>
            <div class="mp-img-box radius-img">
                <a href="<?php the_permalink(); ?>">
                    <?php if($image_url): ?><img src="<?php echo esc_url($image_url); ?>" loading="lazy"><?php else: ?><img src="<?php echo wc_placeholder_img_src(); ?>"><?php endif; ?>
                </a>
                <div class="insta-wishlist"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></div>
            </div>
            <div class="mp-content">
                <div class="mp-footer-row">
                    <a href="<?php the_permalink(); ?>" class="mp-title-link" style="flex:1;"><h3 class="mp-title"><?php echo $title; ?></h3></a>
                    <div class="mp-price"><?php echo $product->get_price_html(); ?></div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</li>