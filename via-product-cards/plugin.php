<?php
/*
Plugin Name: Via Product Cards for FTUD
Description: Display product cards with custom modal and direct checkout option
Plugin URI: https://algerabg.com
Version: 1.0.5
Author: Algera BG
Author URI: https://algerabg.com
Text Domain: via-product-cards-ftud
Domain Path: /lang
*/

use ViaStudio\CheckoutCustomization;
use ViaStudio\Shortcode;


if( !defined( 'ABSPATH' ) ) exit;

if ( is_admin() ) {
  if( ! function_exists('get_plugin_data') ){
      require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  }

  $plugin_data = get_plugin_data( __FILE__ );
  $plugin_version = $plugin_data['Version'];
} else {
  $plugin_data = [];
  $plugin_version = '';
}

define('VIA_PRODUCT_CARDS_ROOT', __DIR__);

// Include Shortcode class
require_once VIA_PRODUCT_CARDS_ROOT . DIRECTORY_SEPARATOR . 'Shortcode.php';
require_once VIA_PRODUCT_CARDS_ROOT . DIRECTORY_SEPARATOR . 'CheckoutCustomization.php';

// Initialize Classes
Shortcode::get_instance();
CheckoutCustomization::get_instance();

// Register CSS and JS files
add_action('wp_enqueue_scripts', 'ftud_enqueue_product_cards_assets', 20);
function ftud_enqueue_product_cards_assets() {
    global $plugin_version;

    wp_enqueue_style('jquery-modal-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css');
    wp_enqueue_script('jquery-modal-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js', array('jquery'), null, true);

    // Enqueue CSS with versioning
    wp_enqueue_style('ftud-product-cards-css', plugins_url('assets/css/product-cards.css', __FILE__), array(), $plugin_version);

    // Enqueue JS with versioning
    wp_enqueue_script('ftud-product-cards-js', plugins_url('assets/js/product-cards.js', __FILE__), array('jquery'), $plugin_version, true);

    if (is_checkout()) {
      wp_enqueue_script('ftud-checkout-js', plugins_url('assets/js/checkout.js', __FILE__), array('jquery'), $plugin_version, true);
      wp_enqueue_style('ftud-checkout-css', plugins_url('assets/css/checkout.css', __FILE__), array(), $plugin_version);
    }

    // Localize the ajax URL for JS file
    wp_localize_script('ftud-product-cards-js', 'ftud_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
