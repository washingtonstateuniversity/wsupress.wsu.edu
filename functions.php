<?php

require_once dirname( __FILE__ ) . '/includes/class-wsu-press-extended-woocommerce.php';
require_once dirname( __FILE__ ) . '/includes/class-wsu-press-author-taxonomy.php';
require_once dirname( __FILE__ ) . '/includes/class-wsu-press-slideshow-shortcode.php';
require_once dirname( __FILE__ ) . '/includes/class-wsu-press-header-link-widget.php';
require_once dirname( __FILE__ ) . '/includes/search.php';

add_filter( 'spine_child_theme_version', 'wsu_press_theme_version' );
/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.1.0
 */
function wsu_press_theme_version() {
	return '0.3.2';
}

add_action( 'after_setup_theme', 'WSU_Press_Extended_WooCommerce' );
/**
 * Starts the class for extending WooCommerce products.
 *
 * @since 0.0.12
 *
 * @return \WSU_Press_Extended_WooCommerce
 */
function WSU_Press_Extended_WooCommerce() {
	return WSU_Press_Extended_WooCommerce::get_instance();
}

add_action( 'after_setup_theme', 'WSU_Press_Author_Taxonomy' );
/**
 * Starts the WSU Press authors functionality.
 *
 * @since 0.1.0
 *
 * @return \WSU_Press_Author_Taxonomy
 */
function WSU_Press_Author_Taxonomy() {
	return WSU_Press_Author_Taxonomy::get_instance();
}

add_action( 'after_setup_theme', 'WSU_Press_Slideshow_Shortcode' );
/**
 * Starts the WSU Press Slideshow shortcode.
 *
 * @since 0.1.0
 *
 * @return \WSU_Press_Slideshow_Shortcode
 */
function WSU_Press_Slideshow_Shortcode() {
	return WSU_Press_Slideshow_Shortcode::get_instance();
}

add_action( 'after_setup_theme', 'woocommerce_support' );
/**
 * Declares WooCommerce support.
 *
 * @since 0.0.6
 */
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}

/**
 * Links product authors to their term pages.
 *
 * @since 0.1.0
 *
 * @param string $meta
 * @param mixed  $authors
 *
 * @return string
 */
function link_wsu_press_authors( $meta, $authors ) {
	if ( $authors && ! is_wp_error( $authors ) ) {
		foreach ( $authors as $term ) {
			$link = '<a href="' . esc_url( get_term_link( $term->slug, 'product-author' ) ) . '">' . esc_html( $term->name ) . '</a>';
			$meta = str_replace( $term->name, $link, $meta );
		}
	}

	return $meta;
}

add_action( 'widgets_init', 'register_wsu_press_header_sidebar' );
/**
 * Registers the widget and sidebar to be used for the header links.
 *
 * @since 0.1.3
 */
function register_wsu_press_header_sidebar() {
	register_widget( 'WSU_Press_Header_Link_Widget' );

	$header_args = array(
		'name' => 'Site Header Links',
		'id' => 'site-header',
	);

	register_sidebar( $header_args );
}

add_filter( 'woocommerce_format_dimensions', 'wsu_press_format_dimensions', 10, 2 );
/**
 * Formats product dimensions.
 *
 * @since 0.1.3
 */
function wsu_press_format_dimensions( $dimension_string, $dimensions ) {
	$reordered_dimensions = array(
		$dimensions['width'],
		$dimensions['length'],
		$dimensions['height'],
	);

	$dimension_string = implode( ' x ', array_filter( array_map( 'wc_format_localized_decimal', $reordered_dimensions ) ) );

	if ( ! empty( $dimension_string ) ) {
		$dimension_string .= ' ' . get_option( 'woocommerce_dimension_unit' );
	} else {
		$dimension_string = __( 'N/A', 'woocommerce' );
	}

	return $dimension_string;
}

add_filter( 'woocommerce_product_add_to_cart_text', 'wsu_press_add_to_cart_text' );
/**
 * Filters the "Add to Cart" button text.
 *
 * @since 0.1.9
 */
function wsu_press_add_to_cart_text( $main_header_elements ) {
	return __( 'Details', 'woocommerce' );
}

/**
 * Wraps product thumbnails in a span tag.
 *
 * @since 0.1.9
 */
function woocommerce_template_loop_product_thumbnail() {
	echo '<span class="product-image-wrapper">' . woocommerce_get_product_thumbnail() . '</span>'; // @codingStandardsIgnoreLine (Choosing to trust WooCommerce)
}

add_filter( 'woocommerce_get_availability_text', 'themeprefix_change_soldout', 10, 2 );

/**
* Change Sold Out Text to Something Else
*/
function themeprefix_change_soldout ( $text, $product) {
	if ( !$product->is_in_stock() ) {
		$text = '<div class="">Coming Soon.</div>';
	}
	return $text;
}


