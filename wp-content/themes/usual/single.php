<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package usual
 */

get_header();
?>

    <main class="body page-product">
        <div class="container">
            <?php
            while ( have_posts() ) :
                the_post();

                the_content();

            endwhile; // End of the loop.
            ?>
        </div>
    </main>

<?php
get_footer();
