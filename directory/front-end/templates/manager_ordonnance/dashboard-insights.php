<?php
/**
 *
 * The template part for displaying the dashboard.
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
//global $current_user;
global $current_user, $wp_roles, $userdata, $post,$theme_settings;
get_header();
$user_identity 	= $current_user->ID;
$url_identity 	= !empty($_GET['identity']) ? intval($_GET['identity']) : '';
$user_type		= apply_filters('doctreat_get_user_type', $user_identity );
$post_id		= doctreat_get_linked_profile_id( $user_identity );
$is_verified 	= get_post_meta($post_id, '_is_verified', true);
//$ref 			= !empty($_GET['ref']) ? esc_html( $_GET['ref'] ) : '';
//$mode 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : '';
//$verify_user	= !empty( $theme_settings['verify_user'] ) ? $theme_settings['verify_user'] : '';
//$system_access	= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';
var_dump($is_verified);


?>
<div class="dc-haslayout dc-jobpostedholder">
	<?php 
		//get_template_part('directory/front-end/templates/manager_ordonnance/dashboard', 'insights'); 
		//get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); 
		//get_template_part('directory/front-end/templates/dashboard', 'statistics-saved-items');
		//get_template_part('directory/front-end/templates/dashboard', 'manage-team');
		//get_template_part('directory/front-end/templates/dashboard', 'manage-specilities-services'); 
	?>
</div>