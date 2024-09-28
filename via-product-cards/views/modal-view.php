<div id="product-modal-<?php echo get_the_ID(); ?>" class="modal">
    <div class="ftud-product-modal-content">
        <!-- <h2><?php echo esc_html(get_the_title()); ?></h2> -->
        <img src="<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
        <!-- <p><?php echo $product->get_short_description(); ?></p> -->

        <?php if ($product->is_in_stock()): ?>
        <!-- Buy Now button if in stock -->
         <a href="<?php echo esc_url(wc_get_checkout_url()); ?>?add-to-cart=<?php echo get_the_ID(); ?>">
            <button class="ftud-card-button ftud-buy-now" data-product-id="<?php echo get_the_ID(); ?>" data-modal="#product-modal-<?php echo get_the_ID(); ?>">
                <span class="ftud-product-buy-btn-title" >Buy Now</span>
                <span class="ftud-product-price"><?php echo $product->get_price_html(); ?> USD</span>
            </button>
        </a>
        <?php else: ?>
            <!-- SOLD button if out of stock -->
            <button class="ftud-card-button ftud-sold-out" disabled>SOLD</button>
        <?php endif; ?>

        <!-- <a href="<?php echo esc_url(wc_get_checkout_url()); ?>?add-to-cart=<?php echo get_the_ID(); ?>" class="button">
            <span class="ftud-button-title">Buy Now</span>
            <span class="ftud-button-price"><?php echo $product->get_price_html(); ?></span>
        </a> -->
    </div>
</div>
