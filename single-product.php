<?php get_header(); ?>
<main id="primary" class="site-main">
    <?php
    while ( have_posts() ) : the_post();
        the_content(); // Elementor content render karega
    endwhile;
    ?>
</main>
<?php get_footer(); ?>
