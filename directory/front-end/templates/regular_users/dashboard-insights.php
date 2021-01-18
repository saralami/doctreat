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
$booking_option	= doctreat_theme_option();
?>
<div class="dc-haslayout dc-jobpostedholder">

	<?php 
	   get_template_part('directory/front-end/templates/regular_users/dashboard', 'info-medical'); 
	   get_template_part('directory/front-end/templates/regular_users/dashboard', 'test-medical'); 
	   
		get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); 
		
		get_template_part('directory/front-end/templates/doctors/dashboard', 'statistics-saved-items');
		if(empty($booking_option)){
			get_template_part('directory/front-end/templates/dashboard', 'statistics-appointments');
			
		}
	?>
</div>