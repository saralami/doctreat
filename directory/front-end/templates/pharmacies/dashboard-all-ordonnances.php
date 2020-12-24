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



//Select ordonnances
 //$ordonnance = $wpdb->get_results("SELECT * FROM `ordonnance` WHERE prescription_id = $prescription_id AND status_ordonnance = 'en cours' ");

//var_dump($ordonnance);
//echo $prescription_id_ord;
// $prescription_id_ord = !empty($select) ? $select : [];
//  foreach($prescription_id_ord as $ord){
//  	//$prescription_id_ord[] = $ord->prescription_id;
//  	echo $ord;
//  }


  

//	if ( $pharmacy_id == $user_identity ) {
		
		
 //foreach($ordonnance as $ord){
 	//$prescription_id_ord[] = $ord->prescription_id;
 //	echo $ord->status_ordonnance;
 //}
		//the_title( '<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>' );
		//echo '<pre>'; print_r($prescription); echo '</pre>';
		?>
<!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
	<div class="dc-dbsectionspacetest"> -->
		<!-- <div class="dc-dashboardbox" id="dc-booking_service_details"> -->
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

						while ( $query->have_posts() ) : $query->the_post();
						global $post;

						$prescription_id = $post ->ID;
						$prescription	= get_post_meta($prescription_id,'_detail', true);
						$pharmacy_id    = $prescription['_pharmacy1'];
						$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
						$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
				  
				  if ( $pharmacy_id == $user_identity ) { 
					  
					 // echo $user_identity;
					  ?>
				   <tr>
				     <td> <?php echo $prescription['_patient_name'];?> </td>
				     <td> <?php echo $prescription['_phone']; ?> </td>
				     <td> <?php echo $prescription['_age'] ;?> </td>
				     <td> <?php echo $prescription['_gender'] ;?> </td>
				     <td>
				      
                          <!-- Button trigger modal -->
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
						Ordonnance
						</button>
					
					 </td>
				  </tr>	 

				
		<?php
		 }
		endwhile;


	?>





	<?php  ?>

</div>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Ordonnance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		 <?php 
		    $medicine = !empty($prescription['_medicine']) ? $prescription['_medicine'] : array();
							  
			if( !empty($medicine) ){
			  foreach( $medicine as  $vals ){
				  $name					= !empty($vals['name']) ? esc_html($vals['name']) : '';
	  $medicine_duration 		= !empty($vals['medicine_duration']) ? get_term_by('id', $vals['medicine_duration'], 'medicine_duration',ARRAY_A) : '';
	  $medicine_duration		= !empty($medicine_duration['name']) ? $medicine_duration['name'] : '';
	  $medicine_types 		= !empty($vals['medicine_types']) ? doctreat_get_term_by_type('id', $vals['medicine_types'], 'medicine_types','name') : '';
	  $medicine_types			= !empty($medicine_types) ? $medicine_types : '';

	  $medicine_usage 			= !empty($vals['medicine_usage']) ? doctreat_get_term_by_type('id', $vals['medicine_usage'], 'medicine_usage','name') : '';
	  $medicine_usage				= !empty($medicine_usage) ? $medicine_usage : '';

	  $detail			= !empty($vals['detail']) ? esc_html($vals['detail']) : '';
	 
	 

	  ?>
	  <?php  
	    //echo esc_html($name).'<br/>'; 
		//global $wpdb;
		//$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}options WHERE option_id = 1", OBJECT );
	  //echo esc_html($medecine_duration).'<br/>'; 
		//echo esc_html($medicine_types).'<br/>'; 
	   // echo esc_html($medicine_usage).'<br/>fin'; 
	//    global $wpdb;
	//    $select = $wpdb->query("SELECT *FROM `ordonnance`");
	//    var_dump($select);
	  ?>
	  <form method="POST">
	   <div class="row">
	  
	    <div class="form-group col-lg-4">
	        <input form-control" type="text" name="medoc[]" value="<?php echo $name;?>" placeholder="<?php echo esc_html($name); ?>" readonly>
		 </div>
		 <div class="form-group col-lg-4">
	        <input form-control" type="text" name="type[]" value="<?php echo $medicine_types; ?>" placeholder="<?php echo esc_html($medicine_types); ?>" readonly>
		 </div>
		 <div class="form-group col-lg-4">
	     <input type="text" name="prix[]" />
		 </div>
	   </div>
	    
	   <?php
	    
			  }
			}
		 ?>
	      <button type="submit" name="submit" class="btn btn-primary">Valider</button>
	  </form>
	
	  
	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
       
      </div>
    </div>
  </div>
</div>

<?php

// foreach($_POST as $key=>$value)
// {
// 	echo $_POST[$key];
// }

if(isset( $_POST['submit'] )) {

	
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
	for ($i=0;$i<=100;$i++){
		//echo $_POST['medoc'][$i]."<br>";
      $name = $_POST['medoc'][$i];
      $type = $_POST['type'][$i];
	  $prix = $_POST['prix'][$i];
	 // $totalPrix = sum($_POST['prix'][$i]);
	  //echo $totalPrix;
	  $dateo = date("Y-m-d");
	  if( (!empty($name)) && (!empty($type)) && (!empty($prix)) ) {

		
		//$update = $wpdb->query ("UPDATE Customers SET ContactName = 'Alfred Schmidt', City= 'Frankfurt' WHERE CustomerID = 1;
		$insert = $wpdb->query("INSERT INTO `ordonnance`(`medicament`, `type` , `prix`, `prescription_id`, `pharmacie_id`, `status_ordonnance`, `date`) VALUES ('$name', '$type', '$prix', '$prescription_id', '$user_identity', 'validé', '$dateo')");
	  }
	//   $insert = $wpdb->query("INSERT INTO `ordonnance`(`prescription_id`, `pharmacie_id`, `medicament`, `prix`, `patient_id`, `patient_name`, `pharmacie_name`, `status`, `date`) 
	//                           VALUES (
	// 							  '$prescription_id', 
	// 						      '$pharmacy_id',
	// 							  '$name',
	// 							  '$prix',
	// 							  '$patient_id',
	// 							  '',
	// 							  '',
	// 							  '',
	// 							  '',
	// 							  '',
	// 						  )");
		}
//	print_r($_POST['medoc']);
	//var_dump($_POST['medoc']);
 //$name = $_POST['medoc'];
// $prix = $_POST['prix'];
 //foreach( $name as $key => $d )    {
	  // echo $name[$key];
//     global $wpdb;

// 	$name[] = $_POST['medoc'];
	//echo $name[$key];
	//echo $prix[$key];
	//var_dump($_POST['medoc']);
	//$prix = $_POST['prix'];
// $table_name = $wpdb->"ordonnance";
// $wpdb->insert( $table_name, array(
//     'medicament' => $name,
//     'prix' => $prix,
// ) );
	// global $wpdb;
	// foreach($_POST as $info)
	// {
	 	//$insert = $wpdb->query("INSERT INTO `ordonnance`(`medicament`, `prix`) VALUES ('$name', '$prix')");
		
	// }
    
//}
}


//$colorList[] = "red"; $colorList[] = "green"; $colorList[] = "blue"; $colorList[] = "black"; $colorList[] = "white"; $colorList[] = "marron";




   //$info = isset( ($_POST['medoc']) &&($_POST['type']) &&($_POST['prix']) ) ? $_POST['medoc'] $_POST['type'] $_POST['prix'] : array();
   //print_r($info);
//   $type = isset($_POST['type']) ? $_POST['type'] : array();
//   print_r($type);
//   $prix = isset($_POST['prix']) ? $_POST['prix'] : array();
//   print_r($prix);
?>

















<?php if( $query->have_posts() ){ ?>



<div>
  <?php 

while ( $query->have_posts()) : $query->the_post();

$prescription_id = $post->ID;
$prescription	= get_post_meta($prescription_id,'_detail', true);
$pharmacy_id    = $prescription['_pharmacy1'];
$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);
 ?>

<h2>
<?php 
     //echo $pharmacy_id. "   "; 
     
?>
</h2>
</div>

<?php 

endwhile;
}