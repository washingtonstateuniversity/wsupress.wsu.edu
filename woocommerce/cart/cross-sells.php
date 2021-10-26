<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

add_filter( 'woocommerce_loop_add_to_cart_link', 'wsupress_add_to_cart_link', 10, 3 );

function wsupress_add_to_cart_link( $link, $product, $args ) {

	return sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( get_permalink( $product->ID ) ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		esc_html( $product->add_to_cart_text() )
	);

}


defined( 'ABSPATH' ) || exit;
if ( $cross_sells ) : ?>

	<div class="cross-sells">

		<h2><?php esc_html_e( 'You may be interested in&hellip;', 'woocommerce' ); ?></h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $cross_sells as $cross_sell ) : ?>

				<?php
					$post_object = get_post( $cross_sell->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited, Squiz.PHP.DisallowMultipleAssignments.Found
					wc_get_template_part( 'content', 'product' );
				?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</div>
	<?php
endif;

remove_filter( 'woocommerce_loop_add_to_cart_link', 'wsupress_add_to_cart_link', 10, 3  );

wp_reset_postdata();
