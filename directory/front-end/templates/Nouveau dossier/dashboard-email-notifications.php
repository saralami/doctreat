<?php 
/**
 *
 * The template part for displaying the template to display email settings
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 = $current_user->ID;
?>
<div class="dc-emailnotiholder tab-pane active fade show" id="dc-emailnoti">
	<div class="dc-emailnoti">
		<div class="dc-tabscontenttitle">
			<h3><?php esc_html_e('Manage Email Notifications', 'doctreat'); ?></h3>
		</div>
		<div class="dc-settingscontent dc-sidepadding">
			<div class="dc-description">
				<p><?php esc_html_e('All the emails will be sent to the below email address','doctreat');?></p>
			</div>
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-disabeld">
						<input type="password" name="useremail" class="form-control" placeholder="<?php echo esc_attr($current_user->user_email);?>" disabled="">
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
