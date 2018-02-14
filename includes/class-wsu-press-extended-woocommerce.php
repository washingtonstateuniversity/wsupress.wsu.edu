<?php

class WSU_Press_Extended_WooCommerce {
	/**
	 * @since 0.0.12
	 *
	 * @var WSU_Press_Extended_WooCommerce
	 */
	private static $instance;

	/**
	 * A list of post meta keys associated with a product.
	 *
	 * @since 0.0.12
	 *
	 * @var array
	 */
	public $post_meta_keys = array(
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
		'_wsu_press_product_short_quotes' => array(
			'description' => 'Recognition',
			'type' => 'string',
			'sanitize_callback' => 'wp_kses_post',
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
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_product', array( $this, 'save_product' ), 10, 2 );
		add_filter( 'woocommerce_product_tabs', array( $this, 'short_quotes_tab' ) );
		add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'filter_shipping_label' ), 10, 2 );
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
	 * Enqueue scripts and styles used in the admin.
	 *
	 * @since 0.0.12
	 *
	 * @param string $hook_suffix
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {
		if ( 'product' !== get_current_screen()->post_type ) {
			return;
		}

		if ( in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			wp_enqueue_style( 'wsu-press-product', get_stylesheet_directory_uri() . '/admin-css/woocommerce-product.css' );
			wp_enqueue_script( 'wsu-press-product', get_stylesheet_directory_uri() . '/js/admin-woocommerce-product.min.js', array( 'jquery', 'underscore', 'jquery-ui-autocomplete' ), '', true );
			wp_localize_script( 'wsu-press-product', 'wsu_press_authors', $this->autocomplete_authors() );
		}

		if ( 'edit.php' === $hook_suffix ) {
			wp_enqueue_style( 'wsu-press-product-list-table', get_stylesheet_directory_uri() . '/admin-css/woocommerce-product-list-table.css' );
		}
	}

	/**
	 * Return a list of authors.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	public function autocomplete_authors() {
		$authors = get_terms( array(
			'taxonomy' => 'product-author',
			'hide_empty' => false,
			'fields' => 'names',
		) );

		if ( ! empty( $authors ) && ! is_wp_error( $authors ) ) {
			return $authors;
		} else {
			return array();
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
		$authors = get_the_terms( $post->ID, 'product-author' );
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
					   class="widefat wsu-press-attribution-input"
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
							   class="wsu-press-attribution-input"
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

			<div class="wsu-press-product-authors">
				<?php
				if ( $authors && ! is_wp_error( $authors ) ) {
					foreach ( $authors as $term ) {
						?><input type="hidden" name="_wsu_press_author[]" value="<?php echo esc_attr( $term->name ); ?>" /><?php
					}
				}
				?>
			</div>

		</div>

		<script type="text/template" id="wsu-press-product-attribution-template">
			<p class="wsu-press-product-field-wrapper wsu-press-product-attribution">
				<label for="wsu-press-product-attribution-<%= number %>">Additional Attribution</label>
				<input type="text"
					   class="wsu-press-attribution-input"
					   name="wsu_press_product_attribution[]"
					   id="wsu-press-product-attribution-<%= number %>"
					   value=""
					   spellcheck="true"
					   autocomplete="off" />
				<button class="button remove-attribution" type="button">Remove</button>
			</p>
		</script>

		<script type="text/template" id="wsu-press-product-author-template">
			<input type="hidden" name="_wsu_press_author[]" value="<%= value %>" />
		</script>

		<?php
		// Add a metabox context.
		$test = do_meta_boxes( get_current_screen(), 'wsu_press_product', $post );
	}

	/**
	 * Add the meta box used to capture short quotes.
	 *
	 * @since 0.1.0
	 *
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {
		if ( 'product' !== $post_type ) {
			return;
		}

		add_meta_box(
			'wsu-press-short-quotes',
			'Recognition',
			array( $this, 'display_short_quotes_meta_box' ),
			null,
			'normal',
			'high'
		);
	}

	/**
	 * Captures short quotes.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Post $post
	 */
	public function display_short_quotes_meta_box( $post ) {
		$value = get_post_meta( $post->ID, '_wsu_press_product_short_quotes', true );

		$wp_editor_settings = array(
			'textarea_rows' => 8,
		);

		wp_editor( $value, '_wsu_press_product_short_quotes', $wp_editor_settings );
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

		// Add author terms.
		if ( isset( $_POST['_wsu_press_author'] ) && is_array( $_POST['_wsu_press_author'] ) ) {
			$author_meta = get_post_meta( $post_id, 'wsu_press_product_author', true );
			$attribution_meta = get_post_meta( $post_id, 'wsu_press_product_attribution', true );
			$attribution = ( $attribution_meta ) ? $author_meta . implode( $attribution_meta ) : $author_meta;
			$authors = array();

			foreach ( $_POST['_wsu_press_author'] as $author ) {
				if ( false !== strpos( $attribution, $author ) ) {
					$authors[] = $author;
				}
			}

			wp_set_object_terms( $post_id, $authors, 'product-author' );
		}
	}

	/**
	 * Adds a tab for short quotes.
	 *
	 * @since 0.1.0
	 *
	 * @param array $tabs
	 *
	 * @return array
	 */
	public function short_quotes_tab( $tabs ) {
		if ( get_post_meta( get_the_ID(), '_wsu_press_product_short_quotes', true ) ) {
			$tabs['short_quotes'] = array(
				'title' => 'Recognition',
				'priority' => 11,
				'callback' => array( $this, 'display_short_quotes_panel' ),
			);
		}

		return $tabs;
	}

	/**
	 * Displays the short quotes panel.
	 *
	 * @since 0.1.0
	 */
	public function display_short_quotes_panel() {
		?><h2>Recognition</h2><?php
		$short_quotes = get_post_meta( get_the_ID(), '_wsu_press_product_short_quotes', true );
		echo wp_kses_post( apply_filters( 'the_content', $short_quotes ) );
	}

	/**
	 * Removes the shipping rate label.
	 *
	 * @since 0.1.7
	 */
	public function filter_shipping_label( $label, $method ) {
		return str_replace( $method->label . ': ', '', $label );
	}
}
