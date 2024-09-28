jQuery(document).ready(function($) {
    var $termsCheckbox = $('#terms');
    var $commercialAgreementCheckbox = $('#commercial_agreement');
    var $placeOrderButton = $('#place_order');

    // Disable the place order button by default
    $placeOrderButton.prop('disabled', true);
    $placeOrderButton.addClass('disabled');

    // Function to check if both checkboxes are checked
    function checkBothCheckboxes() {
        if ($termsCheckbox.prop('checked') && $commercialAgreementCheckbox.prop('checked')) {
            $placeOrderButton.prop('disabled', false);
            $placeOrderButton.removeClass('disabled');
        } else {
            $placeOrderButton.prop('disabled', true);
            $placeOrderButton.addClass('disabled');
        }
    }

    // Call the function whenever cart totals are updated
    jQuery( document.body ).on( 'update_checkout', function(){ 
        console.log('updated_cart_totals');
        checkBothCheckboxes();
    });

    // Trigger the check when checkboxes change
    $termsCheckbox.on('change', checkBothCheckboxes);
    $commercialAgreementCheckbox.on('change', checkBothCheckboxes);


    // Handle remove from cart button click
    $('.ftud-cart-grid').on('click', '.ftud-remove-from-cart', function(e) {
        e.preventDefault();

        var cartItemKey = $(this).data('cart-item-key');
        var $cartItem = $(this).closest('.ftud-cart-product-card');

        $.ajax({
            url: ftud_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'ftud_remove_from_cart',
                cart_item_key: cartItemKey
            },
            beforeSend: function() {
                // Optional: Add loading indicator or visual feedback
            },
            success: function(response) {
                if (response) {
                    $cartItem.fadeOut(300, function() {
                        $(this).remove();
                    });
                    // Refresh cart totals and checkout information
                    $(document.body).trigger('update_checkout');
                }
            },
            error: function() {
                alert('Failed to remove item from cart.');
            }
        });
    });

    
});
