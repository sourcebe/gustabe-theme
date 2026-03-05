<?php
/**
 * Order Details (App Style + Product Images)
 */
defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id );
if ( ! $order ) return;

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );

// Check Variable
if ( ! isset( $show_customer_details ) ) {
    $show_customer_details = true;
}
?>

<div class="gustabe-card order-details-card">
    <div class="card-header">
        <div class="icon-wrap"><i class="huge huge-shopping-bag-02"></i></div>
        <h3>รายละเอียดคำสั่งซื้อ</h3>
    </div>

    <div class="card-body">
        <div class="app-order-items">
            <?php
            foreach ( $order_items as $item_id => $item ) {
                $product = $item->get_product();
                
                // ★★★ เพิ่มส่วนดึงรูปภาพ (Thumbnail) ★★★
                $thumbnail = '';
                if ( $product ) {
                    $thumbnail = $product->get_image( array( 60, 60 ) ); // รูปขนาด 60x60px
                }
                ?>
                <div class="app-order-item">
                    
                    <div class="item-image">
                        <?php echo $thumbnail; ?>
                    </div>

                    <div class="item-content-wrapper">
                        <div class="item-info">
                            <div class="item-name">
                                <?php echo apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ); ?>
                            </div>
                            <div class="item-meta">
                                <span class="qty">x <?php echo $item->get_quantity(); ?></span>
                                <?php do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false ); ?>
                                <?php wc_display_item_meta( $item ); ?>
                                <?php do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false ); ?>
                            </div>
                        </div>
                        <div class="item-total">
                            <?php echo $order->get_formatted_line_subtotal( $item ); ?>
                        </div>
                    </div>

                </div>
                <?php
            }
            ?>
        </div>

        <div class="review-divider"></div>

        <div class="app-order-totals">
            <?php foreach ( $order->get_order_item_totals() as $key => $total ) : ?>
                <div class="total-row <?php echo esc_attr( $key ); ?>">
                    <span class="label"><?php echo esc_html( $total['label'] ); ?></span>
                    <span class="value"><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ( $show_customer_details ) : ?>
    <div class="gustabe-card customer-details-card">
        <div class="card-header">
            <div class="icon-wrap"><i class="huge huge-user-circle"></i></div>
            <h3>ข้อมูลลูกค้า</h3>
        </div>
        <div class="card-body">
             <?php wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) ); ?>
        </div>
    </div>
<?php endif; ?>