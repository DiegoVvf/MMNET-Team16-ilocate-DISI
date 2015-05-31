( function ($) {
'use strict';
jQuery(document).ready(function() {
    
    //Collapse navigation on click (Bootstrap 3 is missing it)
    $('.nav a').on('click', function () {
        $('#my-nav').removeClass('in').addClass('collapse');
    });

    // Minify the Nav Bar
    $(document).scroll(function () {
        var position = $(document).scrollTop();
        var headerHeight = $('#header').outerHeight();
        if (position >= headerHeight - 100){
            $('.navbar').addClass('minified');
        } else {
            $('.navbar').removeClass('minified');
        }

        // Parallax effect on #Header
        $(".banner .container").css({
            'opacity' : (1 - position/500)
        });

        // Show "Back to Top" button
        if (position >= headerHeight - 100){
            $('.scrolltotop').addClass('show-to-top');
        } else {
            $('.scrolltotop').removeClass('show-to-top');
        }
    });

    // Nice scroll to DIVs
    $('.navbar-nav li a').click(function(evt){
        var place = $(this).attr('href');
        $('html, body').animate({
            scrollTop: $(place).offset().top
            }, 1200, 'easeInOutCubic');
        pde(evt);
    });

    // Scroll down from Header
    $('#header p a').click(function(evt) {
        var place = $(this).attr('href');
        $('html, body').animate({scrollTop: $(place).offset().top}, 1200, 'easeInOutCubic');
        pde(evt);
    });

    // Scroll on Top
    $('.scrolltotop, .navbar-brand').click(function(evt) {
        $('html, body').animate({scrollTop: '0'}, 1200, 'easeInOutCubic');
        pde(evt);
    });

    //Function to prevent Default Events
    function pde(e){
        if(e.preventDefault)
            e.preventDefault();
        else
            e.returnValue = false;
    }
});

//Window load function
$(window).load(function() {
    // Animate the header components
    $(window).load(function () {
        jQuery('#header-photo').delay( 100 ).animate({opacity: '1', 'margin-top' : '0'}, 600, 'easeInOutCubic', function() {
            jQuery('#header h1').delay( -200 ).animate({opacity: '1', 'padding-top': '0'}, 600, 'easeInOutCubic', function() {
                jQuery('#header p').animate({opacity: '1'}, 400, 'easeInOutCubic');
            });
        });
    });
});

//placeholder support
$(function() {
    // Invoke the plugin
    $('input, textarea').placeholder();
});
            
}( jQuery ));