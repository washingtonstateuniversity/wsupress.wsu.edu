( function( $ ) {
	"use strict";

	// Override carousel defaults.
	$( ".wsu-press-slideshow" ).cycle( {
		fx: "carousel",
		timeout: 0,
		slides: "article",
		next: ".next",
		prev: ".prev",
		log: false,
		"carousel-fluid": true,
		"carousel-visible": 5
	} );
}( jQuery ) );
