<?php 
/**
 *
 * The template part for displaying appointment in listing Dashboard
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

$date_format		= get_option('date_format');
$appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : date('Y-m-d');
$order 	 			= 'DESC';
$sorting 			= 'ID';

$args = array(
	'posts_per_page' 	=> 5,
    'post_type' 		=> 'booking',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish','pending'),
    'suppress_filters'  => false
);

$meta_query_args[] = array(
						'key' 		=> '_doctor_id',
						'value' 	=> $linked_profile,
						'compare' 	=> '='
					);

$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);

if( !empty( $appointment_date ) ) {
	$meta_query_args[] = array(
							'key' 		=> '_appointment_date',
							'value' 	=> $appointment_date,
							'compare' 	=> '='
						);
	$query_relation 	= array('relation' => 'AND',);
	$args['meta_query'] = array_merge($query_relation, $meta_query_args);
}

$query 		= new WP_Query($args);
$count_post = $query->found_posts;

$width		= 40;
$height		= 40;
$flag 		= rand(9999, 999999);
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
	<div class="dc-dashboardbox dc-dashboardtabsholder">
		<div class="dc-dashboardboxtitle"><h2><?php esc_html_e('Recent Appointments','doctreat');?></h2></div>
		<?php if( $query->have_posts() ){ ?>
			<div class="dc-recentapoint-holder dc-recentapoint-holdertest">
				<?php
					while ($query->have_posts()) : $query->the_post(); 
						global $post;
						$post_auter	= get_post_field( 'post_author',$post->ID );
						$link_id	= doctreat_get_linked_profile_id( $post_auter );
						$name		= doctreat_full_name( $link_id );
						$name		= !empty( $name ) ? $name : ''; 

						$thumbnail      = doctreat_prepare_thumbnail($link_id, $width, $height);
						$post_status	= get_post_status( $post->ID );
						if($post_status === 'pending'){
							$post_status	= esc_html__('Pending','doctreat');
						} elseif($post_status === 'publish'){
							$post_status	= esc_html__('Confirmed','doctreat');
						} elseif($post_status === 'draft'){
							$post_status	= esc_html__('Pending','doctreat');
						}
						$ap_date		= get_post_meta( $post->ID,'_appointment_date',true);
						$ap_date		= !empty( $ap_date ) ? strtotime($ap_date) : '';

						?>
						<div class="dc-recentapoint">
							<?php if( !empty( $ap_date ) ){?>
								<div class="dc-apoint-date">
									<span><?php echo date_i18n('d',$ap_date);?></span>
									<em><?php echo date_i18n('M',$ap_date);?></em>
								</div>
							<?php } ?>
							<div class="dc-recentapoint-content dc-apoint-noti dc-noti-colorone">
								<?php if( !empty( $thumbnail ) ){?>
									<figure><img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr( $name );?>"></figure>
								<?php } ?>
								<div class="dc-recent-content">
									<span><?php echo esc_html( $name );?> <em><?php esc_html_e( 'Status','doctreat');?>: <?php echo esc_html( $post_status );?></em></span>
									<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('appointment', $user_identity,'','listing',$post->ID); ?>" class="dc-btn dc-btn-sm"><?php esc_html_e('View Details','doctreat');?></a>
								</div>
							</div>
						</div>
					<?php
						endwhile;
						wp_reset_postdata();
					?>
			</div>
			<?php 
			} else{ 
				do_action('doctreat_empty_records_html','dc-empty-booking dc-emptyholder-sm',esc_html__( 'There are no appointments.', 'doctreat' ));
			} 
		?>
	</div>
</div>