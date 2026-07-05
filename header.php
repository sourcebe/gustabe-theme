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
$skip_link_url    = apply_filters( 'gustabe_skip_link_url', '#content' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="<?php echo esc_attr( $viewport_content ); ?>">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'antialiased bg-black text-slate-300 selection:bg-blue-500 selection:text-white' ); ?>> <?php wp_body_open(); ?>

<?php if ( $enable_skip_link ) { ?>
<a class="skip-link screen-reader-text sr-only" href="<?php echo esc_url( $skip_link_url ); ?>"><?php echo esc_html__( 'Skip to content', 'gustabe' ); ?></a>
<?php } ?>

<?php
// -----------------------------------------------------------------------
// ⚡ โหลด Custom Header ของเรา (Tailwind V4) โดยตรง
// -----------------------------------------------------------------------
get_template_part( 'template-parts/header/gustabe-header' );