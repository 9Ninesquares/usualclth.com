<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package usual
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="favicon" href="<?= get_template_directory_uri() ?>/favicon.ico">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( is_front_page() ) : ?>
    <div class="notify-section">
        <div class="swiper notify-slider">
            <div class="swiper-wrapper">
                <!-- Slides -->
                <div class="swiper-slide">Слава Україні!</div>
                <div class="swiper-slide">Доставка по всьому світу</div>
                <div class="swiper-slide">UA - ми працюємо!</div>
            </div>
        </div>
    </div>
<?php endif; ?>
<div class="header-sticky-wrapper">
    <div id="HeaderWrapper" class="header-wrapper header-wrapper--sticky">
        <div id="StickyHeaderWrap">
            <header id="SiteHeader" class="site-header">
                <div class="container">
                    <div class="header-in">
                        <div class="header-logo">
                            <a href="<?= home_url(); ?>">
                                <img class="logo-white"
                                     src="<?= get_stylesheet_directory_uri() ?>/assets/images/icons/logo-white.svg"
                                     alt="logo">
                                <img class="logo-black"
                                     src="<?= get_stylesheet_directory_uri() ?>/assets/images/icons/logo-black.svg"
                                     alt="logo">
                            </a>
                        </div>
                        <div class="header-menu">
                            <div class="header-menu-in">
								<?php wp_nav_menu( array( 'theme_location' => 'menu-header' ) ); ?>
                            </div>
                            <div class="mobile-menu-shown mobile-switchcopyright">
                                <ul class="header-langswitch">
									<?php pll_the_languages(
										array(
											'display_names_as' => 'slug',
											'hide_if_empty'    => 0
										)
									); ?>
                                </ul>
                                <div class="header-copyright">
                                    Usual Clothes ©<?= date( 'Y' ) ?>
                                </div>
                            </div>
                        </div>
                        <div class="header-right">
                            <ul class="header-langswitch">
								<?php pll_the_languages(
									array(
										'display_names_as' => 'slug',
										'hide_if_empty'    => 0
									)
								); ?>
                            </ul>
                            <div class="header-search-icon">
                                <svg class="svg-stroke" width="20" height="21" viewBox="0 0 20 21" fill="none"

                                     xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="9.57312" cy="9.57314" r="6.26923"
                                            transform="rotate(45 9.57312 9.57314)"/>

                                    <path d="M13.5 14L19.4455 19.7384"/>
                                </svg>
                            </div>
							<?php woocommerce_mini_cart(); ?>
                            <div class="header-toggle mobile-menu-shown">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                    <div class="header-search-input">
                        <form action="<?= home_url(); ?>" class="container header-search-form">
                            <input type="search" name="s">
                            <input type="submit" value="">
                            <div class="header-search-close">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                    <path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/>
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </header>
        </div>
    </div>
</div>