<?php
/**
 *
 *Template Name: Health Forum
 *
 * The template used for displaying default post style
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */
get_header();

global $post,$theme_settings;

$order_fields 		= array();
$tax_query_args 	= array();
$order_fields		= doctreat_list_order_by();

$post_meta			= doctreat_get_post_meta( $post->ID );
$am_layout			= !empty( $post_meta['am_layout'] ) ? $post_meta['am_layout'] : '';
$am_sidebar			= !empty( $post_meta['am_left_sidebar'] ) ? $post_meta['am_left_sidebar'] : '';

$current_position	= 'full';
if (!empty($am_layout) && $am_layout === 'right_sidebar') {
    $aside_class   		= 'order-last';
    $content_class 		= 'order-first';
	$section_width     	= 'col-12 col-md-7 col-lg-8 col-xl-9';
	$current_position	= 'dc-siderbar';
} elseif (!empty($am_layout) && $am_layout === 'left_sidebar') {
    $aside_class   		= 'order-first';
    $content_class 		= 'order-last';
	$section_width     	= 'col-12 col-md-7 col-lg-8 col-xl-9 float-left';
	$current_position	= 'dc-siderbar';
} else {
    $aside_class   = '';
    $content_class = '';
}

$height = 466;
$width  = 1170;

$title				= !empty( $theme_settings ['hf_title'] ) ? $theme_settings ['hf_title'] : '';
$sub_title			= !empty( $theme_settings ['hf_sub_title'] ) ? $theme_settings ['hf_sub_title'] : '';
$desc				= !empty( $theme_settings ['hf_description'] ) ? $theme_settings ['hf_description'] : '';
$btn_text			= !empty( $theme_settings ['hf_btn_text'] ) ? $theme_settings ['hf_btn_text'] : '';
$img_url			= !empty( $theme_settings ['hf_image']['url'] ) ? $theme_settings ['hf_image']['url'] : '';

$pg_page  	= get_query_var('page') ? get_query_var('page') : 1; 
$pg_paged 	= get_query_var('paged') ? get_query_var('paged') : 1;
$paged    	= max($pg_page, $pg_paged);
$show_posts = get_option('posts_per_page') ? get_option('posts_per_page') : 10;

$order         	= !empty($_GET['order']) ? $_GET['order'] : 'DESC';
$orderby       	= !empty($_GET['orderby']) ? $_GET['orderby'] : 'ID';

$speciality_slug 	= !empty( $_GET['qspecialities']) ? $_GET['qspecialities'] : '';

$keyword 		= !empty( $_GET['search']) ? $_GET['search'] : '';

//Specialities
if ( !empty($speciality_slug) ) {    
	$query_relation = array('relation' => 'OR',);
	$location_args 	= array(
		'taxonomy' => 'specialities',
		'field'    => 'slug',
		'terms'    => $speciality_slug,
	);

	$tax_query_args[] = array_merge($query_relation, $location_args);
}

//Main Query 
$query_args = array(
	'posts_per_page' 		=> $show_posts,
	'post_type' 			=> 'healthforum',
	'paged' 				=> $paged,
	'post_status' 			=> 'publish',
	'ignore_sticky_posts' 	=> 1
);

if( !empty( $orderby ) ){
	$query_args['orderby']  	= $orderby;
}

if( !empty( $order ) ){
	$query_args['order'] 		= $order;
	if( $order === 'DESC') {
		$slected_order	= 'selected="selected"';
	} else {
		$slected_order 	= '';
	}
}

//keyword search
if( !empty($keyword) ){
	$query_args['s']	=  $keyword;
}

//Taxonomy Query
if ( !empty( $tax_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$query_args['tax_query'] = array_merge($query_relation, $tax_query_args);    
}

$query      = new WP_Query($query_args);
$count_post = $query->found_posts;

$height = intval(40);
$width  = intval(40);
$date_formate	= get_option('date_format');

if( have_posts() ) {
	while ( have_posts() ) : the_post();
	the_content();
	wp_link_pages( array(
		'before'      => '<div class="dc-paginationvtwo"><nav class="dc-pagination"><ul>',
		'after'       => '</ul></nav></div>',
		) );
	endwhile;
	wp_reset_postdata();
}

?>
<div class="dc-haslayout dc-parent-section healthforum-temp-main">
	<div class="container">
		<div class="row">
			<div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
				<div class="dc-questionsection">
					<div class="dc-askquery">
						<div class="dc-postquestion">
						<?php if( !empty( $title ) || !empty( $sub_title )){?>
							<div class="dc-title">
								<?php if( !empty( $title ) ) {?><span><?php echo esc_html( $title );?></span><?php } ?>
								<?php if( !empty( $sub_title ) ) {?><h2><?php echo esc_html( $sub_title );?></h2><?php } ?>
							</div>
						<?php } ?>
						<?php if( !empty( $desc ) ){?>
							<div class="dc-description"><p><?php echo do_shortcode($desc);?></p></div>
						<?php } ?>
						<?php if( !empty( $btn_text ) ){?>
							<div class="dc-btnarea">
								<a href="#" data-toggle="modal" data-target="#freequery" class="dc-btn"><?php echo esc_html( $btn_text );?></a>
							</div>
						<?php } ?>
						</div>
						<?php if( !empty( $img_url ) ){?>
							<figure>
								<img src="<?php echo esc_url( $img_url);?>" alt="<?php esc_attr_e('Health Form','doctreat');?>">
							</figure>	
						<?php } ?>									
					</div>	
				</div>
				<div class="dc-innerbanner">
					<form class="dc-formtheme dc-forumform" method="get" id="search_form_healthforum">
						<fieldset>
							<div class="form-group">
								<input type="text" name="search" class="form-control" value="<?php echo esc_attr($keyword);?>" placeholder="<?php esc_attr_e('Type Your Query','doctreat');?>">
							</div>
							<?php 
								if( class_exists('Doctreat_Walker_Location_Dropdown')) { 
									$selected_speciality = !empty( $speciality_slug) ? doctreat_get_term_by_type('slug',$speciality_slug,'specialities','id') : '';
								?>
								<div class="form-group">
									<div class="dc-select">
										<select class="chosen-select locations" data-placeholder="<?php esc_attr_e('specialities','doctreat');?>" name="qspecialities">
											<option value=""><?php esc_html_e('Select a speciality','doctreat');?></option>
												<?php
													wp_list_categories( array(
															'taxonomy' 			=> 'specialities',
															'hide_empty' 		=> false,
															'selected'			=> $selected_speciality,
															'style' 			=> '',
															'walker' 			=> new Doctreat_Walker_Location_Dropdown,
														)
													);
												?>
										</select>
									</div>
								</div>
							<?php } ?>
							<input type="hidden" id="search_orderby_healthforum" value="<?php echo esc_attr( $orderby );?>" name="orderby">
							<div class="dc-btnarea">
								<button type="submit" class="dc-btn"><?php esc_html_e('Search','doctreat');?></button>
							</div>
						</fieldset>
					</form>
				</div>
				<div class="dc-docsingle-holder">
					<div class="tab-content dc-haslayout">
						<div class="dc-contentdoctab dc-feedback-holder" id="feedback">
							<div class="dc-feedback">
								<div class="dc-searchresult-head">
									<?php if( !empty( $title ) ) {?><div class="dc-title"><h4><?php echo esc_html( $title );?></h4></div><?php } ?>
									<?php if( !empty( $order_fields ) ){?>
										<div class="dc-rightarea">
											<div class="dc-select">
												<select name="orderby" class="orderby_healthforum">
													<option value=""><?php esc_html_e('Sort By','doctreat');?></option>
														<?php 
														foreach( $order_fields as $key => $order_field ){
															if( !empty( $orderby ) && $orderby === $key ){
																$slected_orderby	= 'selected="selected"';
															} else {
																$slected_orderby	= '';
															}
														?>
														<option <?php echo do_shortcode( $slected_orderby );?> value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $order_field );?></option>
													<?php } ?>
												</select>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="dc-consultation-content">
									<?php 
									if ($query->have_posts()) { 
										while ($query->have_posts()) { 
											$query->the_post();
											global $post;

											$thumbnail	= '';

											$db_specialities	= apply_filters('doctreat_get_tax_query',array(),$post->ID,'specialities','');
											$speciality_img		= !empty( $db_specialities[0] ) ? get_term_meta( $db_specialities[0]->term_id, 'logo', true ) : '';
											if( !empty( $speciality_img['attachment_id'] ) ){
												$thumbnail	= wp_get_attachment_image_src( $speciality_img['attachment_id'],	 'doctreat_artical_auther', true );

												$thumbnail	= !empty( $thumbnail[0] ) ? $thumbnail[0] : '';
											}

											$title		= get_the_title( $post->ID );
											$title		= !empty( $title ) ? $title : '';
											$contents	= get_the_content($post->ID);
											$link		= get_the_permalink($post->ID);
											$link		= !empty( $link ) ? $link : '';

											$post_date	= get_post_field('post_date',$post->ID);
											$answered	= get_comments_number($post->ID);
											$answered	= !empty( $answered ) ? $answered : 0;
											?>
											<div class="dc-consultation-details">
												<?php if( !empty( $thumbnail ) ){?>
													<figure class="dc-consultation-img dc-imgcolor1">
														<img src="<?php echo esc_url( $thumbnail );?>" alt="<?php echo esc_attr($title);?>">
													</figure>
												<?php } ?>
												<?php if( !empty( $title ) || !empty( $post_date ) ) {?>
													<div class="dc-consultation-title">
														<h5>
															<?php if( !empty( $title ) ) {?>
																<a href="<?php echo esc_url( $link );?>"><?php echo esc_html( $title );?></a>
															<?php } ?>
															<?php if( !empty( $post_date ) ){?>
																<em><?php echo date($date_formate,strtotime($post_date));?></em>
															<?php } ?>
														</h5>
														<span><?php echo intval( $answered );?>&nbsp;<?php esc_html_e('Answered','doctreat');?></span>
													</div>
												<?php } ?>
												<?php if( !empty( $contents ) ){?>
													<div class="dc-description"><p><?php echo esc_html( $contents );?></p></div>
												<?php } ?>
											</div>
										<?php } wp_reset_postdata(); ?>
										<?php doctreat_prepare_pagination($count_post, $show_posts); ?>
									<?php } else { ?>	
										<?php do_action('doctreat_empty_records_html','dc-empty-articls dc-emptyholder-sm',esc_html__( 'No result found.', 'doctreat' ));?>
									<?php } ?>							
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
				if ( !empty( $am_sidebar ) && is_active_sidebar( $am_sidebar ) ) {?>
					<div class="col-12 col-md-5 col-lg-4 col-xl-3 float-left dc-sidebar-grid mt-md-0 <?php echo sanitize_html_class($aside_class); ?>">
						<aside id="dc-sidebar" class="dc-sidebar">
							<?php dynamic_sidebar( $am_sidebar );?>
						</aside>
					</div>
					<?php
				}
			?>
		</div>
	</div>
</div>
<?php get_template_part('directory/post-question');?>
<?php get_footer();
