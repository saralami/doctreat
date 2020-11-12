<?php
/**
 *
 * The template used for add doctors bookings
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$current_user,$theme_settings;
$user_id		= $current_user->ID;
$relationship	= doctreat_patient_relationship();
$system_access	= !empty($theme_settings['system_access']) ? $theme_settings['system_access'] : '';
$post_id		= doctreat_get_linked_profile_id($user_id);
$location_id	= get_post_meta($post_id, '_doctor_location', true);
$location_id	= !empty($location_id) ? $location_id : 0;
$timezone_string	= get_option('timezone_string');
?>
<div class="modal fade dc-appointmentpopup dc-feedbackpopup dc-bookappointment" role="dialog" id="booking-appointment"> 
	<div class="modal-dialog" role="document">
		<div class="dc-modalcontent modal-content">	
				<div class="dc-popuptitle">
					<h3><?php esc_html_e('Book Appointment','doctreat');?></h3>
					<a href="javascript:;" class="dc-closebtn close dc-close" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','doctreat');?>"><i class="ti-close"></i></a>
				</div>
				<div id="dcModalBody" class="modal-body dc-modal-content-one dc-haslayout">
					<div id="dcModalBody1" class="dc-visitingdoctor">
						<form class="dc-booking-doctor dc-formfeedback">
							<div class="dc-title">
								<span><?php esc_html_e('Add patient details','doctreat');?></span>
							</div>
							<div class="dc-formtheme dc-vistingdocinfo">
								<p><?php echo esc_html_e('Please add correct email address to find patient from the database, if not found then you can add new patient into database by typing email address and name','doctreat');?></p>
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="email" id="dc-booking-email" class="form-control" placeholder="<?php esc_attr_e('Email','doctreat');?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="first_name" class="form-control" placeholder="<?php esc_attr_e('First Name','doctreat');?>">
									</div>
									<div class="form-group form-group-half">
										<input type="hidden" name="user_id" class="form-control">
										<input type="text" name="last_name" class="form-control" placeholder="<?php esc_attr_e('Last Name','doctreat');?>">
									</div>
									<div class="form-group">
										<input type="text" name="phone" id="dc-booking-phone" class="form-control" placeholder="<?php esc_attr_e('Phone','doctreat');?>">
									</div>
									<div class="form-group dc-add-new-patient">
										<span class="dc-checkbox dc-creat-user">
											<input type="checkbox" name="create_user" class="dc-user" id="dc-user" value="yes" checked>
											<label for="dc-user"><?php esc_html_e('Create new user','doctreat');?></label>
										</span>
									</div>
								</fieldset>
							</div>
							<div class="dc-title dc-visitingtitle">
								<span><?php esc_html_e('Who is Visiting To Doctor?','doctreat');?></span>
								<div class="dc-tabbtns">
									<span class="dc-radio next-step">
										<input type="radio" name="myself" class="myself" value="myself" id="myself" checked>
										<label for="myself"><?php esc_html_e('Patient only','doctreat');?></label>
									</span>
									<span class="dc-radio next-step">
										<input type="radio" name="myself" class="myself" value="someelse" id="someelse">
										<label for="someelse"><?php esc_html_e('Someone Else','doctreat');?></label>
									</span>
								</div>
							</div>
							
							<div class="dc-formtheme dc-docinfoform form-group-relation">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="other_name" class="form-control" placeholder="<?php esc_attr_e('Name','doctreat');?>">
									</div>
									<div class="form-group form-group-half">
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
													if( !empty($system_access)  ){
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
									<em><?php esc_html_e('*These time slots are based on the timezone','doctreat'); echo esc_html($timezone_string);?></em>
								</div>
								<div class="dc-appointment-content">
									<div class="dc-appointment-calendar">
										<div id="dc-calendar" class="dc-calendar"></div>
									</div>
									<div class="dc-timeslots dc-update-timeslots"><?php do_action('doctreat_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'There are no any slot available.', 'doctreat' ));?></div>
									<input type="hidden" value="<?php echo date('Y-m-d');?>" name="appointment_date" id="appointment_date">
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="modal-footer dc-modal-footer">
					<a href="javascript:;" id="dcbtn" class="btn dc-btn btn-primary dc-booking-doctor-btn" data-id="<?php echo intval($user_id);?>" data-toggle="modal" data-target="#appointment2"><?php esc_html_e('Continue','doctreat');?></a>
				</div>			
		</div>
	</div>
</div> 

<?php
	
	$js_script	= " dc_doctor_booking_model();
	jQuery(document).on('ready', function() {
		var calendar_locale  	= scripts_vars.calendar_locale;
		var is_rtl  			= scripts_vars.is_rtl;
		var calendar_locale  	= scripts_vars.calendar_locale;
		
		if( calendar_locale  && calendar_locale != null){
			jQuery.datetimepicker.setLocale(calendar_locale);
			moment.locale(calendar_locale);
		}

		jQuery('#dc-calendar').fullCalendar({
			height: 'auto',
			locale: calendar_locale,
			viewRender: function(currentView){
				var minDate = moment();
				if (minDate >= currentView.start && minDate <= currentView.end) {
					jQuery('.fc-prev-button').prop('disabled', true); 
					jQuery('.fc-prev-button').addClass('fc-state-disabled'); 
				}
				else {
					jQuery('.fc-prev-button').removeClass('fc-state-disabled'); 
					jQuery('.fc-prev-button').prop('disabled', false); 
				}

			},
			dayClick: function(date, jsEvent, view) {
				if (moment().format('YYYY-MM-DD') === date.format('YYYY-MM-DD') || date.isAfter(moment())) {
					var _date			= date.format();
					var _hospital_id	= jQuery('.dc-booking-hospitals').val();
					jQuery('#dc-calendar .fc-state-highlight').removeClass('fc-state-highlight');
					jQuery('#dc-calendar td[data-date='+_date+']').addClass('fc-state-highlight');

					if( _hospital_id	== '' ){
						jQuery.sticky(scripts_vars.location_required, {classList: 'important',position:'top-right', speed: 200, autoclose: 5000});
						return false;
					}

					jQuery('body').append(loader_html);
					var dataString 	  = '_date='+_date+'&_hospital_id='+_hospital_id+'&action=doctreat_get_slots';   
					jQuery.ajax({
						type: 'POST',
						url: scripts_vars.ajaxurl,
						data: dataString,
						dataType: 'json',
						success: function (response) {
							jQuery('body').find('.dc-preloader-section').remove();
							if (response.type === 'success') {
								jQuery('.dc-update-timeslots').html(response.time_slots);
								jQuery('#appointment_date').val(_date);
							} else {
								jQuery('.dc-update-timeslots').html('');
								jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
							}
						}
					});
				}
			}
		});
	});
";
	 wp_add_inline_script( 'doctreat-dashboard', $js_script, 'after' );
	