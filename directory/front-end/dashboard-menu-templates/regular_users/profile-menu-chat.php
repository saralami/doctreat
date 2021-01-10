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
$url_identity 	 = $current_user->ID;

$user_type	= apply_filters('doctreat_get_user_type', $url_identity );
if( ( !empty( $user_type )  && ( $user_type === 'doctors' && apply_filters('doctreat_is_feature_allowed', 'dc_chat', $url_identity) === true ) )
   || $user_type === 'hospitals' 
   || $user_type === 'regular_users'
) {?>
	<li class="<?php echo esc_attr( $reference === 'chat' ? 'dc-active' : ''); ?>">
		<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('chat', $url_identity); ?>">
			<i class="ti-email"></i>
			<span><?php esc_html_e('Inbox','doctreat');?></span>
		</a>
	</li>
<?php } ?>