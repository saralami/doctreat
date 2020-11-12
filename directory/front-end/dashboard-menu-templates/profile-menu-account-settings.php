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

$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
$user_identity 	 = $current_user->ID;
$user_type		 = apply_filters('doctreat_get_user_type', $user_identity );
$mode_slug		= !empty($user_type) && $user_type!='regular_users' ? 'manage' : 'password';
?>
<li class="<?php echo esc_attr( $reference  === 'account-settings' ? 'dc-active' : ''); ?>">
	<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('account-settings', $user_identity,false,$mode_slug); ?>">
		<i class="ti-panel"></i>
		<span><?php esc_html_e('Account Settings','doctreat');?></span>
	</a>
</li>
