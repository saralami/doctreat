<?php
/**
 *
 * The template used for doctors specialiizations
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$specialities	= get_the_term_list( $post->ID, 'specialities', '<ul class="dc-specializationslist"><li><span>', '</span></li><li><span>', '</span></li></ul>' );

$specialities	= !empty( $specialities ) ? $specialities : '';
if( !empty( $specialities ) ){ ?>
	<div class="dc-specializations dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Specializations','doctreat');?></h3>
		</div>
		<?php echo do_shortcode($specialities);?>
	</div>
<?php } ?>

