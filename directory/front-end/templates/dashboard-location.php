<?php 
/**
 *
 * The template part for displaying the user profile basics
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post,$theme_settings;
$dir_latitude		= !empty($theme_settings['dir_latitude']) ? $theme_settings['dir_latitude'] : '-34';
$dir_longitude		= !empty($theme_settings['dir_longitude']) ? $theme_settings['dir_longitude'] : '51';
$post_id  		= doctreat_get_linked_profile_id($current_user->ID);
$address		= get_post_meta( $post_id , '_address',true );
$address		= !empty( $address ) ? $address : '';
$latitude		= get_post_meta( $post_id , '_latitude',true );
$latitude		= !empty( $latitude ) ? $latitude : $dir_latitude;
$longitude		= get_post_meta( $post_id , '_longitude',true );
$longitude		= !empty( $longitude ) ? $longitude : $dir_longitude;

$location 		= apply_filters('doctreat_get_tax_query',array(),$post_id,'locations','');

//Get country
if( !empty( $location[0]->term_id ) ){
	$location = !empty( $location[0]->term_id ) ? $location[0]->term_id : '';
}

$location 		= !empty( $location ) ? $location : '';
?>
<div class="dc-location dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Your Location', 'doctreat'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="form-group form-group-half">
				<span class="dc-select">
					<?php do_action('doctreat_get_locations_list','location',$location);?>
				</span>
			</div>
			<div class="form-group form-group-half loc-icon">
				<input type="text" id="location-address-0" name="address" class="form-control" value="<?php echo esc_attr( $address ); ?>" placeholder="<?php esc_attr_e('Your Address', 'doctreat'); ?>">
				<a href="javascript:;" class="geolocate"><i class="fa fa-crosshairs"></i></a>
			</div>
			<div class="form-group dc-formmap">
				<div id="location-pickr-map" class="dc-locationmap location-pickr-map" data-latitude="<?php echo esc_attr( $latitude );?>" data-longitude="<?php echo esc_attr( $longitude );?>"></div>
			</div>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" id="location-latitude-0" name="latitude" class="form-control" value="<?php echo esc_attr( $latitude ); ?>" placeholder="<?php esc_attr_e('Enter Latitude', 'doctreat'); ?>">
				<?php do_action('doctreat_get_tooltip','element','latitude');?>
			</div>
			<div class="form-group form-group-half toolip-wrapo">
				<input type="text" id="location-longitude-0" name="longitude" class="form-control" value="<?php echo esc_attr( $longitude ); ?>" placeholder="<?php esc_attr_e('Enter Longitude', 'doctreat'); ?>">
				<?php do_action('doctreat_get_tooltip','element','longitude');?>
			</div>
			
		</fieldset>
	</div>
</div>
<?php
	$script = "jQuery(document).ready(function (e) {
				jQuery.doctreat_init_profile_map(0,'location-pickr-map', ". esc_js($latitude) . "," . esc_js($longitude) . ");
			});";
	wp_add_inline_script('doctreat-maps', $script, 'after');