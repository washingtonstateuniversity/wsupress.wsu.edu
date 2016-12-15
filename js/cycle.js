(function ($) {
	'use strict';

	// Carousel
	// Add the 'ready' class to the carousel after the page is loaded.
	// This class is used in the selector for the CSS transitions
	// to prevent them from firing while the page is loading.
	/*$(window).load(function () {
		$('.carousel').addClass('ready');
	});*/

	// Override carousel defaults.
	$('.new.cycle-slideshow').cycle({
		//fx: 'carousel',
        //visible: 5,
        //'carousel-offset': 2,
        next: '.new-next',
        prev: '.new-prev'
    });
    
    // Override carousel defaults.
	$('.best.cycle-slideshow').cycle({
		//fx: 'carousel',
        //visible: 5,
        //'carousel-offset': 2,
        next: '.best-next',
        prev: '.best-prev'
    });
    
}(jQuery));
