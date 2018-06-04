(function ($) {
    "use strict";
    //document ready function
    jQuery(document).ready(function ($) {
        /*-----
        Bootstrap carousel active
        ----------------------*/
        var mobileSlider = $('.carousel');
        mobileSlider.carousel({
            interval: 8000,
        });

    });//End document ready function

}(jQuery));


// trending sales owl crausel js----////
$("#owl-example").owlCarousel({
    loop: true,
    center: true,
    responsive: {
        0: {
            items: 1,

        },
        767: {
            items: 3,
        },
        1000: {
            items: 3,
        }
    },
    responsiveClass: true,
    // Navigation
    nav: true,
    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
    dotClass: 'owl-dot',
    dotsClass: 'owl-dots',
    dots: true,
    rewindNav: true,
    scrollPerPage: false,
    activeClass: true,
    autoplay: false,
    stoponhover: true,
// END NEW PART
});


// trending sales owl crausel js----////
$("#owl-example2").owlCarousel({
    loop: true,
    center: true,
    responsive: {
        0: {
            items: 1,

        },
        767: {
            items: 3,
        },
        1000: {
            items: 3,
        }
    },
    responsiveClass: true,
    // Navigation
    nav: true,
    navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
    dotClass: 'owl-dot',
    dotsClass: 'owl-dots',
    dots: true,
    rewindNav: true,
    scrollPerPage: false,
    activeClass: true,
    autoplay: false,
    stoponhover: true,
// END NEW PART
});


var $root = $('html, body');

$('a[href^="#"]').click(function () {
    $root.animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 900);

    return false;
});

///

