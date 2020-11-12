<?php
/**
 * Theme functions file
 */

/**
 * Enqueue parent theme styles first
 * Replaces previous method using @import
 * <http://codex.wordpress.org/Child_Themes>
 */
if( !function_exists('doctreat_theme_enqueue_styles') ){
	function doctreat_theme_enqueue_styles() {
		$parent_theme_version = wp_get_theme('doctreat');
		$child_theme_version  = wp_get_theme('doctreat-child');

		$styles	= array( 'bootstrap',
						'fontawesome-all',
						'themify-icons',
						'owl-carousel',
						'scrollbar',
						'fullcalendar',
						'chosen',
						'datetimepicker',
						'doctreat-transitions'
					   );

		$parent_style 	= 'doctreat-style';
		wp_enqueue_style( 'doctreat-child-styles', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ),$child_theme_version->get('Version'));
		wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css',$styles,$parent_theme_version->get('Version'));
		wp_enqueue_style('doctreat-responsive', get_template_directory_uri() . '/css/responsive.css', '',$parent_theme_version->get('Version'));

		if (is_page_template('directory/dashboard.php')) {  
			wp_enqueue_style('doctreat-dashboard', get_template_directory_uri() . '/css/dashboard.css', '',$parent_theme_version->get('Version'));
			wp_enqueue_style('doctreat-dbresponsive', get_template_directory_uri() . '/css/dbresponsive.css', '',$parent_theme_version->get('Version'));
		}

		wp_enqueue_style( 'doctreat-child-customization', get_stylesheet_directory_uri() . '/css/all.css', '',$child_theme_version->get('Version'));
	}

	add_action( 'wp_enqueue_scripts', 'doctreat_theme_enqueue_styles' );
}

//load text domain
if( !function_exists('doctreat_load_child_text_domain') ){
	add_action( 'after_setup_theme', 'doctreat_load_child_text_domain');
	function doctreat_load_child_text_domain() {
		load_child_theme_textdomain( 'doctreat-child', get_stylesheet_directory() . '/languages' );
	}
}


require_once ( get_stylesheet_directory() . '/directory/front-end/class-dashboard-menu.php');
require_once ( get_stylesheet_directory() . '/directory/front-end/functions.php');