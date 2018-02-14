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

$subtitle = get_post_meta( get_the_ID(), 'wsu_press_product_subtitle', true );
$authors = get_the_terms( get_the_ID(), 'product-author' );
$attributions = get_post_meta( get_the_ID(), 'wsu_press_product_attribution', true );

the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );

if ( $subtitle ) {
	?>
	<h2 class="wsu-press-product-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
	<?php
}

if ( $authors ) {
	$author = link_wsu_press_authors( $author, $authors );
	?>
	<p class="wsu-press-product-author"><?php echo wp_kses_post( $author ); ?></p>
	<?php
}

if ( $attributions ) {
	foreach ( $attributions as $attribution ) {
		$attribution = link_wsu_press_authors( $attribution, $authors );
		?>
		<p class="wsu-press-product-extra-attribution"><?php echo wp_kses_post( $attribution ); ?></p>
		<?php
	}
}
