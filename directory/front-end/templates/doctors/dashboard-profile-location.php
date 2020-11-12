<?php
/**
 *
 * The template part for displaying locations
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $theme_settings;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$system_access		= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-9">		
	<form class="dc-user-profile" method="post">	
		<div class="dc-dashboardbox dc-dashboardtabsholder">
			<?php get_template_part('directory/front-end/templates/doctors/dashboard', 'profile-settings-tabs'); ?>
			<div class="dc-tabscontent tab-content">
				<div class="dc-personalskillshold tab-pane active fade show" id="dc-skills">
					<?php 
						if( !empty($system_access) ) { 
							get_template_part('directory/front-end/templates/doctors/dashboard', 'location-basic'); 
						} 
					?>
					<?php get_template_part('directory/front-end/templates/dashboard', 'location'); ?>	
				</div>
			</div>
		</div>
		<div class="dc-updatall">
			<?php wp_nonce_field('wt_doctors_data_nonce', 'profile_submit'); ?>
			<i class="ti-announcement"></i>
			<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on Save &amp; Update button.', 'doctreat'); ?></span>
			<a class="dc-btn dc-update-profile-location" data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="javascript:;"><?php esc_html_e('Save &amp; Update', 'doctreat'); ?></a>
		</div>	
	</form>		
</div>
