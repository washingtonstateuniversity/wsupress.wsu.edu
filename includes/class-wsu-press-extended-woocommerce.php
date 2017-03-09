<?php

class WSU_Press_Extended_WooCommerce {
	/**
	 * @since 0.0.12
	 *
	 * @var WSU_Press_Extended_WooCommerce
	 */
	private static $instance;

	/**
	 * A list of post meta keys associated with a person.
	 *
	 * @since 0.0.12
	 *
	 * @var array
	 */
	var $post_meta_keys = array(
		'wsu_press_product_subtitle' => array(
			'description' => 'Subtitle',
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'wsu_press_product_author' => array(
			'description' => 'Author',
			'type' => 'string',
			'sanitize_callback' => 'sanitize_text_field',
		),
		'wsu_press_product_attribution' => array(
			'description' => 'Additional Attribution',
			'type' => 'array',
			'sanitize_callback' => 'WSUWP_People_Post_Type::sanitize_additional_attribution',
		),
	);

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.12
	 *
	 * @return \WSU_Press_Extended_WooCommerce
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSU_Press_Extended_WooCommerce();
			self::$instance->setup_hooks();
		}
		return self::$instance;
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.0.12
	 */
	public function setup_hooks() {
		add_action( 'init', array( $this, 'register_meta' ) );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'save_post_product', array( $this, 'save_product' ), 10, 2 );
	}

	/**
	 * Register the meta keys used to store additional product information.
	 *
	 * @since 0.0.12
	 */
	public function register_meta() {
		foreach ( $this->post_meta_keys as $key => $args ) {
			$args['single'] = true;
			register_meta( 'post', $key, $args );
		}
	}

	/**
	 * Add inputs below the product title field.
	 *
	 * @since 0.0.12
	 *
	 * @param WP_Post $post Post object.
	 */
	public function edit_form_after_title( $post ) {
		if ( 'product' !== $post->post_type ) {
			return;
		}

		wp_nonce_field( 'save-wsu-press-product-data', '_wsu_press_product_data_nonce' );

		$subtitle = get_post_meta( $post->ID, 'wsu_press_product_subtitle', true );
		$author = get_post_meta( $post->ID, 'wsu_press_product_author', true );
		$attribution = get_post_meta( $post->ID, 'wsu_press_product_attribution', true );
		?>

		<div class="wsu-press-extra-product-fields">

			<p class="wsu-press-product-field-wrapper">
				<label <?php if ( $subtitle ) { echo 'class="screen-reader-text"'; } ?>
					   for="wsu-press-product-subtitle">Subtitle</label>
				<input type="text"
					   class="widefat"
					   name="wsu_press_product_subtitle"
					   id="wsu-press-product-subtitle"
					   value="<?php echo esc_attr( $subtitle ); ?>"
					   spellcheck="true"
					   autocomplete="off" />
			</p>

			<p class="wsu-press-product-field-wrapper">
				<label <?php if ( $author ) { echo 'class="screen-reader-text"'; } ?>
					   for="wsu-press-product-author">Author</label>
				<input type="text"
					   class="widefat"
					   name="wsu_press_product_author"
					   id="wsu-press-product-author"
					   value="<?php echo esc_attr( $author ); ?>"
					   spellcheck="true"
					   autocomplete="off" />
			</p>

			<?php
			if ( $attribution ) {
				foreach ( $attribution as $i => $value ) {
					?>
					<p class="wsu-press-product-field-wrapper wsu-press-product-attribution">
						<label class="screen-reader-text"
							   for="wsu-press-product-attribution-<?php echo esc_attr( $i ); ?>">Additional Attribution</label>
						<input type="text"
							   name="wsu_press_product_attribution[]"
							   id="wsu-press-product-attribution-<?php echo esc_attr( $i ); ?>"
							   value="<?php echo esc_attr( $value ); ?>"
							   spellcheck="true"
							   autocomplete="off" />
						<button class="button remove-attribution" type="button">Remove</button>
					</p>
					<?php
				}
			}
			?>

			<p><a href="#" class="hide-if-no-js wsu-press-product-add-attribution" >+ Add another attribution field</a></p>

		</div>

		<script type="text/template" id="wsu-press-product-attribution-template">
			<p class="wsu-press-product-field-wrapper wsu-press-product-attribution">
				<label for="wsu-press-product-attribution-<%= number %>">Additional Attribution</label>
				<input type="text"
					   name="wsu_press_product_attribution[]"
					   id="wsu-press-product-attribution-<%= number %>"
					   value=""
					   spellcheck="true"
					   autocomplete="off" />
				<button class="button remove-attribution" type="button">Remove</button>
			</p>
		</script>

		<?php
		// Add a metabox context.
		$test = do_meta_boxes( get_current_screen(), 'wsu_press_product', $post );
	}

	/**
	 * Sanitizes additional attribution fields.
	 *
	 * @since 0.0.12
	 *
	 * @param array $values
	 *
	 * @return array|string
	 */
	public static function sanitize_additional_attribution( $values ) {
		if ( ! is_array( $values ) || 0 === count( $values ) ) {
			return '';
		}

		$sanitized_values = array();

		foreach ( $values as $index => $value ) {
			if ( '' !== $value ) {
				$sanitized_values[] = sanitize_text_field( $value );
			}
		}

		return $sanitized_values;
	}

	/**
	 * Save extra data associated with a WooCommerce product.
	 *
	 * @since 0.0.12
	 *
	 * @param int     $post_id
	 * @param WP_Post $post
	 */
	public function save_product( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( 'auto-draft' === $post->post_status ) {
			return;
		}

		if ( ! isset( $_POST['_wsu_press_product_data_nonce'] ) || ! wp_verify_nonce( $_POST['_wsu_press_product_data_nonce'], 'save-wsu-press-product-data' ) ) {
			return;
		}

		$keys = get_registered_meta_keys( 'post' );

		foreach ( $keys as $key => $args ) {
			if ( isset( $_POST[ $key ] ) && isset( $args['sanitize_callback'] ) ) {
				// Each piece of meta is registered with sanitization.
				update_post_meta( $post_id, $key, $_POST[ $key ] );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
	}
}
