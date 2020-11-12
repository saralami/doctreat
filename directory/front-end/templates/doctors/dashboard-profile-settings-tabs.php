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
global $current_user, $wp_roles, $userdata, $post,$theme_settings;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$gallery_option				= !empty($theme_settings['enable_gallery']) ? $theme_settings['enable_gallery'] : '';
$profile_details_url 		= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'settings');
$educations_url	 			= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'educations');
$awardss_url				= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'awards');
$registrations_url			= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'registrations');
$gallery_url				= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'gallery');
$booking_url				= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'booking');
$location_url				= Doctreat_Profile_Menu::Doctreat_profile_menu_link('profile', $user_identity, true,'location');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';

$doctor_booking_option		= '';
if( function_exists( 'doctreat_get_booking_oncall_doctors_option' ) ) {
	$doctor_booking_option		= doctreat_get_booking_oncall_doctors_option();
}
?>
<div class="dc-dashboardboxtitle">
	<h2><?php esc_html_e('Profile Settings','doctreat');?></h2>
</div>
<div class="dc-dashboardtabs">
	<ul class="dc-tabstitle nav navbar-nav">
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'settings' ? 'active' : '';?>" href="<?php echo esc_url( $profile_details_url );?>">
				<?php esc_html_e('Personal Details', 'doctreat'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'educations' ? 'active' : '';?>" href="<?php echo esc_url( $educations_url );?>">
				<?php esc_html_e('Experience &amp; Education', 'doctreat'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'awards' ? 'active' : '';?>" href="<?php echo esc_url( $awardss_url );?>">
				<?php esc_html_e('Awards &amp; Downloads', 'doctreat'); ?>
			</a>
		</li>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'registrations' ? 'active' : '';?>" href="<?php echo esc_url( $registrations_url );?>">
				<?php esc_html_e('Registrations', 'doctreat'); ?>
			</a>
		</li>
		<?php if(!empty($gallery_option)){?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'gallery' ? 'active' : '';?>" href="<?php echo esc_url( $gallery_url );?>">
					<?php esc_html_e('Gallery', 'doctreat'); ?>
				</a>
			</li>
		<?php } ?>

		<?php if(empty($doctor_booking_option)){?>
			<li class="nav-item">
				<a class="<?php echo !empty( $mode ) && $mode === 'booking' ? 'active' : '';?>" href="<?php echo esc_url( $booking_url );?>">
					<?php esc_html_e('Booking settings', 'doctreat'); ?>
				</a>
			</li>
		<?php } ?>
		<li class="nav-item">
			<a class="<?php echo !empty( $mode ) && $mode === 'location' ? 'active' : '';?>" href="<?php echo esc_url( $location_url );?>">
				<?php esc_html_e('Default location', 'doctreat'); ?>
			</a>
		</li>

	</ul>
</div>