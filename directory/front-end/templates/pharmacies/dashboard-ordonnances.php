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
global $current_user;
//$booking_option	= doctreat_theme_option();
//$user_identity = $current_user->ID;
//$user_type = apply_filters('doctreat_get_user_type', $user_identity );
//var_dump($user_type);
?>
<div class="dc-haslayout dc-jobpostedholder">
	<?php 
		get_template_part('directory/front-end/templates/pharmacies/dashboard', 'liste-ordonnances'); 
		//get_template_part('directory/front-end/templates/doctors/dashboard', 'statistics-saved-items');
		//if(empty($booking_option)){
		//	get_template_part('directory/front-end/templates/dashboard', 'statistics-appointments');
		//}
	?>
</div>