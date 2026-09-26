<?php

/**
 * Front page template
 * 
 * The hero from the Customizer, then the front page's own block content
 * 
 * @package PodNest Visual Regressor
 */

get_header();
?>

<main id="main" class="site-main">

    <?php get_template_part('template-parts/sections/hero'); ?>

    <?php while (have_posts()) : the_post(); ?>
        <?php the_content(); ?>
    <?php endwhile; ?>

</main>

<?php
get_footer();
