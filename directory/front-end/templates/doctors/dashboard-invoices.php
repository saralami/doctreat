<?php
/**
 *
 * The template part for displaying the dashboard Available balance for doctor
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-file-empty';
$invoices_image	= !empty( $theme_settings['invoice_img']['url'] ) ? $theme_settings['invoice_img']['url'] : '';
$available_balance			= doctreat_get_sum_earning_doctor($user_identity,'pending','doctor_amount');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<figure class="dc-userlistingimg">
			<?php if( !empty($invoices_image) ) {?>
				<img src="<?php echo esc_url($invoices_image);?>" alt="<?php esc_attr_e('Invoices', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Check Your Invoices', 'doctreat'); ?></h3>
				<a href="<?php Doctreat_Profile_Menu::Doctreat_profile_menu_link('invoices', $user_identity); ?>">
					<?php esc_html_e('Click To View', 'doctreat'); ?>
				</a>
			</div>													
		</div>	
	</div>
</div>