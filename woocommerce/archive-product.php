<?php
/**
 * The Template for displaying product archives
 */
defined( 'ABSPATH' ) || exit;

// 1. เรียก Header (ตรงนี้ธีมจะเปิดแท็ก <main> มาให้)
get_header( 'shop' );

// --- 2. เตรียม Logic รูปภาพ ---
$cover_image_url = '';

// A. ถ้าเป็นหน้าหมวดหมู่ ให้เอารูปหมวดหมู่
if ( is_product_category() ) {
    $current_term = get_queried_object();
    $thumbnail_id = get_term_meta( $current_term->term_id, 'thumbnail_id', true );
    if ( $thumbnail_id ) {
        $cover_image_url = wp_get_attachment_image_url( $thumbnail_id, 'full' );
    }
}

// B. ถ้ายังไม่มีรูป ให้ใช้รูป Default
if ( empty( $cover_image_url ) ) {
    $default_id = get_theme_mod( 'shop_cover_default_image' );
    if ( $default_id ) {
        $cover_image_url = wp_get_attachment_image_url( $default_id, 'full' );
    }
}
?>

<?php 
/**
 * [TEHCNIC] ปิดแท็ก main ชั่วคราว เพื่อให้พื้นที่ตรงนี้หลุดจากกรอบ Theme
 * (ทำให้รูปเต็มจอได้โดยไม่ต้องใช้ CSS ฝืน)
 */
?>
</main>


<?php if ( $cover_image_url ) : ?>
    <div class="shop-cover-wrapper">
        <img src="<?php echo esc_url( $cover_image_url ); ?>" alt="Shop Cover" class="shop-cover-img">
        <div class="shop-cover-content">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="shop-cover-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
            
            <?php if ( category_description() ) : ?>
                <div class="shop-cover-desc"><?php echo category_description(); ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>


<?php 
/**
 * [TEHCNIC] เปิดแท็ก main กลับมาใหม่
 * เพื่อให้เนื้อหาสินค้าด้านล่างยังอยู่ในกรอบสวยงามเหมือนเดิม
 */
?>
<main id="main" class="site-main" role="main">

<?php 
// Hook ของ Woo (เช่น Breadcrumb) จะมาโผล่ตรงนี้ (ใต้รูปปก)
do_action( 'woocommerce_before_main_content' ); 
?>

<header class="woocommerce-products-header app-shop-header">
    
    <?php if ( ! $cover_image_url ) : ?>
        <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            <h1 class="woocommerce-products-header__title page-title" style="text-align:center; margin: 20px 0;"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>
        <?php do_action( 'woocommerce_archive_description' ); ?>
    <?php endif; ?>

    <?php
    $terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0 ) );
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) :
        $current_term_id = get_queried_object_id();
    ?>
    <div class="app-category-scroller">
        <div class="cat-scroll-track">
            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="cat-card <?php echo ( is_shop() ) ? 'active' : ''; ?>">
               <div class="cat-img-box all-items">
                   <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
               </div>
               <span class="cat-name"><?php if(function_exists('pll_e')) pll_e( 'ทั้งหมด' ); else echo 'ทั้งหมด'; ?></span>
            </a>
            <?php foreach ( $terms as $term ) : 
                $is_active = ( $current_term_id === $term->term_id ) ? 'active' : '';
                $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                $image_url = wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' );
            ?>
                <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="cat-card <?php echo $is_active; ?>">
                    <div class="cat-img-box">
                        <?php if ( $image_url ) : ?>
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy">
                        <?php else : ?>
                            <svg class="placeholder-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ccc" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <?php endif; ?>
                    </div>
                    <span class="cat-name"><?php echo esc_html( $term->name ); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="app-action-bar">
        <div class="action-left">
            <span class="result-count-text">
                <?php global $wp_query; echo $wp_query->found_posts . ' '; if(function_exists('pll_e')) pll_e( 'รายการ' ); else echo 'รายการ'; ?>
            </span>
        </div>
        <div class="action-right">
            <button id="mobile-filter-trigger" class="app-action-btn filter-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" y1="21" x2="4" y2="14"></line><line x1="4" y1="10" x2="4" y2="3"></line><line x1="12" y1="21" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="3"></line><line x1="20" y1="21" x2="20" y2="16"></line><line x1="20" y1="12" x2="20" y2="3"></line><line x1="1" y1="14" x2="7" y2="14"></line><line x1="9" y1="8" x2="15" y2="8"></line><line x1="17" y1="16" x2="23" y2="16"></line></svg>
                <?php if(function_exists('pll_e')) pll_e( 'ตัวกรอง' ); else echo 'ตัวกรอง'; ?>
            </button>
            <button id="mobile-sort-trigger" class="app-action-btn sort-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 15l5 5 5-5"></path><path d="M7 9l5-5 5 5"></path></svg>
                <?php if(function_exists('pll_e')) pll_e( 'เรียงลำดับ' ); else echo 'เรียงลำดับ'; ?>
            </button>
        </div>
    </div>

</header>

<?php
if ( woocommerce_product_loop() ) {
    woocommerce_product_loop_start();
    if ( wc_get_loop_prop( 'total' ) ) {
        while ( have_posts() ) {
            the_post();
            do_action( 'woocommerce_shop_loop' );
            wc_get_template_part( 'content', 'product' );
        }
    }
    woocommerce_product_loop_end();
    do_action( 'woocommerce_after_shop_loop' );
} else {
    do_action( 'woocommerce_no_products_found' );
}
do_action( 'woocommerce_after_main_content' );
get_footer( 'shop' );