<?php
/**
 * GUSTABE ORDER HISTORY (Final V5: Translatable)
 */

defined( 'ABSPATH' ) || exit;

$has_orders = 0 < $customer_orders->total;
do_action( 'woocommerce_before_account_orders', $has_orders );

if ( $has_orders ) : ?>

    <div class="gustabe-order-list">
        <?php foreach ( $customer_orders->orders as $customer_order ) :
            $order      = wc_get_order( $customer_order );
            $item_count = $order->get_item_count() - $order->get_item_count_refunded();
            $status     = $order->get_status();
            $order_id   = $order->get_id();
            
            // Logic Timeline
            $current_step = 1;
            if ( in_array($status, ['processing']) ) { $current_step = 2; }
            elseif ( in_array($status, ['wc-shipped', 'shipped', 'shipping']) ) { $current_step = 3; } 
            elseif ( in_array($status, ['completed']) ) { $current_step = 4; }
            elseif ( in_array($status, ['cancelled', 'refunded', 'failed']) ) { $current_step = 0; }
            ?>

            <div class="gustabe-order-card">
                
                <div class="order-card-header">
                    <div class="order-info">
                        <span class="order-id">#<?php echo $order->get_order_number(); ?></span>
                        <span class="order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
                    </div>
                    <div class="order-status-badge status-<?php echo esc_attr( $status ); ?>">
                        <?php 
						// ดึงชื่อสถานะมา แล้วสั่งแปลด้วย Text Domain 'gustabe'
						echo esc_html( __( wc_get_order_status_name( $status ), 'gustabe' ) ); 
						?>
                    </div>
                </div>

                <?php if ($current_step > 0) : ?>
                <div class="order-timeline">
                    <div class="timeline-step <?php echo ($current_step >= 1) ? 'active' : ''; ?>">
                        <div class="step-circle"><i class="huge huge-clipboard"></i></div>
                        <span class="step-label"><?php esc_html_e( 'รับออเดอร์', 'gustabe' ); ?></span>
                    </div>
                    <div class="step-line <?php echo ($current_step >= 2) ? 'active' : ''; ?>"></div>
                    
                    <div class="timeline-step <?php echo ($current_step >= 2) ? 'active' : ''; ?>">
                        <div class="step-circle"><i class="huge huge-package"></i></div>
                        <span class="step-label"><?php esc_html_e( 'เตรียมของ', 'gustabe' ); ?></span>
                    </div>
                    <div class="step-line <?php echo ($current_step >= 3) ? 'active' : ''; ?>"></div>

                    <div class="timeline-step <?php echo ($current_step >= 3) ? 'active' : ''; ?>">
                        <div class="step-circle"><i class="huge huge-delivery-truck-02"></i></div>
                        <span class="step-label"><?php esc_html_e( 'ขนส่ง', 'gustabe' ); ?></span>
                    </div>
                    <div class="step-line <?php echo ($current_step >= 4) ? 'active' : ''; ?>"></div>

                    <div class="timeline-step <?php echo ($current_step >= 4) ? 'active' : ''; ?>">
                        <div class="step-circle"><i class="huge huge-checkmark-circle-02"></i></div>
                        <span class="step-label"><?php esc_html_e( 'สำเร็จ', 'gustabe' ); ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <div class="order-products-preview trigger-popup-ajax" data-order-id="<?php echo $order_id; ?>" data-nonce="<?php echo wp_create_nonce('view-order-'.$order_id); ?>">
                    <?php 
                    $order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
                    $count = 0;
                    foreach ( $order_items as $item_id => $item ) {
                        if($count >= 4) break; 
                        $product = $item->get_product();
                        if ( $product ) {
                            echo '<div class="preview-thumb">' . $product->get_image(array(50, 50)) . '</div>';
                        }
                        $count++;
                    }
                    if ( $item_count > 4 ) { echo '<div class="preview-more">+' . ($item_count - 4) . '</div>'; }
                    ?>
                    <div class="click-hint"><i class="huge huge-view"></i> <span><?php esc_html_e( 'ดูรายการ', 'gustabe' ); ?></span></div>
                </div>

                <div class="order-card-footer">
                    <div class="total-wrapper">
                        <span><?php esc_html_e( 'ยอดรวม:', 'gustabe' ); ?></span>
                        <strong class="total-price"><?php echo $order->get_formatted_order_total(); ?></strong>
                    </div>
                    <div class="action-buttons">
                        <?php
                        $actions = wc_get_account_orders_actions( $order );
                        foreach ( $actions as $key => $action ) {
                            echo '<a href="' . esc_url( $action['url'] ) . '" class="gustabe-btn ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                        }
                        if ( in_array( $status, ['wc-shipped', 'shipped', 'shipping'] ) ) {
                            ?>
                            <button type="button" class="gustabe-btn btn-confirm-receipt" 
                                    data-order-id="<?php echo $order_id; ?>" 
                                    data-nonce="<?php echo wp_create_nonce( 'confirm-receipt-' . $order_id ); ?>">
                                <i class="huge huge-checkmark-circle-02"></i> <?php esc_html_e( 'ได้รับของแล้ว', 'gustabe' ); ?>
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
        <div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
            <?php if ( 1 !== $current_page ) : ?>
                <a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'Previous', 'woocommerce' ); ?></a>
            <?php endif; ?>
            <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                <a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'Next', 'woocommerce' ); ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>
    <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
        <a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
            <?php esc_html_e( 'Browse products', 'woocommerce' ); ?>
        </a>
        <?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?>
    </div>
<?php endif; ?>