<?php
/**
 *
 * The template part for displaying the dashboard current balance for doctor
 *
 * @package   doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);

$icon					= 'lnr lnr-gift';
$current_balance_img	= !empty( $theme_settings['current_balance_img']['url'] ) ? $theme_settings['current_balance_img']['url'] : '';
$current_balance		= doctreat_get_sum_earning_doctor($user_identity,'completed','doctor_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($current_balance_img) ) {?>
				<img src="<?php echo esc_url($current_balance_img);?>" alt="<?php esc_attr_e('Available balance', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php doctreat_price_format($current_balance);?></h3>
				<span><?php esc_html_e('Available balance', 'doctreat'); ?></span>
			</div>													
		</div>	
	</div>
</div>