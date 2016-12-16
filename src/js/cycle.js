( function( $ ) {
	"use strict";

	// Override carousel defaults.
	$( ".new.cycle-slideshow" ).cycle( {
		next: ".new-next",
		prev: ".new-prev"
	} );

	// Override carousel defaults.
	$( ".best.cycle-slideshow" ).cycle( {
		next: ".best-next",
		prev: ".best-prev"
	} );

}( jQuery ) );
