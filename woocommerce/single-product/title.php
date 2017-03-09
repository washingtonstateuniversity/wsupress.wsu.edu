<?php
/**
 * Single Product title
 *
 * Overrides woocommerce/templates/single-product/title.php.
 *
 * @see https://docs.woothemes.com/document/template-structure/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );


if ( $subtitle = get_post_meta( get_the_ID(), 'wsu_press_product_subtitle', true ) ) {
	?>
	<h2 class="wsu-press-product-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
	<?php
}

if ( $author = get_post_meta( get_the_ID(), 'wsu_press_product_author', true ) ) {
	?>
	<p class="wsu-press-product-author"><?php echo esc_html( $author ); ?></p>
	<?php
}

if ( $attribution = get_post_meta( get_the_ID(), 'wsu_press_product_attribution', true ) ) {
	if ( is_array( $attribution ) ) {
		foreach ( $attribution as $attribution ) {
			?>
			<p class="wsu-press-product-extra-attribution"><?php echo esc_html( $attribution ); ?></p>
			<?php
		}
	}
}
