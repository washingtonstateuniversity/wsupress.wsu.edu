<?php

require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-extended-woocommerce.php' );
require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-author-taxonomy.php' );
require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-slideshow-shortcode.php' );
require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-product-search-shortcode.php' );

add_filter( 'spine_child_theme_version', 'wsu_press_theme_version' );
/**
 * Provides a theme version for use in cache busting.
 *
 * @since 0.1.0
 */
function wsu_press_theme_version() {
	return '0.1.0';
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

add_action( 'after_setup_theme', 'WSU_Press_Product_Search_Shortcode' );
/**
 * Starts the WSU Press Product Search shortcode.
 *
 * @since 0.1.0
 *
 * @return \WSU_Press_Product_Search_Shortcode
 */
function WSU_Press_Product_Search_Shortcode() {
	return WSU_Press_Product_Search_Shortcode::get_instance();
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
 * Links product author terms for front end product views.
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
