<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author     WooThemes
 * @package    WooCommerce/Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$subtitle = get_post_meta( get_the_ID(), 'wsu_press_product_subtitle', true );
$authors = get_the_terms( get_the_ID(), 'product-author' );
$author = get_post_meta( get_the_ID(), 'wsu_press_product_author', true );
$attributions = get_post_meta( get_the_ID(), 'wsu_press_product_attribution', true );
 the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );
 if ( $subtitle ) {
	?>
	<h2 class="wsu-press-product-subtitle"><?php echo esc_html( $subtitle ); ?></h2>
	<?php
}
 if ( $author ) {
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
