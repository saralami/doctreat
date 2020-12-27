
  
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

// Booking
	$args = array(
		'posts_per_page' 	=> $show_posts,
		'post_type' 		=> 'booking',
		'post_status' 		=> array('publish'),
	);
    $querybooking = new WP_Query($args);
	$count_booking = $query->found_posts;


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
				
				  <?php 
         
						// while ( $querybooking->have_posts() ) : $querybooking->the_post();
						
						// //$booking_id = $post->ID;
                        
                        // endwhile;
						while ( $query->have_posts() ) : $query->the_post();
						global $post;
                         
						 $prescription_id = $post ->ID;

						

						$prescription	= get_post_meta($prescription_id,'_detail', true);
						$pharmacy_id    = $prescription['_pharmacy1'];
						$patient_id		= get_post_meta( $prescription_id, '_patient_id', true );
						$patient_profile_id	= doctreat_get_linked_profile_id($patient_id);

						$medicine = !empty($prescription['_medicine']) ? $prescription['_medicine'] : array();

				  if ( $pharmacy_id == $user_identity ) { 
				
							  
					if( !empty($medicine) ){

						foreach($medicine as $values){
							$price_val = !empty($values['price']) ? $values['price'] : '';
							if(empty($price_val)){
								//echo  "Les prix doivent etre ajoutés";
						
						
					 ?>
				   <tr>
				     <td> <?php echo $prescription['_patient_name'];?></td>
				     <td> <?php echo $prescription['_phone']; ?> </td>
				     <td> <?php echo $prescription['_age'] ;?> </td>
				     <td> <?php echo $prescription['_gender'];?></td>
				     <td>
					 <!-- <div class="dc-recent-content">
						
						<a href="javascript:;" class="dc-btn dc-btn-sm" id="dc-booking-service" data-id="<?php //echo intval($post->ID); ?>"><?php esc_html_e('View Details','doctreat');?></a>
					</div> -->
                          <!-- Button trigger modal -->
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?php echo $prescription_id; ?>">
				         Ordonnance 
						</button>
					  
					 </td>
				  </tr>	 

				  <?php
					include('modalmedecine.php');
					} 
					}
					}
					}
					endwhile;
				?>

				
                 </tbody>
				
	


</div>


















