<?php
/**
 *
 * The template used for doctors basics
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$theme_settings;
$post_id 	= $post->ID; 
$user_id	= doctreat_get_linked_profile_id( $post_id,'post' );
$verified	= get_post_meta($post_id, '_is_verified', true);
$verified	= !empty( $verified ) ? $verified	: '';

$system_access	= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';

$shoert_des		= doctreat_get_post_meta( $post_id, 'am_short_description');
$tagline		= doctreat_get_post_meta( $post_id, 'am_sub_heading');
$mrv			= doctreat_get_post_meta( $post_id, 'am_registration_number');
$name			= doctreat_full_name( $post_id );
$name			= !empty( $name ) ? $name : '';
$package_expiry	= doctreat_get_subscription_metadata('dc_bookings',$user_id);
$booking_option	= doctreat_get_booking_oncall_option('oncall');

$feedback			= get_post_meta($post_id,'review_data',true);
$feedback			= !empty( $feedback ) ? $feedback : array();
$total_rating		= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;

$doctor_avatar = apply_filters(
					'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar( array( 'width' => 255, 'height' => 250 ), $post_id ), array( 'width' => 255, 'height' => 250 )
				);

$doctor_avatar_2x = apply_filters(
					'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar( array( 'width' => 545, 'height' => 428 ), $post_id ), array( 'width' => 545, 'height' => 428 )
				);

$featured	= get_post_meta($post_id,'is_featured',true);
?>
<div class="dc-docsingle-header">
	<?php if( !empty( $doctor_avatar ) ){?>
		<figure class="dc-docsingleimg">
			<img class="dc-ava-detail" src="<?php echo esc_url( $doctor_avatar );?>" alt="<?php echo esc_attr( get_the_title() );?>">
			<img class="dc-ava-detail-2x" src="<?php echo esc_url( $doctor_avatar_2x );?>" alt="<?php echo esc_attr( get_the_title() );?>">
			<?php if( !empty( $featured ) && intval($featured) > 0 ){ ?>
				<figcaption>
					<span class="dc-featuredtag"><i class="fa fa-bolt"></i></span>
				</figcaption>
			<?php } ?>
		</figure>
	<?php }?>
	<div class="dc-docsingle-content">
		<div class="dc-title">
			<?php do_action('doctreat_specilities_list',$post_id,1);?>
			<h2>
				<a href="<?php echo esc_url( get_the_permalink() );?>"><?php echo esc_html( $name );?></a>
				<?php do_action('doctreat_get_drverification_check',$post_id,esc_html__('Medical Registration Verified','doctreat'));?>
				<?php do_action('doctreat_get_verification_check',$post_id,'');?>
			</h2>
			<ul class="dc-docinfo">
				<?php if( !empty( $tagline ) ) { ?>
					<li><em><?php echo esc_html( $tagline );?></em></li>
				<?php } ?>
				<li>
					<span class="dc-stars"><span style="width: <?php echo intval( $total_percentage );?>%"></span></span><em><?php echo intval( $total_rating );?>&nbsp;<?php esc_html_e('Feedback','doctreat');?></em>
				</li>
			</ul>
		</div>
		<?php if( !empty( $shoert_des ) ) {?>
			<div class="dc-description">
				<p><?php echo esc_html( $shoert_des );?></p>
			</div>
		<?php }?>
		
		<div class="dc-btnarea">
		<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="dc-btn dc-add-feedback"><?php esc_html_e('Add Feedback','doctreat');?></a>
			<?php 
				if( !empty($package_expiry)){
					if(empty($booking_option)){
				?>
					<a href="javascript:;" data-doctor_id="<?php echo intval( $post_id );?>" class="dc-btn dc-booking-model" data-access="<?php echo esc_attr($system_access);?>"><?php esc_html_e('Book Now','doctreat');?></a>
				<?php } else { ?>
					<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="dc-btn dc-booking-contacts"><?php esc_html_e('Call Now','doctreat');?></a>
				<?php } ?>
			<?php } ?>
			<?php do_action('doctreat_get_favorit_check',$post_id,'large');?>
		</div>
	</div>
</div>