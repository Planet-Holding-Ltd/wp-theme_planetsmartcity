<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Planet Smart City
 * @since Planet Smart City 1.0
 */

get_header();
?>

<main>
	<h1><?php esc_html_e('Nothing here', 'planetsmartcity'); ?></h1>
	<div>
		<p><?php esc_html_e('It looks like nothing was found at this location. Maybe try a search?', 'planetsmartcity'); ?></p>
		<?php get_search_form(); ?>
	</div>
</main>

<?php
get_footer();
