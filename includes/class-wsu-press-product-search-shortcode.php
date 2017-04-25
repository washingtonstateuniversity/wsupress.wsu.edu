<?php

class WSU_Press_Product_Search_Shortcode {
	/**
	 * @since 0.1.0
	 *
	 * @var WSU_Press_Product_Search_Shortcode
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.1.0
	 *
	 * @return \WSU_Press_Product_Search_Shortcode
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSU_Press_Product_Search_Shortcode();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.1.0
	 */
	public function setup_hooks() {
		add_shortcode( 'wsu_press_product_search', array( $this, 'display_wsu_press_product_search' ) );
		add_filter( 'pre_get_posts', array( $this, 'product_search_pre_get_posts' ) );
	}

	/**
	 * Displays a form for searching WooCommerce products.
	 *
	 * @since 0.1.0
	 */
	public function display_wsu_press_product_search() {
		ob_start();

		?>
		<form class="wsu-press-product-search-form"
			  action="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>"
			  method="get">
			<div>
				<label class="screen-reader-text" for="s">Search for:</label>
				<input type="text" value="" name="s" id="s" />
				<input type="hidden" value="product" name="post_type" />
				<input type="submit" value="Search" />
			</div>
		</form>
		<?php

		$content = ob_get_clean();

		return $content;
	}

	/**
	 * Includes product meta data in searches.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Query $query
	 */
	public function product_search_pre_get_posts( $query ) {
		if ( is_admin() || ! $query->is_search() || ! $query->is_main_query() || get_query_var( 'post_type' ) !== 'product' ) {
			return;
		}

		$search_term = sanitize_text_field( $_GET['s'] ); //@codingStandardsIgnoreLine

		$query->set( 'meta_query', array(
			'relation' => 'OR',
			array(
				'key' => 'wsu_press_product_subtitle',
				'value' => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key' => 'wsu_press_product_author',
				'value' => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key' => '_wsu_press_product_short_quotes',
				'value' => $search_term,
				'compare' => 'LIKE',
			)
		) );
	}
}
