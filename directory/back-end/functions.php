<?php
/**
 *
 * Functions
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */


/**
 * @get settings
 * @return {}
 */
if (!function_exists('doctreat_profile_backend_settings')) {
	function  doctreat_profile_backend_settings(){
		if(current_user_can('administrator')) {
			$list	= array(
				'payments'	 	=> 'payments',
			);
			return $list;
		}
		
		return array();
	}
}
