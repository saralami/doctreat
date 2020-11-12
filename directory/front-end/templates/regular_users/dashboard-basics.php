<?php 
/**
 *
 * The template part for displaying the users profile basics
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post,$theme_settings;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);

$first_name 	= get_user_meta($user_identity, 'first_name', true);
$last_name 		= get_user_meta($user_identity, 'last_name', true);

$post_id 		= $linked_profile;
$user_meta		= doctreat_get_post_meta( $post_id );
$name_bases		= doctreat_get_name_bases('','user');

$display_name	= get_the_title( $post_id );
$display_name	= !empty( $display_name ) ? $display_name : '';
$base_name_disable		= !empty( $theme_settings['base_name_disable'] ) ? $theme_settings['base_name_disable'] : '';
$am_name_base	= !empty( $user_meta['am_name_base'] ) ? $user_meta['am_name_base'] : '';
$am_sub_heading	= !empty( $user_meta['am_sub_heading'] ) ? $user_meta['am_sub_heading'] : '';
$am_first_name	= !empty( $user_meta['am_first_name'] ) ? $user_meta['am_first_name'] : '';
$am_last_name	= !empty( $user_meta['am_last_name'] ) ? $user_meta['am_last_name'] : '';
$mobile_number	= !empty( $user_meta['am_mobile_number'] ) ? $user_meta['am_mobile_number'] : '';

$am_short_description	= !empty( $user_meta['am_short_description'] ) ? $user_meta['am_short_description'] : '';

$post_object 	= get_post( $post_id );
$content 	 	= $post_object->post_content;

?>

<div class="dc-yourdetails dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Your Details', 'doctreat'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<?php if( !empty($base_name_disable) ){?>
				<div class="form-group form-group-half">
					<span class="dc-select">
						<select name="am_name_base">
							<option value="" disabled=""><?php esc_html_e('Select one', 'doctreat'); ?></option>
							<?php if( !empty( $name_bases ) ) {?>
								<?php foreach ( $name_bases as $key => $name_base ) {?>
									<option value="<?php echo esc_attr( $key );?>" <?php selected( $am_name_base, $key, true); ?>><?php echo esc_html($name_base); ?></option>
								<?php } ?>
							<?php } ?>
						</select>
					</span>
				</div>
			<?php }?>
			<div class="form-group form-group-half">
				<input type="text" value="<?php echo esc_attr( $am_sub_heading ); ?>" name="am_sub_heading" class="form-control" placeholder="<?php esc_attr_e('Sub Heading', 'doctreat'); ?>">
			</div>
			<div class="form-group form-group-half">
				<input type="text" name="am_first_name" class="form-control" value="<?php echo esc_attr( $first_name ); ?>" placeholder="<?php esc_attr_e('First name', 'doctreat'); ?>">
			</div>			
			<div class="form-group form-group-half">
				<input type="text" value="<?php echo esc_attr( $last_name ); ?>" name="am_last_name" class="form-control" placeholder="<?php esc_attr_e('Last Name', 'doctreat'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="display_name" class="form-control" value="<?php echo esc_attr( $display_name ); ?>" placeholder="<?php esc_attr_e('Display name', 'doctreat'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="am_mobile_number" class="form-control" value="<?php echo esc_attr( $mobile_number ); ?>" placeholder="<?php esc_attr_e('Personal mobile number', 'doctreat'); ?>">
			</div>
			<div class="form-group">
				<input type="text" name="am_short_description" class="form-control" value="<?php echo esc_attr( $am_short_description ); ?>" placeholder="<?php esc_attr_e('Short description', 'doctreat'); ?>">
			</div>
			<div class="form-group">
				<textarea name="content" class="form-control" placeholder="<?php esc_attr_e('Description', 'doctreat'); ?>"><?php echo sanitize_textarea_field( $content ); ?></textarea>
			</div>
		</fieldset>
	</div>
</div>
<?php get_template_part('directory/front-end/templates/dashboard', 'location');?>