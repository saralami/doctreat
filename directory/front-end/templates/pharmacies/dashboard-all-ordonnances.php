<?php
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-bubble';

$appointments_img	= !empty( $theme_settings['total_appointments']['url'] ) ? $theme_settings['total_appointments']['url'] : '';

$args = array(
			'posts_per_page' 	=> -1,
			'post_name' 		=> $current_user->display_name,
			//'author'			=> $user_identity,
			//'post_status' 		=> array('publish'),
			//'suppress_filters'  => false
		);
$query 		= new WP_Query( $args );
$count_post = $query->found_posts;
var_dump($query);
?>




<form class="dc-medication-form" method="post">
						 <div class="form-group form-group-half">
								<input type="text" name="patient_name" class="form-control" value="<?php echo esc_attr($name);?>" placeholder="<?php esc_attr_e('Patient Name','doctreat');?>">
							</div>
							
									<?php
									$bk_username	= !empty($prescription['_patient_name']) ? $prescription['_patient_name'] : '';
								//}}

								$args = array(
									'posts_per_page' 	=> -1,
									'post_type' 		=> 'pharmacies',
									'post_status'    =>  'publish',
									//'post_author'			=> '_pharmacie_id'
								);
						$query 	= new WP_Query( $args );
						$posts = $query->posts;
						//$all_doctors_ids = array();
					//	var_dump($posts);
					
					$arg = array(
						'posts_per_page' 	=> -1,
						'post_type' 		=> 'booking',
						'post_status'    =>  'publish',
						'post_author'			=> $user_id
					);
			$query 	= new WP_Query( $arg );
			$postss = $query->posts;
			//$all_doctors_ids = array();
			//var_dump($postss);
								      //echo do_shortcode('[contact-form-7 id="1548" title="ordonnance"]');
								 // [contact-form-7 id="1548" title="ordonnance"]
							?>
						          <div class="dc-select">
										<select name="pharmacie_id" class="">
										<option value="ASC"><?php esc_html_e('Choisir une pharmacie','doctreat');?></option>
										<?php 
										    foreach( $posts as $post ) {
												//$post->post_name;
												$pharmacie = $post->post_title;
												//$all_doctors_ids[] = get_post_meta( $post->ID,'_pharmacie_id',true);
												
										?>
											
											
											<option value="DESC" <?php echo $post->ID;?>><?php echo $pharmacie;?></option>
											<?php
										}
										?>
										</select>

									</div>
							<fieldset>
								<div class="form-group">
									<form method="post" name="downloa_pdf">
									<input type="hidden" name="pdf_doctor_id" value="<?php echo intval($booking_id);?>">
									<a href="javascript:;" onclick="document.forms['downloa_pdf'].submit(); return false;" class="dc-btn dc-pdfbtn"><i class="ti-download"></i></a>
									</form>
								</div>
							</fieldset>
						</div>
						<div class="modal-footer dc-modal-footer">
						<div class="dc-updatall">
					<?php wp_nonce_field('dc_prescription_submit_data_nonce', 'prescription_submit'); ?>
					<i class="ti-announcement"></i>
					<span onclick="doctreat_print();"><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'doctreat'); ?></span>
					<a class="dc-btn dc-update-prescription" data-booking_id="<?php echo intval( $booking_id ); ?>" href="javascript:;"><?php esc_html_e('Send', 'doctreat'); ?></a>
				</div>
						
							<!-- <a href="javascript:;" class="btn dc-btn btn-primary dc-send_medication" data-id="<?php //echo intval($booking_id);?>"><?php //esc_html_e('Envoyer','doctreat');?></a> -->
						</div>	
						</form>		