<?php
/**
 * The template for displaying search results pages.
 * Feature: Advanced Split Results (Products vs Articles Tabs) + Custom Ajax Trigger
 * Author: Gustabe Custom
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header();

// 1. เตรียมคำศัพท์ (Translation Ready)
$txt_results_for = function_exists('pll__') ? pll__('ผลการค้นหาสำหรับ') : 'Search Results for';
$txt_found       = function_exists('pll__') ? pll__('พบทั้งหมด') : 'Found';
$txt_items       = function_exists('pll__') ? pll__('รายการ') : 'items';
$txt_tab_prod    = function_exists('pll__') ? pll__('สินค้า') : 'Products';
$txt_tab_post    = function_exists('pll__') ? pll__('บทความ') : 'Articles';
$txt_no_result   = function_exists('pll__') ? pll__('ไม่พบข้อมูลที่ค้นหา') : 'No results found';
$txt_try_again   = function_exists('pll__') ? pll__('ลองค้นหาคำอื่นดูไหม?') : 'Try different keywords';
$txt_btn_search  = function_exists('pll__') ? pll__('ค้นหาอีกครั้ง') : 'Search Again';
$txt_recommend   = function_exists('pll__') ? pll__('สินค้าแนะนำ') : 'Recommended for you';

// 2. แยกข้อมูล (Bucketing Logic)
$product_results = array();
$post_results    = array();

// วนลูป Main Query รอบแรก เพื่อจัดกลุ่ม
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        if ( get_post_type() == 'product' ) {
            $product_results[] = $post;
        } else {
            $post_results[] = $post;
        }
    }
}

$count_prod  = count( $product_results );
$count_post  = count( $post_results );
$total_count = $count_prod + $count_post;
?>

<style>
    /* =========================================
       1. แก้บั๊กเมาส์กระพริบ / จอกระตุก (สำคัญมาก)
       ========================================= */
    
    /* ลบเส้น Focus เวลาคลิกที่ Header หรือ Tab */
    .search-header,
    .search-header *,
    .nav-tabs .nav-link,
    .nav-tabs .nav-link:focus,
    .nav-tabs .nav-link:active {
        outline: none !important;
        box-shadow: none !important;
        -webkit-tap-highlight-color: transparent !important; /* แก้ในมือถือ */
    }

    /* ป้องกัน Scrollbar เด้ง (บังคับให้มี Scrollbar ตลอดเวลา หน้าจะได้ไม่ขยับซ้ายขวา) */
    html {
        overflow-y: scroll;
    }

    /* ล็อคความสูงของ Header ไม่ให้ยุบยืดจนเมาส์งง */
    .search-header {
        min-height: 80px; 
        overflow: hidden; 
        position: relative;
        z-index: 1;
    }

    /* =========================================
       2. ลบจุดดำ Bullet Points (ที่เคยโผล่มา)
       ========================================= */
    ul.products, 
    #tab-posts ul,
    .tab-content ul {
        list-style: none !important; /* ลบจุด */
        padding-left: 0 !important;  /* ลบย่อหน้าซ้าย */
        margin-left: 0 !important;
    }

    /* =========================================
       3. ดีไซน์ Tab และ Card
       ========================================= */
    .search-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #555;
        font-weight: 600;
        padding: 12px 20px;
        font-size: 16px;
        background: transparent !important; /* บังคับพื้นใส */
    }
    .search-tabs .nav-link.active {
        color: #04a39c !important; /* สีเขียวธีม */
        background: transparent !important;
        border-bottom-color: #04a39c;
    }
    .search-tabs .nav-link:hover {
        color: #04a39c;
    }
    .count-badge {
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 10px;
        background: #f1f1f1;
        color: #333;
        margin-left: 5px;
        vertical-align: middle;
    }
    .search-tabs .nav-link.active .count-badge {
        background: #04a39c;
        color: #fff;
    }
    
    /* Article Card Design */
    .search-article-card {
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        overflow: hidden;
        transition: 0.3s;
        height: 100%;
        position: relative !important; /* ★ เพิ่มตรงนี้: เพื่อคุมเขตลิงก์ stretched-link */
    }
    .search-article-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transform: translateY(-3px);
    }
    .search-article-img {
        height: 200px;
        width: 100%;
        object-fit: cover;
    }
    @media (min-width: 768px) {
        .search-article-img { height: 100%; min-height: 160px; }
    }
</style>

<main id="content" class="site-main" style="padding-top: 40px; padding-bottom: 80px; min-height: 80vh; background-color: #fff;">
    <div class="container-fluid px-4 px-lg-5" style="max-width: 1400px;">

        <div class="search-header mb-5 pb-3 border-bottom d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h1 class="h3 font-weight-bold mb-1">
                    <?php echo esc_html($txt_results_for); ?>: 
                    <span style="color: #04a39c;">"<?php echo get_search_query(); ?>"</span>
                </h1>
                <p class="text-muted mb-0" style="font-size: 14px;">
                    <?php echo esc_html($txt_found) . ' ' . $total_count . ' ' . esc_html($txt_items); ?>
                </p>
            </div>
            
            <div class="d-none d-md-block">
                <button type="button" class="btn btn-outline-dark rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#search-popup-modal">
                    <i class="huge huge-search-02 me-2"></i> <?php echo esc_html($txt_btn_search); ?>
                </button>
            </div>
        </div>

        <?php if ( $total_count > 0 ) : ?>

            <ul class="nav nav-tabs search-tabs mb-4" id="searchTabs" role="tablist">
                
                <?php if ( $count_prod > 0 ) : ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="products-tab" data-bs-toggle="tab" data-bs-target="#tab-products" type="button" role="tab" aria-selected="true">
                        <?php echo esc_html($txt_tab_prod); ?> 
                        <span class="count-badge"><?php echo $count_prod; ?></span>
                    </button>
                </li>
                <?php endif; ?>

                <?php if ( $count_post > 0 ) : ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($count_prod == 0) ? 'active' : ''; ?>" id="posts-tab" data-bs-toggle="tab" data-bs-target="#tab-posts" type="button" role="tab" aria-selected="false">
                        <?php echo esc_html($txt_tab_post); ?> 
                        <span class="count-badge"><?php echo $count_post; ?></span>
                    </button>
                </li>
                <?php endif; ?>

            </ul>

            <div class="tab-content" id="searchTabsContent">
                
                <?php if ( $count_prod > 0 ) : ?>
                <div class="tab-pane fade show active" id="tab-products" role="tabpanel" aria-labelledby="products-tab">
                    
                    <ul class="products columns-4">
                        <?php 
                        foreach ( $product_results as $post ) : 
                            setup_postdata( $post );
                            wc_get_template_part( 'content', 'product' );
                        endforeach; 
                        wp_reset_postdata();
                        ?>
                    </ul>

                </div>
                <?php endif; ?>

                <?php if ( $count_post > 0 ) : ?>
                <div class="tab-pane fade <?php echo ($count_prod == 0) ? 'show active' : ''; ?>" id="tab-posts" role="tabpanel" aria-labelledby="posts-tab">
                    
                    <div class="row g-4">
                        <?php 
                        foreach ( $post_results as $post ) : 
                            setup_postdata( $post ); 
                        ?>
                            <div class="col-12 col-lg-6">
                                <div class="search-article-card bg-white">
                                    <div class="row g-0 h-100">
                                        <div class="col-4 col-sm-4">
                                            
                                            <div class="d-block h-100">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <img src="<?php the_post_thumbnail_url('medium'); ?>" class="search-article-img" alt="<?php the_title(); ?>">
                                                <?php else: ?>
                                                    <div class="search-article-img bg-light d-flex align-items-center justify-content-center text-muted">
                                                        <i class="huge huge-image-01" style="font-size: 24px;"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                        </div>
                                        <div class="col-8 col-sm-8">
                                            <div class="p-3 d-flex flex-column justify-content-center h-100">
                                                <div class="text-muted small mb-1">
                                                    <i class="far fa-calendar-alt me-1"></i> <?php echo get_the_date(); ?>
                                                </div>
                                                <h5 class="card-title mb-2" style="font-size: 16px; line-height: 1.4;">
                                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark fw-bold stretched-link">
                                                        <?php the_title(); ?>
                                                    </a>
                                                </h5>
                                                <p class="card-text text-secondary small mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    <?php echo get_the_excerpt(); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        endforeach; 
                        wp_reset_postdata();
                        ?>
                    </div>

                </div>
                <?php endif; ?>

            </div>

            <div class="mt-5 pt-4 border-top">
                <?php
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '&larr; Previous', 'hello-elementor' ),
                    'next_text' => __( 'Next &rarr;', 'hello-elementor' ),
                ) );
                ?>
            </div>

        <?php else : ?>

            <div class="no-results-state text-center py-5">
                <div class="mb-4">
                    <div style="width: 80px; height: 80px; background: #f5f7f9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="huge huge-search-02" style="font-size: 32px; color: #ccc;"></i>
                    </div>
                </div>
                
                <h3 class="h4 font-weight-bold mb-3"><?php echo esc_html($txt_no_result); ?></h3>
                
                <p class="text-muted mb-4"><?php echo esc_html($txt_try_again); ?></p>
                
                <div class="mx-auto mb-5">
                    <button type="button" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#search-popup-modal" style="background-color: #04a39c; border-color: #04a39c;">
                        <i class="huge huge-search-02 me-2"></i> <?php echo esc_html($txt_btn_search); ?>
                    </button>
                </div>

                <div class="text-start mt-5">
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 4px; height: 24px; background: #04a39c; margin-right: 10px; border-radius: 2px;"></div>
                        <h4 class="m-0 font-weight-bold"><?php echo esc_html($txt_recommend); ?></h4>
                    </div>
                    <?php echo do_shortcode('[products limit="4" columns="4" orderby="popularity"]'); ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php
get_footer();