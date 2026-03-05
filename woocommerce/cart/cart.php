<?php
/**
 * GUSTABE CART PAGE (App Style + Rewards + Polylang Ready)
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="gustabe-cart-wrapper">

    <div class="checkout-timeline">
        <div class="step active">
            <div class="icon"><i class="huge huge-shopping-cart-01"></i></div>
            <span><?php echo function_exists('pll__') ? pll__('ตะกร้า') : 'ตะกร้า'; ?></span>
        </div>
        <div class="line"></div>
        <div class="step">
            <div class="icon"><i class="huge huge-credit-card"></i></div>
            <span><?php echo function_exists('pll__') ? pll__('ชำระเงิน') : 'ชำระเงิน'; ?></span>
        </div>
        <div class="line"></div>
        <div class="step">
            <div class="icon"><i class="huge huge-checkmark-circle-02"></i></div>
            <span><?php echo function_exists('pll__') ? pll__('สำเร็จ') : 'สำเร็จ'; ?></span>
        </div>
    </div>

    <?php
    if ( 'yes' === get_option( 'gustabe_rewards_enabled' ) ) {
        // ดึงข้อมูล Tier จากหลังบ้าน
        $tiers = array();
        for ( $i = 1; $i <= 4; $i++ ) {
            $min = get_option( "gustabe_rewards_tier_{$i}_min" );
            $lbl = get_option( "gustabe_rewards_tier_{$i}_label" );
            
            // แปลภาษา Label รางวัล (เช่น "ส่งฟรี")
            if ( function_exists( 'pll__' ) && !empty($lbl) ) {
                $lbl = pll__( $lbl ); 
            }

            if ( ! empty( $min ) ) {
                $tiers[] = array( 'min' => (float)$min, 'label' => $lbl );
            }
        }

        if ( ! empty( $tiers ) ) {
            // เรียงลำดับ
            usort( $tiers, function($a, $b) { return $a['min'] - $b['min']; });

            $current_total = WC()->cart->get_subtotal();
            $max_goal = end($tiers)['min']; // เป้าสูงสุด
            
            // คำนวณ % ความกว้างของหลอด
            $progress_percent = ($max_goal > 0) ? ($current_total / $max_goal) * 100 : 0;
            $progress_percent = min( 100, max( 0, $progress_percent ) );
            ?>
            <div class="reward-progress-container">
                <div class="reward-bar-bg">
                    <div class="reward-bar-fill" style="width: <?php echo esc_attr( $progress_percent ); ?>%;"></div>
                    
                    <?php foreach ( $tiers as $tier ) : 
                        $pos = ($tier['min'] / $max_goal) * 100;
                        $is_passed = $current_total >= $tier['min'];
                    ?>
                        <div class="tier-point <?php echo $is_passed ? 'passed' : ''; ?>" style="left: <?php echo esc_attr( $pos ); ?>%;">
                            <div class="dot">
                                <?php if($is_passed): ?><i class="huge huge-checkmark"></i><?php endif; ?>
                            </div>
                            <div class="label"><?php echo esc_html( $tier['label'] ); ?></div>
                            <div class="amount"><?php echo wc_price( $tier['min'] ); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="reward-message">
                    <?php 
                    // หาเป้าหมายถัดไป
                    $next_tier = null;
                    foreach($tiers as $tier) {
                        if($current_total < $tier['min']) { $next_tier = $tier; break; }
                    }
                    
                    if ($next_tier) {
                        $diff = $next_tier['min'] - $current_total;
                        
                        // ใช้ printf เพื่อรองรับการแปลภาษาที่มีตัวแปรแทรก
                        // รูปแบบ: "ซื้ออีก %s จะได้รับ %s"
                        echo '<i class="huge huge-gift"></i> ';
                        printf( 
                            function_exists('pll__') ? pll__('ซื้ออีก %s จะได้รับ %s') : 'ซื้ออีก %s จะได้รับ %s',
                            '<strong>' . wc_price($diff) . '</strong>',
                            '<strong>' . esc_html($next_tier['label']) . '</strong>'
                        );
                    } else {
                        echo '<i class="huge huge-party-popper"></i> ';
                        echo function_exists('pll__') ? pll__('ยินดีด้วย! คุณได้รับรางวัลสูงสุดแล้ว') : 'ยินดีด้วย! คุณได้รับรางวัลสูงสุดแล้ว';
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
    ?>

    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
        
        <div class="gustabe-cart-list">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <?php
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                    ?>
                    
                    <div class="cart-item-card <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
                        
                        <div class="item-thumb">
                            <?php
                            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                            if ( ! $product_permalink ) {
                                echo $thumbnail;
                            } else {
                                printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
                            }
                            ?>
                        </div>

                        <div class="item-details">
                            <div class="item-header">
                                <h3 class="item-name">
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) );
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) );
                                    }
                                    ?>
                                </h3>
                                <?php
                                echo apply_filters( 
                                    'woocommerce_cart_item_remove_link', 
                                    sprintf(
                                        '<a href="%s" class="remove-item" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="huge huge-delete-02"></i></a>',
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                        esc_html__( 'Remove this item', 'woocommerce' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() )
                                    ),
                                    $cart_item_key
                                );
                                ?>
                            </div>

                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

                            <div class="item-price-unit">
                                <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
                            </div>

                            <div class="item-actions">
                                <div class="qty-stepper">
                                    <button type="button" class="qty-btn minus"><i class="huge huge-remove-01"></i></button>
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        $product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
                                    } else {
                                        $product_quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }
                                    echo $product_quantity;
                                    ?>
                                    <button type="button" class="qty-btn plus"><i class="huge huge-add-01"></i></button>
                                </div>
                                
                                <div class="item-subtotal">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            
            <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>" style="display:none;"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
            <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
        </div>
    </form>

    <div class="gustabe-cart-totals">
        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
    </div>

</div>

<?php do_action( 'woocommerce_after_cart' ); ?>