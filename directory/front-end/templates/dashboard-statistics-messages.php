<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-bubble';

$new_messages_img	= !empty( $theme_settings['new_messages']['url'] ) ? $theme_settings['new_messages']['url'] : '';
$user_type	= apply_filters('doctreat_get_user_type', $user_identity );

if( ( !empty( $user_type ) 
	 && ( $user_type === 'doctors' && apply_filters('doctreat_is_feature_allowed', 'dc_chat', $user_identity) === true ) ) 
	|| $user_type === 'hospitals' 
    || $user_type === 'regular_users'
) {?>
	<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
		<div class="dc-insightsitem dc-dashboardbox">
			<span><em class="dcunread-count"><?php do_action('doctreat_chat_count', $user_identity );?></em></span>
			<figure class="dc-userlistingimg">
				<?php if( !empty( $new_messages_img ) ) {?>
					<img src="<?php echo esc_url( $new_messages_img );?>" alt="<?php esc_attr_e('New Messages', 'doctreat'); ?>">
				<?php } else {?>
						<span class="<?php echo esc_attr($icon);?>"></span>
				<?php }?>
			</figure>
			<div class="dc-insightdetails">
				<div class="dc-title">
					<h3><?php esc_html_e('New Messages', 'doctreat'); ?></h3>
					<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('chat', $user_identity); ?>">
						<?php esc_html_e('Click To View', 'doctreat'); ?>
					</a>
				</div>													
			</div>	
		</div>
	</div>
<?php } ?>