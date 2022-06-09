<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package usual
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="container">
		<h1><?php esc_html_e('Found results', 'usual'); ?>: </h1>
        <div class="woocommerce">
            <div class="products">
				<?php
				// check if there are posts in the global query - $wp_query variable
				if ( have_posts() ) {
					// go through all available posts and display them
					while ( have_posts() ) {
						the_post();
						wc_get_template( 'content-product.php' );
						?>
						<?php
					}
				}
				?>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
