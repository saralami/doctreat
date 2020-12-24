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


$args_ph = array(
	'posts_per_page' 	=> $show_posts,
		'post_type' 		=> 'prescription',
);





$query 	= new WP_Query($args_ph);
$count_post = $query->found_posts;

$posts = $query->posts;

$all_prescriptions = array();

$all_prescriptions = $posts;

//var_dump($all_prescriptions);
foreach($all_prescriptions as $prescription) {
   $prescription_id = $prescription->ID;
	 $prescriptionn	= get_post_meta($prescription_id,'_detail', true);
	$pharmacy_id  = $prescriptionn['_pharmacy1'];
	$ordonnances = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE pharmacie_id = $user_identity");
	//var_dump($ordonnances);
	 foreach($ordonnances as $ord){
	 	if($ord->prescription_id != $prescription_id) {
			echo $prescription_id;
	// 		// $all_prescription_pharmacy[] = $prescription; 
	// 	    // var_dump($all_prescription_pharmacy);
		}
	
	}
	// 
	//$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
	//$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
//var_dump($prescription);
	// if($pharmacy_id == $user_identity){
		
	// 	//echo $prescription['_patient_name'];
    //   $all_prescription_pharmacy[] = $prescription; 
	//  // var_dump($all_prescription_pharmacy);
	//  $id_prescription [] =  $prescription_id;
	// }
	
	}
	
	// foreach($id_prescription as $id_prescription){
	// //	echo $id_prescription;
    //      foreach($ordonnances as $ord){
	// 		// echo $ord->prescription_id;
			
	// 		if($ord->prescription_id !==  $id_prescription){
	// 			$prescription_pharmacy_not_in_ord[] = $id_prescription;
	// 			var_dump($prescription_pharmacy_not_in_ord);
				
				
	// 		}

	// 		else{
	// 			//echo $id_prescription;
				
	// 			echo "toutes vos ordonnances ont ete valide";
	// 		}
	// 	 }

	// }

	
	//var_dump($prescription_pharmacy_not_in_ord);
	//$ordonnances = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE prescription_id != $prescription_id ");
	//var_dump($ordonnances);

	// foreach( $all_prescription_pharmacy as $prescription_pharmacy){
	// 	 $prescription_id = $prescription_pharmacy->ID;
	// 	// $id_prescription [] =  $prescription_id;
	// }
	

	// foreach($all_prescription_pharmacy as $prescription_pharmacy) {
	// 	//var_dump($prescription_pharmacy);
	//     $prescription_id = $prescription_pharmacy->ID;
	// 	$prescription	= get_post_meta($prescription_
	//id,'_detail', true);
	// 	$pharmacy_id  = $prescription['_pharmacy1'];
	//    $patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
	//    $patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
	//    $prescription_pharmacy_not_in_ordonnance[] = $prescription_pharmacy; 
	//    //var_dump($prescription_pharmacy_not_in_ordonnance);
	// //    if($pharmacy_id == $user_identity){
	// // 	   //echo $prescription['_patient_name'];
	// // 	 $all_prescription_pharmacy[] = $prescription; 
	// // 	 //echo $prescription_id;
   
	// //    }
	   
	//    }
	//var_dump($all_prescription_pharmacy);
	// foreach( $all_prescriptions as $prescription ) {

	//  }
	//var_dump($all_prescription_pharmacy);
	// foreach( $all_prescription_pharmacy as $prescription_pharmacy ) {
	// 	$prescription_id = $post ->ID;
	// 	//$pharmacy_id = $prescription['_pharmacy1'];
	// 	//if($pharmacy_id == $user_identity){
	// 		//$all_prescription_pharmacy[] = $prescription; 
			
	// 	//}
	// 	echo $prescription_pharmacy->prescription_id;
	// 	var_dump($prescription_pharmacy);
		
	// }

//	var_dump($all_prescription_pharmacy);
	
	//echo $prescription_id = $post->ID;
?>

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
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>

<?php 

//if( !empty($all_prescriptions) ){
	//$prescription_id = $post ->ID;
//	$ordonnances = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE prescription_id != $prescription_id ");
	//var_dump($ordonnances);
//	foreach($ordonnances as $ord){
	//	echo $ord->prescription_id;
	//}
			//foreach($ordonnance_not_in as $ordonnance){
		///		echo $ordonnance->$prescription_id;
			//}
	// foreach( $all_prescriptions as $prescription ) {
		
		//if($prescription['_pharmacy1'] == $user_identity){
		//	echo $prescription_id;
			
			
	//	}
	
	// 	//$ordonnance = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE prescription_id = $prescription_id AND status_ordonnance = 'en cours' ");
	// 	//$ordonnance = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE prescription_id = $prescription_id ");
	
//	 }

//}

//if ( $pharmacy_id == $user_identity ) { 

	// if(!empty($ordonnance)){
	// 	foreach($ordonnance as $ord)
	// 	{ 
	// 		if($ord->prescription_id == $prescription_id){
	// 			echo "jai des ordonnances";
	// 		}
			
	// 	}
	// }

	//var_dump($prescriptions);
	
//}
?>
		
		
		
<!-- $prescription_id = $post ->ID;
	$prescription	= get_post_meta($prescription_id,'_detail', true);
	$pharmacy_id    = $prescription['_pharmacy1'];
	$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
	$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);	
			 -->
			

	




