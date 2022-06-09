<?php

/**
 * Planet Smart City functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Planet_Smart_City
 * @since Planet Smart City 1.0
 */

if (!function_exists('planetsmartcity_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Planet Smart City 1.0
	 *
	 * @return void
	 */
	function planet_smart_city_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain('planetsmartcity', get_template_directory() . '/languages');

		/*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
		add_theme_support('title-tag');

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			array(
				'gallery',
				'image',
			)
		);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');
		set_post_thumbnail_size(1568, 9999); /* TODO */

		register_nav_menus(
			array(
				'primary' => esc_html__('Primary menu', 'planetsmartcity'),
				'footer'  => esc_html__('Secondary menu', 'planetsmartcity'),
			)
		);
	}
endif;

add_action('after_setup_theme', 'planetsmartcity_setup');

if (!function_exists('planetsmartcity_styles')) :

	/**
	 * Enqueue styles.
	 *
	 * @since Planet Smart City 1.0
	 *
	 * @return void
	 */
	function planetsmartcity_styles()
	{
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get('Version');

		$version_string = is_string($theme_version) ? $theme_version : false;
		wp_register_style(
			'planetsmartcity-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Add styles inline.
		wp_add_inline_style('planetsmartcity-style', planetsmartcity_get_font_face_styles());

		// Enqueue theme stylesheet.
		wp_enqueue_style('planetsmartcity-style');
	}

endif;

add_action('wp_enqueue_scripts', 'planetsmartcity_styles');

if (!function_exists('planetsmartcity_get_font_face_styles')) :

	/**
	 * Get font face styles.
	 * Called by functions planetsmartcity_styles() above.
	 *
	 * @since Planet Smart City 1.0
	 *
	 * @return string
	 */
	function planetsmartcity_get_font_face_styles()
	{

		return "
		/*
		 * Font Face Styles
		 * 
		 * This is where you can add your own font face styles.
		 * Resources: https://google-webfonts-helper.herokuapp.com/fonts/exo-2?subsets=latin
		 *
		/* exo-2-regular - latin */
		@font-face {
			font-display: swap;
			font-family: 'Exo 2';
			font-stretch: normal;
			font-style: normal;
			font-weight: 400;
			src: url('" . get_theme_file_uri('assets/fonts/exo-2-v19-latin-regular.woff2') . "') format('woff');
		}
		/* exo-2-600 - latin */
		@font-face {
			font-display: swap;
			font-family: 'Exo 2';
			font-stretch: normal;
			font-style: normal;
			font-weight: 600;
			src: url('" . get_theme_file_uri('assets/fonts/exo-2-v19-latin-600.woff2') . "') format('woff2');
		}
		";
	}

endif;

if (!function_exists('planetsmartcity_preload_webfonts')) :

	/**
	 * Preload the main web fonts to improve performance.
	 *
	 * Only the main web fonts (font-weight: 400 and 600) are preloaded here since that fonts are always relevant.
	 *
	 * @since Planet Smart City 1.0
	 *
	 * @return void
	 */
	function planetsmartcity_preload_webfonts()
	{
?>
		<link rel="preload" href="<?php echo esc_url(get_theme_file_uri('assets/fonts/exo-2-v19-latin-regular.woff2')); ?>" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="<?php echo esc_url(get_theme_file_uri('assets/fonts/exo-2-v19-latin-600.woff2')); ?>" as="font" type="font/woff2" crossorigin>
<?php
	}

	add_action('wp_head', 'planetsmartcity_preload_webfonts');

endif;

if (!function_exists('planetsmartcity_disable_emojis')) :

	/**
	 * Disable the emoji's on the site.
	 * 
	 * @since Planet Smart City 1.0
	 */
	function planetsmartcity_disable_emojis()
	{
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

		// Remove from TinyMCE
		add_filter('tiny_mce_plugins', 'planetsmartcity_disable_emojis_tinymce');
	}
	add_action('init', 'planetsmartcity_disable_emojis');

	/**
	 * Filter out the tinymce emoji plugin.
	 * 
	 * @since Planet Smart City 1.0
	 */
	function planetsmartcity_disable_emojis_tinymce($plugins)
	{
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}

endif;

if (!function_exists('planetsmartcity_remove_wp_block_library_css')) :

	/**
	 * Remove Gutenberg Block Library CSS from loading on the frontend
	 */
	function planetsmartcity_remove_wp_block_library_css()
	{
		wp_dequeue_style('wp-block-library');
		wp_dequeue_style('wp-block-library-theme');
		wp_dequeue_style('wc-blocks-style'); // Remove WooCommerce block styles
		wp_dequeue_style('global-styles'); // Remove Global Styles
	}
	add_action('wp_enqueue_scripts', 'planetsmartcity_remove_wp_block_library_css', 100);

endif;
