<?php

namespace ViaStudio;

class Shortcode {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        // Register the shortcode
        add_shortcode('ftud-product-cards-home', array($this, 'display_product_cards'));

        // Handle AJAX request for loading more products
        add_action('wp_ajax_nopriv_load_more_products', array($this, 'load_more_products'));
        add_action('wp_ajax_load_more_products', array($this, 'load_more_products'));

        // Redirect shop page to collection
        add_action( 'template_redirect', array($this, 'custom_shop_page_redirect'));

    }

    // Shortcode callback to display product cards
    public function display_product_cards($atts) {
        // Set default number of products to display
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 24, // 24 products initially
            'orderby' => 'date',
            'order' => 'DESC',

        );

        $products = new \WP_Query($args);

        ob_start();
        
        if ($products->have_posts()) {
            echo '<div class="ftud-product-grid">'; // Main grid wrapper

            while ($products->have_posts()) {
                $products->the_post();
                global $product;

                if ($product->get_catalog_visibility() == 'visible') {
                    include VIA_PRODUCT_CARDS_ROOT . '/views/card-view.php';
                }
                // Load the card view for each product
            }

            echo '</div>'; // End of grid

            // Load more button
            echo '<div class="ftud-load-more-wrapper"><button id="ftud-load-more" data-page="2">Load More</button></div>';
        }

        wp_reset_postdata();
        
        return ob_get_clean();
    }

    // AJAX handler for loading more products
    public function load_more_products() {
        $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 24,
            'paged' => $paged,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        $products = new \WP_Query($args);

        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();
                global $product;

                if ($product->get_catalog_visibility() == 'visible') {
                // Product card for AJAX-loaded products
                    include VIA_PRODUCT_CARDS_ROOT . '/views/card-view.php';
                }
            }
        }

        wp_die();
    }

    // Redirect Default WooCommerce shop page to ckc-collections page by ID
    function custom_shop_page_redirect() {
        if( is_shop() ){
            wp_redirect(get_permalink(843));
            exit();
        }
    }
}
