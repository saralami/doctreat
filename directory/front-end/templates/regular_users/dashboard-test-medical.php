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
//$query = $wpdb->get_results("SELECT * FROM info_patient WHERE (patient_id = '$user_identity')");
//$now = date('Y-m-d');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
	    <div class="dc-title">
				<h3><?php esc_html_e('Test medical', 'doctreat'); ?></h3>
                <a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('test-medical-listing', $user_identity,''); ?>">
					<?php esc_html_e('Voir mes tests médicaux', 'doctreat'); ?> 
				</a> 
        </div>		
    </div>
</div>