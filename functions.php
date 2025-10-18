<?php
// -----------------------------------------------------------
// Millenzy Custom Theme Setup Additions (Elementor + WooCommerce)
// -----------------------------------------------------------

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

// -----------------------------------------------------------
// Enqueue Styles (Main + Common + Page-specific)
// -----------------------------------------------------------
// =============================
// Conditionally Enqueue Sticky Header Script
// =============================
function millenzy_fixed_header_script() {
    if ( get_theme_mod('millenzy_fixed_header', false) ) {
        wp_enqueue_script(
            'millenzy-sticky-header',
            get_template_directory_uri() . '/assets/js/header/sticky-header.js',
            array(),
            filemtime(get_template_directory() . '/assets/js/header/sticky-header.js'),
            true
        );
    }
}
function millenzy_search_overlay_header_script() {

    wp_enqueue_script(
        'millenzy-search-overlay-header',
        get_template_directory_uri() . '/assets/js/header/search-overlay.js',
        array(),
        filemtime(get_template_directory() . '/assets/js/header/search-overlay.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'millenzy_fixed_header_script');
add_action('wp_enqueue_scripts', 'millenzy_search_overlay_header_script');
add_action('wp_enqueue_scripts', function() {
    $dir  = get_template_directory_uri() . '/assets/css/';
    $custom_dir  = get_template_directory_uri() . '/assets/custom_css/';
    $path = get_template_directory() . '/assets/css/';
    $custom_path = get_template_directory() . '/assets/custom_css/';
    $root_dir    = get_template_directory_uri() . '/assets';
    $root_path   = get_template_directory() . '/assets';

    $font_choice = get_theme_mod('millenzy_font_family', 'Montserrat');

    // Font Awesome
    wp_deregister_style('font-awesome');
    wp_dequeue_style('font-awesome');
    wp_enqueue_style('font-awesome-local', $dir . 'all.min.css', [], '6.5.0');

    // Main stylesheet
    wp_enqueue_style('millenzy-style', get_stylesheet_uri(), [], filemtime(get_stylesheet_directory() . '/style.css'));

    // Common style
    wp_enqueue_style('millenzy-common', $custom_dir . 'common.css', ['millenzy-style'], filemtime($custom_path . 'common.css'));

    // Remove previously enqueued fonts (if any)
    wp_dequeue_style('millenzy-local-font');
    // wp_enqueue_style(
    //     'montserrat-font',
    //     $root_dir. '/fonts/montserrat/fonts.css',
    //     array(),
    //     filemtime( $root_path. '/fonts/montserrat/montserrat-font.css')
    // );

    // Load only the selected one
    switch ($font_choice) {
        case 'Raleway':
            wp_enqueue_style(
                'millenzy-local-font',
                $root_dir . '/fonts/Raleway/raleway-font.css',
                [],
                filemtime($root_path . '/fonts/Raleway/raleway-font.css')
            );
            break;

        case 'Poppins':
            wp_enqueue_style(
                'millenzy-local-font',
                $root_dir . '/fonts/Poppins/poppins-font.css',
                [],
                filemtime($root_path . '/fonts/Poppins/poppins-font.css')
            );
            break;

        default: // Montserrat
            wp_enqueue_style(
                'millenzy-local-font',
                $root_dir . '/fonts/montserrat/montserrat-font.css',
                [],
                filemtime($root_path . '/fonts/montserrat/montserrat-font.css')
            );
            break;
    }
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
// 🧩 Customizer Settings: Header, Footer & Logo Height
// -----------------------------------------------------------
add_action('customize_register', function($wp_customize) {

    // Ensure Color Control class exists
    if (!class_exists('WP_Customize_Color_Control')) {
        require_once ABSPATH . 'wp-includes/class-wp-customize-control.php';
    }

    // ===== Header Section =====
    $wp_customize->add_section('millenzy_header_colors', [
        'title'    => __('Header Colors', 'millenzy'),
        'priority' => 30,
    ]);
    $wp_customize->add_setting('millenzy_fixed_header', array(
        'default'   => false,
        'transport' => 'refresh',
    ));

    $wp_customize->add_control('millenzy_fixed_header_control', array(
        'label'    => __('Enable Fixed Header', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_fixed_header',
        'type'     => 'checkbox',
    ));

    // Header Top bar background color
    $wp_customize->add_setting('millenzy_header_topbar_bg_color', [
        'default'   => '#f7f7f7',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_topbar_bg_color_control', [
        'label'    => __('Header Top Bar Background Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_topbar_bg_color',
    ]));

    // Header Top bar text color
    $wp_customize->add_setting('millenzy_header_topbar_text_color', [
        'default'   => '#353030',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_topbar_text_color_control', [
        'label'    => __('Header Top Bar Text Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_topbar_text_color',
    ]));

    // Header Top bar text hover color
    $wp_customize->add_setting('millenzy_header_topbar_hover_text_color', [
        'default'   => '#000000',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_topbar_hover_text_color_control', [
        'label'    => __('Header Top Bar Text Hover Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_topbar_hover_text_color',
    ]));

    // Header background color
    $wp_customize->add_setting('millenzy_header_bg_color', [
        'default'   => '#ffffff',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_bg_color_control', [
        'label'    => __('Header Background Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_bg_color',
    ]));

    // Header text color
    $wp_customize->add_setting('millenzy_header_text_color', [
        'default'   => '#0e0d0d',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_text_color_control', [
        'label'    => __('Header Text Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_text_color',
    ]));
    
    // Header Active Menu Item text color
    $wp_customize->add_setting('millenzy_header_active_item_text_color', [
        'default'   => '#c5a059',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_active_item_text_color_control', [
        'label'    => __('Header Active Menu Item Text Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_active_item_text_color',
    ]));
    // Header Hover text color
    $wp_customize->add_setting('millenzy_header_hover_text_color', [
        'default'   => '#000000',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_header_hover_text_color_control', [
        'label'    => __('Header Hover Text Color', 'millenzy'),
        'section'  => 'millenzy_header_colors',
        'settings' => 'millenzy_header_hover_text_color',
    ]));

    // ===== Logo Height Control (NEW ADDITION) =====
    $wp_customize->add_setting('millenzy_logo_height', [
        'default' => 65,
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control('millenzy_logo_height_control', [
        'label' => __('Logo Height (px)', 'millenzy'),
        'section' => 'title_tagline',
        'type' => 'number',
    ]);

    // ===== Footer Section =====
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

    // Footer Heading 4 text color
    $wp_customize->add_setting('millenzy_footer_text_h4_color', [
        'default'   => '#c5a059',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_footer_text_h4_color_control', [
        'label'    => __('Footer Heading 4 Text Color', 'millenzy'),
        'section'  => 'millenzy_footer_colors',
        'settings' => 'millenzy_footer_text_h4_color',
    ]));

    // Footer Anchor text color
    $wp_customize->add_setting('millenzy_footer_text_link_color', [
        'default'   => '#000000',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_footer_text_link_color_control', [
        'label'    => __('Footer Anchor Text Color', 'millenzy'),
        'section'  => 'millenzy_footer_colors',
        'settings' => 'millenzy_footer_text_link_color',
    ]));

    $wp_customize->add_setting('millenzy_footer_text_link_hover_color', [
        'default'   => '#c5a059',
        'transport' => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'millenzy_footer_text_link_hover_color_control', [
        'label'    => __('Footer Anchor Hover Text Color', 'millenzy'),
        'section'  => 'millenzy_footer_colors',
        'settings' => 'millenzy_footer_text_link_hover_color',
    ]));

     // Typography Section
    $wp_customize->add_section('millenzy_typography', [
        'title'    => __('Typography', 'millenzy'),
        'priority' => 35,
    ]);

    // Font Family Setting
    $wp_customize->add_setting('millenzy_font_family', [
        'default'   => 'Montserrat',
        'transport' => 'refresh',
    ]);

    // Font Family Control
    $wp_customize->add_control('millenzy_font_family_control', [
        'label'    => __('Select Font Family', 'millenzy'),
        'section'  => 'millenzy_typography',
        'settings' => 'millenzy_font_family',
        'type'     => 'select',
        'choices'  => [
            'Raleway'    => 'Raleway',
            'Montserrat' => 'Montserrat',
            'Poppins'    => 'Poppins',
        ],
    ]);
});

// -----------------------------------------------------------
// 🧠 Apply Dynamic Variables via <style> (for :root vars)
// -----------------------------------------------------------
add_action('wp_head', function() {
    $logo_height              = get_theme_mod('millenzy_logo_height', 65);
    $header_topbar_bg         = get_theme_mod('millenzy_header_topbar_bg_color', '#f7f7f7');
    $header_topbar_text       = get_theme_mod('millenzy_header_topbar_text_color', '#353030');
    $header_topbar_hover_text = get_theme_mod('millenzy_header_topbar_hover_text_color', '#000000');

    $header_bg         = get_theme_mod('millenzy_header_bg_color', '#f7f7f7');
    $header_text       = get_theme_mod('millenzy_header_text_color', '#353030');
    $header_hover_text = get_theme_mod('millenzy_header_hover_text_color', '#000000');
    $header_active_item_text = get_theme_mod('millenzy_header_active_item_text_color', '#c5a059');
    $footer_bg         = get_theme_mod('millenzy_footer_bg_color', '#f8f8f8');
    $footer_text       = get_theme_mod('millenzy_footer_text_color', '#000000');
    $footer_link_h4_text    = get_theme_mod('millenzy_footer_text_h4_color', '#c5a059');
    $footer_link_text       = get_theme_mod('millenzy_footer_text_link_color', '#000000');
    $footer_link_hover_text = get_theme_mod('millenzy_footer_text_link_hover_color', '#c5a059');

    echo "<style id='millenzy-dynamic-vars'>
        :root {
            --logo-height: {$logo_height}px;
            --header-topbar-bg: {$header_topbar_bg};
            --header-topbar-text: {$header_topbar_text};
            --header-topbar-hover-text: {$header_topbar_hover_text};
            --header-bg: {$header_bg};
            --header-text: {$header_text};
            --header-hover-text: {$header_hover_text};
            --header-active-item-text: {$header_active_item_text};
            --footer-bg: {$footer_bg};
            --footer-text: {$footer_text};
            --footer-link-h4-text: {$footer_link_h4_text};
            --footer-link-text: {$footer_link_text};
            --footer-link-hover-text: {$footer_link_hover_text};
        }
    </style>";
});

// -----------------------------------------------------------
// Apply Customizer Colors Directly to Common Selectors
// -----------------------------------------------------------
add_action('wp_head', function() {
    $header_bg   = get_theme_mod('millenzy_header_bg_color', '#f7f7f7');
    $header_text = get_theme_mod('millenzy_header_text_color', '#353030');
    $footer_bg   = get_theme_mod('millenzy_footer_bg_color', '#f8f8f8');
    $footer_text = get_theme_mod('millenzy_footer_text_color', '#000000');


    echo "<style id='millenzy-dynamic-styles'>
        /* 🔹 Header Dynamic Colors */
        header.site-header,
        .main-header,
        .header-wrap,
         {
            background-color: {$header_bg} !important;
            color: {$header_text} !important;
        }

        /* 🔹 Footer Dynamic Colors */
        footer.site-footer,
        .footer-wrap {
            background-color: {$footer_bg} !important;
            color: {$footer_text} !important;
        }
        footer.site-footer a,
        .footer-wrap a {
            color: {$footer_text} !important;
        }
    </style>";
}, 999);
