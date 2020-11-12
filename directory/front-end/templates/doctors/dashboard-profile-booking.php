<?php 
/**
 *
 * The template part for displaying the user profile avatar
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$post_id			= $linked_profile;

$am_contact_numbers	= doctreat_get_post_meta( $post_id,'am_booking_contact');
$am_contact_numbers	= !empty($am_contact_numbers) ? $am_contact_numbers : array();

$booking_detail		= doctreat_get_post_meta( $post_id,'am_booking_detail');
$booking_detail		= !empty($booking_detail) ? $booking_detail : '';

?>
<div class="dc-skills dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Contact phone numbers','doctreat');?></h3>
	</div>
	<div class="dc-skillscontent-holder">
		<div class="dc-formtheme dc-skillsform">
			<fieldset>
				<div class="form-group">
					<div class="form-group-holder">
						<input type="text" class="form-control" id="input_booking_contact" placeholder="<?php echo esc_attr('Add phone number','doctreat');?>">
					</div>
				</div>
				<div class="form-group dc-btnarea">
					<a href="javascript:;" class="dc-btn dc-add_booking_contact"><?php esc_html_e('Add Now','doctreat');?></a>
				</div>
			</fieldset>
		</div>
		<div class="dc-myskills">
			<ul class="sortable list dc-booking_contacts">
				<?php foreach( $am_contact_numbers as $key => $am_contact_number ) {?>
					<li class="dc-membership-list">
						<div class="dc-dragdroptool">
							<a href="javascript:" class="lnr lnr-menu"></a>
						</div>
						<span class="skill-dynamic-html"><em class="skill-val"><?php echo esc_html($am_contact_number);?></em></span>
						<span class="skill-dynamic-field">
							<input type="text" name="am_booking_contact[<?php echo intval($key);?>]" value="<?php echo esc_attr($am_contact_number);?>">
						</span>
						<div class="dc-rightarea">
							<a href="javascript:;" class="dc-addinfo"><i class="lnr lnr-pencil"></i></a>
							<a href="javascript:;" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
		<div class="dc-formtheme">
			<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Booking Details','doctreat');?></h3>
			</div>
			<fieldset>
				<div class="form-group">
					<textarea name="am_booking_detail" class="form-control" placeholder="<?php esc_attr_e('Add contact details','doctreat');?>"><?php echo do_shortcode($booking_detail);?></textarea>
				</div>
			</fieldset>
		</div>
	</div>
</div>

<script type="text/template" id="tmpl-load-booking_contact">
	<li>
		<div class="dc-dragdroptool">
			<a href="javascript:" class="lnr lnr-menu"></a>
		</div>
		<span class="skill-dynamic-html"><em class="skill-val">{{data.name}}</em></span>
		<span class="skill-dynamic-field">
			<input type="text" name="am_booking_contact[{{data.id}}]" value="{{data.name}}">
		</span>
		<div class="dc-rightarea">
			<a href="javascript:;" class="dc-addinfo"><i class="lnr lnr-pencil"></i></a>
			<a href="javascript:;" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
		</div>
	</li>	
</script>