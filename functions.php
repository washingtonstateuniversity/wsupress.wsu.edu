<?php

require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-extended-woocommerce.php' );
require_once( dirname( __FILE__ ) . '/includes/class-wsu-press-author-taxonomy.php' );

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

add_action( 'after_setup_theme', 'woocommerce_support' );
/**
 * Declares WooCommerce support.
 *
 * @since 0.0.6
 */
function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
