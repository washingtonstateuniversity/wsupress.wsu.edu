<?php
/**
 * Single Product Meta
 *
 * Overrides woocommerce/single-product/meta.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php
	do_action( 'woocommerce_product_meta_start' );

	if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
		$sku = ( $product->get_sku() ) ? $product->get_sku() : 'N/A';
		?>
		<span class="sku_wrapper"><?php esc_html_e( 'SKU/ISBN:', 'woocommerce' ); ?>
			<span class="sku"><?php echo esc_html( $sku ); ?></span>
		</span>
		<?php
	}

	echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); // @codingStandardsIgnoreLine (Choosing to trust WooCommerce)

	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); // @codingStandardsIgnoreLine (Choosing to trust WooCommerce)

	do_action( 'woocommerce_product_meta_end' );
	?>

</div>
