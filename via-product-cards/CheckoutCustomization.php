<?php

namespace ViaStudio;

class CheckoutCustomization {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_filter('woocommerce_checkout_fields', array($this, 'customize_checkout_fields'));
        add_action('woocommerce_checkout_before_customer_details', array($this, 'add_custom_message_above_checkboxes'));
        // Commercial Agreement checkbox
        add_action('woocommerce_checkout_after_terms_and_conditions', array($this, 'add_commercial_agreement_checkbox'));
        add_action('woocommerce_checkout_process', array($this, 'validate_commercial_agreement_checkbox'));
        // Privacy Policy checkbox
        add_action('woocommerce_checkout_after_terms_and_conditions', array($this, 'add_privacy_policy_checkbox'));
        add_action('woocommerce_checkout_process', array($this, 'validate_privacy_policy_checkbox'));
        add_action('woocommerce_before_checkout_form', array($this, 'custom_cart_info'), 15); // Place cart info after notifications

        // Remove cart_item functionality
        add_action('wp_ajax_ftud_remove_from_cart', array($this, 'ftud_remove_from_cart'));
        add_action('wp_ajax_nopriv_ftud_remove_from_cart', array($this, 'ftud_remove_from_cart'));
        
        // Remove "Billing Details" and "Additional Information" titles
        add_filter('woocommerce_checkout_fields', array($this, 'remove_checkout_titles'), 10, 1);
    }

    // Customize checkout fields to only show email and remove unnecessary fields
    public function customize_checkout_fields($fields) {
        unset($fields['billing']['billing_first_name']);
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_company']); // Remove Company Name
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);
        unset($fields['billing']['billing_phone']);
        unset($fields['order']['order_comments']); // Remove Order Notes
        
        return $fields;
    }

    // Custom Cart Info below WooCommerce notifications
    public function custom_cart_info() {
        global $woocommerce;
        $cart = $woocommerce->cart->get_cart();
        
        // foreach ($cart as $cart_item_key => $cart_item) {
        //     $product = wc_get_product($cart_item['product_id']);
            include VIA_PRODUCT_CARDS_ROOT . '/views/cart-info-view.php';
        // }
    }

    // Add the "I accept Commercial Business Agreement" checkbox after terms and conditions
    public function add_commercial_agreement_checkbox() {
        woocommerce_form_field('commercial_agreement', array(
            'type' => 'checkbox',
            'class' => array('form-row commercial-agreement'),
            'label' => __('I accept the <a href="https://funnytilludie.com/wp-content/uploads/2024/09/FTUD-DCP-CBA-Version-1-Septembet-2024-For-information-puproses-only.pdf" target="_blank">Commercial Business Agreement</a>'),
            'required' => true,
        ), WC()->checkout->get_value('commercial_agreement'));
    }

    // Validate the Commercial Aggreement checkbox
    public function validate_commercial_agreement_checkbox() {
        if (!isset($_POST['commercial_agreement'])) {
            wc_add_notice(__('You must accept the Commercial Business Agreement to proceed.'), 'error');
        }
    }


    // Add the "I accept the Privacy Policy" checkbox after terms and conditions
    public function add_privacy_policy_checkbox() {
        woocommerce_form_field('privacy_policy', array(
            'type' => 'checkbox',
            'class' => array('form-row commercial-agreement'),
            'label' => __('I accept the <a href="https://funnytilludie.com/privacy-policy/" target="_blank">Privacy Policy</a>'),
            'required' => true,
        ), WC()->checkout->get_value('ftud_privacy_policy'));
    }
    // Validate the Privacy Policy checkbox
    public function validate_privacy_policy_checkbox() {
        if (!isset($_POST['privacy_policy'])) {
            wc_add_notice(__('You must accept the Privacy Policy to proceed.'), 'error');
        }
    }


    // Remove the "Billing Details" and "Additional Information" titles
    public function remove_checkout_titles($fields) {
        add_filter('woocommerce_checkout_show_billing_title', '__return_false'); // Remove Billing Details title
        add_filter('woocommerce_enable_order_notes_field', '__return_false'); // Remove Additional Information title
        return $fields;
    }

    // Remove Cart Item from cart and refresh cart and checkout details
    public function ftud_remove_from_cart() {
        // Get the cart item key from the AJAX request
        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
    
        if ($cart_item_key && WC()->cart->remove_cart_item($cart_item_key)) {
            // Success, refresh cart and checkout fragments
            \WC_AJAX::get_refreshed_fragments();  // Use global namespace for WC_AJAX
        } else {
            // Return error if failed
            wp_send_json_error();
        }
    
        wp_die();
    }

    // Add custom message above checkboxes
    public function add_custom_message_above_checkboxes() {
        echo '<div class="checkout-dnft-info"><ul><li>dNFTs will be delivered by December 16, 2024</li><li>dNFT owners will be able to "NAME" their Clown through the Upgrade process.</li></ul></div>';
    }
}
