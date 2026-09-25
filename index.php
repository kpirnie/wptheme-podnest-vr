<?php
/**
 * Main template file
 * 
 * @package PodNest Visual Regressor
 */

get_header(); 
?>

<main id="main" class="site-main">
    <?php //pnvr_container(); ?>
    
    <?php if ( have_posts() ) : ?>
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            
        <?php endwhile; ?>
        
        <?php the_posts_navigation(); ?>
        
    <?php else : ?>
        
        <p><?php esc_html_e( 'No content found.', 'pn-vr' ); ?></p>
        
    <?php endif; ?>
    
    <?php //pnvr_container_end(); ?>
</main>

<?php
get_footer();