
  
<?php
/**
 *
 * The template part for displaying appointment in listing
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wpdb, $post;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;

$date_format		= get_option('date_format');
$appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : '';
$show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 			= get_query_var('page') ? get_query_var('page') : 1;
$pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1;
$paged 				= max($pg_page, $pg_paged);
$order 	 			= 'DESC';
$sorting 			= 'ID';
?>


	<?php

	$args_ph = array(
		'posts_per_page' 	=> $show_posts,
			'post_type' 		=> 'prescription',
	);
    $query 		= new WP_Query($args_ph);
	$count_post = $query->found_posts;

	$posts = $query->posts;
	
		
// Booking
	// $args = array(
	// 	'posts_per_page' 	=> $show_posts,
	// 	'post_type' 		=> 'booking',
	// 	'post_status' 		=> array('publish'),
	// );
    // $querybooking = new WP_Query($args);
	//$count_booking = $query->found_posts;
	$medicine_status = !empty($_POST['medicine_status']) ? $_POST['medicine_status'] : '';
	//$medicine = !empty($_POST['medicine']) ? ($_POST['medicine']) : array();
	//while ( $query->have_posts() ) : $query->the_post();
	  
	//endwhile;

	//var_dump($ord);
//if(isset($_POST)){

//}
	?>
	 <form action="" method="POST">
	  	<!-- Etat de l'ordonnance field -->
		 
					<div class="form-group">
					<select class="form-control-lg" name="medicine_status">
					    <option value="">Toutes les ordonnances</option>
					    <option value="En coursP" <?php if($medicine_status == "En coursP") echo("selected")?>>Pas encore validé par le pharmacien</option>
						<option  value="TerminerP"<?php if($medicine_status == "TerminerP") echo("selected")?>>Validé par le pharmacien</option>
						<option  value="En cours"<?php if($medicine_status == "En cours") echo("selected")?>>Pas encore livré</option>
						<option  value="Terminer"<?php if($medicine_status == "Terminer") echo("selected")?>>Livré</option>
					</select>
				  </div>
			<!-- End etat de l'ordonnance Field  -->
			<div class="form-group">
			    <button type="submit" class="btn btn-outline-primary">Rechercher</button>
			</div>
			
	 </form>
		<div class="table-responsive">
			<table class="table table-hover table-bordered border-primary">
			
				<thead>
					<tr>
						<th>Nom complet</th>
						<th>Téléphone</th>
						<th>Age</th>
						<th>Sexe</th>
						<th>Ordonnance</th>
						
					</tr>
				</thead>
				<tbody>
			
				  <?php 
					//echo $medicine_status;
					
						//while ( $querybooking->have_posts() ) : $querybooking->the_post();
						
						// //$booking_id = $post->ID;
						//var_dump($medicine_status);
                        // endwhile;
						//while ( $query->have_posts() ) : $query->the_post();
						//foreach($ord as $ordo) {
						//global $post;
                        // var_dump($ordo);
//echo $prescription_id = $ordo->ID;

						foreach($posts as $post){
							$prescription_id = $post->ID;
                        //var_dump($post);
						$prescription	= get_post_meta($prescription_id,'_detail', true);
						$pharmacy_id    = $prescription['_pharmacy1'];
						//$pharmacy	= get_post_meta($pharmacy_id,'_nicename', true);
						//$pharmacy1	= !empty($prescription['_pharmacy1']) ? $prescription['_pharmacy1'] : '';
                      // var_dump($pharmacy1);
						//$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
						//$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
						$booking_id	= get_post_meta( $prescription_id, '_booking_id', true );
						//$medicine = !empty($prescription['_medicine']) ? $prescription['_medicine'] : array();
						$med_status = !empty($prescription['_medicine_status']) ? $prescription['_medicine_status'] : '';
						//$prescription_status = !empty($prescription['_prescription_status']) ? $prescription['_prescription_status'] : array();
				 // if ( $pharmacy_id == $user_identity ) { 
					//echo $booking_id;
				//	if(!empty($medicine)){
					$prescription_url	= !empty($booking_id) ?Doctreat_Profile_Menu::doctreat_profile_menu_link('prescription', $user_identity,true,'view').'&booking_id='.$booking_id : '';
						if(empty($medicine_status)){

					
					 ?>
				   <tr>
				     <td> <?php echo $prescription['_patient_name'];?></td>
				     <td> <?php echo $prescription['_phone']; ?> </td>
				     <td> <?php echo $prescription['_age'] ;?> </td>
				     <td> <?php echo $prescription['_gender'];?></td>
				     <td>
					     <a href="<?php echo esc_url($prescription_url);?>" class="btn btn-primary">Ordonnance</a>
					 </td>
				  </tr>	 

				 
                 <?php
					  }

					  if( ($med_status == "Terminer") && ($medicine_status == "Terminer" ) ){
				?>

			   
                 
                   <tr>
				     <td> <?php echo $prescription['_patient_name'];?></td>
				     <td> <?php echo $prescription['_phone']; ?> </td>
				     <td> <?php echo $prescription['_age'] ;?> </td>
				     <td> <?php echo $prescription['_gender'];?></td>
				     <td>
					     <a href="<?php echo esc_url($prescription_url);?>" class="btn btn-primary">Ordonnance</a>
					 </td>
				  </tr>	 
				
				<?php
					}  
					if( ($med_status == "En cours") && ($medicine_status == "En cours" ) ){
					?>
					<tr>
				     <td> <?php echo $prescription['_patient_name'];?></td>
				     <td> <?php echo $prescription['_phone']; ?> </td>
				     <td> <?php echo $prescription['_age'] ;?> </td>
				     <td> <?php echo $prescription['_gender'];?></td>
				     <td>
					     <a href="<?php echo esc_url($prescription_url);?>" class="btn btn-primary">Ordonnance</a>
					 </td>
				  </tr>	 
					<?php	
					}

					
					//}
				    }
					//endwhile;
				?>

				
                 </tbody>
				
	


</div>


















