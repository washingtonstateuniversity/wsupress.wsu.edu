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

?>
<header class="main-header">

	<div id="header-links">
		<ul>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>product-category/all-titles/">Browse Books</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>news/">News</a></li>
			<li><a href="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>events/">Calendar</a></li>
		</ul>
	</div>

	<div id="press-header">

		<div id="press-logo">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/wsu-press-lg.png' ); ?>"
				 alt="<?php echo esc_html( $spine_main_header_values['site_name'] ); ?>" />
		</div>

		<div id="press-slogan"><?php echo esc_html( $spine_main_header_values['site_tagline'] ); ?></div>

	</div>
</header>
