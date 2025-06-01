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
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'tld-cargo' ); ?></a>

    <header id="masthead" class="site-header">

        <div class="d-flex align-items-center text-md-left top-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col d-flex">
                        <div class="top-text">
                            <small class="txt-black">Address</small>
                            254 Street Avenue, LA US
                        </div>
                        <div class="top-text">
                            <small class="txt-black">Emaii Us</small>
                            <a href="#">support@logzee.com</a>
                        </div>
                        <div class="top-text">
                            <small class="txt-black">Phone Number</small>
                            +88 (0) 202 0000 001
                        </div>
                    </div>
                    <div class="col-md-auto d-flex">

                        <!-- Topbar Language Dropdown Start -->
                        <div class="dropdown d-inline-flex lang-toggle">
                            <a href="#" class="dropdown-toggle btn" data-bs-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false" data-hover="dropdown"
                               data-animations="slideInUp slideInUp slideInUp slideInUp">
                                <img src="assets/images/us.svg" alt="" class="dropdown-item-icon">
                                <span class="d-inline-block d-lg-none">US</span>
                                <span class="d-none d-lg-inline-block">United States</span> <i
                                        class="icofont-rounded-down"></i>
                            </a>
                            <div class="dropdown-menu dropdownhover-bottom dropdown-menu-end" role="menu">
                                <a class="dropdown-item active" href="#">English</a>
                                <a class="dropdown-item" href="#">Deutsch</a>
                                <a class="dropdown-item" href="#">Español&lrm;</a>
                            </div>
                        </div>
                        <!-- Topbar Language Dropdown End -->

                        <div class="d-inline-flex request-btn ms-2">
                            <a class="btn-theme icon-left bg-orange no-shadow d-none d-lg-inline-block align-self-center"
                               href="#" role="button" data-bs-toggle="modal" data-bs-target="#request_popup"><i
                                        class="icofont-page"></i> Request Quote</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <nav id="site-navigation" class="main-navigation">
            <button class="menu-toggle" aria-controls="primary-menu"
                    aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'tld-cargo' ); ?></button>
            <div class="navbar-collapse offcanvas offcanvas-start offcanvas-collapse" id="navbarCollapse">
                <div class="offcanvas-header">
                    <a class="navbar-brand" href="index.html"><img src="assets/images/logo_footer.svg" alt=""></a>
                    <button class="navbar-toggler x collapsed" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarCollapse"
                            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="icofont-close-line"></i>
                    </button>
                </div>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'menu-1',
                        'menu_id'        => 'primary-menu',
                        'container'       => false,
                        'menu_class'      => 'navbar-nav ms-auto mb-2 mb-md-0',
                        'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                        'walker'          => new Bootstrap_Walker_Nav_Menu(),
                    )
                );
                ?>
            </div>
        </nav><!-- #site-navigation -->
    </header><!-- #masthead -->
