<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package usual
 */

get_header();
?>

    <main>

        <section class="error-404 not-found">
            <div class="container">
                <div class="error-404-in">
                    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'usual' ); ?></h1>

                    <p><?php esc_html_e( 'We\'ve searched everywhere, but found nothing. Let\'s try again.', 'usual' ); ?></p>

                    <a href="<?= home_url() ?>" class="btn"><?php esc_html_e( 'Return to homepage', 'usual' ); ?></a>
                </div>
            </div>
        </section><!-- .error-404 -->

    </main><!-- #main -->

<?php
get_footer();
