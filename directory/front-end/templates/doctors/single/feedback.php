<?php
/**
 *
 * The template used for displaying feedback
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
$user_id	= doctreat_get_linked_profile_id( $post_id ,'post');
$name		= doctreat_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 

$date_formate	= get_option('date_format');
$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; 
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1;
$paged 			= max($pg_page, $pg_paged);
$order 	 		= 'DESC';
$sorting 		= 'ID';

$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'reviews',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish','pending'),
    'author' 			=> $user_id,
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

$query 		= new WP_Query($args);
$count_post = $query->found_posts;

$width	= 40;
$height	= 40;
?>
<div class="dc-feedback-holder tab-pane" id="feedback">
	<div class="dc-feedback">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4><?php esc_html_e('Patient Feedback','doctreat');?></h4></div>
		</div>
		<div class="dc-consultation-content dc-feedback-content">
			<?php if( $query->have_posts() ){ ?>
				<div class="dc-consultation-details">
					<?php				
						while ($query->have_posts()) : $query->the_post(); 
							global $post;
							$user_id			= get_post_meta($post->ID, '_user_id', true);

							$recommend_class	= '';
							$recommend_text		= '';

							$recommend			= get_post_meta($post->ID, '_feedback_recommend', true);
	
							if( !empty( $recommend ) && $recommend === 'yes' ){
								$recommend_text		= esc_html__('I Recommend This Doctor','doctreat');
							} elseif($recommend === 'no' ) {
								$recommend_text	= esc_html__('I Don’t Recommend','doctreat');
								$recommend_class	= 'dontrecommend';
							}

							$user_profile_id	= doctreat_get_linked_profile_id( $user_id);
							$feedbackpublicly	= get_post_meta($post->ID, '_feedbackpublicly', true);
							$feedbackpublicly	= !empty( $feedbackpublicly ) ? $feedbackpublicly : '';
							$name				= doctreat_full_name( $user_profile_id );
							$name				= !empty( $name ) ? $name : ''; 
							$tag_line			= doctreat_get_tagline($user_profile_id);
							$tag_line			= !empty( $tag_line ) ? $tag_line : '';
							$post_date			= !empty($post->ID) ? get_post_field('post_date',$post->ID) : "";
							?>
							<?php if( !empty( $feedbackpublicly ) && $feedbackpublicly	=== 'yes' ){?>
								<figure class="dc-consultation-img">
									<img src="<?php echo esc_url( get_template_directory_uri().'/images/user.png' );?>" alt="<?php esc_attr_e('Anonymous','doctreat');?>">
								</figure>
								<div class="dc-consultation-title">
									<h5><?php esc_html_e('Anonymous','doctreat');?></h5>
									<span><?php echo date($date_formate,strtotime($post_date));?></span>
								</div>
							<?php } else { ?>
								<?php
									$avatar_url 	= apply_filters(
														'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => $width, 'height' => $height), $user_profile_id), array('width' => $width, 'height' => $height) 
													);
									if( !empty( $avatar_url ) ){ ?>
										<figure class="dc-consultation-img">
											<img src="<?php echo esc_url( $avatar_url );?>" alt="<?php echo esc_attr( $name );?>">
										</figure>
									<?php } ?>
								<div class="dc-consultation-title">
									<?php if( !empty( $tag_line ) || !empty( $name )) { ?>
										<h5>
											<?php if( !empty( $tag_line ) ){?>
												<a href="javascript:;"><?php echo esc_html( $tag_line );?></a>
											<?php } ?>
											<?php if( !empty( $name ) ){?>
												<em>
													<?php echo esc_html( $name); ?>
													<?php do_action('doctreat_get_verification_check',$user_profile_id,'');?>
												</em>
											<?php } ?>
										</h5>
									<?php } ?>
									<?php if( !empty( $post_date ) ){?>
										<span><?php echo date($date_formate,strtotime($post_date));?></span>
									<?php } ?>
								</div>
							<?php } ?>
							<div class="dc-description">
								<?php the_content();?>
								<a href="javascript:" class="<?php echo esc_attr( $recommend_class );?>"><i class="ti-thumb-up"></i><?php echo esc_html( $recommend_text );?></a>
							</div>

					<?php
						endwhile;
						wp_reset_postdata();			 

						if (!empty($count_post) && $count_post > $show_posts) {
							doctreat_prepare_pagination($count_post, $show_posts);
						}
					?>
				</div>
				<?php
					} else {
						do_action('doctreat_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'No patient feedback yet now.', 'doctreat' ));
					}
				?>
		</div>
	</div>
</div>