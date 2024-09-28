<div class="ftud-cart-grid">
    <?php foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item): 
        $product = wc_get_product($cart_item['product_id']);
    ?>
        <div class="ftud-cart-product-card" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">
            <div class="ftud-remove-product">
                <a href="#" class="ftud-remove-from-cart" data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>">&times;</a>
            </div>
            <img src="<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="ftud-cart-featured-image">
            <div class="ftud-cart-product-details">
                <h3><?php echo esc_html($product->get_name()); ?></h3>
                <p class="ftud-cart-price"><?php echo $product->get_price_html(); ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>