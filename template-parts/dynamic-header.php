<?php
/**
 * theme name hello-elementor-child
 * dir template-parts\
 * file dynamic-header.php
 * The template for displaying header.
 * Update: Added Polylang Logic & Safe Currency Switcher Integration
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );
$announcement_text = get_theme_mod( 'announcement_text', 'จัดส่งฟรีทั่วประเทศ!' );

$desktop_menu_args = [
    'theme_location' => 'menu-1',
    'fallback_cb' => false,
    'container' => false,
    'echo' => false,
    'menu_class' => 'navbar-nav d-flex flex-row',
];

$mobile_menu_args = [
    'theme_location' => 'menu-1',
    'fallback_cb' => false,
    'container' => false,
    'echo' => false,
    'menu_class' => 'navbar-nav d-flex flex-column',
];

$header_nav_menu = wp_nav_menu( $desktop_menu_args );
$header_mobile_nav_menu = wp_nav_menu( $mobile_menu_args );

$mobile_logo_id = get_theme_mod( 'hello_elementor_child_mobile_logo' );
$mobile_logo_url = '';
if ( $mobile_logo_id ) {
    $mobile_logo_url = wp_get_attachment_image_url( $mobile_logo_id, 'full' );
}
?>

<header id="site-header" class="gustabe-header-full-width">
    <div class="header-wrapper">
        <div class="header-top-bar">
            <div class="container-fluid py-2 px-4 d-flex justify-content-center align-items-center">
                <span class="announcement-text">
                    <?php
                        if ( function_exists( 'pll__' ) ) {
                            echo esc_html( pll__( $announcement_text ) );
                        } else {
                            echo esc_html( $announcement_text );
                        }
                    ?>
                </span>
            </div>
        </div>

        <div class="header-main-nav">
            <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-4">
                <div class="site-branding d-flex align-items-center">
                    <?php
                    if ( has_custom_logo() ) {
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        $desktop_logo = wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo desktop-logo' ) );
                        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home" aria-current="page">' . $desktop_logo . '</a>';
                    } elseif ( $site_name ) {
                        ?>
                        <div class="site-title desktop-logo"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
                                <?php echo esc_html( $site_name ); ?>
                            </a>
                        </div>
                        <?php
                    }

                    if ( ! empty( $mobile_logo_url ) ) {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link-mobile mobile-logo" rel="home">
                            <img src="<?php echo esc_url( $mobile_logo_url ); ?>" class="custom-logo-mobile" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                        </a>
                        <?php
                    } elseif ( get_bloginfo( 'name' ) ) {
                        ?>
                        <div class="site-title mobile-logo"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
                                <?php echo esc_html( $site_name ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php if ( $header_nav_menu ) : ?>
                    <nav id="site-navigation" class="main-navigation d-none d-lg-block d-flex justify-content-end" aria-label="<?php echo esc_attr__( 'Main menu', 'hello-elementor' ); ?>">
                        <?php echo $header_nav_menu; ?>
                    </nav>
                <?php endif; ?>

                <div class="header-actions d-flex align-items-center">
                    <?php if ( shortcode_exists( 'gustab_currency_switcher' ) ) : ?>
                        <div class="currency-switcher-desktop me-3 d-none d-lg-block">
                            <?php echo do_shortcode( '[gustab_currency_switcher]' ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( function_exists( 'pll_the_languages' ) ) : ?>
                        <div class="language-switcher-desktop me-3 d-none d-lg-block">
                            <ul class="d-flex list-unstyled m-0 gap-2 align-items-center" style="font-size: 14px; font-weight: 500;">
                                <?php 
                                pll_the_languages( array( 
                                    'show_flags' => 0, 
                                    'show_names' => 1,
                                    'display_names_as' => 'slug',
                                    'hide_current' => 0
                                ) ); 
                                ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="search-toggle me-3">
                        <a href="#" class="search-icon-link" data-bs-toggle="modal" data-bs-target="#search-popup-modal">
                            <i class="huge huge-search-02"></i>
                        </a>
                    </div>

                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents position-relative me-3">
                        <i class="huge huge-shopping-cart-01"></i>
                        <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </span>
                    </a>

                    <?php if ( is_user_logged_in() ) :
                        $current_user = wp_get_current_user();
                    ?>
                        <div class="dropdown">
                            <a href="#" class="my-account-link-logged-in-split-text me-3 d-flex align-items-center" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-wrapper me-2">
                                    <?php echo get_avatar( $current_user->ID, 40 ); ?>
                                </span>
                                <span class="d-flex flex-column d-none d-lg-flex">
                                    <span class="split-text-greeting"><?php echo esc_html( function_exists('pll__') ? pll__('Hello') : 'Hello' ); ?></span>
                                    <span class="split-text-username"><?php echo esc_html( $current_user->display_name ); ?></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>">
                                        <?php echo esc_html( function_exists('pll__') ? pll__('My Account') : 'My Account' ); ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>">
                                        <?php echo esc_html( function_exists('pll__') ? pll__('Orders') : 'Orders' ); ?>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">
                                        <?php echo esc_html( function_exists('pll__') ? pll__('Logout') : 'Logout' ); ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( get_smart_auth_url() ); ?>" class="my-account-link-split-text me-3 d-flex align-items-center">
                            <i class="huge huge-user-circle me-2"></i>
                            <span class="d-flex flex-column d-none d-lg-flex">
                                <span class="split-text-greeting" style="font-size: 0.7em;">
                                    <?php echo esc_html( function_exists('pll__') ? pll__('Welcome') : 'Welcome' ); ?>
                                </span>
                                <span class="split-text-username" style="font-size: 0.5em; font-weight: bold;">
                                    <?php echo esc_html( function_exists('pll__') ? pll__('Login / Register') : 'Login / Register' ); ?>
                                </span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <button class="navbar-toggler d-lg-none ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-expanded="false" aria-label="<?php echo esc_attr__( 'Menu', 'hello-elementor' ); ?>">
                        <i class="huge huge-menu-01" style="font-size: 28px; color: #333;"></i> 
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="search-popup-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; overflow:hidden; border:none;">
            
            <div class="modal-header border-0 p-3 bg-white d-flex align-items-center" style="box-shadow: 0 2px 10px rgba(0,0,0,0.05); z-index:10;">
                <div class="flex-grow-1 position-relative">
                    <i class="huge huge-search-02 position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                    <input type="text" id="gustabe-search-input" class="form-control border-0 bg-light" 
                           placeholder="<?php echo esc_attr( function_exists('pll__') ? pll__('Search...') : 'Search...' ); ?>" 
                           style="padding-left: 45px; height: 50px; border-radius: 12px; font-size: 16px;" autocomplete="off">
                    <div id="gustabe-search-spinner" class="spinner-border spinner-border-sm text-success position-absolute" style="right: 15px; top: 17px; display:none;" role="status"></div>
                </div>
                <button type="button" class="btn-close ms-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="px-3 pt-2 pb-0 bg-white">
                <div class="search-filter-buttons d-flex gap-2">
                    <input type="radio" class="btn-check" name="search_type_selector" id="st_product" value="product" checked>
                    <label class="btn btn-sm btn-outline-success rounded-pill px-3" for="st_product">
                        <?php echo esc_html( function_exists('pll__') ? pll__('Product') : 'Product' ); ?>
                    </label>

                    <input type="radio" class="btn-check" name="search_type_selector" id="st_post" value="post">
                    <label class="btn btn-sm btn-outline-success rounded-pill px-3" for="st_post">
                        <?php echo esc_html( function_exists('pll__') ? pll__('Article') : 'Article' ); ?>
                    </label>

                    <input type="radio" class="btn-check" name="search_type_selector" id="st_all" value="all">
                    <label class="btn btn-sm btn-outline-success rounded-pill px-3" for="st_all">
                        <?php echo esc_html( function_exists('pll__') ? pll__('Everything') : 'Everything' ); ?>
                    </label>
                </div>
            </div>

            <div class="modal-body p-0 bg-white" style="min-height: 100px; max-height: 60vh; overflow-y: auto;">
                
                <div id="gustabe-search-results"></div>

                <div class="p-4" id="search-default-state">
                    <p class="text-muted small fw-bold mb-3">
                        <?php echo esc_html( function_exists('pll__') ? pll__('แนะนำสำหรับคุณ') : 'Recommended' ); ?>
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo esc_url(home_url('/shop/?orderby=popularity')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill border-0 bg-light text-dark">
                            <?php echo esc_html( function_exists('pll__') ? pll__('🔥 สินค้าขายดี') : 'Best Seller' ); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/shop/?filter_on_sale=1')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill border-0 bg-light text-dark">
                            <?php echo esc_html( function_exists('pll__') ? pll__('💰 ลดราคา') : 'Promotion' ); ?>
                        </a>
                        <a href="<?php echo esc_url(home_url('/shop/?orderby=date')); ?>" class="btn btn-sm btn-outline-secondary rounded-pill border-0 bg-light text-dark">
                            <?php echo esc_html( function_exists('pll__') ? pll__('🆕 มาใหม่') : 'New Arrival' ); ?>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if ( $header_mobile_nav_menu ) : ?>
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel"><?php echo esc_html__( 'Menu', 'hello-elementor' ); ?></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo esc_attr__( 'Close', 'hello-elementor' ); ?>"></button>
        </div>
        <div class="offcanvas-body">

            <?php if ( shortcode_exists( 'gustab_currency_switcher' ) ) : ?>
                <div class="currency-switcher-mobile mt-4 border-top pt-3">
                    <span class="text-muted small mb-2 d-block">
                        <?php echo esc_html( function_exists('pll__') ? pll__('สกุลเงิน / Currency') : 'Currency' ); ?>
                    </span>
                    <div class="d-flex list-unstyled gap-3 m-0">
                        <?php echo do_shortcode( '[gustab_currency_switcher]' ); ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ( function_exists( 'pll_the_languages' ) ) : ?>
                <div class="language-switcher-mobile mt-4 border-top pt-3">
                    <span class="text-muted small mb-2 d-block">ภาษา / Language</span>
                    <ul class="d-flex list-unstyled gap-3 m-0">
                        <?php 
                        pll_the_languages( array( 
                            'show_flags' => 1, 
                            'show_names' => 1,
                        ) ); 
                        ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php
            echo $header_mobile_nav_menu; 
            ?>
        </div>
    </div>
<?php endif; ?>