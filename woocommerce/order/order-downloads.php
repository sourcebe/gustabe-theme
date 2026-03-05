<?php
/**
 * Order Downloads (App Style Cards)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! $downloads ) return;
?>

<section class="gustabe-card downloads-section">
    <div class="card-header">
        <div class="icon-wrap"><i class="huge huge-download-04"></i></div>
        <h3><?php esc_html_e( 'Downloads', 'woocommerce' ); ?></h3>
    </div>

    <div class="card-body">
        <div class="downloads-grid">
            <?php foreach ( $downloads as $download ) : ?>
                <div class="download-item-card">
                    <div class="d-icon">
                        <i class="huge huge-file-02"></i>
                    </div>
                    <div class="d-info">
                        <div class="d-name">
                            <a href="<?php echo esc_url( $download['download_url'] ); ?>">
                                <?php echo esc_html( $download['product_name'] ); ?>
                            </a>
                        </div>
                        <div class="d-meta">
                            <span class="expiry">
                                <?php
                                if ( ! empty( $download['access_expires'] ) ) {
                                    echo '<i class="huge huge-time-02"></i> หมดอายุ: ' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) );
                                } else {
                                    echo '<i class="huge huge-infinite"></i> ตลอดชีพ';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    <div class="d-action">
                        <a href="<?php echo esc_url( $download['download_url'] ); ?>" class="download-btn">
                            <i class="huge huge-download-01"></i> ดาวน์โหลด
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>