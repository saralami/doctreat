<?php
/**
 *
 * The template used for doctors experience
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;

$post_id 		= $post->ID;
$am_experiences	= doctreat_get_post_meta( $post_id,'am_experiences');
if( !empty( $am_experiences ) ) {
?>
<div class="dc-experiencedoc dc-aboutinfo">
	<div class="dc-infotitle">
		<h3><?php esc_html_e('Experience','doctreat');?></h3>
	</div>
	<ul class="dc-expandedu">
		<?php 
			foreach( $am_experiences as $exp ){
				$company_name	= !empty( $exp['company_name'] ) ? $exp['company_name'] : '';
				$job_title		= !empty( $exp['job_title'] ) ? $exp['job_title'] : '';
				$start		= !empty( $exp['start_date'] ) ? date('Y', strtotime($exp['start_date'])) : '';
				$ending		= !empty( $exp['ending_date'] ) ? date('Y', strtotime($exp['ending_date'])) : esc_html__('Present','doctreat');
				if( !empty( $job_title ) ){ ?>
					<li>
						<span>
							<?php echo esc_html( $job_title );?>
							<?php if( !empty( $start ) && !empty( $ending ) ){?><em>( <?php echo esc_html( $start );?> - <?php echo esc_html( $ending );?> )</em><?php } ?>
						</span>
						<?php if( !empty( $company_name ) ) { ?><em><?php echo esc_html( $company_name );?></em><?php } ?>
					</li>
			<?php } ?>
		<?php } ?>
	</ul>
</div>
<?php }