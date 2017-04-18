<?php

/* Template Name: WooCommerce Single */

get_header(); ?>

	<main id="wsuwp-main" class="woocommerce-main">

		<?php get_template_part( 'parts/headers' ); ?>
		<?php get_template_part( 'parts/featured-images' ); ?>

		<?php while ( have_posts() ) : the_post(); ?>
		<section class="row single gutter pad-top">

			<div class="column one">
				<?php if ( true === spine_get_option( 'articletitle_show' ) ) : ?>
					<header class="article-header">
						<h1 class="article-title"><?php the_title(); ?></h1>
					</header>
				<?php endif; ?>
			</div>
		</section>

			<?php the_content(); ?>

		<?php endwhile; ?>

		<?php get_template_part( 'parts/footers' ); ?>

	</main>

<?php get_footer();
