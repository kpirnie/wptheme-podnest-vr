<?php

/**
 * Default page template
 * 
 * The page hero, then the block content full width. Section blocks span the page, everything else sits in the reading column
 * 
 * @package PodNest Visual Regressor
 */

get_header();
?>

<main id="main" class="site-main">

    <?php while (have_posts()) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php get_template_part('template-parts/page/hero', null, ['intro' => has_excerpt() ? get_the_excerpt() : '']); ?>

            <div class="pnvr-page-content entry-content">
                <?php the_content(); ?>
            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
