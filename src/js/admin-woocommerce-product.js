/* global _ */
( function( $ ) {

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
}( jQuery ) );
