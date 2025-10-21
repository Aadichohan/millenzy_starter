<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- 🔹 Top Bar -->
<div class="top-bar">
  <div class="container">
    <div class="social-icons">
      <a href="#"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-instagram"></i></a>
      <a href="#"><i class="fab fa-youtube"></i></a>
      <a href="#"><i class="fab fa-tiktok"></i></a>
    </div>
    <div class="delivery-info">
      Standard Delivery in 3–4 Business Days
    </div>
  </div>
</div>

<!-- 🔹 Main Header -->
<header class="main-header">
  <div class="container header-inner">

    <!-- 🔹 Burger Icon (Mobile Only) -->
    <div class="nav-toggle" id="navToggle">
      <span></span>
      <span></span>
      <span></span>
    </div>

  <div class="logo-nav-section">
    <div class="logo">
      <?php 
        if (has_custom_logo()) {
          the_custom_logo();
        } else { ?>
          <a href="<?php echo esc_url(home_url('/')); ?>">Millenzy</a>
        <?php } ?>
    </div>


   <!-- 🔹 Navigation -->
    <nav class="main-nav" id="mobileNav">
      <?php
        if ( has_nav_menu( 'primary' ) ) {
          wp_nav_menu(array(
            'theme_location' => 'primary',
            'menu_class'     => 'menu-items',
            'container'      => false,
          ));
        } else {
          echo '<ul class="menu-items">
            <li class="menu-item"><a href="' . esc_url( home_url( '/' ) ) . '">Home</a></li>
            <li class="menu-item"><a href="#">Collections</a></li>
            <li class="menu-item"><a href="#">Contact</a></li>
            <li class="menu-item"><a href="#">About Us</a></li>
          </ul>';
        }
      ?>
    </nav>
    <!-- Overlay -->
    <!-- <div class="menu-overlay" id="menuOverlay"></div> -->
  </div>
    <div class="header-icons">
      <a href="#" id="searchToggle" class="search-icon"><i class="fas fa-search"></i></a>
      <a href="<?php echo wc_get_page_permalink('myaccount'); ?>"><i class="far fa-user"></i></a>
      <a href="<?php echo wc_get_cart_url(); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4H6z" />
          <line x1="3" y1="6" x2="21" y2="6" />
          <path d="M16 10a4 4 0 1 1-8 0" />
        </svg>
      </a>
    </div>
    <!-- Search Overlay -->
    <div id="searchOverlay" class="search-overlay">
      <div class="search-box">
        <input type="text" placeholder="Search..." />
        <button type="button" id="closeSearch" class="close-btn">&times;</button>
      </div>
    </div>
    <!-- <div class="header-icons">
        <a href="#" class="icon-search" aria-label="Search">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
        </a>

        <a href="<?php echo wc_get_page_permalink('myaccount'); ?>" class="icon-user" aria-label="My Account">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="7" r="4" />
            <path d="M5.5 21a6.5 6.5 0 0 1 13 0Z" />
            </svg>
        </a>

        <a href="<?php echo wc_get_cart_url(); ?>" class="icon-cart" aria-label="Cart">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4H6z" />
            <line x1="3" y1="6" x2="21" y2="6" />
            <path d="M16 10a4 4 0 1 1-8 0" />
            </svg>
        </a>
    </div> -->

  </div>
</header>

<main id="primary" class="site-main">
