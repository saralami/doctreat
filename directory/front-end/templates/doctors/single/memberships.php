<?php
/**
 *
 * The template used for doctors membership
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$am_memberships = doctreat_get_post_meta( $post_id,'am_memberships_name');

if( !empty( $am_memberships ) ){?>
	<div class="dc-specializations dc-aboutinfo dc-memberships">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Memberships','doctreat');?></h3>
		</div>
		<ul class="dc-specializationslist">
			<?php foreach( $am_memberships as $membership )  {?>
				<li><span><?php echo esc_html( $membership );?></span></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>

