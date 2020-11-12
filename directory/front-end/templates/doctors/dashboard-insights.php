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
$payment_type	= doctreat_theme_option('payment_type');
$listing_type	= doctreat_theme_option('listing_type');
?>
<div class="dc-haslayout dc-jobpostedholder">
<?php
	get_template_part('directory/front-end/templates/dashboard', 'statistics-messages'); 
	if( !empty($listing_type) && $listing_type ==='paid' ){
		get_template_part('directory/front-end/templates/dashboard', 'statistics-package-expiry');
	}
	get_template_part('directory/front-end/templates/dashboard', 'statistics-saved-items'); 
	get_template_part('directory/front-end/templates/doctors/dashboard', 'invoices');
	get_template_part('directory/front-end/templates/doctors/dashboard', 'submit-articles');
	get_template_part('directory/front-end/templates/doctors/dashboard', 'posted-articles');

	if( !empty($payment_type) && $payment_type ==='online' ){
		get_template_part('directory/front-end/templates/dashboard', 'statistics-available-balance');
	}
	
	get_template_part('directory/front-end/templates/doctors/dashboard', 'statistics-services');
?>
</div>
<?php	get_template_part('directory/front-end/templates/doctors/dashboard', 'current-appointment-listing');
 		get_template_part('directory/front-end/templates/dashboard', 'package-detail');