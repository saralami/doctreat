<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wp_roles, $userdata, $post;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$url_identity 	 = $current_user->ID;
if( apply_filters('doctreat_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
?>
<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='listing' ? 'dc-active' : ''); ?>">
	<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('appointment', $url_identity,'','listing'); ?>">
		<i class="lnr lnr-clock"></i>
		<span><?php esc_html_e('Appointment List','doctreat');?></span>
	</a>
</li>
<?php }?>