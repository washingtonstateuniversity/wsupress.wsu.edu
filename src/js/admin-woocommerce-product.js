/* global _ */
( function( $, window ) {

	var $extra_fields = $( ".wsu-press-extra-product-fields" ),
		attribution_template = _.template( $( "#wsu-press-product-attribution-template" ).html() ),
		$add_attribution = $( ".wsu-press-product-add-attribution" );

	// Mimic the `wptitlehint` functionality.
	$extra_fields.on( "click", "label", function() {
		$( this ).addClass( "screen-reader-text" ).next( "input" ).focus();
	} );

	$extra_fields.on( "blur", "input", function() {
		if ( "" === this.value ) {
			$( this ).prev( "label" ).removeClass( "screen-reader-text" );
		}
	} ).on( "focus", "input", function() {
		$( this ).prev( "label" ).addClass( "screen-reader-text" );
	} );

	// Add an attribution field.
	$add_attribution.click( function( e ) {
		e.preventDefault();

		$( this ).closest( "p" ).before( attribution_template( {
			number: $( ".wsu-press-product-attribution" ).length
		} ) );
	} );

	// Remove an attribution field.
	$extra_fields.on( "click", ".remove-attribution", function() {
		$( this ).closest( ".wsu-press-product-attribution" ).remove();
	} );

	// Use jQuery UI Autocomplete to suggest Authors.
	$extra_fields.on( "focus", ".wsu-press-attribution-input", function() {
		$( this ).autocomplete( {
			minLength: 3,
			source: function( request, response ) {
				response( $.ui.autocomplete.filter(
					window.wsu_press_authors, extract_last( request.term )
				) );
			},
			focus: function() {
				return false;
			},
			open: function() {
				$( this ).autocomplete( "widget" ).addClass( "wsu-press-authors-menu" );
			},
			select: function( event, ui ) {
				var terms = split( this.value );

				terms.pop();
				terms.push( ui.item.value );
				terms.push( "" );

				this.value = terms.join( " " ).replace( /\s+$/, "" );

				return false;
			}
		} );
	} );

	function split( val ) {
		return val.split( / \s*/ );
	}

	function extract_last( term ) {
		return split( term ).pop();
	}
}( jQuery, window ) );
