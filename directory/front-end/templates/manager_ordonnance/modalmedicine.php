
    <!-- Modal -->
	<div class="modal fade" id="<?php echo $prescription_id;?>" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
       
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	     
      <?php
      echo $prescription_id;
/**
 *
 * The template part for add/update prescription
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
//global $current_user;
//var_dump($current_user);
$booking_id	= get_post_meta( $prescription_id, '_booking_id', true );


//$booking_id			= !empty($_GET['booking_id']) ? intval($_GET['booking_id']) : "";

$timezone_string	= !empty(get_option('timezone_string')) ? get_option('timezone_string') : 'UTC';
$male_checked		= '';
$female_checked		= '';
$shemale_checked	= '';
$prescription_id	= '';
$marital_status		= '';
$medical_history	= '';
$medicine			= array();
$diseases			= array();
$childhood_illness	= array();
$diseases_list		= array();
$vital_signs		= array();
if( !empty($booking_id) ){

	$doctor_profile_id	= doctreat_get_linked_profile_id($current_user->ID);
	$specialities 		= wp_get_post_terms( $doctor_profile_id, 'specialities', array( 'fields' => 'ids' ) );
	if(!empty($specialities) ){
		$diseases_arg = array(
				'hide_empty' => false,
				'meta_query' => array(
					array(
					'key'       => 'speciality',
					'value'     => $specialities,
					'compare'   => 'IN'
					)
				),
				'taxonomy'  => 'diseases',
				'fields'	=> 'ids'
			);
		$diseases = get_terms( $diseases_arg );
	}
	$prescription_id	= get_post_meta( $booking_id, '_prescription_id', true );
}

if( !empty($booking_id) && empty($prescription_id) ){
	$bk_username	= get_post_meta( $booking_id, 'bk_username', true );
	$bk_phone		= get_post_meta( $booking_id, 'bk_phone', true );
	$patient_id		= get_post_field( 'post_author', $booking_id );
	$patient_id		= !empty($patient_id) ? $patient_id : '';
	$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);

	$patient_address	= get_post_meta( $patient_profile_id , '_address',true );
	$base_name			= doctreat_get_post_meta( $patient_profile_id , 'am_name_base' );
	$base_name			= !empty($base_name) ? $base_name : '';

	$dob				= get_post_meta( $patient_profile_id , '_dob',true );
	$dob				= !empty($dob) ? $dob : '12/12/1990';

	$time_zone  = new DateTimeZone($timezone_string);
	$age 		= !empty($dob) ? DateTime::createFromFormat('d/m/Y', $dob, $time_zone)->diff(new DateTime('now', $time_zone))->y : '';

	if( !empty($base_name) ){
		if($base_name === 'mr'){
			$male_checked	= 'checked';
		} else if($base_name === 'miss'){
			$female_checked	= 'checked';
		}
	}
	$location 			= apply_filters('doctreat_get_tax_query',array(),$patient_profile_id,'locations','');
	//Get country


} else if( !empty($prescription_id) ){
	$prescription	= get_post_meta( $prescription_id, '_detail', true );

	$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );

	$patient_id			= !empty($patient_id) ? $patient_id : '';
	$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);


	$bk_username	= !empty($prescription['_patient_name']) ? $prescription['_patient_name'] : '';
	$bk_phone		= !empty($prescription['_phone']) ? $prescription['_phone'] : '';
	$age			= !empty($prescription['_age']) ? $prescription['_age'] : '';
	$pharmacy1		= !empty($prescription['_pharmacy1']) ? $prescription['_pharmacy1'] : '';

	//PRESCRIPTION STATUS 
	$prescription_status = !empty($prescription['_prescription_status']) ? $prescription['_prescription_status'] : '';
    //MEDICINE STATUS 
	$medicine_status = !empty($prescription['_medicine_status']) ? $prescription['_medicine_status'] : '';
	//DELIVERY STATUS 
	$delivery_status = !empty($prescription['_delivery_status']) ? $prescription['_delivery_status'] : '';

	$gender			= !empty($prescription['_gender']) ? $prescription['_gender'] : '';

	$medical_history	= !empty($prescription['_medical_history']) ? $prescription['_medical_history'] : '';
	$medicine			= !empty($prescription['_medicine']) ? $prescription['_medicine'] : array();
	$vital_signs		= !empty($prescription['_vital_signs']) ? $prescription['_vital_signs'] : '';
	$patient_address	= !empty($prescription['_address']) ? $prescription['_address'] : '';
	$marital_status		= !empty($prescription['_marital_status']) ? $prescription['_marital_status'] : '';
	$childhood_illness	= !empty($prescription['_childhood_illness']) ? $prescription['_childhood_illness'] : array();

	if( !empty($gender) && $gender === 'male'){
		$male_checked	= 'checked';
	} else if(!empty($gender) && $gender === 'female'){
		$female_checked	= 'checked';
	}

	$location 			= apply_filters('doctreat_get_tax_query',array(),$prescription_id,'locations','');
	$diseases_list 		= wp_get_post_terms( $prescription_id, 'diseases', array( 'fields' => 'ids' ) );

}

$prescription_id	= !empty($prescription_id) ? $prescription_id : '';
$username			= !empty($bk_username) ? $bk_username : '';
$phone				= !empty($bk_phone) ? $bk_phone : '';
$patient_address	= !empty($patient_address) ? $patient_address : '';

if( !empty( $location[0]->term_id ) ){
	$location = !empty( $location[0]->term_id ) ? $location[0]->term_id : '';
}

$location 				= !empty( $location ) ? $location : '';
$laboratory_tests 		= doctreat_get_taxonomy_array('laboratory_tests');
$rand_val				= rand(1, 9999);

?>
<!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-xl-8"> -->
	<div class="dc-haslayout dc-prescription-wrap dc-dashboardbox dc-dashboardtabsholder">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Ordonnance','doctreat');?></h2>
		</div>
		<div class="dc-dashboardboxcontent">
			<form class="dc-prescription-form" method="post">
			
				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Patient Information','doctreat');?>:</h4>
					</div>
					<div class="dc-formtheme dc-userform">
						<fieldset>
							<div class="form-group form-group-half">
								<input type="text" name="patient_name" class="form-control" value="<?php echo esc_attr($username);?>" placeholder="<?php esc_attr_e('Patient Name','doctreat');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="phone" class="form-control" value="<?php echo esc_attr($bk_phone);?>" placeholder="<?php esc_attr_e('Patient Phone','doctreat');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="age" class="form-control" value="<?php echo esc_attr($age);?>" placeholder="<?php esc_attr_e('Age','doctreat');?>">
							</div>
							<div class="form-group form-group-half">
								<input type="text" name="address" value="<?php echo esc_attr($patient_address);?>" class="form-control" placeholder="<?php esc_attr_e('Address','doctreat');?>">
							</div>
							
							<div class="form-group form-group-half">
								<div class="dc-radio-holder">
									<span class="dc-radio">
										<input id="dc-mo-male" type="radio" name="gender" value="male" <?php echo esc_attr($male_checked);?>>
										<label for="dc-mo-male"><?php esc_html_e('Male','doctreat');?></label>
									</span>
									<span class="dc-radio">
										<input id="dc-mo-female" type="radio" name="gender" value="female" <?php echo esc_attr($female_checked);?>>
										<label for="dc-mo-female"><?php esc_html_e('Female','doctreat');?></label>
									</span>
								</div>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Marital Status','doctreat');?>:</h4>
					</div>
					<div class="dc-formtheme dc-userform">
					<?php do_action( 'doctreat_get_texnomy_radio','marital_status','marital_status',$marital_status);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Childhood illness','doctreat');?>:</h4>
					</div>
					<div class="dc-formtheme dc-userform">
						<?php do_action( 'doctreat_get_texnomy_checkbox','childhood_illness','childhood_illness[]',$childhood_illness);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Diseases','doctreat');?>:</h4>
					</div>
					<div class="dc-formtheme dc-userform">
						<?php do_action( 'doctreat_get_texnomy_checkbox','diseases','diseases[]',$diseases_list,$diseases);?>
					</div>
				</div>

				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Select Laboratory Tests', 'doctreat'); ?></h4>
					</div>
					<div class="dc-settingscontent">
						<div class="dc-formtheme dc-userform">
							<fieldset>
								<div class="form-group">
									<select style="width:auto" data-placeholder="<?php esc_attr_e('Laboratory Tests', 'doctreat'); ?>" class="form-control tests-<?php echo esc_attr($rand_val );?>" name="laboratory_tests[]" multiple="multiple">
										<?php if( !empty( $laboratory_tests ) ){
											foreach( $laboratory_tests as $key => $item ){
												$selected = '';
												if( has_term( $item->term_id, 'laboratory_tests', $prescription_id )  ){
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

				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Common Issue','doctreat');?>:</h4><a href="javascript:;" class="dc-add-vitals"><?php esc_html_e('Add New','doctreat');?></a>
					</div>
					<div class="dc-formtheme dc-userform" id="dc-vital-signs">
						<fieldset>
							<div class="form-group form-group-half">
								<?php do_action( 'doctreat_get_texnomy_select','vital_signs','',esc_html__('Select vital sign','doctreat') ,'','vital_signs');?>
							</div>
							<div class="form-group form-group-half dc-delete-group">
								<input type="text" id="dc-vital-signs-val" class="form-control" placeholder="<?php esc_attr_e('Value','doctreat');?>">
							</div>
						</fieldset>
					</div>
						<?php
						if(!empty($vital_signs) ){
							foreach($vital_signs as $vital_key	=> $vital_values ){
								$vital_val	= !empty($vital_values['value']) ? $vital_values['value'] : '';
								?>
								<div class="dc-formtheme dc-userform dc-visal-sign dc-visal-<?php echo esc_attr($vital_key);?>">
									<fieldset>
										<div class="form-group form-group-half">
											<?php do_action( 'doctreat_get_texnomy_select','vital_signs','',esc_html__('Select vital sign','doctreat') ,$vital_key);?>
										</div>
										<div class="form-group form-group-half dc-delete-group">
											<input type="text" name="vital_signs[<?php echo esc_attr($vital_key);?>][value]" value="<?php echo esc_attr($vital_val);?>" class="form-control" placeholder="<?php esc_attr_e('Value','doctreat');?>">
											<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="lnr lnr-trash"></i></a>
										</div>
									</fieldset>
								</div>
							<?php }
						}
					?>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Medical History','doctreat');?>:</h4>
					</div>
					<div class="dc-formtheme dc-userform">
						<fieldset>
							<div class="form-group">
								<textarea name="medical_history" class="form-control" placeholder="<?php esc_attr_e('Your Patient Medical History','doctreat');?>"><?php echo do_shortcode($medical_history);?></textarea>
							</div>
						</fieldset>
					</div>
				</div>
				<div class="dc-dashboardbox dc-prescriptionbox dc-medications">
			
					<div class="dc-formtheme dc-userform" id="dc-medican-html">
				
						<?php
							if( !empty($medicine) ){
								foreach( $medicine as $key => $values ){
                                 

									$name_val				= !empty($values['name']) ? $values['name'] : '';
									$medicine_types_val		= !empty($values['medicine_types']) ? $values['medicine_types'] : '';
									$medicine_duration_val	= !empty($values['medicine_duration']) ? $values['medicine_duration'] : '';
									$medicine_usage_val		= !empty($values['medicine_usage']) ? $values['medicine_usage'] : '';
                                    $detail_val				= !empty($values['detail']) ? $values['detail'] : '';
                                    
                                    $medicine_types 		= !empty($values['medicine_types']) ? doctreat_get_term_by_type('id', $values['medicine_types'], 'medicine_types','name') : '';
									$medicine_types			= !empty($medicine_types) ? $medicine_types : '';
									
									 $price_val = !empty($values['price']) ? $values['price'] : '';
									
								
								?>
									<div class="dc-visal-sign dc-medician-<?php echo esc_attr($key);?>">
										<fieldset>
											<div class="form-group form-group-half">
											<label for="">Médicament</label>
												<input type="text" name="medicine[<?php echo esc_attr($key);?>][name]" class="form-control" value="<?php echo esc_attr($name_val);?>" placeholder="<?php esc_attr_e('Name','doctreat');?>" readonly>
											</div>
                                            <div class="form-group form-group-half">
											<label for="">Type de médicament</label>
                                                <input class="form-control" type="text" value="<?php echo $medicine_types; ?>" placeholder="<?php echo esc_html($medicine_types); ?>" readonly>
                                            </div>
                                           <div class="form-group form-group-half dc-hide-form">
												<?php do_action( 'doctreat_get_texnomy_select','medicine_types','medicine['.esc_attr($key).'][medicine_types]',esc_html__('Select type','doctreat') ,$medicine_types_val,'medicine_types-.'.esc_attr($key).'');?>
											</div>
											<div class="form-group form-group-half dc-hide-form">
												<?php do_action( 'doctreat_get_texnomy_select','medicine_duration','medicine['.esc_attr($key).'][medicine_duration]',esc_html__('Select medicine duration','doctreat') ,$medicine_duration_val,'medicine_duration-'.esc_attr($key).'');?>
											</div>
											<div class="form-group form-group-half dc-hide-form">
											
												<?php do_action( 'doctreat_get_texnomy_select','medicine_usage','medicine['.esc_attr($key).'][medicine_usage]',esc_html__('Select medician Usage','doctreat') ,$medicine_usage_val,'medicine_usage-'.esc_attr($key).'');?>
											</div>
                                            <!-- INPUT PRICE -->
											<div class="form-group">
											<label for="">Prix</label>
												<input type="text" name="medicine[<?php echo esc_attr($key);?>][price]" class="form-control" value="<?php echo esc_attr($price_val);?>" placeholder="<?php esc_attr_e('Mettre le prix du médicament','doctreat');?>">
											</div>
                                           
										</fieldset>
									</div>
								<?php
								}
							}
						?>
					</div>
				</div>

				<!-- Pharmacy Field -->
				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Pharmacy','doctreat');?>:</h4>
					</div>
					<div class="form-group form-group-half">
						<span class="dc-select">
							<?php
								wp_dropdown_users(array('name' => 'pharmacy1', 'role' => 'pharmacies' ,'show_option_none' => esc_html__('Select a Pharmacy', 'doctreat'), 'selected' => $pharmacy1));
							?>
							<?php // do_action('doctreat_get_pharmacies_list','pharmacy1',$pharmacy1);?>
						</span>
						<?php
							// $pharmacy_list = array();
							// $args_ph = array(
							// 		'role'    => 'pharmacies',
							// 		'orderby' => 'user_nicename',
							// 		'order'   => 'ASC'
							// );
							// $users_ph = get_users( $args_ph );


							// foreach ( $users_ph as $user_ph ) {
							// 	$pharmacy_list[]= $user_ph->display_name;
							// }


						?>

					</div>
				</div>
				<!-- End Pharmacy Field -->
                
			     	<!-- Etat de prescription Field -->
					 <div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Etat de la prinscription','doctreat');?>:</h4>
					</div>
					<div class="form-group">
					
                    <select class="form-control form-control-lg" name="prescription_status">
					    <option value="">Oû en êtes vous avec la prinscription?</option>
					    <option value="En cours" <?php if($prescription_status == "En cours") echo("selected")?>>En cours</option>
						<option  value="Terminer"<?php if($prescription_status == "Terminer") echo("selected")?>>Terminer</option>
					</select>
					
					</div>
				</div>
				<!-- End etat prescription Field -->
                
				

				<!-- Etat de l'ordonnance field -->
				<div class="dc-dashboardbox dc-prescriptionbox">
					<div class="dc-title">
						<h4><?php esc_html_e('Etat de l\'ordonnance','doctreat');?>:</h4>
					</div>
					<div class="form-group">
				
                    <select class="form-control form-control-lg" name="medicine_status">
					    <option value="">Oû en êtes vous avec l'ordonnance?</option>
					    <option value="En cours" <?php if($medicine_status == "En cours") echo("selected")?>>En cours</option>
						<option  value="Terminer"<?php if($medicine_status == "Terminer") echo("selected")?>>Terminer</option>
					</select>
					
					</div>
				</div>
				<!-- End etat de l'ordonnance Field -
                
				<!-- Etat de livraison Field -->
				<div class="dc-dashboardbox dc-prescriptionbox dc-hide-form">
					<div class="dc-title">
						<h4><?php esc_html_e('Etat de la livraison des médicaments','doctreat');?>:</h4>
					</div>
					<div class="form-group">
					
                    <select class="form-control" name="delivery_status">
					    <option value="">Oû en êtes vous avec la livraison?</option>
					    <option value="En cours" <?php if($delivery_status == "En cours") echo("selected")?>>En cours</option>
						<option  value="Terminer"<?php if($delivery_status == "Terminer") echo("selected")?>>Terminer</option>
					</select>
				
					</div>
				</div>
			    <!-- End etat de livraison Field  -->


					<div class="dc-updatall">
					<?php wp_nonce_field('dc_prescription_submit_data_nonce', 'prescription_submit'); ?>
					<i class="ti-announcement"></i>
					<span onclick="doctreat_print();"><?php esc_html_e('Update all the latest changes made by you, by just clicking on “Save &amp; Update button.', 'doctreat'); ?></span>
					<a class="dc-btn dc-update-prescription" data-booking_id="<?php echo intval( $booking_id ); ?>" href="javascript:;"><?php esc_html_e('Save &amp; Update', 'doctreat'); ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
      </div>
	  
	
      </div>
     
    </div>
  </div>
 </div> 

<?php

// foreach($_POST as $key=>$value)
// {
// 	echo $_POST[$key];
// }

//if(isset( $_POST['submit'] )) {

	
//var_dump ($user_identity);
	
//var_dump($patient_profile_id);
	//echo $prescription['_patient_name'];
 
	// if ( $pharmacy_id == $user_identity ) {
    //    echo $prescription['_patient_name'];
	// }

	
	//$prescription_id = $post ->ID;
//   $prescription	= get_post_meta($prescription_id,'_detail', true);
//   $pharmacy_id    = $prescription['_pharmacy1'];
//   $patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
//    $patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
   

  //  global $wpdb;
// 	for ($i=0;$i<=100;$i++){
// 		//echo $_POST['medoc'][$i]."<br>";
//       $name = $_POST['medoc'][$i];
//       $type = $_POST['type'][$i];
// 	  $prix = $_POST['prix'][$i];
// 	 // $totalPrix = sum($_POST['prix'][$i]);
// 	  //echo $totalPrix;
// 	  $dateo = date("Y-m-d");
// 	  if( (!empty($name)) && (!empty($type)) && (!empty($prix)) ) {

		
// 		//$update = $wpdb->query ("UPDATE Customers SET ContactName = 'Alfred Schmidt', City= 'Frankfurt' WHERE CustomerID = 1;
// 		$insert = $wpdb->query("INSERT INTO `ordonnance`(`medicament`, `type` , `prix`, `prescription_id`, `pharmacie_id`, `status_ordonnance`, `date`) VALUES ('$name', '$type', '$prix', '$prescription_id', '$user_identity', 'validé', '$dateo')");
// 	  }
// 	//   $insert = $wpdb->query("INSERT INTO `ordonnance`(`prescription_id`, `pharmacie_id`, `medicament`, `prix`, `patient_id`, `patient_name`, `pharmacie_name`, `status`, `date`) 
// 	//                           VALUES (
// 	// 							  '$prescription_id', 
// 	// 						      '$pharmacy_id',
// 	// 							  '$name',
// 	// 							  '$prix',
// 	// 							  '$patient_id',
// 	// 							  '',
// 	// 							  '',
// 	// 							  '',
// 	// 							  '',
// 	// 							  '',
// 	// 						  )");
// 		}
// //	print_r($_POST['medoc']);
// 	//var_dump($_POST['medoc']);
//  //$name = $_POST['medoc'];
// // $prix = $_POST['prix'];
//  //foreach( $name as $key => $d )    {
// 	  // echo $name[$key];
// //     global $wpdb;

// // 	$name[] = $_POST['medoc'];
// 	//echo $name[$key];
// 	//echo $prix[$key];
// 	//var_dump($_POST['medoc']);
// 	//$prix = $_POST['prix'];
// // $table_name = $wpdb->"ordonnance";
// // $wpdb->insert( $table_name, array(
// //     'medicament' => $name,
// //     'prix' => $prix,
// // ) );
// 	// global $wpdb;
// 	// foreach($_POST as $info)
// 	// {
// 	 	//$insert = $wpdb->query("INSERT INTO `ordonnance`(`medicament`, `prix`) VALUES ('$name', '$prix')");
		
// 	// }
    
// //}
// }


//$colorList[] = "red"; $colorList[] = "green"; $colorList[] = "blue"; $colorList[] = "black"; $colorList[] = "white"; $colorList[] = "marron";




   //$info = isset( ($_POST['medoc']) &&($_POST['type']) &&($_POST['prix']) ) ? $_POST['medoc'] $_POST['type'] $_POST['prix'] : array();
   //print_r($info);
//   $type = isset($_POST['type']) ? $_POST['type'] : array();
//   print_r($type);
//   $prix = isset($_POST['prix']) ? $_POST['prix'] : array();
//   print_r($prix);
?>

<script type="text/template" id="tmpl-load-dc-visals">
	<div class="dc-visal-sign dc-visal-{{data.id}}">
		<fieldset>
			<div class="form-group form-group-half">
				<?php do_action( 'doctreat_get_texnomy_select','vital_signs','',esc_html__('Select vital sign','doctreat') ,'');?>
			</div>
			<div class="form-group form-group-half dc-delete-group">
				<input type="text" name="vital_signs[{{data.id}}][value]" value="{{data.value}}" class="form-control" placeholder="<?php esc_attr_e('Value','doctreat');?>">
				<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="lnr lnr-trash"></i></a>
			</div>
		</fieldset>
	</div>
</script>
<script type="text/template" id="tmpl-load-dc-medician">
	<div class="dc-visal-sign dc-medician-{{data.id}}">
		<fieldset>
			<div class="form-group form-group-half">
				<input type="text" name="medicine[{{data.id}}][name]" class="form-control" value="{{data.name}}" placeholder="<?php esc_attr_e('Name','doctreat');?>">
			</div>
			<!-- INPUT PRICE -->
			<div class="form-group form-group-half">
				<input type="text" name="price[{{data.id}}][price]" class="form-control" value="{{data.price}}" placeholder="<?php esc_attr_e('Price','doctreat');?>">
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doctreat_get_texnomy_select','medicine_types','medicine[{{data.id}}][medicine_types]',esc_html__('Select type','doctreat') ,'','medicine_types-{{data.id}}');?>
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doctreat_get_texnomy_select','medicine_duration','medicine[{{data.id}}][medicine_duration]',esc_html__('Select medicine duration','doctreat') ,'','medicine_duration-{{data.id}}');?>
			</div>
			<div class="form-group form-group-half">
				<?php do_action( 'doctreat_get_texnomy_select','medicine_usage','medicine[{{data.id}}][medicine_usage]',esc_html__('Select medician Usage','doctreat') ,'','medicine_usage-{{data.id}}');?>
			</div>
			<div class="form-group dc-delete-group">
				<input type="text" name="medicine[{{data.id}}][detail]" value="{{data.detail}}" class="form-control" placeholder="<?php esc_attr_e('Add Comment','doctreat');?>">
				<a href="javascript:;" class="dc-deletebtn dc-remove-visual"><i class="lnr lnr-trash"></i></a>
			</div>
		</fieldset>
	</div>
</script>
<?php
$js_script	= "
	jQuery(document).ready(function(){
		jQuery('.tests-".esc_js( $rand_val )."').select2({
			tags: true,
			insertTag: function (data, tag) {
				data.push(tag);
			},
			createTag: function (params) {
				return {
				id: params.term,
				text: params.term
				}
			}
		});

	} );

	";

	wp_add_inline_script( 'doctreat-dashboard', $js_script, 'after' );