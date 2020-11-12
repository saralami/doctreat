<?php 
/**
 *
 * The template part for displaying doctors in listing like team
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $post,$current_user;
$user_identity  = $current_user->ID;
$link_id		= doctreat_get_linked_profile_id( $user_identity );
$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
$paged 			= max($pg_page, $pg_paged);

$order 			= 'DESC';
$sorting 		= 'ID';
$meta_query_args = array();

$args 			= array(
					'posts_per_page' 	=> $show_posts,
					'post_type' 		=> 'hospitals_team',
					'orderby' 			=> $sorting,
					'order' 			=> $order,
					'post_status' 		=> array('publish','pending','draft'),
					'paged' 			=> $paged,
					'suppress_filters' 	=> false
				);

$meta_query_args[] = array(
						'key' 		=> 'hospital_id',
						'value' 	=> $link_id,
						'compare' 	=> '='
					);
$query_relation 	= array('relation' => 'AND',);
$args['meta_query'] = array_merge($query_relation, $meta_query_args);

$query 				= new WP_Query($args);

$count_post 		= $query->found_posts;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
	<div class="dc-dbsectionspacetest">
		<div class="dc-dashboardbox dc-manageteam-wrap">	
			<div class="dc-searchresult-head">
				<div class="dc-title"><h3><?php esc_html_e('Manage Team','doctreat');?></h3></div>
				<div class="dc-rightarea">
					<a href="javascript:;"  class="dc-btn dc-invitation-users dc-btn-tab"><?php esc_html_e('Invite doctors','doctreat');?></a>
				</div>
			</div>
			<div class="dc-recentapoint-holder">
				<?php if( $query->have_posts() ){ ?>
				<?php 
					while ($query->have_posts()) : $query->the_post();
						global $post;
						$doctors_id 			= get_post_field ('post_author', $post->ID);
						$doctor_profile_id		= doctreat_get_linked_profile_id( $doctors_id );
	
						$name		= doctreat_full_name( $doctor_profile_id );
						$name		= !empty( $name ) ? $name : ''; 
						
						$link		= get_the_permalink( $doctor_profile_id );
						$status		= get_post_status( $post->ID );
	
						$width		= 30;
						$height		= 30;
	
						$avatar_url = apply_filters(
										'doctreat_doctor_avatar_fallback', 
										doctreat_get_doctor_avatar( array('width' => $width, 'height' => $height ), $doctor_profile_id ), 
										array( 'width' => $width, 'height' => $height )
									);
						?>
						<div class="dc-recentapoint">
							<div class="dc-recentapoint-content dc-apoint-noti dc-noti-colorone">
								<?php if( !empty( $avatar_url ) ){?>
									<div class="dc-recentapoint-figure">
										<figure><img src="<?php echo esc_url( $avatar_url );?>" alt="<?php echo esc_attr( $name );?>"></figure>
									</div>
								<?php } ?>
								<div class="dc-recent-content">
									<?php if( !empty( $name ) || !empty( $status ) ){?>
										<span>
											<a href="<?php echo esc_url( $link );?>"><?php echo esc_html( $name );?></a>
											<em><?php esc_html_e('Status','doctreat');?>: <?php echo esc_html( ucfirst($status) );?></em>
										</span>
									<?php } ?>
									<div class="dc-recent-contenttest">
										<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('location', $user_identity, false, 'details', $post->ID); ?>" class="dc-btn dc-btn-sm"><?php esc_html_e('View Details','doctreat');?></a>    
										<?php if( $status === 'pending' || $status === 'draft' ){?>
											<a href="javascript:;" data-id="<?php echo intval($post->ID);?>" data-status="publish" class="dc-btn dc-btn-sm dc-chage-status"><?php esc_html_e('Approve User','doctreat');?></a>
										<?php } ?>    
										<?php if( $status === 'pending' || $status === 'draft' || $status === 'publish'){?>
											<a href="javascript:;" data-id="<?php echo intval($post->ID);?>" data-status="trash" class="dc-userbtn dc-chage-status"><?php esc_html_e('Reject User','doctreat');?></a>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					<?php 
						endwhile;
						wp_reset_postdata();
	
						if (!empty( $count_post ) && $count_post > $show_posts) {
							doctreat_prepare_pagination( $count_post, $show_posts );
						}
					?>
				<?php } else { 
					do_action('doctreat_empty_records_html','dc-empty-saved-doctors dc-emptyholder-sm',esc_html__( 'Empty team members.', 'doctreat' ));
				} ?>
			</div>
		</div>
	</div>
</div>
<?php
	get_template_part('directory/front-end/templates/dashboard', 'add-invitation');