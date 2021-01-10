<?php 
/**
 *
 * The template part for displaying the template to delete account
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$reasons		 = doctreat_get_account_delete_reasons();
?>
<div class="dc-yourdetails dc-tabsinfo dc-delete-account">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Delete Account', 'doctreat'); ?></h4>
	</div>
	<div class="dc-formtheme dc-userform dc-sidepadding">
		<fieldset>
			<div class="form-group form-group-half">
				<input type="password" name="delete[password]" class="form-control" placeholder="<?php esc_attr_e('Enter Password','doctreat');?>">
			</div>
			<div class="form-group form-group-half">
				<input type="password" name="delete[retype]" class="form-control" placeholder="<?php esc_attr_e('Retype Password','doctreat');?>">
			</div>
			<div class="form-group">
				<span class="dc-select">
					<select name="delete[reason]">
						<option value=""><?php esc_html_e('Select Reason to Leave','doctreat');?></option>
						<?php foreach( $reasons as $key => $value ){?>
							<option value="<?php echo esc_attr($key);?>"><?php echo esc_html($value);?></option>
						<?php }?>
					</select>
				</span>
			</div>
			<div class="form-group">
				<textarea name="delete[description]" class="form-control" placeholder="<?php esc_attr_e('Description (Optional)','doctreat');?>"></textarea>
			</div>
		</fieldset>
	</div>
</div>
