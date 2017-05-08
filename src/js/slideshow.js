( function( $ ) {
	"use strict";

	// Add control buttons and configure slideshow items to achieve the infinite carousel effect.
	$( ".wsu-press-slideshow" ).each( function() {
		var $slideshow = $( this ),
			$item_wrapper = $slideshow.find( ".wsu-press-slideshow-items" ),
			$items = $item_wrapper.find( "figure" );

		// Add control buttons.
		$slideshow.prepend(
			"<button type='button' role='button' aria-label='previous'>" +
				"<svg xmlns='http://www.w3.org/2000/svg' width='11' height='40' viewBox='0 0 10.9 40'>" +
					"<path fill='#717171' d='M9.9 40c0.1 0 0.3 0 0.4-0.1 0.5-0.2 0.7-0.8 0.5-1.3L2.2 20l8.6-18.6c0.2-0.5 0-1.1-0.5-1.3C9.8-0.1 9.2 0.1 9 0.6L0 20l9 19.4C9.2 39.8 9.5 40 9.9 40z'/>" +
				"</svg>" +
			"</button>" +
			"<button type='button' role='button' aria-label='next'>" +
				"<svg xmlns='http://www.w3.org/2000/svg' width='11' height='40' viewBox='0 0 10.9 40'>" +
					"<path fill='#717171' d='M1 40c-0.1 0-0.3 0-0.4-0.1 -0.5-0.2-0.7-0.8-0.5-1.3L8.7 20 0.1 1.4C-0.1 0.9 0.1 0.3 0.6 0.1 1.1-0.1 1.7 0.1 1.9 0.6L10.9 20 1.9 39.4C1.7 39.8 1.4 40 1 40z'/>" +
				"</svg>" +
			"</button>"
		);

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

		// Set `tabindex` for each item other than the active one to `-1`.
		$item_wrapper.find( "figure[aria-hidden='true'] a" ).attr( "tabindex", "-1" );
	} );

	// Control button navigation handling.
	$( ".wsu-press-slideshow" ).on( "click", "button", function() {
		var direction = $( this ).attr( "aria-label" ),
			$slideshow = $( this ).closest( ".wsu-press-slideshow" );

		navigate_slideshow_items( 1, direction, $slideshow );
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

	// Inactive item navigation handling.
	$( ".wsu-press-slideshow" ).on( "click", "figure[aria-hidden='true']", function( e ) {
		e.preventDefault();

		var $slideshow = $( this ).closest( ".wsu-press-slideshow" ),
			item_index = $( this ).index(),
			active_item_index = $( this ).siblings( "figure[aria-hidden='false']" ).index(),
			direction = ( item_index > active_item_index ) ? "next" : "previous",
			move_by = Math.abs( active_item_index - item_index );

		navigate_slideshow_items( move_by, direction, $slideshow );
	} );

	// Animate the slideshow.
	function navigate_slideshow_items( move_by, direction, $slideshow ) {
		var left_value = ( "next" === direction ) ? "-" + move_by + "00%" : move_by + "00%",
			$item_wrapper = $slideshow.find( ".wsu-press-slideshow-items" ),
			$active_item = $item_wrapper.find( "figure[aria-hidden='false']" ),
			$new_active_item = ( "next" === direction ) ? $active_item.nextAll( "figure" ).eq( move_by - 1 ) : $active_item.prevAll( "figure" ).eq( move_by - 1 );

		$item_wrapper.not( ":animated" ).animate( {
			"left": left_value
		}, {
			duration: 500,
			easing: "swing",
			start: function() {
				$active_item.attr( "aria-hidden", "true" ).find( "a" ).attr( "tabindex", "-1" );
				$new_active_item.attr( "aria-hidden", "false" ).find( "a" ).removeAttr( "tabindex" );
			},
			complete: function() {
				if ( "next" === direction ) {
					$item_wrapper.append( $item_wrapper.find( "figure" ).slice( 0, move_by ) );
				} else {
					$item_wrapper.prepend( $item_wrapper.find( "figure" ).slice( "-" + move_by ) );
				}

				$item_wrapper.css( "left", 0 );
			}
		} );
	}
}( jQuery ) );
