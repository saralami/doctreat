<?php
/**
 *
 * The template part for displaying the dashboard experience
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		= $linked_profile;

$am_experiences	= doctreat_get_post_meta( $post_id,'am_experiences');

?>
<div class="dc-userexperience dc-tabsinfo">
	<div class="dc-tabscontenttitle dc-addnew">
		<h3><?php esc_html_e('Add Your experience','doctreat');?></h3>
		<a href="javascript:;" class="dc-add_experience"><?php esc_html_e('Add New','doctreat');?></a>
	</div>
	<ul class="dc-experienceaccordion accordion dc-experiences">
		<?php 
			if( !empty( $am_experiences ) ) {
				$count_edu	= 1;
				foreach( $am_experiences as $key => $val ) {
					if( $count_edu === 1 ) { $show = 'show'; }else { $show = '';}
					$count_edu ++;
					$company_name		= !empty( $val['company_name'] ) ? $val['company_name'] : '';
					$start_date			= !empty( $val['start_date'] ) ? $val['start_date'] : '';
					$ending_date		= !empty( $val['ending_date'] ) ? $val['ending_date'] : '';
					$job_title			= !empty( $val['job_title'] ) ? $val['job_title'] : '';
					$pstart_date		= !empty( $val['start_date'] ) ? date('F Y', strtotime($val['start_date'])) : '';
					$pending_date		= !empty( $val['ending_date'] ) ? date('F Y', strtotime($val['ending_date'])) : '';
					$job_description	= !empty( $val['job_description'] ) ? $val['job_description'] : '';
					
					$end_date	= '';
					if( empty( $pending_date ) ){
						$end_date = esc_html__('Current', 'doctreat');
					}
					
					if( !empty( $pstart_date ) ){
						$period = $pstart_date . ' - ' .$pending_date;		
					}
					
					if( $end_date == 'Current' ){
						$period = $end_date;
					} ?>
					<li>
						<div class="dc-accordioninnertitle">
							<span id="accordioninnertitle<?php echo intval( $key );?>" data-toggle="collapse" data-target="#innertitle<?php echo intval( $key );?>"><?php echo esc_html( $company_name );?><em> (<?php echo esc_html( $period);?>)</em></span>
							<div class="dc-rightarea">
								<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle<?php echo intval( $key );?>" data-toggle="collapse" data-target="#innertitle<?php echo intval( $key );?>" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
								<a href="javascript:void(0);" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
							</div>
						</div>
						<div class="dc-collapseexp collapse" id="innertitle<?php echo intval( $key );?>" aria-labelledby="accordioninnertitle<?php echo intval( $key );?>" data-parent="#accordion">
							<div class="dc-formtheme dc-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][company_name]" class="form-control" placeholder="<?php esc_attr_e('Company name','doctreat');?>" value="<?php echo esc_attr( $company_name );?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doctreat');?>" value="<?php echo esc_attr($start_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doctreat');?>" value="<?php echo esc_attr($ending_date);?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_experiences[<?php echo intval($key);?>][job_title]" class="form-control" placeholder="<?php esc_attr_e('job title','doctreat');?>" value="<?php echo esc_attr($job_title);?>">
									</div>
									<div class="form-group">
										<textarea name="am_experiences[<?php echo intval($key);?>][job_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doctreat');?>"><?php echo esc_html($job_description);?></textarea>
									</div>
									<div class="form-group">
										<span>* <?php esc_html_e('Leave ending date empty if its your current job','doctreat');?></span>
									</div>
								</fieldset>
							</div>
						</div>
					</li>
			<?php } ?>
		<?php } ?>		
	</ul>
</div>
<script type="text/template" id="tmpl-load-experience">
	<li>
		<div class="dc-accordioninnertitle">
			<span id="accordioninnertitle{{data.counter}}" data-toggle="collapse" data-target="#innertitle{{data.counter}}"><?php esc_html_e('Company title', 'doctreat'); ?></span>&nbsp;<em><?php esc_html_e('(Start Date - End Date)', 'doctreat'); ?></em></span>
			<div class="dc-rightarea">
				<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle{{data.counter}}" data-toggle="collapse" data-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
				<a href="javascript:void(0);" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
			</div>
		</div>
		<div class="dc-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle{{data.counter}}" data-parent="#accordion">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][company_name]" class="form-control" placeholder="<?php esc_attr_e('Comapny name','doctreat');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][start_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Starting Date','doctreat');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][ending_date]" class="form-control dc-date-pick" placeholder="<?php esc_attr_e('Ending Date','doctreat');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_experiences[{{data.counter}}][job_title]" class="form-control" placeholder="<?php esc_attr_e('job title','doctreat');?>" value="">
					</div>
					<div class="form-group">
						<textarea name="am_experiences[{{data.counter}}][job_description]" class="form-control" placeholder="<?php esc_attr_e('Your Job Description','doctreat');?>"></textarea>
					</div>
					<div class="form-group">
						<span>* <?php esc_html_e('Leave ending date empty if its your current job','doctreat');?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>