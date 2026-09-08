<?php
/**
 * dir: /
 * file: header.php
 * The template for displaying the header
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$viewport_content = apply_filters( 'gustabe_viewport_content', 'width=device-width, initial-scale=1' );
$enable_skip_link = apply_filters( 'gustabe_enable_skip_link', true );
$skip_link_url    = apply_filters( 'gustabe_skip_link_url', '#main' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <!-- แก้ปัญหา Chaining Critical Requests & Element Render Delay ด้วยการ Preload Font -->
    <link rel="preload" href="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/fonts/RD CHULAJARUEK.ttf' ); ?>" as="font" type="font/ttf" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'antialiased bg-black text-slate-300 selection:bg-blue-500 selection:text-white' ); ?>> <?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<!-- เพิ่มคลาส focus:not-sr-only และจัด Styling ตอนถูก Focus ด้วยปุ่ม Tab -->
<a class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[10000] focus:px-4 focus:py-2 focus:bg-blue-600 focus:text-white focus:rounded-md focus:font-medium focus:shadow-lg focus:outline-none transition-all" href="<?php echo esc_url( $skip_link_url ); ?>">
    <?php echo esc_html__( 'Skip to content', 'gustabe' ); ?>
</a>
<?php } ?>

<?php
// -----------------------------------------------------------------------
// ⚡ โหลด Custom Header ของเรา (Tailwind V4) โดยตรง
// -----------------------------------------------------------------------
get_template_part( 'template-parts/header/gustabe-header' );
?>

<!-- เปิดแท็ก Main Landmark สำหรับ Accessibility (Screen Reader) -->
<main id="main" class="site-main">
