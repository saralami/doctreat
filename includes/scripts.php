<?php

/**
 *
 * General Theme Functions
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
if (!function_exists('doctreat_scripts')) {

    function doctreat_scripts() {
		global $theme_settings;
        $theme_version 	= wp_get_theme('doctreat');
        $google_key 	= '';
		$google_key		= !empty( $theme_settings['google_map'] ) ? $theme_settings['google_map'] : '';
		$script_source	= '/';
        $protocol 		= is_ssl() ? 'https' : 'http';

	
		//Dashboard scripts
		if (is_page_template('directory/dashboard.php')) { 
		
			
			wp_enqueue_script('doctreat-dashboard', get_stylesheet_directory_uri() . '/js'.$script_source.'dashboard.js', array('jquery'), $theme_version->get('Ve
			rsion'), true);
			

		}
				
		

    }

	add_action('wp_enqueue_scripts', 'doctreat_scripts', 88);
}


/**
 * @Enqueue admin scripts and styles.
 * @return{}
 */


/**
 * @Theme Editor/guttenberg Style
 * 
