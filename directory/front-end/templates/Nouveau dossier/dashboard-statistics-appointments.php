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

$appointments_img	= !empty( $theme_settings['total_appointments']['url'] ) ? $theme_settings['total_appointments']['url'] : '';

$args = array(
			'posts_per_page' 	=> -1,
			'post_type' 		=> 'booking',
			'author'			=> $user_identity,
			'post_status' 		=> array('publish','pending'),
			'suppress_filters'  => false
		);
$query 		= new WP_Query( $args );
$count_post = $query->found_posts;
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<span><em class="dcunread-count"><?php echo intval( $count_post );?></em></span>
		<figure class="dc-userlistingimg">
			
			<?php if( !empty( $appointments_img ) ) {?>
				<img src="<?php echo esc_url( $appointments_img );?>" alt="<?php esc_attr_e('New Messages', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Appointments', 'doctreat'); ?></h3>
				<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('appointment', $user_identity,'','listing'); ?>">
					<?php esc_html_e('Click To View', 'doctreat'); ?>
				</a>
			</div>													
		</div>	
	</div>
</div>