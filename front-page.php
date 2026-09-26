<?php
/**
 * Front page template
 * 
 * @package PodNest Visual Regressor
 */

get_header(); 
?>

<main id="main" class="site-main">

    <?php get_template_part( 'template-parts/sections/hero' ); ?>
    <?php get_template_part( 'template-parts/sections/marquee' ); ?>
    <?php get_template_part( 'template-parts/sections/features' ); ?>
    <?php get_template_part( 'template-parts/sections/how-it-works' ); ?>
    <?php get_template_part( 'template-parts/sections/ai-judge' ); ?>
    <?php get_template_part( 'template-parts/sections/security' ); ?>
    <?php get_template_part( 'template-parts/sections/pricing' ); ?>
    <?php get_template_part( 'template-parts/sections/cta' ); ?>

</main>

<?php
get_footer();
