
jQuery(document).ready(function($) {
    // Function to handle Buy Now button click event
    function setupProductCardListeners() {
        $('.ftud-product-card').on('click', function(e) {
            e.preventDefault();
            var modalId = $(this).data('modal');
            $(modalId).modal();  // Open the modal associated with the clicked button
        });
    }

    // Initial call to setup event listeners on page load
    setupProductCardListeners();

    // Load more products with fade-in effect
    $('#ftud-load-more').on('click', function() {
        var page = $(this).data('page');
        var button = $(this);

        $.ajax({
            url: ftud_ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'load_more_products',
                page: page
            },
            beforeSend: function() {
                button.text('Loading...'); // Change button text to "Loading..."
            },
            success: function(response) {
                // var newCards = $(response).find( "a" ).hide().addClass("hidden-card"); // Hide new cards initially
                // console.log( $( newCards ).find( "a" ));
                $('.ftud-product-grid').append(response); // Append new cards
                // newCards.fadeIn(700); // Fade in new cards over 700ms
                setupProductCardListeners(); // Reapply event listeners to new cards
                button.data('page', page + 1); // Increment page number
                button.text('Load More'); // Reset button text
            },
            error: function() {
                button.text('Load More'); // Reset button text on error
            }
        });
    });

    // Hover animation for the product card (zoom effect)
    $('.ftud-product-card').hover(function() {
        $(this).css({
            'transform': 'scale(1.05)',
            'transition': 'transform 0.3s ease'
        });
    }, function() {
        $(this).css({
            'transform': 'scale(1)',
            'transition': 'transform 0.3s ease'
        });
    });

});
