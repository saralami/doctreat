<?php
/**
 *
 * The template used for doctors Education
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;

$post_id 		= $post->ID;
$education		= doctreat_get_post_meta( $post_id,'am_education');

if( !empty( $education ) ){ ?>
	<div class="dc-education-holder dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Education','doctreat');?></h3>
		</div>
		<ul class="dc-expandedu">
			<?php
				foreach( $education as $edu ) {
					$degree_title	= !empty( $edu['degree_title'] ) ? $edu['degree_title'] : '';
					$institute_name	= !empty( $edu['institute_name'] ) ? $edu['institute_name'] : '';
					$start		= !empty( $edu['start_date'] ) ? date('Y', strtotime($edu['start_date'])) : '';
					$ending		= !empty( $edu['ending_date'] ) ? date('Y', strtotime($edu['ending_date'])) : '';
					if( !empty( $degree_title ) ){ ?>
						<li>
							<span>
								<?php echo esc_html( $degree_title );?> 
								<?php if( !empty( $start ) && !empty( $ending ) ) {?><em>( <?php echo esc_html( $start.' - '.$ending);?> )</em><?php } ?>
							</span>
							<?php if( !empty( $institute_name )) {?><em><?php echo esc_html( $institute_name );?></em><?php } ?>
						</li>
				<?php }?>
			<?php }?>
		</ul>
	</div>
<?php } ?>