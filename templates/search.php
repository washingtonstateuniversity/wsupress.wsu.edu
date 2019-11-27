<?php /* Template Name: Search */

require_once get_stylesheet_directory() . '/classes/class-press-product-search.php';

$products = Press_Product_Search::get_products( get_query_var( 'q' ) );

//$search_results = WSU\Press\Search\get_elastic_response( get_query_var( 'q' ) );

get_header();

?>

	<main class="spine-blank-template">

	<?php
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			?>

			<?php get_template_part( 'parts/headers' ); ?>

			<section class="row side-right gutter pad-top search-results-header">
				<header>
					<h1>Search Results</h1>
				</header>
				<div class="column one">
					<?php get_search_form(); ?>
				</div>
				<div class="column two"></div>
			</section>
			<section class="row side-right gutter pad-ends search-results-container">

				<div class="column one deck deck--results">
					<?php

					if ( empty( $products ) && '' !== get_query_var( 'q' ) ) {
						?><h2>No search results found.</h2><?php
					}

					foreach ( $products as $product ) { ?>
						<article class="card card--result">

							<?php if ( ! empty( $product['img'] ) ) { ?>
							<figure class="card-image">
								<a href="<?php echo esc_url( $product['link'] ); ?>">
									<?php echo get_the_post_thumbnail( $product['id'], array( 200, 300 ) ); ?>
								</a>
							</figure>
							<?php } ?>

							<h2><a href="<?php echo esc_url( $product['link'] ); ?>"><?php echo esc_html( $product['title'] ); ?></a></h2>

							<div class="visible-content">
							<span class="press-author"><?php echo wp_kses_post( $product['author'] ); ?></span>
							<?php echo esc_html( $product['excerpt'] ); ?><br />
							</div>

						</article><?php
					}
					?>
				</div><!--/column-->

				<div class="column two">

				</div>

			</section>
			<?php
		endwhile;
	endif;

	get_template_part( 'parts/footers' );

	?>
	</main>
<?php get_footer();
