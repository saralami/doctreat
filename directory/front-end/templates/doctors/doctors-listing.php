<?php 
/**
 *
 * The template part for displaying doctors in listing
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $post;
$specialities 	= !empty( $_GET['specialities']) ? $_GET['specialities'] : '';
?>
<div class="dc-docpostholder dc-search-doctors">
	<div class="dc-docpostcontent">
		<div class="dc-searchvtwo">
			<a href="<?php echo esc_url(get_the_permalink($post->ID));?>"><?php do_action('doctreat_get_doctor_thumnail',$post->ID);?></a>
			<?php do_action('doctreat_get_doctor_details',$post->ID);?>
			<?php do_action('doctreat_get_doctor_services',$post->ID,'services');?>
		</div>
		<?php do_action('doctreat_get_doctor_booking_information',$post->ID);?>
	</div>
</div>