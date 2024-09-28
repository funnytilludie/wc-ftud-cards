<!-- <div class="ftud-product-card" style="background-image: url('<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>');"> -->
<a href="javascript:void(0);" class="ftud-product-card" data-product-id="<?php echo get_the_ID(); ?>" data-modal="#product-modal-<?php echo get_the_ID(); ?>">
    <img src="<?php echo esc_url(wp_get_attachment_url($product->get_image_id())); ?>" class="ftuf-card-image" alt="">
    <?php if ($product->is_in_stock()): ?>
        <!-- Buy Now button if in stock -->
        <button class="ftud-card-button ftud-buy-now" data-product-id="<?php echo get_the_ID(); ?>" data-modal="#product-modal-<?php echo get_the_ID(); ?>">
            <span class="ftud-product-buy-btn-title" >Buy Now</span>
        </button>
    <?php else: ?>
        <!-- SOLD button if out of stock -->
        <button class="ftud-card-button ftud-sold-out" disabled>SOLD</button>
    <?php endif; ?>
    <!-- <div class="ftuf-card-image-wrapper">
    </div> -->
</a>

<?php include VIA_PRODUCT_CARDS_ROOT . '/views/modal-view.php'; ?>
