( function( $ ) {
	"use strict";

	// Override carousel defaults.
	$( ".new.cycle-slideshow" ).cycle( {
		fx: "fade",
		timeout: 0,
		slides: "div",
		next: ".new-next",
		prev: ".new-prev",
		log: false,
		"carousel-fluid": true,
		"carousel-visible": 1
	} );

	// Override carousel defaults.
	$( ".best.cycle-slideshow" ).cycle( {
		fx: "fade",
		timeout: 0,
		slides: "div",
		next: ".best-next",
		prev: ".best-prev",
		log: false,
		"carousel-fluid": true,
		"carousel-visible": 1
	} );

}( jQuery ) );
