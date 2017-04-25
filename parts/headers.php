<?php

/**
 * Retrieve an array of values to be used in the header.
 *
 * site_name
 * site_tagline
 * page_title
 * post_title
 * section_title
 * subsection_title
 * posts_page_title
 * sup_header_default
 * sub_header_default
 * sup_header_alternate
 * sub_header_alternate
 */
$spine_main_header_values = spine_get_main_header();

if ( spine_get_option( 'main_header_show' ) === true ) :

?>
<header class="main-header">
	<div class="header-group hgroup guttered padded-bottom short">

		<sup class="sup-header"
			 data-section="<?php echo esc_attr( $spine_main_header_values['section_title'] ); ?>"
			 data-pagetitle="<?php echo esc_attr( $spine_main_header_values['page_title'] ); ?>"
			 data-posttitle="<?php echo esc_attr( $spine_main_header_values['post_title'] ); ?>"
			 data-default="<?php echo esc_html( $spine_main_header_values['sup_header_default'] ); ?>"
			 data-alternate="<?php echo esc_html( $spine_main_header_values['sup_header_alternate'] ); ?>">
				<span class="sup-header-default"><?php echo wp_kses_post( strip_tags( $spine_main_header_values['sup_header_default'], '<a>' ) ); ?></span>
		</sup>
		<sub class="sub-header"
			 data-sitename="<?php echo esc_attr( $spine_main_header_values['site_name'] ); ?>"
			 data-pagetitle="<?php echo esc_attr( $spine_main_header_values['page_title'] ); ?>"
			 data-posttitle="<?php echo esc_attr( $spine_main_header_values['post_title'] ); ?>"
			 data-default="<?php echo esc_html( $spine_main_header_values['sub_header_default'] ); ?>"
			 data-alternate="<?php echo esc_html( $spine_main_header_values['sub_header_alternate'] ); ?>">
				<h1 class="sub-header-default"><?php echo wp_kses_post( strip_tags( $spine_main_header_values['sub_header_default'], '<a>' ) ); ?></h1>
		</sub>

	</div>
	<div id="header-links">
		<ul>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>product-category/all-titles/">Browse Books</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>news/">News</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>events/">Calendar</a></li>
		</ul>
	</div>

	<div id="press-header">

	<div id="press-logo">
	<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/wsu-press-lg.png' ); ?>" />
	</div>

	<div id="press-slogan">
	Connecting curious minds with uncommon, undeniably Northwest reads
	</div>

	</div>
</header>

<?php endif; ?>
