( function( $ ) {
	"use strict";

	// Configure slideshow items to achieve the infinite carousel effect.
	$( ".wsu-press-slideshow" ).each( function() {
		var $item_wrapper = $( this ).find( ".wsu-press-slideshow-items" ),
			$items = $item_wrapper.find( "figure" );

		// Bail if there are no items in the slideshow.
		if ( !$items.length ) {
			return;
		}

		// Add clones of the whole set until there are at least nine items.
		// (This is so we don't get the same item adjacent to itself.)
		if ( 9 > $items.length ) {
			for ( var i = 0; $item_wrapper.find( "figure" ).length < 9; ++i ) {
				$item_wrapper.append( $items.clone().attr( "aria-hidden", "true" ) );
			}
		}

		// Change the `aria-hidden` attribute of the first item to `false`.
		// Move the last four items to the front of the slideshow.
		$items.first().attr( "aria-hidden", "false" ).before( $item_wrapper.find( "figure" ).slice( -4 ) );
	} );

	// Control button handling.
	$( ".wsu-press-slideshow" ).on( "click", "button", function() {
		var direction = $( this ).attr( "aria-label" ),
			left_value = ( "next" === direction ) ? "-100%" : "100%",
			$slideshow = $( this ).closest( ".wsu-press-slideshow" ),
			$item_wrapper = $slideshow.find( ".wsu-press-slideshow-items" ),
			$active_slide = $item_wrapper.find( "figure[aria-hidden='false']" ),
			$adjacent_slide = ( "next" === direction ) ? $active_slide.next( "figure" ) : $active_slide.prev( "figure" );

		$item_wrapper.not( ":animated" ).animate( {
			"left": left_value
		}, {
			duration: 500,
			easing: "swing",
			start: function() {
				$active_slide.attr( "aria-hidden", "true" );
				$adjacent_slide.attr( "aria-hidden", "false" );
			},
			complete: function() {
				if ( "next" === direction ) {
					$item_wrapper.append( $item_wrapper.find( "figure:first" ) );
				} else {
					$item_wrapper.prepend( $item_wrapper.find( "figure:last" ) );
				}

				$item_wrapper.css( "left", "0" );
			}
		} );
	} );

	// Keyboard left/right arrow navigation handling.
	$( ".wsu-press-slideshow" ).keyup( function( e ) {
		if ( e.which === 39 ) {
			$( this ).find( "button[aria-label='next']" ).trigger( "click" );
		}

		if ( e.which === 37 ) {
			$( this ).find( "button[aria-label='previous']" ).trigger( "click" );
		}

		// Change focus to the active slide if the focus is within a slide item.
		// Otherwise, we'll lose the right/left arrow functionality once an item
		// with focus is moved to the front or back of the slideshow.
		if ( ( e.which === 39 || e.which === 37 ) && $( "a" ).is( ":focus" ) ) {
			$( this ).find( "figure[aria-hidden='false'] > a" ).focus();
		}
	} );
}( jQuery ) );
