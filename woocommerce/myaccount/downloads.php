<?php
/**
 * GUSTABE DOWNLOADS (Style: Attachment Card + Empty State)
 */

defined( 'ABSPATH' ) || exit;

$downloads = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<?php if ( $has_downloads ) : ?>

    <div class="gustabe-download-list">
        <?php foreach ( $downloads as $download ) : 
            // ดึงข้อมูลสินค้าเพื่อเอารูปภาพ
            $product = wc_get_product( $download['product_id'] );
            $image   = $product ? $product->get_image( array( 80, 80 ) ) : ''; 
            $prod_name = $product ? $product->get_name() : '';
            ?>
            
            <div class="gustabe-download-card">
                <div class="dl-card-img">
                    <?php echo $image ? $image : '<div class="no-img"><i class="huge huge-image-02"></i></div>'; ?>
                </div>

                <div class="dl-card-info">
                    <h4 class="dl-name">
                        <?php echo esc_html( $download['download_name'] ); ?>
                    </h4>
                    <span class="dl-product-ref">
                        <i class="huge huge-package"></i> <?php echo esc_html( $prod_name ); ?>
                    </span>
                    
                    <div class="dl-meta-group">
                        <span class="dl-meta">
                            <?php
                            if ( is_numeric( $download['downloads_remaining'] ) ) {
                                echo '<span class="status-warning">' . sprintf( esc_html__( 'เหลือ %s ครั้ง', 'gustabe' ), $download['downloads_remaining'] ) . '</span>';
                            } else {
                                echo '<span class="status-success"><i class="huge huge-infinity"></i> ' . esc_html__( 'ตลอดชีพ', 'gustabe' ) . '</span>';
                            }
                            ?>
                        </span>

                        <?php if ( ! empty( $download['access_expires'] ) ) : ?>
                            <span class="dl-meta dl-expire">
                                <i class="huge huge-time-02"></i> 
                                <?php echo esc_html( date( 'd/m/Y', strtotime( $download['access_expires'] ) ) ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="dl-card-action">
                    <a href="<?php echo esc_url( $download['download_url'] ); ?>" class="gustabe-btn-download">
                        <i class="huge huge-download-04"></i> 
                        <span><?php esc_html_e( 'ดาวน์โหลด', 'gustabe' ); ?></span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>

<?php else : ?>

    <div class="gustabe-empty-state">
        <div class="empty-icon">
            <i class="huge huge-folder-02"></i>
        </div>
        <h3><?php esc_html_e( 'ยังไม่มีรายการดาวน์โหลด', 'gustabe' ); ?></h3>
        <p><?php esc_html_e( 'ไฟล์คู่มือ หรือเอกสารดิจิทัลจากการสั่งซื้อ จะปรากฏที่นี่', 'gustabe' ); ?></p>
        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="gustabe-btn primary">
            <?php esc_html_e( 'เลือกซื้อสินค้า', 'gustabe' ); ?>
        </a>
    </div>

<?php endif; ?>