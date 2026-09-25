<?php
/**
 * Default page template
 * 
 * @package PodNest Visual Regressor
 */

get_header(); 
?>

<main id="main" class="site-main">

    <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <?php get_template_part( 'template-parts/page/hero', null, [ 'intro' => has_excerpt( ) ? get_the_excerpt( ) : '' ] ); ?>

            <div class="container container-2xl">
                <div class="pnvr-content entry-content">
                    <?php the_content(); ?>
                </div>
            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
