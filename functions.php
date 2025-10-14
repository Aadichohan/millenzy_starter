<?php
// -----------------------------------------------------------
// Millenzy Custom Theme Setup Additions (Elementor + WooCommerce)
// -----------------------------------------------------------
// Disable Elementor’s automatic Google Fonts loading
// add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

// Add theme supports
add_action('after_setup_theme', function(){
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('woocommerce'); // WooCommerce integration
});

// Elementor compatibility settings
add_action('after_switch_theme', function(){
    if (class_exists('\\Elementor\\Plugin')) {
        update_option('elementor_disable_color_schemes', 'yes');
        update_option('elementor_disable_typography_schemes', 'yes');
    }
});

// Register menus
add_action('init', function() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'millenzy'),
        'footer'  => __('Footer Menu', 'millenzy'),
    ]);
});

// Enqueue styles
add_action('wp_enqueue_scripts', function() {
    $dir  = get_template_directory_uri() . '/assets/css/';
    $custom_dir  = get_template_directory_uri() . '/assets/custom_css/';
    $path = get_template_directory() . '/assets/css/';
    $custom_path = get_template_directory() . '/assets/custom_css/';

    // Font Awesome
    // Remove Elementor's default Font Awesome
    wp_deregister_style('font-awesome');
    wp_dequeue_style('font-awesome');

    // Enqueue latest Font Awesome 6
    // wp_enqueue_style('font-awesome-6', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');
    // wp_enqueue_style('font-awesome-local', $dir . 'all.min.css', [], filemtime($path . 'all.min.css'));    
    wp_enqueue_style('font-awesome-local', $dir . 'all.min.css', [], '6.5.0');
    
    // Main stylesheet
    wp_enqueue_style('millenzy-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));

    // Common style
    wp_enqueue_style('millenzy-common', $custom_dir . 'common.css', [], filemtime($custom_path . 'common.css'));

    // Conditional styles
    if (is_front_page()) {
        wp_enqueue_style('home', $custom_dir . 'home.css', [], filemtime($custom_path . 'home.css'));
    } elseif (is_shop() || is_product_category()) {
        wp_enqueue_style('shop', $custom_dir . 'shop.css', [], filemtime($custom_path . 'shop.css'));
    } elseif (is_product()) {
        wp_enqueue_style('product', $custom_dir . 'product.css', [], filemtime($custom_path . 'product.css'));
    } elseif (is_cart()) {
        wp_enqueue_style('cart', $custom_dir . 'cart.css', [], filemtime($custom_path . 'cart.css'));
    } elseif (is_checkout()) {
        wp_enqueue_style('checkout', $custom_dir . 'checkout.css', [], filemtime($custom_path . 'checkout.css'));
    } elseif (is_account_page()) {
        wp_enqueue_style('account', $custom_dir . 'account.css', [], filemtime($custom_path . 'account.css'));
    } elseif (is_page('contact')) {
        wp_enqueue_style('contact', $custom_dir . 'contact.css', [], filemtime($custom_path . 'contact.css'));
    } elseif (is_page('about')) {
        wp_enqueue_style('about', $custom_dir . 'about.css', [], filemtime($custom_path . 'about.css'));
    } elseif (is_page(['men','women','unisex'])) {
        wp_enqueue_style('collection', $custom_dir . 'collection.css', [], filemtime($custom_path . 'collection.css'));
    }
});

// -----------------------------------------------------------
// Customizer Settings: Header & Footer Colors
// -----------------------------------------------------------
add_action('customize_register', function($wp_customize) {

    // Ensure Color Control class exists
    if (!class_exists('WP_Customize_Color_Control')) {
        require_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
    }

    // Header Section
    $wp_customize->add_section('millenzy_header_colors', [
        'title'    => __('Header Colors', 'millenzy'),
        'priority' => 30,
    ]);

    // Header background color
    $wp_customize->add_setting('millenzy_header_bg_color', [
        'default'   => '#f8f8f8', // ash white
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_bg_color_control', [
        'label'    => __('Header Background Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_bg_color',
    ]));

    // Header text color
    $wp_customize->add_setting('millenzy_header_text_color', [
        'default'   => '#000000', // black
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_text_color_control', [
        'label'    => __('Header Text Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_text_color',
    ]));

    // Footer Section
    $wp_customize->add_section('millenzy_footer_colors', [
        'title'    => __('Footer Colors', 'millenzy'),
        'priority' => 31,
    ]);

    // Footer background color
    $wp_customize->add_setting('millenzy_footer_bg_color', [
        'default'   => '#f8f8f8',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_footer_bg_color_control', [
        'label'    => __('Footer Background Color', 'millenzy'),
        'section'  => 'millenzy_footer_colors',
        'settings' => 'millenzy_footer_bg_color',
    ]));

    // Footer text color
    $wp_customize->add_setting('millenzy_footer_text_color', [
        'default'   => '#000000',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_footer_text_color_control', [
        'label'    => __('Footer Text Color', 'millenzy'),
        'section'  => 'millenzy_footer_colors',
        'settings' => 'millenzy_footer_text_color',
    ]));
});

// -----------------------------------------------------------
// Apply Customizer Colors to Header & Footer (Dynamic + Extended Selectors)
// -----------------------------------------------------------
add_action('wp_head', function() {
    $header_bg   = get_theme_mod('millenzy_header_bg_color', '#f8f8f8');
    $header_text = get_theme_mod('millenzy_header_text_color', '#000000');
    $footer_bg   = get_theme_mod('millenzy_footer_bg_color', '#f8f8f8');
    $footer_text = get_theme_mod('millenzy_footer_text_color', '#000000');

    echo "<style id='millenzy-dynamic-styles'>
        /* 🔹 Header Dynamic Colors */
        header.site-header,
        .millenzy-header,
        .main-header,
        .header-wrap,
        .top-bar {
            background-color: {$header_bg} !important;
            color: {$header_text} !important;
        }
        header.site-header a,
        .millenzy-header a,
        .main-header a,
        .header-wrap a,
        .top-bar a,
        .nav-links li a,
        .nav-icons a {
            color: {$header_text} !important;
        }

        /* 🔹 Footer Dynamic Colors */
        footer.site-footer,
        .millenzy-footer,
        .footer-wrap,
        .footer-inner {
            background-color: {$footer_bg} !important;
            color: {$footer_text} !important;
        }
        footer.site-footer a,
        .millenzy-footer a,
        .footer-wrap a,
        .footer-inner a {
            color: {$footer_text} !important;
        }
    </style>";
}, 999);
