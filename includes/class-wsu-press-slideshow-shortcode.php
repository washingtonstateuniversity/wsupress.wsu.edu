<?php

class WSU_Press_Slideshow_Shortcode {
	/**
	 * @since 0.1.0
	 *
	 * @var WSU_Press_Slideshow_Shortcode
	 */
	private static $instance;

	/**
	 * Maintain and return the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.1.0
	 *
	 * @return \WSU_Press_Slideshow_Shortcode
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSU_Press_Slideshow_Shortcode();
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
		add_shortcode( 'wsu_press_slideshow', array( $this, 'display_wsu_press_slideshow' ) );
	}

	/**
	 * Displays a slideshow.
	 *
	 * @since 0.1.0
	 *
	 * @param array $atts
	 */
	public function display_wsu_press_slideshow( $atts ) {
		$defaults = array(
			'title' => '',
			'count' => 5,
			'product_category_slug' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		$args = array(
			'post_type' => 'product',
			'posts_per_page' => absint( $atts['count'] ),
		);

		if ( $atts['product_category_slug'] ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field' => 'slug',
				'terms' => sanitize_key( $atts['product_category_slug'] ),
			);
		}

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return '';
		}

		wp_enqueue_script( 'wsu-press-slideshow', get_stylesheet_directory_uri() . '/js/slideshow.min.js', array( 'jquery' ), wsu_press_theme_version(), true );

		ob_start();
		?>

		<h2 class="wsu-press-slideshow-heading"
			id="wsu-press-slideshow-heading-<?php echo esc_attr( sanitize_title( $atts['title'] ) ); ?>">
			<?php echo esc_html( $atts['title'] ); ?><span class="screen-reader-text"> Slideshow</span>
		</h2>

		<div class="wsu-press-slideshow"
			 role="region"
			 aria-labelledby="wsu-press-slideshow-heading-<?php echo esc_attr( sanitize_title( $atts['title'] ) ); ?>">

			<div class="wsu-press-slideshow-items">

			<?php
			while ( $query->have_posts() ) {
				$query->the_post();

				if ( ! get_the_post_thumbnail() ) {
					continue;
				}

				$subtitle = get_post_meta( get_the_ID(), 'wsu_press_product_subtitle', true );
				$author = get_post_meta( get_the_ID(), 'wsu_press_product_author', true );
				?>
				<figure aria-hidden="<?php echo ( 0 === $query->current_post ) ? 'false' : 'true'; ?>">

					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'medium' ); ?>
					</a>

					<figcaption>

						<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

						<?php if ( $subtitle ) { ?>
						<p class="subtitle"><?php echo esc_html( $subtitle ); ?></p>
						<?php } ?>

						<?php if ( $author ) { ?>
						<p class="author"><?php echo esc_html( $author ); ?></p>
						<?php } ?>

					</figcaption>

				</figure>
				<?php
			}
			?>

			</div>

		</div>

		<?php

		$content = ob_get_clean();

		return $content;
	}
}
