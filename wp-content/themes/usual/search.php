<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package usual
 */

get_header();
?>

    <main>

        <div class="container">

			<?php
			// check if there are posts in the global query - $wp_query variable
			if ( have_posts() ) { ?>
                <h1><?php esc_html_e( 'Found results', 'usual' ); ?>: </h1>
                <div class="woocommerce">
                    <div class="products">
						<?php
						// go through all available posts and display them
						while ( have_posts() ) {
							the_post();
							wc_get_template( 'content-product.php' );
							?>
							<?php
						} ?>
                    </div>
                </div>
				<?php
			} else { ?>
                <h1><?php esc_html_e( 'No results found', 'usual' ); ?></h1>
				<?php }
			?>
        </div>

		<?php the_posts_navigation(); ?>

    </main><!-- #main -->

<?php
get_footer();
