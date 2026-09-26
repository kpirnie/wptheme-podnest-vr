<?php
/**
 * Header template
 * 
 * @package PodNest Visual Regressor
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <link rel="icon" type="image/svg+xml" href="<?php echo esc_url( pnvr_logo_url( 'svg' ) ); ?>">
    <link rel="alternate icon" type="image/svg+xml" href="<?php echo esc_url( pnvr_logo_url( 'svg' ) ); ?>">
    <link rel="apple-touch-icon" href="<?php echo esc_url( pnvr_logo_url( 'png' ) ); ?>">
    <link rel="icon" href="<?php echo esc_url( pnvr_logo_url( 'ico' ) ); ?>" sizes="any">
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'pn-vr' ); ?></a>

    <header id="masthead" class="site-header navbar navbar-sticky">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand" rel="home" aria-label="<?php esc_attr_e( 'PodNest Visual Regressor - Home', 'pn-vr' ); ?>">
            <?php get_template_part( 'template-parts/brand/wordmark', null, [ 'tag' => 'h1' ] ); ?>
        </a>

        <?php get_template_part( 'template-parts/navigation/primary' ); ?>

    </header>
