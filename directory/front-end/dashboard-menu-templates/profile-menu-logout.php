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

if (is_user_logged_in()) { ?>
	<li><a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><i class="ti-shift-right"></i> <span><?php esc_html_e('Logout', 'doctreat'); ?></span></a></li>
<?php }