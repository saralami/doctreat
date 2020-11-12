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
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

if (is_active_sidebar('user-dashboard-sidebar-right')) {?>
	<div class="dc-companyad">
		<figure><?php dynamic_sidebar('user-dashboard-sidebar-right'); ?></figure>
	</div>
<?php }?>