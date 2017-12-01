<?php /* Template Name: Search */

$search_results = WSU\Press\Search\get_elastic_response( get_query_var( 'q' ) );

get_header();

?>

	<main class="spine-blank-template">

		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

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

					if ( empty( $search_results ) ) {
						?><h2>No search results found.</h2><?php
					}
					foreach ( $search_results as $search_result ) {

						$result_post = get_page_by_path( basename( $search_result->_source->url ), OBJECT, $search_result->_source->post_type );
						?>
						<article class="card card--result">

							<?php if ( $result_post && has_post_thumbnail( $result_post->ID ) ) { ?>
							<figure class="card-image">
								<a href="<?php echo esc_url( $search_result->_source->url ); ?>">
									<?php echo get_the_post_thumbnail( $result_post->ID, array( 200, 300 ) ); ?>
								</a>
							</figure>
							<?php } ?>

							<h2><a href="<?php echo esc_url( $search_result->_source->url ); ?>"><?php echo esc_html( $search_result->_source->title ); ?></a></h2>

							<div class="visible-content">
								<?php
								$visible_content = WSU\Press\Search\filter_elastic_content( $search_result->_source->content );

								echo wp_kses_post( $visible_content );
								?>
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
