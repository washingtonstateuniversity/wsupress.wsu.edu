<?php

namespace WSU\Press\Search;

add_filter( 'query_vars', 'WSU\Press\Search\filter_query_variable' );
add_action( 'template_redirect', 'WSU\Press\Search\redirect_wp_default_search' );
add_filter( 'wsuwp_search_post_types', 'WSU\Press\Search\filter_post_types' );
add_filter( 'wsuwp_search_post_data', 'WSU\Press\Search\search_data', 10, 2 );

/**
 * Redirect requests to the default WordPress search to our new URL.
 *
 * @since 0.2.0
 */
function redirect_wp_default_search() {
	if ( is_search() ) {
		wp_redirect( home_url( '/search/?q=' . get_Query_var( 's' ) ) );
		exit;
	}
}

/**
 * Adds `q` as our search query variable.
 *
 * @since 0.2.0
 *
 * @param $vars
 *
 * @return array
 */
function filter_query_variable( $vars ) {
	$vars[] = 'q';
	return $vars;
}

/**
 * Filters the content returned by Elastic Search for display in a search
 * results page.
 *
 * @since 0.2.0
 *
 * @param string $visible_content
 *
 * @return mixed|string
 */
function filter_elastic_content( $visible_content ) {
	$visible_content = preg_replace( '/[\r\n]+/', "\n", $visible_content );
	$visible_content = preg_replace( '/[ \t]+/', ' ', $visible_content );
	$visible_content = strip_tags( $visible_content, '<p><strong><em>' );
	$visible_content = trim( $visible_content );
	$visible_content = substr( $visible_content, 0, 260 );
	$visible_content = force_balance_tags( $visible_content . '....' );
	$visible_content = wpautop( $visible_content, false );

	return $visible_content;
}

/**
 * Processes a search request by passing to the WSU ES server.
 *
 * @since 0.2.0
 *
 * @param string $var
 *
 * @return array
 */
function get_elastic_response( $var ) {
	if ( '' === trim( $var ) ) {
		return array();
	}

	$search_key = md5( 'search' . $var );
	$results = wp_cache_get( $search_key, 'search' );

	if ( $results ) {
		return $results;
	}

	$request_url = 'https://elastic.wsu.edu/wsu-web/_search?q=%2bhostname:wsupress.wsu.edu%20%2b' . rawurlencode( $var );

	$response = wp_remote_get( $request_url, array( 'sslverify' => false ) );

	if ( is_wp_error( $response ) ) {
		return array();
	}

	if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return array();
	}

	$response = wp_remote_retrieve_body( $response );
	$response = json_decode( $response );

	if ( isset( $response->hits ) && isset( $response->hits->total ) && 0 === $response->hits->total ) {
		return array(); // no results found.
	}

	$search_results = $response->hits->hits;

	wp_cache_set( $search_key, $search_results, 'search', 3600 );

	return $search_results;
}

/**
 * Add the WooCommerce product post type to those supported by the WSUWP search plugin.
 *
 * @since 0.2.0
 *
 * @param array $post_types
 *
 * @return array
 */
function filter_post_types( $post_types ) {
	if ( ! in_array( 'product', $post_types, true ) ) {
		$post_types[] = 'product';
	}

	return $post_types;
}

/**
 * Filter the data sent to Elasticsearch for a product record.
 *
 * @since 0.3.0
 *
 * @param array    $data The data being sent to Elasticsearch.
 * @param \WP_Post $post The full post object.
 *
 * @return array Modified list of data to send to Elasticsearch.
 */
function search_data( $data, $post ) {
	if ( 'product' !== $post->post_type ) {
		return $data;
	}

	$author = get_post_meta( $post->ID, 'wsu_press_product_author', true );
	$subtitle = get_post_meta( $post->ID, 'wsu_press_product_subtitle', true );

	$data['content'] .= ' <div class="search-result-author">' . esc_attr( $author ) . '</div>';
	$data['content'] .= ' <div class="search-result-subtitle">' . esc_attr( $subtitle ) . '</div>';

	return $data;
}
