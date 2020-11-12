<?php
/**
 *
 * The template used for doctors registration
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$post_meta				= doctreat_get_post_meta( $post_id);
$am_is_verified			= !empty( $post_meta['am_is_verified'] ) ? $post_meta['am_is_verified'] : '';
$am_registration_number	= !empty( $post_meta['am_registration_number'] ) ? $post_meta['am_registration_number'] : '';

if( !empty( $am_is_verified ) && $am_is_verified === 'yes' ) { ?>
	<div class="dc-specializations dc-aboutinfo dc-memberships">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Registrations','doctreat');?></h3>
		</div>
		<?php if( !empty( $am_registration_number ) ){?>
			<ul class="dc-specializationslist">
				<li><span><?php echo esc_html( $am_registration_number );?></span></li>
			</ul>
		<?php } ?>
	</div>
<?php } ?>

