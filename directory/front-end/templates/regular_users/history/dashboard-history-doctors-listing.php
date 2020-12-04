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

global $current_user, $post;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;

// $date_format		= get_option('date_format');
// $appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : '';
 $show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
// $pg_page 			= get_query_var('page') ? get_query_var('page') : 1; 
// $pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1;
// $paged 				= max($pg_page, $pg_paged);
// $order 	 			= 'ASC';
// $sorting 			= 'ID';

// $args = array(
// 	'posts_per_page' 	=> $show_posts,
//     'post_type' 		=> 'booking',
//     'orderby' 			=> $sorting,
//     'order' 			=> $order,
// 	'author'			=> $user_identity,
// 	'post_status' 		=> array('publish','pending','cancelled'),
//     'paged' 			=> $paged,
//     'suppress_filters'  => false
// );

// if( !empty( $appointment_date ) ) {
// 	$meta_query_args[] = array(
// 							'key' 		=> '_appointment_date',
// 							'value' 	=> $appointment_date,
// 							'compare' 	=> '='
// 						);
// 	$query_relation 	= array('relation' => 'AND',);
// 	$args['meta_query'] = array_merge($query_relation, $meta_query_args);
// }

// $query 		= new WP_Query($args);
 $count_post = $query->found_posts;

$args = array(
	'posts_per_page' 	=> $show_posts,
	//'post_status' 		=> array('publish','pending','cancelled'),
	'post_type' 		=> 'booking',
	'author'			=> $user_identity
);


$query 	= new WP_Query( $args );
$posts = $query->posts;
$all_doctors_ids = array();
$name = array();

$count_post = $query->found_posts;



foreach( $posts as $post ) {
$all_doctors_ids[] = get_post_meta( $post->ID,'_doctor_id',true);

}
$doctors_ids = array_unique($all_doctors_ids);
$count_doctors = count ($doctors_ids);
$width		= 40;
$height		= 40;

//doctor
$args_doc = array(
	//'posts_per_page' 	=> $show_posts,
	//'post_status' 		=> array('publish','pending','cancelled'),
	'post_type' 		=> 'doctors',
	//'author'			=> $user_identity
);


$query_doc 	= new WP_Query( $args_doc );
$posts_doc = $query_doc->posts;
//$doc_url = array();
//var_dump($posts_doc);
//$name = array();

//$count_post = $query->found_posts;
//fin doctor
//$flag 		= rand(9999, 999999);
//var_dump($name);


?>




<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
    <a class="dc-btn dc-btn-sm" href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('history-regular', $user_identity,'','listing'); ?>">
        <?php esc_html_e('Retour au tableau de bord des historiques', 'doctreat'); ?> 
    </a> 
    <br>
    <br>
	<div class="dc-dashboardbox dc-apointments-wrap dc-apointments-wraptest">	

			<div class="dc-apointments-holder dc-apointments-holder-test">
				<div class="dc-appointment-border">
					<div class="dc-dashes">	
						<div class="dc-main-circle">
							<div class="dc-circle-raduis">
								<div class="rounded-circle dc-circle"></div>
							</div>
						</div>										
					</div>
				</div>

				<div class="dc-recentapointdate-holder dc-recentapoint-test">
					<div class="dc-recentapointdate dc-recentapointdate-test">
						<h2><?php echo intval($count_doctors);?></h2>
						<span><?php $count_doctors <= 1 ? esc_html_e('Docteur','doctreat') : esc_html_e('Docteurs','doctreat') ;?></span>
					</div>
				</div>
			</div>

		<div class="dc-searchresult-head">
			<div class="dc-title"><h3><?php esc_html_e('Medecins','doctreat');?></h3></div>
		</div>
        
		<?php if( !empty($doctors_ids) ){ ?>
			<div class="dc-recentapoint-holder dc-recentapoint-holdertest">
				<?php  

				foreach( $doctors_ids as $doctor_id ) {
				    $name = doctreat_full_name( $doctor_id );
					$thumbnail   	= doctreat_prepare_thumbnail($doctor_id, $width, $height);
					$doctor_url		= get_the_permalink( $doctor_id );
					$doctor_url		= !empty( $doctor_url ) ? $doctor_url : '';
					echo $doctor_id;
					//$services		= get_post_meta($booking_id,'_booking_service', true);
					$specialities   =  doctreat_get_post_meta($doctor_id, 'am_specialities');	
					foreach ( $specialities as $key => $specialities) { 
						$specialities_title	= doctreat_get_term_name($key ,'specialities');
						$logo 				= get_term_meta( $key, 'logo', true );
						$logo				= !empty( $logo['url'] ) ? $logo['url'] : '';
						$services			= !empty( $specialities ) ? $specialities : '';
						$service_count		= !empty($services) ? count($services) : 0;
						$email		= get_post_meta($doctor_id,'email', true);
					//$service_title	= doctreat_get_term_name($key ,'services');
					//$services       =  get_post_meta($doctor_id, 'booking_service', true);	
					//var_dump($specialities_title);
					}	
					
                	?>
         
						<div class="dc-recentapoint">
							<div class="dc-recentapoint-content">
								<?php if( !empty( $thumbnail ) ){?>
									<a href="<?php echo esc_url( $doctor_url );?>">
										<figure><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>"></figure>
									</a>
								<?php } ?>
								<div class="dc-recent-content">
									<span><?php echo esc_html( $name );?></span>
									<span><?php echo esc_html( $email );?></span>
									<a href="javascript:;" class="dc-btn dc-btn-sm" id="dc-doctor_btn" data-id="<?php echo intval($doctor_id); ?>"><?php esc_html_e('View Details','doctreat');?></a>
								</div>
                            </div>
						</div>
					<?php
						
						}   //}
						wp_reset_postdata();
						if (!empty($count_post) && $count_post > $show_posts) {
							doctreat_prepare_pagination($count_post, $show_posts);
						}
					?>
			</div>
		<?php } else { ?>
			<?php do_action('doctreat_empty_records_html','dc-empty-booking dc-emptyholder-sm',esc_html__( 'Aucun Docteur .', 'doctreat' ));?>
		<?php } ?>
	</div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
	<div class="dc-dbsectionspacetest">
		<div class="dc-dashboardbox" id="dc-doctor_services">
		     
		</div>
	</div> 
</div>
<?php 
	   
//	$current_date		= date('Y-m-d');
//	$appointment_date	= !empty($appointment_date) ? $appointment_date : $current_date;
	//$array_date		= !empty($appointment_date) ?  array( 'title' => 'current', 'start'	=> $appointment_date ) : '';
	//do_action('doctreat_doctors_details');
?>
	<script>

	//Service details
	jQuery(document).on('click', '#dc-doctor_btn', function (event) {
        'use strict';
        event.preventDefault();
        var _this 	= jQuery(this);        
        var _id     = parseInt(_this.data('id'));
        jQuery('body').append(loader_html);
		jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					action	: 'doctreat_get_doctor_byID',
					id		: _id,
					dashboard	: 'yes'
				},
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.dc-preloader-section').remove();
					if (response.type === 'success') {
						jQuery('#dc-doctor_services').html(response.booking_data);
					} else {
						jQuery('#dc-doctor_services').html('');
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					}
				}
			});
    });
//   // "some info here"
// 	function myFunction(element) {
// 		//var myDiv = document.querySelector('#doctor');
//         //console.log(myDiv.data.id);
// 			//var id = data(id);
			
// 	  //document.getElementById("demo").style.removeProperty('visibility');
// 	  //document.getElementById("demo").hinnerHtml('visibility');
// 	 // var doc_id = id;

// 	 var testVar = document.getElementById("doctor");
// 	 var show = testVar.getAttribute("data-id");
// 	 console.log(show);
// 	}


	</script>