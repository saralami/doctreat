<?php
/**
 *
 * The template part for displaying the dashboard menu
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

$post_type		= get_post_type($post_id);
$settings		= doctreat_get_account_settings($post_type);
$languages 		= doctreat_get_taxonomy_array('languages');
?>
<div class="dc-securityhold tab-pane active fade show" id="dc-security">
	<div class="dc-securitysettings dc-tabsinfo">
		<div class="dc-tabscontenttitle">
			<h3><?php esc_html_e('Account Security &amp; Settings','doctreat');?></h3>
		</div>
		<div class="dc-settingscontent dc-sidepadding">
			<?php if( !empty( $settings ) ){?>
				<ul class="dc-accountinfo">
					<?php 
					foreach( $settings as $key => $value ){
						$db_val 	= get_post_meta($linked_profile, $key, true);
						$db_val 	= !empty( $db_val ) ?  $db_val : 'off';
						?>
						<li>
							<div class="dc-on-off">
								<input type="hidden" name="settings[<?php echo esc_attr($key); ?>]" value="off">
								<input type="checkbox" <?php checked( $db_val, 'on' ); ?>  value="on" id="<?php echo esc_attr( $key );?>" name="settings[<?php echo esc_attr( $key );?>]">
								<label for="<?php echo esc_attr( $key );?>"><i></i></label>
							</div>
							<span><?php echo esc_html( $value );?></span>
						</li>
					<?php }?>
				</ul>
			<?php }?>
		</div>
	</div>
	<div class="dc-tabsinfo">
		<div class="dc-tabscontenttitle">
			<h3><?php esc_html_e('Select Languages', 'doctreat'); ?></h3>
		</div>
		<div class="dc-settingscontent dc-sidepadding">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group">
						<select data-placeholder="<?php esc_attr_e('Languages', 'doctreat'); ?>" name="settings[languages][]" multiple class="chosen-select">
							<?php if( !empty( $languages ) ){
								foreach( $languages as $key => $item ){
									$selected = '';
									if( has_term( $item->term_id, 'languages', $post_id )  ){
										$selected = 'selected';
									}
								?>
								<option <?php echo esc_attr($selected);?> value="<?php echo intval( $item->term_id );?>"><?php echo esc_html( $item->name );?></option>
							<?php }}?>
						</select>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>