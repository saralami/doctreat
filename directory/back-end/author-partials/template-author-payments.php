<?php
/**
 *
 * Author Payments Template.
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      https://themeforest.net/user/amentotech/portfolio
 * @since 1.0
 */
global $profileuser, $woocommerce;
if (class_exists('WooCommerce')) {
	$user_identity		= $profileuser->ID;

if( apply_filters('doctreat_is_listing_free', false,$user_identity) === false ){
	
	$current_date 		= current_time('mysql');
	$today				= strtotime($current_date);
	$user_role			= doctreat_get_user_type( $user_identity );
	$package_id 		= doctreat_get_subscription_metadata('subscription_id', $user_identity);
	
	$package_expiry 	= doctreat_get_subscription_metadata('subscription_package_string', $user_identity);
	$package_title 		= esc_html( get_the_title($package_id));
	$package_title		= !empty($package_title) ? $package_title : esc_html__('Nill', 'doctreat');
	
	$currency_symbol	= doctreat_get_current_currency();
	$pakeges_features 	= doctreat_get_pakages_features();
	$meta_query_args	= array();
	$packages_options 	= array();
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
	$packages 			= new WP_Query( $args );
?>
	<div class="dc-formtheme dc-dashboardbox dashboard-admin-pack" id="sp-pkgexpireyandcounter">
		<div class="sp-row">
			<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left">
				<div class="dc-pkgexpireyandcounter">
					<div class="dc-dashboardtitle">
						<h2><?php esc_html_e('Available Packages', 'doctreat'); ?></h2>
					</div>
				</div>
				<div class="dc-dashboardbox dc-languagesbox">
					<div class="dc-packagesbox">
						<div class="dc-dashboardboxcontent dc-packages">
							<?php if ( $packages->have_posts() ) {?>
							<div class="dc-package dc-packagedetails">
								<div class="dc-packagehead"></div>
								<div class="dc-packagecontent">
									<ul class="dc-packageinfo">
										<li class="dc-packageprices"><span><?php esc_html_e('Price','doctreat');?></span></li>
										<?php foreach ( $pakeges_features as $key => $values ) { 
											if( $values['user_type'] === $user_role || $values['user_type'] === 'common' ) {?>
												<li><span><?php echo esc_html( $values['title']);?></span></li>
										<?php }}?>
									</ul>
								</div>
							</div>
							<?php 
								while ( $packages->have_posts() ) : $packages->the_post();
									global $product;					  
									$post_id 		= intval($product->get_id());
									$duration_type	= get_post_meta($post_id,'wt_duration_type',true);
									$duration_title = doctreat_get_duration_types($duration_type,'title'); 
									$packages_options[$product->get_id()] = esc_html( get_the_title()); ?>
									<div class="dc-package dc-baiscpackage">
										<div class="dc-packagehead">
											<h3><?php echo esc_html( get_the_title()); ?></h3>
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
										</div>
									</div>
								<?php
							endwhile;
							wp_reset_postdata();?>	
							<?php } else {
									do_action('doctreat_empty_records_html','',esc_html__( 'No package has been made yet.', 'doctreat' ),true);
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="sp-row">
			<div class="sp-xs-12 sp-sm-12 sp-md-6 sp-lg-6 pull-left">
				<div class="dc-languagesbox">
					<div class="dc-startendtime">
						<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-6 pull-left">
							<span class="dc-select">
								<select name="package_id">
									<option value=""><?php esc_html_e('Select Package', 'doctreat'); ?></option>
									<?php
										if (!empty($packages_options)) {
											$counter = 0;
											foreach ($packages_options as $key => $pack) {
												echo '<option value="' . $key . '">' . $pack . '</option>';
											}
										}
									?>

								</select>
							</span>
						</div>
						<div class="sp-xs-12 sp-sm-12 sp-md-12 sp-lg-12 pull-left"><p><?php esc_html_e('Leave it empty to while updating user, otherwise selected package will be updated as user current package.', 'doctreat'); ?></p></div>
					</div>
				</div>
			</div>		
		</div>		
	</div>	
<?php }} ?>
