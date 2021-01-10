<?php
/**
 *
 * The template part for displaying Packages
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$user_role			= doctreat_get_user_type( $user_identity );
$currency_symbol	= doctreat_get_current_currency();
$pakeges_features 	= doctreat_get_pakages_features();
$meta_query_args	= array();

$args 				= array(
						'post_type' 			=> 'product',
						'posts_per_page' 		=> -1,
						'post_status' 			=> 'publish',
						'ignore_sticky_posts' 	=> 1
					);
$meta_query_args[] = array(
						'key' 		=> 'package_type',
						'value' 	=> $user_role,
						'compare' 	=> '=',
					);

$query_relation 	= array('relation' => 'AND',);
$meta_query_args 	= array_merge($query_relation, $meta_query_args);
$args['meta_query'] = $meta_query_args;
$loop = new WP_Query( $args );
?>
<div class="col-12 col-sm-12 col-md-12 col-lg-12 float-left">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Packages','doctreat');?></h2>
		</div>
			<?php 
				if( class_exists('woocommerce') ){
					if ( $loop->have_posts() ) {?>
					<div class="dc-dashboardboxcontent dc-packages">
						<div class="dc-package dc-packagedetails">
							<div class="dc-packagehead"></div>
							<div class="dc-packagecontent">
								<ul class="dc-packageinfo">
									<li class="dc-packageprices"><span><?php esc_html_e('Price','doctreat');?></span></li>
									<?php foreach ( $pakeges_features as $key => $values ) { 
										if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
											<li><span><?php echo esc_html($values['title']);?></span></li>
									<?php }}?>
								</ul>
							</div>
						</div>
						<?php 
							
							while ( $loop->have_posts() ) : $loop->the_post();
								global $product; 
								$post_id 		= intval($product->get_id());
								$duration_type	= get_post_meta($post_id,'dc_duration',true);
								$duration_title = doctreat_get_duration_types($duration_type,'title'); ?>
								<div class="dc-package dc-baiscpackage">
									<div class="dc-packagehead">
										<h3><?php echo esc_html(get_the_title()); ?></h3>
										<div class="packages-desc"><?php the_content();?></div>
									</div>
									<div class="dc-packagecontent">
										<ul class="dc-packageinfo">
											<li class="dc-packageprice">
												<span>
													<sup><?php echo esc_html($currency_symbol['symbol']);?></sup><?php echo esc_html($product->get_price()); ?><sub>\ <?php echo  esc_html($duration_title);?></sub>
												</span>
											</li>
											<?php 
												if ( !empty ( $pakeges_features )) {
													foreach( $pakeges_features as $key => $vals ) {
														if( $vals['user_type'] === $user_role || $vals['user_type'] === 'common' ) {
															do_action('doctreat_print_pakages_features',$key,$vals,$post_id);
														}
													}
												}
											?>
										</ul>
										<a class="dc-btn renew-package" data-key="<?php echo intval($post_id);?>" href="javascript:;"><span><?php esc_html_e('Buy Now','doctreat');?></span></a>
									</div>
								</div>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>	
					<?php
					} else {
						do_action('doctreat_empty_records_html','',esc_html__( 'No package has been made yet.', 'doctreat' ),true);
					}
				} else {
					do_action('doctreat_empty_records_html','',esc_html__( 'Please install woocommerce.', 'doctreat' ),true);
			}
		?>	
	</div>
</div>