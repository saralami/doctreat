<?php
/**
 *
 * The template used for doctors details
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post,$theme_settings;
$post_id 		= $post->ID;
$name			= doctreat_full_name( $post_id );
$name			= !empty( $name ) ? $name : ''; 

$width			= 271;
$height			= 194;
$thumbnail      = doctreat_prepare_thumbnail($post->ID, $width, $height);
$gallery_option	= !empty($theme_settings['enable_gallery']) ? $theme_settings['enable_gallery'] : '';
?>
<div class="dc-userdetails-holder active tab-pane" id="userdetails">
	<div class="dc-aboutdoc dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e( 'About','doctreat');?> “<?php echo esc_html( $name );?>”</h3>
		</div>
		<div class="dc-description"><?php the_content();?></div>
	</div>
	<?php get_template_part('directory/front-end/templates/doctors/single/services'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/experience'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/education'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/specializations'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/languages'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/awards'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/memberships'); ?>	
	<?php get_template_part('directory/front-end/templates/doctors/single/registrations'); ?>
	<?php get_template_part('directory/front-end/templates/doctors/single/downloads'); ?>
	<?php
		if(!empty($gallery_option)){
			get_template_part('directory/front-end/templates/gallery');
		}
	?>
</div>