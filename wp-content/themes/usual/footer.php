<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package usual
 */

?>

<footer class="footer">
    <div class="container">
        <div class="footer-in">
            <div class="footer-col">
                <div class="footer-menu">
                    <b class="footer-col-title"><?php esc_html_e( 'For buyers', 'usual' ); ?></b>
                    <div class="footer-menu-list">
						<?php wp_nav_menu( array(
							'theme_location' => 'footer-menu',
							'container'      => null
						) ); ?>
                    </div>
                </div>
            </div>
            <div class="footer-col">
                <div class="footer-menu">
                    <b class="footer-col-title"><?php esc_html_e( 'Payment methods', 'usual' ); ?></b>
                    <div class="footer-payments">
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/visa.svg" alt=""/>
                        </span>
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/mastercard.svg"
                                 alt=""/>
                        </span>
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/apple_pay.svg"
                                 alt=""/>
                        </span>
                        <span>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/google_pay.svg"
                                 alt=""/>
                        </span>
                    </div>
                </div>
            </div>
            <div class="footer-col">
                <div class="footer-menu">
                    <b class="footer-col-title">Контакти</b>
                    <div class="footer-socials">
                        <ul>
                            <li>
                                <a href="https://www.instagram.com/usual.clth" target="_blank">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/instagram.svg"
                                         alt=""/>
                                </a>
                            </li>
                            <li>
                                <a href="https://t.me/marktokarchuk" target="_blank">
                                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icons/telegram.svg"
                                         alt=""/>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<?php if ( is_product() ) : ?>
	<?php
	global $product;
	$typ   = wc_get_product_terms( $product->get_id(), 'pa_typ' );
	$typid = false;
	if ( isset( $typ[0] ) ) {
		$typid = $typ[0]->term_id;
	}
	$sp = 'pa_typ_' . $typid;
	if ( have_rows( 'table_row', 'pa_typ_' . $typid ) ) {
		?>

        <div class="modal" id="size-modal">
            <div class="modal-in">
                <div class="modal-close">
                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <line x1="13.6334" y1="0.53033" x2="0.633372" y2="13.5303" stroke="#565A5F" stroke-width="1.5"/>
                        <line x1="14.1977" y1="13.5303" x2="1.19771" y2="0.530343" stroke="#565A5F" stroke-width="1.5"/>
                    </svg>
                </div>
                <h3><?php the_field( 'title', $sp ); ?></h3>
                <div class="sizeguide-table">
					<?php
					while ( have_rows( 'table_row', $sp ) ) {
						the_row(); ?>
                        <div class="sgt-column">
							<?php
							// Loop over sub repeater rows.
							if ( have_rows( 'column', $sp ) ) {
								while ( have_rows( 'column', $sp ) ) {
									the_row(); ?>
                                    <div class="row size"><?= get_sub_field('text') ?></div>
									<?php
								};
							} ?>
                        </div>
						<?php
					}
					?>
                </div>
            </div>
            <div class="modal-back"></div>
        </div>
	<?php };
endif;
?>

<style>
    html {
        margin-top: 0 !important;
    }
</style>
<?php wp_footer(); ?>

</body>
</html>
