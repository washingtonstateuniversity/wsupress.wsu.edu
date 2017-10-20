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

				<div class="column one">
					<?php

					if ( empty( $search_results ) ) {
						?><h2>No search results found.</h2><?php
					}
					foreach ( $search_results as $search_result ) {
						?>
						<article>
						<h2><a href="<?php echo esc_url( $search_result->_source->url ); ?>"><?php echo esc_html( $search_result->_source->title ); ?></a></h2>
						<span class="visible-url"><?php echo esc_url( $search_result->_source->url ); ?></span>
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