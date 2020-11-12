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
$user_type		 = apply_filters('doctreat_get_user_type', $user_identity );
$manage_url 		= Doctreat_Profile_Menu::Doctreat_profile_menu_link('account-settings', $user_identity, true,'manage');
$password_url		= Doctreat_Profile_Menu::Doctreat_profile_menu_link('account-settings', $user_identity, true,'password');

$remove_account_url			= Doctreat_Profile_Menu::Doctreat_profile_menu_link('account-settings', $user_identity, true,'delete');
$emails_notification_url	= Doctreat_Profile_Menu::Doctreat_profile_menu_link('account-settings', $user_identity, true,'emails');
$mode 			 			= !empty($_GET['mode']) ? esc_html( $_GET['mode'] ) : 'settings';
$class						= '';
?>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
	<form class="dc-haslayout dc-user-account" method="post">	
		<div class="dc-dashboardbox dc-dashboardtabsholder dc-accountsettingholder">
			<div class="dc-dashboardtabs">
				<ul class="dc-tabstitle nav navbar-nav">
					<?php if(!empty($user_type) && $user_type != 'regular_users'){?>
						<li class="nav-item">
							<a class="<?php echo !empty( $mode ) && $mode === 'manage' ? 'active' : '';?>" href="<?php echo esc_url( $manage_url );?>">
								<?php esc_html_e('Security & Settings', 'doctreat'); ?>
							</a>
						</li>
					<?php } ?>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'password' ? 'active' : '';?>" href="<?php echo esc_url( $password_url );?>">
							<?php esc_html_e('Password', 'doctreat'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'emails' ? 'active' : '';?>" href="<?php echo esc_url( $emails_notification_url );?>">
							<?php esc_html_e('Email Notification', 'doctreat'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a class="<?php echo !empty( $mode ) && $mode === 'delete' ? 'active' : '';?>" href="<?php echo esc_url( $remove_account_url );?>">
							<?php esc_html_e('Delete Account', 'doctreat'); ?>
						</a>
					</li>	
				</ul>
			</div>
			<div class="dc-tabscontent tab-content">
				<?php 
					if( !empty( $mode ) && $mode === 'manage' ){
						if( $user_type === 'doctors' ){
							get_template_part('directory/front-end/templates/doctors/dashboard', 'manage-account-settings');
						} else if( $user_type === 'hospitals' ){
							get_template_part('directory/front-end/templates/dashboard', 'manage-account-settings'); 
						}
						
						wp_nonce_field('dc_account_setting_nonce', 'account_submit');
						$class	= 'dc-update-account';
					} elseif( !empty( $mode ) && $mode === 'password' ){
						get_template_part('directory/front-end/templates/dashboard', 'reset-password'); 
						wp_nonce_field('dc_change_password_nonce', 'change_password');
						$class	= 'dc-reset-password';
					}elseif( !empty( $mode ) && $mode === 'emails' ){
						get_template_part('directory/front-end/templates/dashboard', 'email-notifications'); 
					}elseif( !empty( $mode ) && $mode === 'delete' ){
						get_template_part('directory/front-end/templates/dashboard', 'delete-account'); 
						wp_nonce_field('dc_account_delete_nonce', 'account_delete'); 
						$class	= 'dc-delete-user-account';
					}
				?>
			</div>
		</div>
		<?php if( !empty( $mode ) && $mode != 'emails' ){ ?>
			<div class="dc-updatall">
				<i class="ti-announcement"></i>
				<span><?php esc_html_e('Update all the latest changes made by you, by just clicking on Save &amp; Update button.', 'doctreat'); ?></span>
				<a class="dc-btn <?php echo esc_attr( $class );?> " data-id="<?php echo esc_attr( $user_identity ); ?>" data-post="<?php echo esc_attr( $post_id ); ?>" href="javascript:;"><?php esc_html_e('Save &amp; Update', 'doctreat'); ?></a>
			</div>
		<?php } ?>	
	</form>
</div>

