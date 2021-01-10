<?php
/**
 *
 * The template part for displaying the Save item statistics
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-cart';

$pending_balance_img	= !empty( $theme_settings['pending_balance']['url'] ) ? $theme_settings['pending_balance']['url'] : '';
$available_balance		= doctreat_get_sum_earning_doctor($user_identity,'completed','doctor_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($pending_balance_img) ) {?>
				<img src="<?php echo esc_url($pending_balance_img);?>" alt="<?php esc_attr_e('Pending Balance', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php doctreat_price_format($available_balance);?></h3>
				<span><?php esc_html_e('Pending balance', 'doctreat'); ?></span>
			</div>													
		</div>
	</div>
</div>