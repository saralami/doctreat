<?php
/**
 *
 * The template part for displaying the dashboard menu
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	= $current_user->ID;
$link_id		= doctreat_get_linked_profile_id( $user_identity );
?>
<li>
	<a target="_blank" href="<?php echo esc_url(get_the_permalink( $link_id ) );?>">
		<i class="ti-eye"></i>
		<span><?php esc_html_e('View My Profile','doctreat');?></span>
	</a>
</li>
