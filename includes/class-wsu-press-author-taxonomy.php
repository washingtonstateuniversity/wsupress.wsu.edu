<?php

class WSU_Press_Author_Taxonomy {
	/**
	 * @since 0.1.0
	 *
	 * @var WSU_Press_Author_Taxonomy
	 */
	private static $instance;

	/**
	 * The slug used to register the authors taxonomy.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	public $taxonomy_slug = 'product-author';

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.0.12
	 *
	 * @return \WSU_Press_Author_Taxonomy
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSU_Press_Author_Taxonomy();
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
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_filter( 'parent_file', array( $this, 'parent_file' ), 999 );
		add_action( 'add_meta_boxes', array( $this, 'remove_author_box' ), 99 );
	}

	/**
	 * Registers the author taxonomy.
	 *
	 * @since 0.1.0
	 */
	public function register_taxonomy() {
		$labels = array(
			'name' => 'Authors',
			'singular_name' => 'Author',
			'search_items' => 'Search Authors',
			'all_items' => 'All Authors',
			'edit_item' => 'Edit Author',
			'update_item' => 'Update Author',
			'add_new_item' => 'Add New Author',
			'new_item_name' => 'New Author',
			'separate_items_with_commas' => 'Separate authors with commas',
			'add_or_remove_items' => 'Add or remove authors',
			'not_found' => 'No authors found',
			'no_terms' => 'No authors',
		);

		$args = array(
			'labels' => $labels,
			'description' => 'Authors associated with WooCommerce products.',
			'public' => true,
			'hierarchical' => false,
			'show_ui' => true,
			'show_in_menu' => false,
			'show_in_nav_menus' => false,
		);

		register_taxonomy( $this->taxonomy_slug, array( 'product' ), $args );
	}

	/**
	 * Adds the authors page as a top-level menu item.
	 *
	 * @since 0.1.0
	 */
	public function menu_page( $parent_file ) {
		add_menu_page(
			'Authors',
			'Authors',
			'edit_posts',
			"edit-tags.php?taxonomy={$this->taxonomy_slug}&post_type=product",
			'',
			'dashicons-welcome-write-blog',
			56
		);
	}

	/**
	 * Sets the authors page as active.
	 *
	 * @since 0.1.0
	 *
	 * @param string $parent_file
	 *
	 * @return string
	 */
	public function parent_file( $parent_file ) {
		$screen = get_current_screen();

		if ( 'edit-product-author' === $screen->id ) {
			$parent_file = 'edit-tags.php?taxonomy=product-author&post_type=product';
		}

		return $parent_file;
	}

	/**
	 * Removes the authors taxonomy box from the WooCommerce product screen.
	 * This data is managed via custom input.
	 *
	 * @since 0.1.0
	 *
	 * @param string $post_type
	 */
	public function remove_author_box( $post_type ) {
		if ( 'product' !== $post_type ) {
			return;
		}

		remove_meta_box( 'tagsdiv-product-author', 'product', 'side' );
	}
}
