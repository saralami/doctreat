<?php
/**
 *
 * The template used for doctors articles
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id 	= $post->ID;
$name		= doctreat_full_name( $post_id );
$name		= !empty( $name ) ? $name : ''; 
?>
<div class="dc-location-holder tab-pane fade" id="articles">
	<div class="dc-searchresult-holder">
		<div class="dc-searchresult-head">
			<div class="dc-title"><h4><?php esc_html_e('Articles','doctreat');?></h4></div>
		</div>
		<?php get_template_part('directory/front-end/templates/doctors/single/dashboard-articles'); ?>
	</div>
</div>