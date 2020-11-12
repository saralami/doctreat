<?php
/**
 *
 * The template part for displaying the dashboard education
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;
$education 		 = array();
$am_awards		 = doctreat_get_post_meta( $post_id,'am_award');
?>
<div class="dc-useraward dc-tabsinfo">
	<div class="dc-tabscontenttitle dc-addnew">
		<h3><?php esc_html_e('Add Your Awards','doctreat');?></h3>
		<a href="javascript:;" class="dc-add_award"><?php esc_html_e('Add New','doctreat');?></a>
	</div>
	<ul class="dc-awardaccordion accordion dc-award">
		<?php 
			if( !empty( $am_awards ) ) {
				$count_edu	= 1;
				foreach( $am_awards as $key => $val ) {
					if( $count_edu === 1 ) { $show = 'show'; }else { $show = '';}
					$count_edu ++;
					$title			= !empty( $val['title'] ) ? $val['title'] : '';
					$year			= !empty( $val['year'] ) ? $val['year'] : ''; ?>
					<li>
						<div class="dc-accordioninnertitle">
							<span id="accordioninnertitle<?php echo intval( $key );?>" data-toggle="collapse" data-target="#innertitle<?php echo intval( $key );?>"><?php echo esc_html( $title );?><em> (<?php echo esc_html( $year);?>)</em></span>
							<div class="dc-rightarea">
								<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle<?php echo intval( $key );?>" data-toggle="collapse" data-target="#innertitle<?php echo intval( $key );?>" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
								<a href="javascript:void(0);" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
							</div>
						</div>
						<div class="dc-collapseexp collapse" id="innertitle<?php echo intval( $key );?>" aria-labelledby="accordioninnertitle<?php echo intval( $key );?>" data-parent="#accordion">
							<div class="dc-formtheme dc-userform">
								<fieldset>
									<div class="form-group form-group-half">
										<input type="text" name="am_award[<?php echo intval($key);?>][title]" class="form-control" placeholder="<?php esc_attr_e('Title','doctreat');?>" value="<?php echo esc_attr( $title );?>">
									</div>
									<div class="form-group form-group-half">
										<input type="text" name="am_award[<?php echo intval($key);?>][year]" class="form-control dc-year-pick" placeholder="<?php esc_attr_e('Year','doctreat');?>" value="<?php echo esc_attr($year);?>">
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
<script type="text/template" id="tmpl-load-award">
	<li>
		<div class="dc-accordioninnertitle">
			<span id="accordioninnertitle{{data.counter}}" data-toggle="collapse" data-target="#innertitle{{data.counter}}"><?php esc_html_e('Award title', 'doctreat'); ?></span>&nbsp;<em><?php esc_html_e('(Year)', 'doctreat'); ?></em></span>
			<div class="dc-rightarea">
				<a href="javascript:;" class="dc-addinfo dc-skillsaddinfo" id="accordioninnertitle{{data.counter}}" data-toggle="collapse" data-target="#innertitle{{data.counter}}" aria-expanded="true"><i class="lnr lnr-pencil"></i></a>
				<a href="javascript:void(0);" class="dc-deleteinfo"><i class="lnr lnr-trash"></i></a>
			</div>
		</div>
		<div class="dc-collapseexp collapse show" id="innertitle{{data.counter}}" aria-labelledby="accordioninnertitle{{data.counter}}" data-parent="#accordion">
			<div class="dc-formtheme dc-userform">
				<fieldset>
					<div class="form-group form-group-half">
						<input type="text" name="am_award[{{data.counter}}][title]" class="form-control" placeholder="<?php esc_attr_e('Title','doctreat');?>" value="">
					</div>
					<div class="form-group form-group-half">
						<input type="text" name="am_award[{{data.counter}}][year]" class="form-control dc-year-pick" placeholder="<?php esc_attr_e('Year','doctreat');?>" value="">
					</div>
					<div class="form-group">
						<span>* <?php esc_html_e('Leave ending date empty if its your current job','doctreat');?></span>
					</div>
				</fieldset>
			</div>
		</div>
	</li>
</script>