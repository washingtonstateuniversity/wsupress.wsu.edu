<?php

/**
 * Class to handle product searches for woocommerce
 *
 * @since 1.0.0
 *
 * @uses: WP_Query
*/
class Press_Product_Search {


	/**
	 * Description
	 *
	 * @since 1.0.0
	 *
	 * @param string $term Search term.
	 *
	 * @return array Product.
	*/
	public static function get_products( $term ) {

		$products = array();

		$args = array(
			'posts_per_page' => 20,
			's'              => $term,
			'post_type' => 'product',
		);

		$the_query = new WP_Query( $args );

		// The Loop
		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();


				$authors = get_the_terms( get_the_ID(), 'product-author' );
				$author = get_post_meta( get_the_ID(), 'wsu_press_product_author', true );
				$author = link_wsu_press_authors( $author, $authors );

				$product = array(
					'id'      => get_the_ID(),
					'img'     => get_the_post_thumbnail_url( get_the_ID(), 'thumbnail' ),
					'title'   => get_the_title(),
					'link'    => get_permalink(),
					'excerpt' => wp_trim_words( wp_strip_all_tags( get_the_excerpt() ), 40 ),
					'author'  => $author,
				);

				$products[] = $product;

			}
		}

		/* Restore original Post Data */
		wp_reset_postdata();

		return $products;

	} // End get_products

}
