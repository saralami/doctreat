<?php 
/**
 *
 * The template part for displaying doctors in listing
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $post;

$display_name	= doctreat_full_name( $post->ID );
$profile_url	= get_the_permalink( $post->ID );
$sub_heading	= doctreat_get_post_meta( $post->ID ,'am_sub_heading' );

?>
<div class="dc-docpostholder dc-search-doctors">
	<div class="dc-docpostcontent">
		<div class="dc-searchvtwo">
			<?php do_action('doctreat_get_doctor_thumnail',$post->ID);?>
			<div class="dc-title">
				<h3>
					<?php if( !empty( $display_name ) ){?><a href="<?php echo esc_url( $profile_url );?>"><?php echo esc_html( $display_name );?></a><?php }?>
					<?php do_action('doctreat_get_verification_check',$post->ID,'');?>
				</h3>
				<ul class="dc-docinfo">
					<?php if( !empty( $sub_heading ) ){?>
						<li>
							<em><?php echo esc_html( $sub_heading );?></em>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>
</div>