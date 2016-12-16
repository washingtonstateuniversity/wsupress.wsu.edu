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

if ( spine_get_option( 'main_header_show' ) == 'true' ) :

?>
<header class="main-header">
	<div class="header-group hgroup guttered padded-bottom short">

		<sup class="sup-header" data-section="<?php echo $spine_main_header_values['section_title']; ?>" data-pagetitle="<?php echo $spine_main_header_values['page_title']; ?>" data-posttitle="<?php echo $spine_main_header_values['post_title']; ?>" data-default="<?php echo esc_html($spine_main_header_values['sup_header_default']); ?>" data-alternate="<?php echo esc_html($spine_main_header_values['sup_header_alternate']); ?>"><span class="sup-header-default"><?php echo strip_tags( $spine_main_header_values['sup_header_default'], '<a>' ); ?></span></sup>
		<sub class="sub-header" data-sitename="<?php echo $spine_main_header_values['site_name']; ?>" data-pagetitle="<?php echo $spine_main_header_values['page_title']; ?>" data-posttitle="<?php echo $spine_main_header_values['post_title']; ?>" data-default="<?php echo esc_html($spine_main_header_values['sub_header_default']); ?>" data-alternate="<?php echo esc_html($spine_main_header_values['sub_header_alternate']); ?>"><h1 class="sub-header-default"><?php echo strip_tags( $spine_main_header_values['sub_header_default'], '<a>' ); ?></h1></sub>

	</div>
	<div id="header-links"><ul><li><a href="/#/">Browse Books</a></li><li><a href="/#/">News</a></li><li><a href="/#/">Calendar</a></li></ul></div>
	
	<div id="press-header">
	
	<div id="press-logo">
	<img src="https://stage.wsupress.wsu.edu/wp-content/uploads/sites/1175/2016/12/wsu-press-lg.png" />
	</div>
	
	<div id="press-slogan">
	Telling unique, focused stories of the Northwest
	</div>
	
	</div>
</header>

<?php endif; ?>
