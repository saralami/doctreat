<?php
/**
 *
 * The template used for doctors bookings
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user,$theme_settings;
$post_id 				= $post->ID; 
$user_id				= doctreat_get_linked_profile_id( $post_id ,'post');
$relationship			= doctreat_patient_relationship();
$system_access  		= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';
$location_id			= get_post_meta($post_id, '_doctor_location', true);
$location_id			= !empty( $location_id ) ? intval( $location_id ) : '';
$booking_optin			= apply_filters('doctreat_doctor_appointment_settings',$post_id);
$payment_type			= !empty( $theme_settings['payment_type'] ) ? $theme_settings['payment_type'] : '';
$enable_checkout_page	= !empty( $theme_settings['enable_checkout_page'] ) ? $theme_settings['enable_checkout_page'] : '';
$offline_success_title 	= !empty( $theme_settings['success_title'] ) ? $theme_settings['success_title'] : '';
$offline_success_desc 	= !empty( $theme_settings['success_desc'] ) ? $theme_settings['success_desc'] : '';
$user 					= wp_get_current_user();
$user_email	 			= !empty($current_user->user_email) ? $current_user->user_email : '';

$mobile_number			= get_user_meta( $current_user->ID, 'mobile_number', true );

$mobile_number			= !empty($mobile_number) ? $mobile_number : '';
$full_name				= !empty($current_user->ID) ? doctreat_get_username($current_user->ID) : '';

$success_title		= esc_html__('Please wait...', 'doctreat');
$success_desc		= esc_html__('To confirm your booking, please deposit your payment.', 'doctreat');
$timezone_string	= get_option('timezone_string');
if(!empty($payment_type) && $payment_type == 'offline' && empty($enable_checkout_page)){	
	$success_title	= esc_html($offline_success_title);
	$success_desc	= esc_html($offline_success_desc);
}
?>
<div class="modal fade dc-appointmentpopup" tabindex="-1" role="dialog" id="appointment" data-backdrop="static"> 
	<div class="modal-dialog" role="document">
		<div class="dc-modalcontent modal-content">
			<?php if( !empty($booking_optin) ){ ?>   
				<section id="sec1" class="sec1">
					<div class="dc-popuptitle">
						<h3><?php esc_html_e('Book Appointment','doctreat');?></h3>
						<a href="javascript:;" class="dc-closebtn close dc-close" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','doctreat');?>"><i class="ti-close"></i></a>
					</div>
					<div id="dcModalBody" class="modal-body dc-modal-content-one dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('01','doctreat');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('02','doctreat');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('03','doctreat');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('04','doctreat');?></a></li>
						</ul>
						<div id="dcModalBody1" class="dc-visitingdoctor">
							<form class="dc-booking-step1">
								<div class="dc-title">
									<span><?php esc_html_e('Who is Visiting To Doctor?','doctreat');?></span>
									<div class="dc-tabbtns">
										<span class="dc-radio next-step">
											<input type="radio" name="myself" class="myself" value="myself" id="myself" checked>
											<label for="myself"><?php esc_html_e('Myself','doctreat');?></label>
										</span>
										<span class="dc-radio next-step">
											<input type="radio" name="myself" class="myself" value="someelse" id="someelse">
											<label for="someelse"><?php esc_html_e('Someone Else','doctreat');?></label>
										</span>
									</div>
								</div>
								<div class="dc-formtheme dc-docinfoform">
									<fieldset>
										<div class="form-group form-group-half">
											<input type="text" name="bk_email" class="form-control" value="<?php echo esc_attr($user_email);?>" placeholder="<?php esc_attr_e('Email address','doctreat');?>">
										</div>
										<div class="form-group form-group-half">
											<input type="text" name="bk_phone" class="form-control" value="<?php echo esc_attr($mobile_number);?>" placeholder="<?php esc_attr_e('Your mobile number','doctreat');?>">
										</div>
										<div class="form-group form-group-half">
											<input type="text" name="other_name" value="<?php echo esc_attr($full_name);?>" class="form-control" placeholder="<?php esc_attr_e('Patient Name','doctreat');?>">
										</div>
										<div class="form-group form-group-half form-group-relation">
											<span class="dc-select">
												<select data-placeholder="<?php esc_attr_e('Relation with you? *','doctreat');?>" name="relation">
													<option value=""><?php esc_html_e('Relation with you?','doctreat');?></option>
													<?php foreach( $relationship as $key => $val ){?>
														<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
													<?php } ?>
												</select>
											</span>
										</div>
									</fieldset>
								</div>
								<div class="dc-formtheme dc-vistingdocinfo">
									<fieldset>
										<div class="form-group">
											<span class="dc-select">
												<select name="booking_hospitals" data-doctor_id="<?php echo intval( $post_id );?>" class="dc-booking-hospitals">
													<?php 
														if( !empty($system_access) ){
															if( !empty($location_id) ){
																echo '<option selected="selected" value="'.$location_id.'">'.get_the_title($location_id).'</option>';
															}
														} else {
															echo '<option value="">'.esc_html__('Where to visit?','doctreat').'*</option>';
															doctreat_get_list_hospital('hospitals_team',$user_id);
														}
													?>
												</select>
											</span>
										</div>
										<div class="form-group" id="booking_service_select"></div>
										<div class="form-group" id="booking_fee"></div>
										<div class="form-group">
											<textarea class="form-control" placeholder="<?php esc_attr_e('Comments:','doctreat');?>" name="booking_content"></textarea>
										</div>
									</fieldset>
								</div>
								<div class="dc-appointment-holder">
									<div class="dc-title">
										<h4><?php esc_html_e('Select best time for appointment with time zone','doctreat');?></h4>
										<em><?php esc_html_e('*These time slots are based on the timezone','doctreat');echo esc_html($timezone_string);?></em>
									</div>
									<div class="dc-appointment-content">
										<div class="dc-appointment-calendar">
											<div id="dc-calendar" class="dc-calendar"></div>
										</div>
										<div class="dc-timeslots dc-update-timeslots">
											<?php do_action('doctreat_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any slot available.', 'doctreat' ));?>
										</div>
										<input type="hidden" value="<?php echo date('Y-m-d');?>" name="appointment_date" id="appointment_date">
									</div>
								</div>
							</form>
						</div>
					</div>
					<div id="dcModalBody2" class="modal-body dc-modal-content-two dc-haslayout">
						<div class="dc-visitingdoctor dc-popup-doc">
							<ul class="dc-joinsteps">
								<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
								<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('02','doctreat');?></a></li>
								<li><a href="javascrip:;"><?php esc_html_e('03','doctreat');?></a></li>
								<li><a href="javascrip:;"><?php esc_html_e('04','doctreat');?></a></li>
							</ul>
							<form class="dc-booking-step2">
								<?php if( $current_user->ID ) { ?>
									<div class="dc-visit">
										<span><?php esc_html_e('Verify is that you?','doctreat');?></span>
									</div>
									<div class="form-row dc-popup-row">
										<div class="form-group col-6">
											<input type="password" class="form-control" name="password" placeholder="<?php esc_attr_e('Password*','doctreat');?>">
										</div>
										<div class="form-group col-6">
											<input type="password" class="form-control" name="retype_password" placeholder="<?php esc_attr_e('Retype Password*','doctreat');?>">
										</div>
									</div>							
								<?php } else { ?>
									<div class="dc-visit">
										<span><?php esc_html_e('Verify is that you?','doctreat');?></span>
									</div>	
									<div class="form-row dc-popup-row">
										<div class="form-group col-6">
											<input type="text" class="form-control" name="full_name"  placeholder="<?php esc_attr_e('Name*','doctreat');?>">
										</div>
										<div class="form-group col-6">
											<input type="text" class="form-control" name="phone_number"  placeholder="<?php esc_attr_e('Phone Number*','doctreat');?>">
										</div>
										<div class="form-group col-12">
											<input type="email" class="form-control" placeholder="<?php esc_attr_e('Email*','doctreat');?>" name="email">
										</div>
									</div>
								<?php } ?>
							</form>
						</div>
					</div>
					<div id="dcModalBody3" class="modal-body dc-modal-content-three dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-active"><a href="javascrip:;"><?php esc_html_e('03','doctreat');?></a></li>
							<li><a href="javascrip:;"><?php esc_html_e('04','doctreat');?></a></li>
						</ul>
						<h5><?php esc_html_e('Enter Authentication Code','doctreat');?></h5>
						<p><?php esc_html_e('We’ve sent verification code on your email at','doctreat');?>&nbsp;<a class="email_address" href="javascript:;"></a></p>
						<form class="dc-booking-step3">
							<input type="text" placeholder="<?php esc_attr_e('Add authentication code','doctreat');?>" name="authentication_code">
							<label><a href="javascript:;" class="dc-resend-booking-code"><?php esc_html_e('Resend Verification Code', 'doctreat'); ?></a></label>
						</form>
						
					</div>
					<div id="dcModalBody4" class="modal-body dc-modal-content-four dc-haslayout">
						<ul class="dc-joinsteps">
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
							<li class="dc-done-next"><a href="javascrip:;"><i class="fa fa-check"></i></a></li>
						</ul>
						<div class="dc-modal-body4-title">
							<h4><?php echo esc_html($success_title);?></h6>
							<p><?php echo esc_html($success_desc);?></p>
							<?php if(!empty($payment_type) && $payment_type == 'offline' && empty($enable_checkout_page)){ ?>
							<div class="dc-offline-checkout"></div>
							<a href="javascript:;" class="dc-btn finish-appointment"><?php esc_html_e('Finish','doctreat');?></a>
							<?php } ?>
						</div>
					</div>
					<div class="modal-footer dc-modal-footer">
						<a href="javascript:;" id="dcbtn" class="btn dc-btn btn-primary dc-booking-step1-btn" data-id="<?php echo intval($user_id);?>" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Continue','doctreat');?></a>
						<a href="javascript:;" id="dcbtn2" data-id="<?php echo intval($user_id);?>" class="btn dc-btn btn-primary dc-booking-step2-btn" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Continue','doctreat');?></a>
						<a href="javascript:;" id="dcbtn3" class="btn dc-btn btn-primary dc-booking-step3-btn" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Verify Now','doctreat');?></a>
					</div>
				</section>			
			<?php } else { ?>
				<div class="dc-popuptitle">
					<h3><?php esc_html_e('Book Appointment','doctreat');?></h3>
					<a href="javascript:;" class="dc-closebtn close dc-close" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','doctreat');?>"><i class="ti-close"></i></a>
				</div>
					<?php do_action('doctreat_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'Doctor has not updated his booking settings.', 'doctreat' ));?>

			<?php }?>	
		</div>
	</div>
</div> 