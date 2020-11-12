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

global $current_user, $theme_settings;

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$url_identity 	= $current_user->ID;
$system_access	= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';
$detail_page	= !empty($system_access) ? 'location-settings' : 'setting';

if( apply_filters('doctreat_is_appointment_allowed', 'dc_bookings', $url_identity) === true ){
?>
<li class="<?php echo esc_attr( $reference === 'appointment' && $mode ==='setting' ? 'dc-active' : ''); ?>">
	<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('appointment', $url_identity,'',$detail_page); ?>">
		<i class="ti-clipboard"></i>
		<span><?php esc_html_e('Appointment Settings','doctreat');?></span>
	</a>
</li>
<?php }