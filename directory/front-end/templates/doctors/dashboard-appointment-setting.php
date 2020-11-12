<?php 
/**
 *
 * The template part for display appointment setting
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $post;
$user_identity 	= $current_user->ID;
$linked_profile = doctreat_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;

$times			= doctreat_get_time();
$intervals		= doctreat_get_padding_time();
$durations		= doctreat_get_meeting_time();
$days			= doctreat_get_week_array();
$show_posts 	= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page 		= get_query_var('page') ? get_query_var('page') : 1; 
$pg_paged 		= get_query_var('paged') ? get_query_var('paged') : 1;
$paged 			= max($pg_page, $pg_paged);
$order 	 		= 'DESC';
$sorting 		= 'ID';

$args = array(
	'posts_per_page' 	=> $show_posts,
    'post_type' 		=> 'hospitals_team',
    'orderby' 			=> $sorting,
    'order' 			=> $order,
	'post_status' 		=> array('publish','pending'),
    'author' 			=> $user_identity,
    'paged' 			=> $paged,
    'suppress_filters'  => false
);

$query 		= new WP_Query($args);
$count_post = $query->found_posts;

$width		= 80;
$height		= 80;
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle">
			<h2><?php esc_html_e('Appointment Locations','doctreat');?></h2>
		</div>
		<div class="dc-dashboardboxcontent dc-clinicloc-holder">
			<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Avaiable Locations','doctreat');?></h3>
			</div>
			<?php if( $query->have_posts() ){ ?>
				<div class="dc-content-holder">
					<?php
						while ($query->have_posts()) : $query->the_post(); 
							global $post;

							do_action('doctreat_get_hospital_team_byID', $post->ID, 'show', $user_identity); 

						endwhile;
						wp_reset_postdata();			 

						if (!empty($count_post) && $count_post > $show_posts) {
							doctreat_prepare_pagination($count_post, $show_posts);
						}
					?>
				</div>
			<?php } else{ ?>
				<?php do_action('doctreat_empty_records_html','dc-empty-hospital-location dc-emptyholder-sm',esc_html__( 'There are no hopital in your list.', 'doctreat' ));?>
			<?php } ?>
		</div>
	</div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-6 dc-sidebar-grid mt-xl-0">
	<div class="dc-dashboardbox">
		<div class="dc-dashboardboxtitle dc-titlewithbtn">
			<h2><?php esc_html_e('Appointment Setting','doctreat');?></h2>
			<div class="dc-rightarea">
				<a href="javascript:;"  class="dc-btn dc-invitation-users dc-btn-tab"><?php esc_html_e('Invite hospitals','doctreat');?></a>
			</div>
		</div>
		<div class="dc-dashboardboxcontent dc-appsetting">
			<div class="dc-tabscontenttitle">
				<h3><?php esc_html_e('Add New Location','doctreat');?></h3>
			</div>
			<form class="dc-formtheme dc-userform dc-form-appointment dc-hospital-team">
				<fieldset>
					<div class="form-group dc-inputwithicon">
						<i class="fa fa-check dc-display-none"></i>
						<input type="text" name="search_string" id="search_hospitals" autocomplete="off" class="form-control suggestquestion autocomplete-input" placeholder="<?php esc_attr_e('Search Hospital','doctreat');?>">
						<input type="hidden" name="hospital_id" id="hospitals_team_id" value="">
					</div>
					<div class="form-group dc-datepicker form-group-half">
						<span class="dc-select">
							<select name="start_time">
								<option value=""><?php esc_html_e( 'Start time','doctreat' );?></option>
								<?php 
									if( !empty( $times ) ) {
										foreach( $times as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
								<?php } ?>
							<?php } ?>
							</select>
						</span>
					</div>
					<div class="form-group dc-datepicker form-group-half">
						<span class="dc-select">
							<select name="end_time">
								<option value=""><?php esc_html_e( 'End time','doctreat' );?></option>
								<?php 
									if( !empty( $times ) ) {
										foreach( $times as $key => $val ) {
										?>
										<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
								<?php } ?>
							<?php } ?>
							</select>
						</span>
					</div>
					<div class="form-group form-group-half">
						<span class="dc-select">
							<select name="intervals">
								<?php 
									if( !empty( $intervals ) ) {
										foreach( $intervals as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
								<?php } ?>
							<?php } ?>
							</select>
						</span>
					</div>
					<div class="form-group form-group-half">
						<span class="dc-select">
							<select name="durations">
								<?php 
									if( !empty( $durations ) ) {
										foreach( $durations as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key );?>"><?php echo esc_html( $val );?></option>
								<?php } ?>
							<?php } ?>
							</select>
						</span>
					</div>
					<div class="form-group">
						<textarea name="content" class="form-control" placeholder="<?php esc_attr_e('Your Description','doctreat');?>"></textarea>
					</div>
				</fieldset>

				<fieldset class="dc-spacesholder">
					<div class="form-group">
						<?php do_action('doctreat_get_group_services_with_speciality',$post_id,'');?>
					</div>
					<legend><?php esc_html_e('Assign Appointment Spaces','doctreat');?>:</legend>
					<div class="form-group form-group-half dc-radio-holder">
						<span class="dc-radio">
							<input id="dc-spaces1" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(1);?>">
							<label for="dc-spaces1">01</label>
						</span>
						<span class="dc-radio">
							<input id="dc-spaces2" class="dc-spaces" type="radio" name="spaces" value="<?php echo intval(2);?>">
							<label for="dc-spaces2">02</label>
						</span>
						<span class="dc-radio">
							<input id="dc-others" class="dc-spaces" type="radio" name="spaces" value="<?php echo esc_attr('others');?>">
							<label for="dc-others"><?php esc_html_e('Others','doctreat');?></label>
						</span>
					</div>
					<div class="form-group form-group-half dc-others dc-display-none">
						<input type="text" name="custom_spaces" class="form-control custom_spaces" placeholder="<?php esc_attr_e('Custom Spaces','doctreat');?>" value="">
					</div>
				</fieldset>
				<fieldset class="dc-offer-holder">
					<legend><?php esc_html_e('Days I Offer My Services','doctreat');?>:</legend>
					<div class="form-group dc-checkbox-holder">
						<?php foreach( $days as $key => $val ) {
							$day_name	= doctreat_get_week_keys_translation($key);
							?>
						<span class="dc-checkbox">
							<input id="dc-<?php echo esc_attr( $key );?>" type="checkbox" name="week_days[]" value="<?php echo esc_html( $key );?>">
							<label for="dc-<?php echo esc_attr( $key );?>"><?php echo esc_html( $day_name );?></label>
						</span>
						<?php  }?>
					</div>
					<legend><?php esc_html_e('Consultation fee','doctreat');?>:</legend>
					<div class="form-group">
						<input type="text" name="consultant_fee" class="form-control" placeholder="<?php esc_attr_e('Consultation fee','doctreat');?>">
					</div>
					<?php wp_nonce_field('dc_hospital_team_data_nonce', 'hospital_team_submit'); ?>
					<div class="form-group dc-btnarea"><a href="javascript:;" class="dc-btn dc-add_hospital_team"><?php esc_html_e('Add Now','doctreat');?></a></div>
				</fieldset>
				
			</form>
		</div>
	</div>
</div>
<?php
	get_template_part('directory/front-end/templates/dashboard', 'add-invitation');
	$inline_script = 'jQuery(document).on("ready", function() { 
		themeAccordion();
		childAccordion(); });';
	wp_add_inline_script( 'doctreat-callback', $inline_script, 'after' );
	