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
?>
<div class="dc-haslayout dc-jobpostedholder">
	<?php 
		get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); 
		get_template_part('directory/front-end/templates/dashboard', 'statistics-saved-items');
		get_template_part('directory/front-end/templates/dashboard', 'manage-team');
		get_template_part('directory/front-end/templates/dashboard', 'manage-specilities-services'); 
	?>
</div>