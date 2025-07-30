<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package tld-cargo
 */
$tld_navigation = get_field('tld_navigation', 'option');
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<!--<div id="page" class="site">-->
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'tld-cargo' ); ?></a>
<!-- header.php template -->
<header class="header-one">
    <div class="d-flex align-items-center text-md-left top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col d-flex">
                    <div class="top-text">
                        <small class="txt-black">Address</small>
						<?php echo get_theme_mod('company_address', '254 Street Avenue, LA US'); ?>
                    </div>
                    <div class="top-text">
                        <small class="txt-black">Email Us</small>
                        <a href="mailto:<?php echo get_theme_mod('company_email', 'support@logzee.com'); ?>">
							<?php echo get_theme_mod('company_email', 'support@logzee.com'); ?>
                        </a>
                    </div>
                    <div class="top-text">
                        <small class="txt-black">Phone Number</small>
						<?php echo get_theme_mod('company_phone', '+88 (0) 202 0000 001'); ?>
                    </div>
                </div>
                <div class="col-md-auto d-flex">

                    <div class="d-inline-flex request-btn ms-2">
                        <a class="btn-theme icon-left bg-orange no-shadow d-none d-lg-inline-block align-self-center"
                           href="<?php echo get_permalink(get_page_by_path('request-quote')); ?>"
                           role="button" data-bs-toggle="modal" data-bs-target="#request_popup">
                            <i class="icofont-page"></i> Request Quote
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="header-fullpage navbar navbar-expand-lg nav-light">
        <div class="container text-nowrap bdr-nav">
            <div class="d-flex me-auto">
                <a class="navbar-brand rounded-bottom light-bg" href="<?php echo home_url(); ?>">
					<?php
					$logo = get_theme_mod('custom_logo');
					if ($logo) {
						$logo_url = wp_get_attachment_image_src($logo, 'full')[0];
						echo '<img src="' . esc_url($logo_url) . '" alt="' . get_bloginfo('name') . '">';
					} else {
						echo '<img src="' . get_template_directory_uri() . '/assets/images/logo_white.svg" alt="' . get_bloginfo('name') . '">';
					}
					?>
                </a>
            </div>

            <!-- Search Button -->
            <span class="order-lg-last d-inline-flex request-btn">
                <a class="nav-link" href="#" id="search_home"><i class="icofont-search"></i></a>
            </span>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="navbar-collapse offcanvas offcanvas-start offcanvas-collapse" id="navbarCollapse">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="<?php echo home_url(); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-white.png" alt="<?php bloginfo('name'); ?>">
                    </a>
                    <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <i class="icofont-close-line"></i>
                    </button>
                </div>
                <div class="offcanvas-body w-100" data-lenis-prevent>
					<?php
					wp_nav_menu(array(
						'theme_location' => 'primary-menu',
						'menu_class' => 'navbar-nav ms-auto mb-2 mb-md-0',
						'container' => false,
						'walker' => new Bootstrap_Walker_Nav_Menu(),
						'fallback_cb' => false,
						'depth' => 3
					));
					?>
                </div>
            </div>
        </div>
    </nav>
</header>
