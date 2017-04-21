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

		wp_enqueue_script( 'wsu-cycle', get_template_directory_uri() . '/js/cycle2/jquery.cycle2.min.js', array( 'jquery' ), wsu_press_theme_version(), true );
		wp_enqueue_script( 'cycle_carousel', get_stylesheet_directory_uri() . '/js/jquery.cycle2.carousel.min.js', array( 'wsu-cycle' ), wsu_press_theme_version(), true );
		wp_enqueue_script( 'cycle_options', get_stylesheet_directory_uri() . '/js/cycle2-options.min.js', array( 'cycle_carousel' ), wsu_press_theme_version(), true );

		ob_start();
		?>

		<div class="wsu-press-slideshow">
			<svg class="slideshow-control prev" xmlns="http://www.w3.org/2000/svg" width="11" height="40" viewBox="0 0 10.9 40">
				<path d="M9.9 40c0.1 0 0.3 0 0.4-0.1 0.5-0.2 0.7-0.8 0.5-1.3L2.2 20l8.6-18.6c0.2-0.5 0-1.1-0.5-1.3C9.8-0.1 9.2 0.1 9 0.6L0 20l9 19.4C9.2 39.8 9.5 40 9.9 40z"/>
			</svg>
			<svg class="slideshow-control next" xmlns="http://www.w3.org/2000/svg" width="11" height="40" viewBox="0 0 10.9 40">
				<path d="M1 40c-0.1 0-0.3 0-0.4-0.1 -0.5-0.2-0.7-0.8-0.5-1.3L8.7 20 0.1 1.4C-0.1 0.9 0.1 0.3 0.6 0.1 1.1-0.1 1.7 0.1 1.9 0.6L10.9 20 1.9 39.4C1.7 39.8 1.4 40 1 40z"/>
			</svg>

		<?php
		while ( $query->have_posts() ) {
			$query->the_post();

			if ( ! get_the_post_thumbnail() ) {
				continue;
			}

			$subtitle = get_post_meta( get_the_ID(), 'wsu_press_product_subtitle', true );
			$author = get_post_meta( get_the_ID(), 'wsu_press_product_author', true );
			?>
			<article class="slideshow-item">

				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'shop_thumbnail' ); ?>
				</a>

				<div class="slideshow-item-about">

					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

					<?php if ( $subtitle ) { ?>
					<p class="subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php } ?>

					<?php if ( $author ) { ?>
					<p class="author"><?php echo esc_html( $author ); ?></p>
					<?php } ?>

				</div>

			</article>
			<?php
		}
		?>

		</div>

		<?php

		$content = ob_get_clean();

		return $content;
	}
}
