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
global $post,$current_user;
 	$user_identity   = $current_user->ID;
	$link_id		 = doctreat_get_linked_profile_id( $user_identity );
?>
<div class="dc-docpostholder">
	<div class="dc-docpostcontent">
		<div class="dc-searchvtwo">
			<?php do_action('doctreat_get_doctor_thumnail',$post->ID);?>
			<?php do_action('doctreat_get_doctor_details',$post->ID);?>
		</div>
		<div class="dc-actions">
			<a href="#" data-id="<?php echo intval($link_id);?>" data-itme-type="_saved_doctors" data-item-id="<?php echo intval($post->ID);?>" class="dc-removesingle_saved">
				<span class="lnr lnr-trash"></span>
			</a>
		</div>
	</div>
</div>