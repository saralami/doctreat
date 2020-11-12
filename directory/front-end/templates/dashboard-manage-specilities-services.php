<?php
/**
 *
 * The template part for displaying the Specilities and service link
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-heart-pulse';

$service_spec_img	= !empty( $theme_settings['service_spec']['url'] ) ? $theme_settings['service_spec']['url'] : '';
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($service_spec_img) ) {?>
				<img src="<?php echo esc_url($service_spec_img);?>" alt="<?php esc_attr_e('Service', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Manage Specialties and Services', 'doctreat'); ?></h3>
				<a href="<?php Doctreat_Profile_Menu::Doctreat_profile_menu_link('specialities', $user_identity); ?>">
					<?php esc_html_e('Click To View', 'doctreat'); ?>
				</a>
			</div>													
		</div>
	</div>
</div>