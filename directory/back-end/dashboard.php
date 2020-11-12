<?php
/**
 * Dashboard backend
 *
 * @package Doctreat
 * @since Doctreat 1.0
 * @desc Template used for front end dashboard.
 */

/**
 * @User Public Profile Save
 * @return {}
 */
if (!function_exists('doctreat_personal_options_save')) {

    function doctreat_personal_options_save($user_identity) {
        if ( current_user_can('edit_user',$user_identity) ) {
			$current_date		= current_time('mysql');
			$user_role			= doctreat_get_user_type( $user_identity );
			$post_package		= !empty($_POST['package_id']) ? intval( $_POST['package_id'] ) : '';
			$package_include	= !empty($_POST['package_include']) ? intval( $_POST['package_include'] ) : '';
			$package_exclude	= !empty($_POST['package_exclude']) ? intval( $_POST['package_exclude'] ) : '';
			$dc_subscription	= array();
			
			if( !empty( $post_package ) ) {
				doctreat_update_package_data( $post_package, $user_identity, '' );
			}
		}
	}
}



/**
 * @User Public Profile
 * @return {}
 */
if (!function_exists('doctreat_edit_user_profile_edit')) {

    function doctreat_edit_user_profile_edit($user) {
		if ( ( $user->roles[0] === 'doctors' ) ){
			$profile_settings	= doctreat_profile_backend_settings();
			$profile_settings	= apply_filters('doctreat_filter_profile_back_end_settings', $profile_settings);
			
			foreach( $profile_settings as $key => $value  ){
				get_template_part('directory/back-end/author-partials/template-author', $key);
			}
		} else if ( $user->roles[0] === 'administrator' ){
			$display_img_url 			= '';
			$display = $display_image 	= 'block';
			$display_img_url 			= doctreat_get_user_avatar( 0, $user->ID );
			
			if ( empty( $display_img_url ) ) {
				$display_image = 'elm-display-none';
			}
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th><?php esc_html_e('Display Photo', 'doctreat'); ?></th>
						<td>
							<input type="hidden" name="author_profile_avatar" class="media-image" id="author_profile_avatar" value="<?php echo doctreat_get_user_avatar(0, $user->ID); ?>"/>
							<input type="button" id="upload-user-avatar" class="button button-secondary" value="<?php esc_html_e('Upload Public Avatar', 'doctreat'); ?>"/>
						</td>
					</tr>
					<tr id="avatar-wrap" class="<?php echo esc_attr($display_image); ?>">
						<td class="backgroud-image">
							<a href="javascript:;" class="delete-auhtor-media"><i class="fa fa-times"></i></a>
							<img class="avatar-src-style" height="100px" src="<?php echo esc_url($display_img_url); ?>" id="avatar-src"/>
						</td>
					</tr>
				</tbody>
			</table>
			<?php
		}
	}
}

/**
 * @Get User Avatar
 * @return {}
 */
if ( !function_exists( 'doctreat_get_user_avatar' ) ) {

	function doctreat_get_user_avatar( $size = 0, $doctreat_user_id = '' ) {
		if ( $doctreat_user_id != '' ) {
			$doctreat_user_avatars = get_the_author_meta( 'author_profile_avatar', $doctreat_user_id );
			if ( is_array( $doctreat_user_avatars ) && isset( $doctreat_user_avatars[ $size ] ) ) {
				return $doctreat_user_avatars[ $size ];
			} else if ( !is_array( $doctreat_user_avatars ) && $doctreat_user_avatars <> '' ) {
				return $doctreat_user_avatars;
			}
		}
	}

}
