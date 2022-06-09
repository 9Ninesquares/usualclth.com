<?php
/**
 * usual functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package usual
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( 'usual_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function usual_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on usual, use a find and replace
		 * to change 'usual' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'usual', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'footer-menu' => esc_html__( 'Footer menu', 'usual' ),
                'menu-header' => esc_html__( 'Header Menu', 'usual' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'usual_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

        add_theme_support('woocommerce');

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );
	}
endif;
add_action( 'after_setup_theme', 'usual_setup' );


require_once 'inc/shop-functions.php';

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function usual_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'usual' ),
			'id'            => 'shop-sidebar',
			'description'   => esc_html__( 'Add widgets here.', 'usual' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'usual_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function usual_scripts() {

    wp_enqueue_style( 'usual-main', get_template_directory_uri() . '/assets/css/main.css' );
    wp_enqueue_script( 'usual-swiper', get_template_directory_uri() . '/assets/js/swiper-bundle.min.js');
    wp_enqueue_script( 'usual-scripts', get_template_directory_uri() . '/assets/js/main.js', array('usual-swiper'));

	wp_enqueue_style( 'select2' );
	wp_enqueue_script( 'select2' );
}
add_action( 'wp_enqueue_scripts', 'usual_scripts' );


function attachment_cache_busting( $url ) {
    return add_query_arg( 'v', time(), $url );
}
add_filter( 'wp_get_attachment_url', 'attachment_cache_busting' );

add_action( 'pre_get_posts', 'get_posts_search_filter' );
function get_posts_search_filter( $query ){

	if ( ! is_admin() && $query->is_main_query() && $query->is_search ) {
		$query->set( 'post_type', [ 'product' ] );
	}
}