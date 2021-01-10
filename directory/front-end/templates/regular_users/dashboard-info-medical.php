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
$query = $wpdb->get_results("SELECT * FROM info_patient WHERE (patient_id = '$user_identity')");
$now = date('Y-m-d');
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
	    <div class="dc-title">
				<h3><?php esc_html_e('Information medical', 'doctreat'); ?></h3>
                <?php if(!empty($query)){ ?>
                 <?php foreach($query as $info) { 
                    //print_r(dateDiff($now, $info->date_naissance));
                      $age = ($now - $info->date_naissance);
                      $age;
                      //echo $info->date_naissance;
                    ?>
                <h5>Age: <span class="badge badge-pill badge-primary" style="font-size:15px"><?php echo $age; ?> ans</span></h5>
                <h5>Groupe sanguin <span class="badge badge-pill badge-danger" style="font-size:20px"><?php echo $info->groupe_sanguin; ?></span></h5>
                <?php } ?>
				<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('info-medical-listing', $user_identity,''); ?>">
					<?php esc_html_e('Modifier ces informations', 'doctreat'); ?> 
				</a> 
                <?php 
                } else {
               ?>
               <a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('info-medical-listing', $user_identity,''); ?>">
					<?php esc_html_e('Ajouter votre groupe sanguin', 'doctreat'); ?> 
				</a> 
                <?php } ?>
			</div>		
    </div>
</div>