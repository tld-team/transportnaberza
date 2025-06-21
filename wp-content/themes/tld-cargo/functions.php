<?php
/**
 * tld-cargo functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package tld-cargo
 */

if (!defined('_S_VERSION')) {
    // Replace the version number of the theme on each release.
    define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function tld_cargo_setup()
{
    /*
        * Make theme available for translation.
        * Translations can be filed in the /languages/ directory.
        * If you're building a theme based on tld-cargo, use a find and replace
        * to change 'tld-cargo' to the name of your theme in all the template files.
        */
    load_theme_textdomain('tld-cargo', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
        * Let WordPress manage the document title.
        * By adding theme support, we declare that this theme does not use a
        * hard-coded <title> tag in the document head, and expect WordPress to
        * provide it for us.
        */
    add_theme_support('title-tag');

    /*
        * Enable support for Post Thumbnails on posts and pages.
        *
        * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
        */
    add_theme_support('post-thumbnails');

    // This theme uses wp_nav_menu() in one location.
	register_nav_menus(
        array(
	        'primary-menu' => __('Primary Menu'),
	        'mobile-menu' => __('Mobile Menu')
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
            'tld_cargo_custom_background_args',
            array(
                'default-color' => 'ffffff',
                'default-image' => '',
            )
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support(
        'custom-logo',
        array(
            'height' => 250,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        )
    );
}
add_action('after_setup_theme', 'tld_cargo_setup');


//// Registruje navigation menus
//function register_my_menus() {
//	register_nav_menus(
//		array(
//			'primary-menu' => __('Primary Menu'),
//			'mobile-menu' => __('Mobile Menu')
//		)
//	);
//}
/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function tld_cargo_content_width()
{
    $GLOBALS['content_width'] = apply_filters('tld_cargo_content_width', 640);
}

add_action('after_setup_theme', 'tld_cargo_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function tld_cargo_widgets_init()
{
    register_sidebar(
        array(
            'name' => esc_html__('Sidebar', 'tld-cargo'),
            'id' => 'sidebar-1',
            'description' => esc_html__('Add widgets here.', 'tld-cargo'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<h2 class="widget-title">',
            'after_title' => '</h2>',
        )
    );
}

add_action('widgets_init', 'tld_cargo_widgets_init');

/***
 * log function
 */
if ( ! function_exists( 'tld_log' ) ) {
	function tld_log( $entry, $mode = 'a', $file = 'tld_log' ) {
		// Get WordPress uploads directory.
		$upload_dir = wp_upload_dir();

		$upload_dir = $upload_dir['basedir'];
		$upload_dir = dirname(__FILE__);
		// If the entry is array, json_encode.
		if ( is_array( $entry ) ) {
			$entry = json_encode( $entry );
		}
		// Write the log file.
		$file  = $upload_dir . '/' . $file . '.log';
		$file  = fopen( $file, $mode );
		$bytes = fwrite( $file, current_time( 'mysql' ) . "::" . $entry . "\n" );
		fclose( $file );
		return $bytes;
	}
}

function dd($array, $array2 = null )
{
	if ($array2 == null) {
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	} else {
		echo '<div class="flex-container" style="display: flex"> <div class="column" style="background-color:#f5f5f5;"><pre>';
		print_r($array);
		echo '</pre></div> <pre class="column" style="background-color:#f5eded;"><pre>';
		print_r($array2);
		echo '</pre></div> </div>';
	}
}
/** ==================================================== */
/**
 * Enqueue scripts and styles.
 */
function tld_cargo_scripts()
{
    wp_enqueue_style('tld-cargo-style', get_stylesheet_uri(), array(), _S_VERSION);
    wp_style_add_data('tld-cargo-style', 'rtl', 'replace');
    // Bootstrap Style CSS
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css');


    // Main Style CSS
    wp_enqueue_style('theme-plugins', get_template_directory_uri() . '/assets/css/theme-plugins.min.css');
    wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');
    wp_enqueue_style('responsive-style', get_template_directory_uri() . '/assets/css/responsive.css');

    // Revolution Slider Styles
    wp_enqueue_style('revolution-layers', get_template_directory_uri() . '/assets/revolution/css/layers.css');
    wp_enqueue_style('revolution-navigation', get_template_directory_uri() . '/assets/revolution/css/navigation.css');
    wp_enqueue_style('revolution-settings', get_template_directory_uri() . '/assets/revolution/css/settings.css');
    wp_enqueue_style('pe-icon-7-stroke', get_template_directory_uri() . '/assets/revolution/fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css');
    wp_enqueue_style('font-awesome-rev', get_template_directory_uri() . '/assets/revolution/fonts/font-awesome/css/font-awesome.css');

    wp_enqueue_script('tld-cargo-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

    // Theme plugins
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), null, true);


    // Osnovne JS skripte
//    wp_enqueue_script('theme-plugins', get_template_directory_uri() . '/assets/js/theme-plugins.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-tweet', get_template_directory_uri() . '/assets/js/twitter/jquery.tweet.js', array('jquery'), null, true);

    // Revolution Slider glavne skripte
    wp_enqueue_script('themepunch-tools', get_template_directory_uri() . '/assets/revolution/js/jquery.themepunch.tools.min.js', array('jquery'), null, true);
    wp_enqueue_script('themepunch-revolution', get_template_directory_uri() . '/assets/revolution/js/jquery.themepunch.revolution.min.js', array('jquery', 'themepunch-tools'), null, true);

    // Revolution Slider ekstenzije
    $revolution_extensions = array(
        'revolution-actions',
        'revolution-carousel',
        'revolution-kenburn',
        'revolution-layeranimation',
        'revolution-migration',
        'revolution-navigation',
        'revolution-parallax',
        'revolution-slideanims',
        'revolution-video'
    );

    foreach ($revolution_extensions as $extension) {
        wp_enqueue_script(
            $extension,
            get_template_directory_uri() . '/assets/revolution/js/extensions/revolution.extension.' . str_replace('revolution-', '', $extension) . '.min.js',
            array('jquery', 'themepunch-revolution'),
            null,
            true
        );
    }

    // Custom JS
    wp_enqueue_script('site-custom', get_template_directory_uri() . '/assets/js/site-custom.js', array('jquery'), null, true);

    /**
     * form Ajax
     */

    if (is_page('account')) {
	    wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css' );

        wp_enqueue_script('tld-child-user', get_template_directory_uri() . '/assets/js/forms/child-users.js', array('jquery'), '', true);

        // Localize script for AJAX URL
        wp_localize_script('tld-child-users', 'tld_child_users', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sS8yMjPQ5EEJ7Qa')
        ));

    }
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}

add_action('wp_enqueue_scripts', 'tld_cargo_scripts');

function tld_cargo_dashboards_script(): void {
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );

	// Main Style CSS
	wp_enqueue_style('theme-plugins', get_template_directory_uri() . '/assets/css/theme-plugins.min.css');
	wp_enqueue_style('main-style', get_template_directory_uri() . '/assets/css/style.css');
	wp_enqueue_style('responsive-style', get_template_directory_uri() . '/assets/css/responsive.css');
}

add_action( 'admin_enqueue_scripts', 'tld_cargo_dashboards_script' );
/**
 * Helpers functions
 */
require get_template_directory() . '/inc/Custom_Walker_Nav.php';
/**
 * Helpers functions
 */
require get_template_directory() . '/inc/helper-functions.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
    require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Functions for blocks registration.
 */
require get_template_directory() . '/inc/register-blocks.php';

/**
 * user profile
 */
require get_template_directory() . '/inc/forms/license.php';
require get_template_directory() . "/inc/forms/child-user-action.php";
require get_template_directory() . '/inc/user-profile.php';
require get_template_directory() . '/inc/forms/user-form.php';
