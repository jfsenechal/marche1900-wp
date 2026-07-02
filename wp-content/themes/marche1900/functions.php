<?php
/**
 * Marche 1900 block theme — setup and asset loading.
 *
 * @package Marche1900
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'marche1900_setup' ) ) :
	/**
	 * Theme setup. Most configuration lives in theme.json; this covers the rest.
	 */
	function marche1900_setup() {
		load_theme_textdomain( 'marche1900', get_template_directory() . '/languages' );

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'wp-block-styles' );

		// Mirror the front-end stylesheet inside the block editor.
		add_editor_style( 'assets/css/marche1900.css' );
	}
endif;
add_action( 'after_setup_theme', 'marche1900_setup' );

/**
 * Enqueue front-end styles.
 *
 * Block themes do not auto-enqueue style.css, and a few interaction states
 * (focus-visible rings, the navigation bar's hover/active treatment) need real
 * CSS that theme.json cannot express.
 */
function marche1900_enqueue_assets() {
	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' ) ? $theme->get( 'Version' ) : '1.0.0';

	wp_enqueue_style(
		'marche1900-style',
		get_stylesheet_uri(),
		array(),
		$version
	);

	wp_enqueue_style(
		'marche1900-app',
		get_theme_file_uri( 'assets/css/marche1900.css' ),
		array( 'marche1900-style' ),
		$version
	);
}
add_action( 'wp_enqueue_scripts', 'marche1900_enqueue_assets' );

/**
 * Register a pattern category so theme patterns group together in the inserter.
 */
function marche1900_register_pattern_categories() {
	register_block_pattern_category(
		'marche1900',
		array( 'label' => __( 'Marche 1900', 'marche1900' ) )
	);
}
add_action( 'init', 'marche1900_register_pattern_categories' );

/**
 * Register the signature "engraved sign" heading style — a centered title with
 * a short sage rule beneath it, the theme's most recognizable brand device.
 */
function marche1900_register_block_styles() {
	register_block_style(
		'core/heading',
		array(
			'name'  => 'engraved-sign',
			'label' => __( 'Engraved sign', 'marche1900' ),
		)
	);
}
add_action( 'init', 'marche1900_register_block_styles' );
