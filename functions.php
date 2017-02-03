<?php

/**
 * Remove additional page templates from the drop-down menu when editing pages.
 */
function tfc_remove_page_templates( $templates ) {
	unset( $templates['templates/halves.php'] );
	unset( $templates['templates/margin-left.php'] );
	unset( $templates['templates/margin-right.php'] );
	unset( $templates['templates/side-left.php'] );
	unset( $templates['templates/side-right.php'] );
	unset( $templates['templates/single.php'] );
	return $templates;
}
add_filter( 'theme_page_templates', 'tfc_remove_page_templates' );


add_action( 'wp_enqueue_scripts', 'wsu_press_enqueue_scripts' );
// Enqueue custom scripts
function wsu_press_enqueue_scripts() {
	if ( is_front_page() ) {
		wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), spine_get_script_version(), true );
		wp_enqueue_script( 'cycle_carousel', get_stylesheet_directory_uri() . '/js/jquery.cycle2.carousel.min.js', array( 'wsu-cycle' ), false, true );
		wp_enqueue_script( 'cycle_scripts', get_stylesheet_directory_uri() . '/js/cycle.min.js', array( 'cycle_carousel' ), false, true );
	}
}

// Declare WooCommerce Support
add_action( 'after_setup_theme', 'woocommerce_support' );

function woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
 