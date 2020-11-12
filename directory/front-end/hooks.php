<?php
/**
 * Manage user colums
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_user_manage_user_columns')) {
    add_filter('manage_users_columns', 'doctreat_user_manage_user_columns');

    function doctreat_user_manage_user_columns($column) {
        $column['wt_profile']	= esc_html__('Linked Profile', 'doctreat');
		$column['wt_varifiled']	= esc_html__('Verified Profile', 'doctreat');
		
        return $column;
    }
}

/**
 * Manage users rows column
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_user_manage_user_column_row')) {
    add_filter('manage_users_custom_column', 'doctreat_user_manage_user_column_row', 10, 3);

    function doctreat_user_manage_user_column_row($val, $column_name, $user_id) {
        switch ($column_name) {
            case 'wt_profile' :
				$linked_profile	= doctreat_get_linked_profile_id($user_id);
				if( !empty( $linked_profile ) ){
					$val = '<a target="_blank" href="'.esc_url( get_edit_post_link($linked_profile) ).'">' . get_the_title($linked_profile). '</a>';
					return $val;
				}
				
				return $val;
				
			case 'wt_varifiled' :
				$linked_profile	= doctreat_get_linked_profile_id($user_id);
				$is_verified 	= get_post_meta($linked_profile, '_is_verified',true);
				
				if( !empty( $is_verified ) && $is_verified === 'yes' ) {
					$val = '<span class="dc-item-success">' . esc_html__('Verified', 'doctreat') . '</span>';
				} else {
					$val = '<span class="dc-item-danger">' . esc_html__('Not Verified', 'doctreat') . '</span>';
				}
				
				return $val;
				
				break;
            default:
        }
    }
}

/**
 * Manage user table action links
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_user_user_table_action_links')) {
    add_filter('user_row_actions', 'doctreat_user_user_table_action_links', 10, 2);

    function doctreat_user_user_table_action_links($actions, $user) {
		$linked_profile			= doctreat_get_linked_profile_id($user->ID);
		$is_verified 			= get_post_meta($linked_profile, '_is_verified',true);
		
		if( !empty( $linked_profile ) ){
			$profile 				= '<a href="'.esc_url( get_edit_post_link($linked_profile)).'">' .  esc_html__('View linked profile', 'doctreat'). '</a>';
			$actions['wt_profile']  = $profile;
			
			$actions['doctreat_status']		= "<a class=" . ((isset($is_verified) && $is_verified === 'yes') ? 'dc-item-danger' : 'dc-item-success') . "' href='" . esc_url(admin_url("users.php?action=doctreat_change_status&users=" . $user->ID . "&nonce=" . wp_create_nonce('doctreat_change_status_' . $user->ID))) . "'>" . ((isset($is_verified) && $is_verified === 'yes') ? esc_html__('Unverify', 'doctreat') : esc_html__('verify', 'doctreat') ) . "</a>";
		}
        
		
        return $actions;
    }

}

/**
 * Change users status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_change_status')) {
    add_action('admin_action_doctreat_change_status', 'doctreat_change_status');

    function doctreat_change_status() {

        if (isset($_REQUEST['users']) && isset($_REQUEST['nonce'])) {
            $nonce = !empty($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
            $users = !empty($_REQUEST['users']) ? $_REQUEST['users'] : '';

            if (wp_verify_nonce($nonce, 'doctreat_change_status_' . $users)) {
				
				$linked_profile			= doctreat_get_linked_profile_id($users);
				$is_verified 			= get_post_meta($linked_profile, '_is_verified',true);
				
                if (isset($is_verified) && $is_verified === 'yes') {
                    $new_status = 'no';
                    $message_param = 'unapproved';
                } else {
                    $new_status = 'yes';
                    $message_param = 'approved';
                }
				
                update_post_meta($linked_profile, '_is_verified', $new_status);
				update_user_meta($users, '_is_verified', $new_status);
				
                $redirect = admin_url('users.php?updated=' . $message_param);
				
            } else {
                $redirect = admin_url('users.php?updated=doctreat_false');
            }
        } else {
            $redirect = admin_url('users.php?updated=doctreat_false');
        }
		
        wp_redirect($redirect);
    }

}

/**
 * Users change status notices
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_user_change_status_notices')) {
    add_action('admin_notices', 'doctreat_user_change_status_notices');

    function doctreat_user_change_status_notices() {
        global $pagenow;
        if ($pagenow == 'users.php') {
            if (isset($_REQUEST['updated'])) {
                $message = $_REQUEST['updated'];
                if ($message == 'doctreat_false') {
                    print '<div class="updated notice error is-dismissible"><p>' . esc_html__('Something wrong. Please try again.', 'doctreat') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'doctreat') . '</span></button></div>';
                }
                if ($message == 'approved') {
                    print '<div class="updated notice is-dismissible"><p>' . esc_html__('User approved.', 'doctreat') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'doctreat') . '</span></button></div>';
                }
                if ($message == 'unapproved') {
                    print '<div class="updated notice is-dismissible"><p>' . esc_html__('User unapproved.', 'doctreat') . '</p><button class="notice-dismiss" type="button"><span class="screen-reader-text">' . esc_html__('Dismiss this notice.', 'doctreat') . '</span></button></div>';
                }
            }
        }
    }

}

/**
 * Get user role by id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists('doctreat_get_user_role') ){
  function doctreat_get_user_role($user_id = ''){
    if( !empty( $user_id ) ){
        $user = get_userdata( $user_id );
        $user_roles = $user->roles;
        $role = '';
        if( !empty( $user_roles[0] ) ) {
            $role = $user_roles[0];
        }
        return $role;
    }
  }
  add_filter('doctreat_get_user_role','doctreat_get_user_role', 10, 1);
}
/**
 * Load booking details
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

 //NEED IT RIGHT NOW!!!!!
if (!function_exists('doctreat_booking_details')) {

    function doctreat_booking_details() {
		$booking_id	= !empty($_GET['id']) ? intval($_GET['id']) : 0;
		if ( !empty($booking_id) ){
			$script = "jQuery(document).ready(function ($) { setTimeout(function() {jQuery('a[data-id=".$booking_id."]').trigger('click');},100);});";
			wp_add_inline_script('doctreat-dashboard', $script, 'after');
		}
	}
	add_action('doctreat_booking_details', 'doctreat_booking_details');
}

/**
 * User reset password
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_reset_password_form')) {

    function doctreat_reset_password_form() {
        global $wpdb;      

        if (!empty($_GET['key']) &&
                ( isset($_GET['action']) && $_GET['action'] == "reset_pwd" ) &&
                (!empty($_GET['login']) )
        ) {
            $reset_key = $_GET['key'];
            $user_login = $_GET['login'];
            $reset_action = $_GET['action'];

            $key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));

            if ($reset_key === $key) {
                $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s AND user_login = %s", $reset_key, $user_login));

                $user_login = $user_data->user_login;
                $user_email = $user_data->user_email;

                if (!empty($user_data)) {
                    ob_start();
                    ?>
                    <div class="modal fade dc-user-reset-model dc-offerpopup" tabindex="-1" role="dialog">
                        <div class="modal-dialog dc-modaldialog">
                            <div class="modal-content dc-modalcontent">
                                <div class="panel-lostps">
                                    <form class="dc-form-modal dc-form-signup dc-reset_password_form">
                                        <div class="form-group">
                                            <div class="dc-popuptitle">
												<h3><?php esc_html_e('Reset Password', 'doctreat'); ?></h3>
												<a href="javascript:;" class="dc-closebtn close" data-dismiss="modal" aria-label="Close"><i class="lnr lnr-cross"></i></a>
											</div>
											<div class="modal-body dc-formtheme dc-formhelp">
												<p><?php echo wp_get_password_hint(); ?></p>
												<div class="forgot-fields dc-resetpassword">
													<fieldset>
														<div class="form-group">
															<label for="password"><?php esc_html_e('New password', 'doctreat') ?></label>
															<input type="password"  name="password" id="password" class="input" size="20" value="" autocomplete="off" />
														</div>
														<div class="form-group">
															<label for="verify_password"><?php esc_html_e('Repeat new password', 'doctreat') ?></label>
															<input type="password" name="verify_password" id="verify_password" class="input" size="20" value="" autocomplete="off" />

															<input type="hidden" name="wt_change_pwd_nonce" value="<?php echo wp_create_nonce("wt_change_pwd_nonce"); ?>" />
														</div>
													</fieldset>
												</div>                                     
												<button class="dc-btn dc-change-password" type="button"><?php esc_html_e('Submit', 'doctreat'); ?></button>

												<input type="hidden" name="key" value="<?php echo esc_attr($reset_key); ?>" />
												<input type="hidden" name="reset_action" value="<?php echo esc_attr($reset_action); ?>" />
												<input type="hidden" name="login" value="<?php echo esc_attr($user_login); ?>" />
											</div>
                                        </div>
                                    </form>    
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="open-reset-window" data-toggle="modal" data-target=".dc-user-reset-model"></a>
                    <script>
						jQuery(document).ready(function ($) { setTimeout(function() {jQuery('.open-reset-window').trigger('click');},100);});
					</script>
                    <?php
                    echo ob_get_clean();
					
                }
            }
        }
    }

    add_action('doctreat_reset_password_form', 'doctreat_reset_password_form');
}


/**
 * Get location list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_get_locations_list' ) ) {
	function doctreat_get_locations_list($name='location',$selected=''){
		wp_dropdown_categories( array(
								'taxonomy' 			=> 'locations',
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'show_option_all' 	=> esc_html__('Select Location', 'doctreat'),
								'walker' 			=> new Doctreat_Walker_Location_Dropdown,
								'class' 			=> 'item-location-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'location-dp',
								'selected' 			=> $selected
							)
						);
	}
	add_action('doctreat_get_locations_list', 'doctreat_get_locations_list', 10,2);
}

/**
 * Get Services list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_get_specialities_list' ) ) {
	function doctreat_get_specialities_list($name='specialities',$selected='' ,$echo = 1){
		if( $echo === 1 ){
			wp_dropdown_categories( array(
								'taxonomy' 			=> 'specialities',
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'show_option_all' 	=> esc_html__('Select Speciality', 'doctreat'),
								'class' 			=> 'item-specialities-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'specialities-dp',
								'selected' 			=> $selected,
								'echo'				=> $echo 
							)
						);
		} else{
			$data	= wp_dropdown_categories( array(
								'taxonomy' 			=> 'specialities',
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'show_option_all' 	=> esc_html__('Select Speciality', 'doctreat'),
								'class' 			=> 'item-specialities-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'specialities-dp',
								'selected' 			=> $selected,
								'echo'				=> $echo 
							)
						);
		
			return $data;
		}
		
	}
	add_action('doctreat_get_specialities_list', 'doctreat_get_specialities_list', 10,3);
	add_filter('doctreat_get_specialities_list', 'doctreat_get_specialities_list', 10,3);
}

/**
 * Get taxonomy list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_get_taxonomy_list' ) ) {
	function doctreat_get_taxonomy_list($taxnomy_name,$name='specialities',$selected='' ,$echo = 1){
		if( $echo === 1 ){
			wp_dropdown_categories( array(
								'taxonomy' 			=> $taxnomy_name,
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'show_option_all' 	=> esc_html__('Select one', 'doctreat'),
								'class' 			=> 'item-taxnomy-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'taxnomy-dp',
								'selected' 			=> $selected,
								'echo'				=> $echo 
							)
						);
		} else{
			$data	= wp_dropdown_categories( array(
								'taxonomy' 			=> $taxnomy_name,
								'hide_empty' 		=> false,
								'hierarchical' 		=> 1,
								'show_option_all' 	=> esc_html__('Select one', 'doctreat'),
								'class' 			=> 'item-taxnomy-dp chosen-select',
								'orderby' 			=> 'name',
								'name' 				=> $name,
								'id'                => 'taxnomy-dp',
								'selected' 			=> $selected,
								'echo'				=> $echo 
							)
						);
		
			return $data;
		}
		
	}
	add_action('doctreat_get_taxonomy_list', 'doctreat_get_taxonomy_list', 10,4);
	add_filter('doctreat_get_taxonomy_list', 'doctreat_get_taxonomy_list', 10,4);
}

/**
 * get allowed featured by key and user id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_is_feature_allowed')) {

    function doctreat_is_feature_allowed( $key,$user_id ) {
		$listing_type	= doctreat_theme_option('listing_type');
		if( $listing_type === 'free' ){
			return true;
		}

		$is_enabled	= doctreat_get_subscription_metadata( $key,$user_id );
        if (!empty($is_enabled) && $is_enabled === 'yes') {
            return true;
        } else {
            return false;
        }
		
    }
	add_filter('doctreat_is_feature_allowed', 'doctreat_is_feature_allowed', 10 , 2);
}

/**
 * get allowed featured by key and user id
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_is_appointment_allowed')) {

    function doctreat_is_appointment_allowed( $key,$user_id ) {
		$listing_type	= doctreat_theme_option('listing_type');
		if( $listing_type === 'free' ){
			return true;
		}

		$is_enabled	= doctreat_get_subscription_metadata( $key,$user_id );
        if (!empty($is_enabled) && $is_enabled === 'yes') {
            return true;
        } else {
            return false;
        }
		
    }
	add_filter('doctreat_is_appointment_allowed', 'doctreat_is_appointment_allowed', 10 , 2);
}

/**
 * subscription feature value
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_is_feature_value')) {

    function doctreat_is_feature_value( $key,$user_id ) {
		$listing_type	= doctreat_theme_option('listing_type');
		if( $listing_type === 'free' ){
			return 10000;
		}
		$value	= doctreat_get_subscription_metadata( $key,$user_id );
        if ( !empty($value) ) {
            return $value;
        } else {
            return false;
        }
    }
	add_filter('doctreat_is_feature_value', 'doctreat_is_feature_value', 10 , 2);
}

/**
 * Get numaric values
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_numaric_values')) {

    function doctreat_get_numaric_values($step = 1,$max = 1) {
		$number_values = array(
                        'step' => $step,
                        'min' => $max
                    );
		return $number_values;
	}
	add_action('doctreat_get_numaric_values', 'doctreat_get_numaric_values' , 2);
}

/**
 * Get option values
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_option_values')) {

    function doctreat_get_option_values($options ='') {
		if ( empty ( $options )) {
			$options = array ( 'yes' => esc_html__( 'Yes', 'doctreat' ),'no' => esc_html__( 'No', 'doctreat' ));
		}
		
		$number_values = $options;
		return $number_values;
	}
	add_action('doctreat_get_option_values', 'doctreat_get_option_values' , 2);
}

/**
 * Get packages duration type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_duration_types')) {

    function doctreat_get_duration_types($key='',$type='array') {
		$durations = array(
                        'weekly' 	=> array( 
										'title' => esc_html__( 'Weekly ( 7 days )', 'doctreat' ),
										'value' => 7
									),
						'biweekly' 	=> array( 
										'title' => esc_html__( 'Biweekly ( 14 days )', 'doctreat' ),
										'value' => 14
									),
                        'monthly' 	=> array( 
										'title' => esc_html__( 'Monthly', 'doctreat' ),
										'value' => 30
									),
						'bimonthly' 	=> array( 
										'title' => esc_html__( 'Bimonthly ( 60 days )', 'doctreat' ),
										'value' => 60
									),
                        'quarterly' 	=> array( 
										'title' => esc_html__( 'Quarterly ( 90 days )', 'doctreat' ),
										'value' => 90
									),
						'biannually'	=> array( 
										'title' => esc_html__( 'Biannually( 6 Months )', 'doctreat' ),
										'value' => 180
									),
						'yearly'	=> array( 
										'title' => esc_html__( 'Yearly', 'doctreat' ),
										'value' => 365
									)
						
                    );
		
		if( $type === 'title' ){
			return !empty( $durations[$key]['title'] ) ? $durations[$key]['title'] : '';
		} else if( $type === 'value' ){
			return !empty( $durations[$key]['value'] ) ? $durations[$key]['value'] : '';
		} else{
			$options = array();
			foreach( $durations as $key => $item ){
				$options[$key] =  $item['title'];
			}
			return $options;
		}
		
	}
	add_action('doctreat_get_duration_types', 'doctreat_get_duration_types');
}

/**
 * Package features
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_print_pakages_features')) {
	add_action('doctreat_print_pakages_features', 'doctreat_print_pakages_features',10,3);
    function doctreat_print_pakages_features($key='',$val='',$post_id) {
		$item	 = get_post_meta($post_id,$key,true);
		$text	 = !empty( $val['text'] ) ? $val['text'] : '';

		if( isset( $item ) && ( $item === 'no' || empty($item) ) ){
			$feature = '<i class="ti-na"></i>';
		} elseif( isset( $item ) && $item === 'yes' ){
			$feature = '<i class="ti-check"></i>';
		} elseif( isset( $key ) && $key === 'dc_duration' ){
			$feature = doctreat_get_duration_types($item,'value');
		} elseif( isset( $key ) && $key === 'dc_featured_duration' ){
			$feature = $item.' ('.esc_html__('days','doctreat').')';
		}  else{
			$feature = $item;
		}
		ob_start();
		?>
			<li><span><?php echo do_shortcode($feature);?>&nbsp;<?php echo esc_html($text);?></span></li>
		<?php
		echo ob_get_clean();
	}
}

/**
 * Get packages features
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_pakages_features')) {

    function doctreat_get_pakages_features() {
       $packages = array(
				'dc_services' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of services','doctreat'),
					'classes' 		=> 'dc_doctors dc-all-field',
					'hint' 			=> esc_html__('No of services per speciality.','doctreat'),
					'user_type'		=> 'doctors',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> array()

				),
		   		'dc_downloads' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of Brochures','doctreat'),
					'classes' 		=> 'dc_doctors dc-all-field',
					'hint' 			=> esc_html__('No of Brochures/Downloads.','doctreat'),
					'user_type'		=> 'doctors',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> array()

				),
		   		'dc_articles' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of Articles','doctreat'),
					'classes' 		=> 'dc_doctors dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'doctors',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> array()

				),
		   		'dc_awards' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of Awards','doctreat'),
					'classes' 		=> 'dc_doctors dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'doctors',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> array()

				),
		   		'dc_memberships' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('No of Memberships','doctreat'),
					'classes' 		=> 'dc_doctors dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'doctors',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> array()

				),
		   		'dc_chat' => array(
					'type'			=> 'select',
					'title' 		=> esc_html__('Private Quick Chat','doctreat'),
					'classes' 		=> 'dc-common-field dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'common',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> doctreat_get_option_values()
				),
		   		'dc_bookings' => array(
					'type'			=> 'select',
					'title' 		=> esc_html__('Bookings','doctreat'),
					'classes' 		=> 'dc-common-field dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'common',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> doctreat_get_option_values()
				),
		   		'dc_featured' => array(
					'type'			=> 'select',
					'title' 		=> esc_html__('Featured Profile?','doctreat'),
					'classes' 		=> 'dc-common-field dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'common',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> doctreat_get_option_values()
				),
		   		'dc_featured_duration' => array(
					'type'			=> 'number',
					'title' 		=> esc_html__('Featured days?','doctreat'),
					'classes' 		=> 'dc-common-field dc-all-field',
					'text' 			=> '',
					'user_type'		=> 'common',
					'hint'			=> esc_html__('No of days for featured profile','doctreat'),
					'options'		=> doctreat_get_option_values()
				),
		   		'dc_duration' => array(
					'type'			=> 'select',
					'title' 		=> esc_html__('Duration','doctreat'),
					'classes' 		=> 'dc-common-field dc-duration dc-all-field',
					'hint' 			=> '',
					'user_type'		=> 'common',
					'text'			=> esc_html__('','doctreat'),
					'options'		=> doctreat_get_duration_types()
				),
			);
		
		$packages	= apply_filters('doctreat_filter_packages_features',$packages);
		return $packages;
    }

    add_action('doctreat_get_pakages_features', 'doctreat_get_pakages_features');
}


/**
 * get user type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_user_type')) {

    function doctreat_get_user_type($user_identity) {
        if (!empty($user_identity)) {
            $data = get_userdata($user_identity);
            if (!empty($data->roles[0]) && $data->roles[0] === 'doctors') {
                return 'doctors';
            } else if (!empty($data->roles[0]) && $data->roles[0] === 'hospitals') {
                return 'hospitals';
            } else if (!empty($data->roles[0]) && $data->roles[0] === 'regular_users') {
                return 'regular_users';
            } else{
                return false;
            }
        }

        return false;
    }

    add_filter('doctreat_get_user_type', 'doctreat_get_user_type', 10, 1);
}

/**
 * price format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_price_format' ) ) {
	function doctreat_price_format($price='', $type = 'echo'){
		if (class_exists('WooCommerce')) {
			$price = wc_price( $price );
		} else{
			$currency	= doctreat_get_current_currency();
			$price = !empty($currency['symbol'] ) ? $currency['symbol'].$price : '$';
		}
		
		if( $type === 'return' ) {
			return wp_strip_all_tags( $price );
		} else {
			echo wp_strip_all_tags( $price );
		}
		
	}
	
	add_action( 'doctreat_price_format', 'doctreat_price_format',10,2 );
	add_filter( 'doctreat_price_format', 'doctreat_price_format',10,2 );
}

/**
 * Show doctors/hospitals specilities
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_specilities_list' ) ) {
	
	function doctreat_specilities_list($post_id='',$show_number=1){
		
		if( !empty($post_id) ) {
			$specialities	= wp_get_post_terms( $post_id, 'specialities', array( 'fields' => 'all' ) );
			ob_start();
			
			if(!empty($specialities) && !is_wp_error($specialities)){
				
				$sp_count		= 0;
				$tipsco_html	= '';
				$total_sp_count	= !empty($specialities) ? count($specialities) : 0;
				$remining_count	= $total_sp_count - $show_number;
				?>
				<div class="dc-doc-specilities-tag">
					<?php foreach( $specialities as $speciality ){

						$term_url	= get_term_link($speciality);
					
						if( $sp_count<$show_number ){ ?>
							<a href="<?php echo esc_url($term_url);?>"><?php echo esc_html($speciality->name);?></a>
						<?php } else { 
							$tipsco_html	.="<a href='".esc_url($term_url)."' >".esc_html($speciality->name)."</a>";
						}
					
						$sp_count++;
					}

					if($total_sp_count>$show_number){
					?>
						<a href="javascript:;" class="dc-specilites-tipso dc-tipso" data-tipso="<?php echo do_shortcode( $tipsco_html );?>" data-id="<?php echo intval($post_id);?>">+<?php echo intval($remining_count);?><i class="fa fa-caret-down"></i></a>
					<?php }?>
				</div>
				<?php
			}
			
			echo ob_get_clean();
				
		}
	}
	add_action( 'doctreat_specilities_list', 'doctreat_specilities_list',10,2 );
	
}

/**
 * user verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_verification_check' ) ) {
	
	function doctreat_get_verification_check($user_id='',$text=''){
		
		if( !empty($user_id) ) {
			$is_verified 	= get_post_meta($user_id, '_is_verified', true);
			ob_start();
			if( !empty( $is_verified ) && $is_verified === 'yes' ){ ?>
				<i class="far fa-check-circle dc-awardtooltip dc-tipso" data-tipso="<?php esc_attr_e('Verified user','doctreat');?>"></i>
			<?php } 
				echo ob_get_clean();
			}
	}
	add_action( 'doctreat_get_verification_check', 'doctreat_get_verification_check',10,2 );
}

/**
 * user Doctor verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_drverification_check' ) ) {
	
	function doctreat_get_drverification_check($post_id='',$text=''){
		if( !empty($post_id) ) {
			$is_verified	= doctreat_get_post_meta($post_id,'am_is_verified');
			ob_start();
			if( !empty( $is_verified ) && $is_verified === 'yes' ){ ?>
				<i class="icon-sheild dc-awardtooltip dc-tipso" data-tipso="<?php echo esc_attr( $text ); ?>"></i> 
			<?php } 
				echo ob_get_clean();
			}
	}
	add_action( 'doctreat_get_drverification_check', 'doctreat_get_drverification_check',10,2 );
}

/**
 * user Doctor verification check
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_favorit_check' ) ) {
	
	function doctreat_get_favorit_check($post_id='',$size=''){
		
		if( !empty($post_id) ) {
			$post_type		= get_post_type($post_id);
			$post_key		= '_saved_'.$post_type;
			
			$check_wishlist	= doctreat_check_wishlist($post_id,$post_key);
			$class			= !empty( $check_wishlist ) ? 'dc-liked' : 'dc-add-wishlist';
			ob_start();
			if( !empty( $size ) && $size === 'large' ){ ?>
				<a href="javascript:;" class="dc-like <?php echo esc_attr( $class );?>" data-id="<?php echo intval($post_id);?>"><i class="far fa-heart"></i></a> 
			<?php } 
				echo ob_get_clean();
			}
	}
	add_action( 'doctreat_get_favorit_check', 'doctreat_get_favorit_check',10,2 );
}

/**
 * fallback image for doctor banner
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'doctreat_doctor_banner_fallback' ) ) {

	function doctreat_doctor_banner_fallback( $object, $atts = array() ) {
		
		extract( shortcode_atts( array(
			"width" => '1920',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/drbanner-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'doctreat_doctor_banner_fallback', 'doctreat_doctor_banner_fallback', 10, 2 );
}

/**
 * fallback image for doctors avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'doctreat_doctor_avatar_fallback' ) ) {

	function doctreat_doctor_avatar_fallback( $object, $atts = array() ) {
		extract( shortcode_atts( array(
			"width" => '1920',
			"height" => '400',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/dravatar-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'doctreat_doctor_avatar_fallback', 'doctreat_doctor_avatar_fallback', 10, 2 );
}

/**
 * fallback image for hospital avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( !function_exists( 'doctreat_hospitals_avatar_fallback' ) ) {

	function doctreat_hospitals_avatar_fallback( $object, $atts = array() ) {
		extract( shortcode_atts( array(
			"width" => '1110',
			"height" => '300',
		), $atts ) );

		if ( isset( $object ) && !empty( $object ) && $object != NULL ) {
			return $object;
		} else {
			return get_template_directory_uri() . '/images/hoavatar-' . intval( $width ) . 'x' . intval( $height ) . '.jpg';
		}
	}

	add_filter( 'doctreat_hospitals_avatar_fallback', 'doctreat_hospitals_avatar_fallback', 10, 2 );
}

/**
 * get QR code
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_qr_code')) {
	add_action('doctreat_get_qr_code', 'doctreat_get_qr_code',10,2);
    function doctreat_get_qr_code($type='user',$id='') {
		?>
		<div class="dc-authorcodescan dc-widget dc-widgetcontent">
			<div class="dc-qrscan">
				<figure class="dc-qrcodeimg">
					<img class="dc-qrcodedata" src="<?php echo get_template_directory_uri() ; ?>/images/qrcode.png" alt="<?php esc_attr_e('QR-Code', 'doctreat'); ?>">
					<figcaption>
						<a href="javascript:;" class="dc-qrcodedetails" data-type="<?php echo esc_attr( $type ); ?>" data-key="<?php echo esc_attr( $id ); ?>">
							<span><i class="lnr lnr-redo"></i><?php esc_html_e('load', 'doctreat'); ?><br><?php esc_html_e('QR code', 'doctreat'); ?></span>
						</a>
					</figcaption>
				</figure>
			</div>
			<div class="dc-qrcodedetail">
				<span class="lnr lnr-laptop-phone"></span>
				<div class="dc-qrcodefeat">
	                <h3><?php esc_html_e('Scan with your', 'doctreat'); ?> <span><?php echo esc_html_e('Smart Phone', 'doctreat'); ?> </span> <?php esc_html_e('To Get It Handy.', 'doctreat'); ?></h3>
	            </div>	
            </div>	
		</div>
		<?php
	}
}

/**
 * Display Templates name with Page Name in
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_display_post_status' ) ) {
    add_filter( 'display_post_states', 'doctreat_display_post_status', 10, 2 );

    /**
     * Add a post display state for special WC pages in the page list table.
     *
     * @param array   $post_states An array of post display states.
     * @param WP_Post $post        The current post object.
     */
    function doctreat_display_post_status( $post_states, $post ) {

        $temp_name  = get_post_meta( $post->ID, '_wp_page_template', true );
        if( !empty( $temp_name ) && $temp_name === 'directory/healthforum.php' ){
            $post_states['doctreat_forum_search'] = esc_html__('Health Forum Page', 'doctreat');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/doctor-search.php' ){
            $post_states['doctreat_doctors_search']  = esc_html__('Search Doctors Page', 'doctreat');
        }else if( !empty( $temp_name ) && $temp_name === 'directory/dashboard.php' ){
            $post_states['doctreat_dashboard']  = esc_html__('Dashboard', 'doctreat');
        }
        
        return $post_states;
    }
}

/**
 * Print locations html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_print_locations' ) ){
    function doctreat_print_locations(){
        $location = !empty( $_GET['location']) ? $_GET['location'] : array();

        ob_start(); 
        ?>
        <div class="dc-widget dc-effectiveholder">
            <div class="dc-widgettitle">
                <h2><?php esc_html_e('Location', 'doctreat'); ?></h2>
            </div>
            <div class="dc-widgetcontent">
                <div class="dc-formtheme dc-formsearch">
                    <fieldset>
                        <div class="form-group">
                            <input type="text" value="" class="form-control dc-filter-field" placeholder="<?php esc_attr_e('Search Location', 'doctreat'); ?>">
                            <a href="javascrip:;" class="dc-searchgbtn"><i class="fa fa-search"></i></a>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="dc-checkboxholder dc-filterscroll">              
                            <?php 
								wp_list_categories( array(
										'taxonomy' 			=> 'locations',
										'hide_empty' 		=> false,
										'current_category'	=> $location,
										'style' 			=> '',
										'walker' 			=> new Doctreat_Walker_Location,
									)
								);
                             ?>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <?php
        echo ob_get_clean();      
    }
    add_action('doctreat_print_locations', 'doctreat_print_locations', 10);
}

/**
 * Print languages html
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_print_languages' ) ){
    function doctreat_print_languages(){
        $language = !empty( $_GET['language']) ? $_GET['language'] : array();
		
		if( taxonomy_exists('languages') ){
			$languages = get_terms( 
				array(
					'taxonomy' 		=> 'languages',
					'hide_empty' 	=> false,
				) 
			);

			if( !empty( $languages ) ){
				ob_start(); 
				?>
				<div class="dc-widget dc-effectiveholder">
					<div class="dc-widgettitle">
						<h2><?php esc_html_e('Languages', 'doctreat'); ?></h2>
					</div>
					<div class="dc-widgetcontent">
						<div class="dc-formtheme dc-formsearch">
							<fieldset>
								<div class="form-group">
									<input type="text" value="" class="form-control dc-filter-field" placeholder="<?php esc_attr_e('Search Language', 'doctreat'); ?>">
									<a href="javascript:;" class="dc-searchgbtn"><i class="fa fa-search"></i></a>
								</div>
							</fieldset>
							<fieldset>
								<div class="dc-checkboxholder dc-filterscroll">              
									<?php foreach ($languages as $key => $value) { ?>
										<span class="dc-checkbox">
											<input id="language<?php echo esc_attr( $value->term_id ); ?>" type="checkbox" name="language[]" value="<?php echo esc_attr( $value->slug ); ?>" <?php checked( in_array( $value->slug, $language ) ); ?>>
											<label for="language<?php echo esc_attr( $value->term_id ); ?>"> <?php echo esc_attr( $value->name ); ?></label>
										</span>
									<?php } ?>
								</div>
							</fieldset>
						</div>
					</div>
				</div>
				<?php
				echo ob_get_clean();   
			}
		}
    }
    add_action('doctreat_print_languages', 'doctreat_print_languages', 10);
}

/**
 * print post date
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_post_date' ) ){
    function doctreat_post_date( $post_id = '' ){              
		ob_start(); 
		
		$date_formate	= get_option('date_format');
		$post_date		= !empty($post_id) ? get_post_field('post_date',$post_id) : "";
		
		if( !empty($post_date) ) {?>
			<span class="dc-datetime"><i class="lnr lnr-clock"></i> <?php echo date_i18n($date_formate,strtotime($post_date));?></span>
		<?php 
		}
        echo ob_get_clean();       
    }
    add_action('doctreat_post_date', 'doctreat_post_date', 10 , 1);
}

/**
 * if no Record Found
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if ( ! function_exists( 'doctreat_empty_records_html' ) ) {
	add_action('doctreat_empty_records_html', 'doctreat_empty_records_html', 10, 3);
	add_filter('doctreat_empty_records_html', 'doctreat_empty_records_html',10,3);
	function doctreat_empty_records_html($class_name= '', $message = '',$wrap=false) {
		ob_start();
		?>
			<div class="dc-emptydata-holder">
    			<div class="dc-emptydata">
    				<div class="dc-emptydetails <?php echo esc_attr( $class_name );?>">
    					<span></span>
    					<?php if( !empty($message) ) { ?>
    						<em><?php echo esc_html($message);?></em>
    					<?php } ?>
    				</div>
    			</div>
    		</div>
		<?php
		if( empty($wrap) ){
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
}

/**
 * User default avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_user_profile_avatar')) {
	add_filter('get_avatar','doctreat_user_profile_avatar',10,5);
	function doctreat_user_profile_avatar($avatar = '', $id_or_email, $size = 60, $default = '', $alt = false ){
		
		if ( is_numeric( $id_or_email ) )
			$user_id = (int) $id_or_email;
		elseif ( is_string( $id_or_email ) && ( $user = get_user_by( 'email', $id_or_email ) ) )
			$user_id = $user->ID;
		elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) )
			$user_id = (int) $id_or_email->user_id;
		 
		if ( empty( $user_id ) )
			return $avatar;
		
		$user_type	= apply_filters('doctreat_get_user_type', $user_id );
		
		if( ( $user_type === 'doctors' 
			  || $user_type === 'hospitals'
			)
			  && !empty( $user_id )
		) {
			$profile_linked_profile		= doctreat_get_linked_profile_id($user_id);
			if( $user_type === 'doctors' ) {
				$filter		= 'doctreat_doctor_avatar_fallback';
				$local_avatars 	= apply_filters(
					'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
			} else if( $user_type === 'hospitals' ) {
				$filter		= 'doctreat_hospitals_avatar_fallback';
				$local_avatars 	= apply_filters(
					'doctreat_hospitals_avatar_fallback', doctreat_get_hospital_avatar( array('width' => $size, 'height' => $size), $profile_linked_profile ), array('width' => $size, 'height' => $size)
				);
			} else {
				$filter	= 'doctreat_avatar_fallback';
			}

			if ( empty( $local_avatars ) )
				return $avatar;

			$size = (int) $size;

			if ( empty( $alt ) )
				$alt = get_the_author_meta( 'display_name', $user_id );

			$author_class = is_author( $user_id ) ? ' current-author' : '' ;
			$avatar       = "<img alt='" . esc_attr( $alt ) . "' src='" . $local_avatars . "' class='avatar photo' width='".intval( $size )."' height='".intval( $size )."'  />";
			
			return apply_filters( $filter, $avatar );
		} else{
			return $avatar;
		}
	}
}

/**
 * Doctor thumnail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_thumnail' ) ) {
	
	function doctreat_get_doctor_thumnail($profile_id='' ,$width = 100 , $height = 100){
		
		if( !empty( $profile_id ) ) {
			
			$display_name	= get_the_title($profile_id);
			$display_name	= !empty( $display_name ) ? $display_name : '';
			$avatar_url 	= apply_filters(
								'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => $width, 'height' => $height), $profile_id), array('width' => $width, 'height' => $height)
							);
			
			$avatar_2x 	= apply_filters(
								'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => 545, 'height' => 428), $profile_id), array('width' => 545, 'height' => 428)
							);
			
			$user_id	= doctreat_get_linked_profile_id( $profile_id,'post' );
			$featured	= get_post_meta($profile_id,'is_featured',true);
			
			ob_start();
			?>
			<figure class="dc-docpostimg">
				<img width="" height="" class="dc-image-res" src="<?php echo esc_url( $avatar_url );?>" alt="<?php echo esc_attr( $display_name );?>">
				<img width="" height="" class="dc-image-res-2x" src="<?php echo esc_url( $avatar_2x );?>" alt="<?php echo esc_attr( $display_name );?>">
				<?php if( !empty( $featured ) && intval($featured) > 0 ){ ?>
					<figcaption>
						<span class="dc-featuredtag"><i class="fa fa-bolt"></i></span>
					</figcaption>
				<?php } ?>
			</figure>
			<?php
				echo ob_get_clean();
			}
	}
	add_action( 'doctreat_get_doctor_thumnail', 'doctreat_get_doctor_thumnail',10,3 );
}

/**
 * Doctor personal detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_details' ) ) {
	
	function doctreat_get_doctor_details($profile_id='',$show_speci = 1){
		if( !empty( $profile_id ) ) {
			
			$display_name	= doctreat_full_name($profile_id);
			$sub_heading	= doctreat_get_post_meta( $profile_id ,'am_sub_heading');
			$feedback		= get_post_meta($profile_id,'review_data',true);
			$feedback		= !empty( $feedback ) ? $feedback : array();
			$total_rating	= !empty( $feedback['dc_total_rating'] ) ? $feedback['dc_total_rating'] : 0 ;
			
			$total_percentage	= !empty( $feedback['dc_total_percentage'] ) ? $feedback['dc_total_percentage'] : 0 ;
			
			
			$profile_url	= get_the_permalink( $profile_id );
			$profile_url	= !empty( $profile_url ) ? $profile_url : '';
			
			$user_identity	= doctreat_get_linked_profile_id($profile_id,'post');
			$user_type		= apply_filters('doctreat_get_user_type', $user_identity );
			
			ob_start();
			?>
			<div class="dc-title">
				<?php do_action('doctreat_specilities_list',$profile_id,$show_speci);?>
				<h3>
					<?php if( !empty( $display_name ) ){?>
						<a href="<?php echo esc_url( $profile_url );?>"><?php echo esc_html( $display_name );?></a> 
					<?php }?>
					<?php do_action('doctreat_get_drverification_check',$profile_id,esc_html__('Medical Registration Verified','doctreat'));?>
					<?php do_action('doctreat_get_verification_check',$profile_id,'');?>
				</h3>
				<ul class="dc-docinfo">
					<?php if( !empty( $sub_heading ) ){?>
						<li><em><?php echo esc_html( $sub_heading );?></em></li>
					<?php } ?>
					<?php if( !empty( $user_type ) && $user_type === 'doctors'){ ?>
						<li>
							<span class="dc-stars"><span style="width: <?php echo intval( $total_percentage );?>%;"></span></span><em><?php echo intval( $feedback );?>&nbsp;<?php esc_html_e('Feedback','doctreat');?></em>
						</li>
					<?php }  ?>
				</ul>
				
			</div>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_doctor_details', 'doctreat_get_doctor_details',10,2 );
}

/**
 * Doctor Services
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_services' ) ) {
	
	function doctreat_get_doctor_services($profile_id='',$term = ''){
		if( !empty( $profile_id ) && !empty( $term ) ) {
			$terms 		= apply_filters('doctreat_get_tax_query',array(),$profile_id,$term,'');
			if( !empty( $terms ) ){
				$limit		= 5;
				$total		= count($terms);
				$term_count	= 0;
				$style		= '';
				ob_start();
				?>
				<div class="dc-tags">
					<ul>
						<?php 
							foreach ( $terms as $term ) {
								$term_count++;
								$term_link	= get_term_link($term);
								$term_link	= !empty( $term_link ) ? $term_link	: '';
								
								if( $term_count > $limit ){
									$style	= 'dc-display-none';
								}
							?>
							<li class="<?php echo esc_attr( $style );?>">
								<a href="<?php echo esc_url( $term_link );?>"><?php echo esc_html( $term->name );?></a>
							</li>
							
							<?php if( $term_count == $limit && $total>$term_count){?>
								<li class="dc-viewall-services">
									<a href="javascript:;" class="dc-tagviewall"><?php esc_html_e('View all','doctreat');?></a>
								</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
			<?php
				echo ob_get_clean();
			}
		}
	}
	add_action( 'doctreat_get_doctor_services', 'doctreat_get_doctor_services',10,2 );
}

/**
 * Doctor Listing Booking information
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_booking_information' ) ) {
	
	function doctreat_get_doctor_booking_information( $profile_id=''){
		
		$availability	= 'no';
		
		if( !empty( $profile_id ) ) {
			
			$profile_url	= get_the_permalink( $profile_id );
			$profile_url	= !empty( $profile_url ) ? $profile_url : '';
			$location		= doctreat_get_location($profile_id);
			$location		= !empty( $location['_country'] ) ? $location['_country'] : '';	
			$user_id		= doctreat_get_linked_profile_id($profile_id,'post');
			$package_expiry	= doctreat_get_subscription_metadata('dc_bookings',$user_id);
			$bookig_days	= doctreat_get_booking_days( $user_id );
			$bookig_days	= !empty( $bookig_days ) ? $bookig_days : array();
			
			$day				= strtolower(date('D'));
			$review_meta		= array(
									'_feedback_recommend' => 'yes'
									);
			
			$votes				= doctreat_get_total_posts_by_multiple_meta('reviews','publish',$review_meta,$user_id);
			$votes				= !empty( $votes ) ? intval( $votes ) : 0 ;
			
			$am_starting_price	= doctreat_get_post_meta( $profile_id,'am_starting_price');
			$am_starting_price	= !empty( $am_starting_price ) ? $am_starting_price : '';
			$booking_option		= doctreat_get_booking_oncall_option('oncall');
			ob_start();
			?>
			<div class="dc-doclocation dc-doclocationvtwo">
				<?php if( !empty( $location ) ){?>
					<span><i class="ti-direction-alt"></i><?php echo esc_html( $location );?></span>
				<?php } ?>
				<?php if( !empty( $bookig_days ) ){?>
					<span>
						<i class="lnr lnr-clock"></i><?php 
							$total_bookings	= count( $bookig_days );
							$start			= 0;
							foreach( $bookig_days as $val ){ 
								$day_name	= doctreat_get_week_keys_translation($val);
								$start ++;
								if( $val == $day ){  
									$availability	= 'yes';
									echo '<em class="dc-bold">'.$day_name.'</em>'; 
								} else {
									echo esc_html( $day_name );
								}
								if( $start != $total_bookings ) {
									echo ', ';
								}
							}
						?>
					</span>
				<?php } ?>
				<span><i class="ti-thumb-up"></i><?php echo intval( $votes );?> <?php esc_html_e( 'Votes','doctreat');?></span>
				<?php if( !empty( $am_starting_price ) ){?>
					<span><i class="ti-wallet"></i> <?php esc_html_e('Starting From','doctreat');?> <?php doctreat_price_format($am_starting_price);?></span>
				<?php } ?>
				<span><i class="ti-clipboard"></i> 
					<?php if( $availability === 'yes' ){ ?>
						<em class="dc-available"><?php esc_html_e('Available Today','doctreat');?></em>
					<?php } else{ ?>
						<em class="dc-dayon"><?php esc_html_e('Not Available','doctreat');?></em>
					<?php } ?>
				</span>
				
				<div class="dc-btnarea">
					<?php 
						if(empty($booking_option)){ ?>
							<a href="<?php echo esc_url( $profile_url );?>" class="dc-btn"><?php esc_html_e('View Full Profile','doctreat');?></a>
						<?php } else { ?>
							<?php if( !empty($package_expiry) && $package_expiry ==='yes' ){ ?>
								<a href="javascript:;" data-id="<?php echo intval( $profile_id );?>" class="dc-btn dc-booking-contacts"><?php esc_html_e('Call Now','doctreat');?></a>
							<?php } else { ?>
								<a href="<?php echo esc_url( $profile_url );?>" class="dc-btn"><?php esc_html_e('View Full Profile','doctreat');?></a>
							<?php } ?>
						<?php } ?>
					<?php do_action('doctreat_get_favorit_check',$profile_id,'large');?>
				</div>
			</div>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_doctor_booking_information', 'doctreat_get_doctor_booking_information',10,1 );
}

/**
 * Hospital Listing Booking information
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_hospital_booking_information' ) ) {
	
	function doctreat_get_hospital_booking_information( $profile_id=''){
		$availability	= 'no';
		
		if( !empty( $profile_id ) ) {
			$profile_url		= get_the_permalink( $profile_id );
			$profile_url		= !empty( $profile_url ) ? $profile_url : '';
			$location			= doctreat_get_location($profile_id);
			$location			= !empty( $location['_country'] ) ? $location['_country'] : '';	
			$user_id			= doctreat_get_linked_profile_id($profile_id,'post');
			$bookig_days		= doctreat_get_post_meta( $profile_id,'am_week_days');
			$bookig_days		= !empty( $bookig_days ) ? $bookig_days : array();
			$day				= strtolower(date('D'));
			$tem_members		= doctreat_get_total_posts_by_multiple_meta('hospitals_team','publish',array('hospital_id' => $profile_id));
			$tem_members		= !empty( $tem_members ) ? intval($tem_members) : 0 ;
			$am_availability	= doctreat_get_post_meta( $profile_id,'am_availability');
			$am_availability	= !empty( $am_availability ) ? $am_availability : '';
			
			if( !empty( $am_availability ) && $am_availability === 'others' ) {
				$am_availability	= doctreat_get_post_meta( $profile_id,'am_other_time');
			} else if($am_availability === 'yes') {
				$am_availability	= esc_html__('24/7 available','doctreat');
			}
			
			ob_start();
			?>
			<div class="dc-doclocation dc-doclocationvtwo">
				<?php if( !empty( $location ) ){?>
					<span><i class="ti-direction-alt"></i><?php echo esc_html( $location );?></span>
				<?php } ?>
				<?php if( !empty( $bookig_days ) ){?>
					<span>
						<i class="lnr lnr-clock"></i><?php 
							$total_bookings	= count( $bookig_days );
							$start			= 0;
							foreach( $bookig_days as $val ){ 
								$day_name	= doctreat_get_week_keys_translation($val);
								$start ++;
								if( $val == $day ){  
									echo '<em class="dc-bold">'.ucfirst( $day_name ).'</em>'; 
								} else {
									echo ucfirst( $day_name );
								}
								
								if( $start != $total_bookings ) {
									echo ', ';
								}
							}
						?>
					</span>
				<?php } ?>
				<?php if(!empty($tem_members)){?>
					<span><i class="ti-thumb-up"></i><?php esc_html_e( 'Doctors Onboard:','doctreat');?>&nbsp;<?php echo intval( $tem_members );?></span>
				<?php } ?>
				<?php if( !empty( $am_availability ) ){?>
					<span><i class="ti-wallet"></i><?php echo esc_html($am_availability);?></span>
				<?php } ?>
				<div class="dc-btnarea">
					<a href="<?php echo esc_url( $profile_url );?>" class="dc-btn"><?php esc_html_e('View More','doctreat');?></a>
					<?php do_action('doctreat_get_favorit_check',$profile_id,'large');?>
				</div>
			</div>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_hospital_booking_information', 'doctreat_get_hospital_booking_information',10,1 );
}

/**
 * Doctor Listing Booking 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_booking' ) ) {
	
	function doctreat_get_doctor_booking( $profile_id='',$show_btn='yes'){
		$availability	= 'no';
		if( !empty( $profile_id ) ) {
			$location		= doctreat_get_location($profile_id);
			$location		= !empty( $location['_country'] ) ? $location['_country'] : '';	
			
			$profile_url	= get_the_permalink( $profile_id );
			$profile_url	= !empty( $profile_url ) ? $profile_url : '';
			$post_author	= doctreat_get_linked_profile_id($profile_id,'post');
			$bookig_days	= doctreat_get_booking_days( $post_author );
			$bookig_days	= !empty( $bookig_days ) ? $bookig_days : array();
			$day			= strtolower(date('D'));
			$total_bookings	= count( $bookig_days );
			$start			= 0;
			foreach( $bookig_days as $val ){ 
				$day_name	= doctreat_get_week_keys_translation($val);
				$start ++;
				if( $val == $day ){  
					$availability	= 'yes';
					echo '<em class="dc-bold">'.ucfirst( $day_name ).'</em>'; 
				}
			}
			
			ob_start();
			?>
			<div class="dc-doclocation">
				<?php if( !empty( $location ) ){?>
					<span><i class="ti-direction-alt"></i><?php echo esc_html( $location );?></span>
				<?php } ?>
				<span><i class="ti-clipboard"></i> 
					<?php if( !empty( $availability ) && $availability === 'yes' ){ ?>
						<em class="dc-available"><?php esc_html_e('Available Today','doctreat');?></em>
					<?php } else{ ?>
						<em class="dc-dayon"><?php esc_html_e('Not Available','doctreat');?></em>
					<?php } ?>
				</span>
				<?php if( !empty( $show_btn ) && $show_btn === 'yes' ){?>
					<div class="dc-btnarea">
						<a href="<?php echo esc_url( $profile_url );?>" class="dc-btn"><?php esc_html_e('Book Now','doctreat');?></a>
					</div>
				<?php }?>
			</div>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_doctor_booking', 'doctreat_get_doctor_booking',10,2 );
}

/**
 * Article Author
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_article_author' ) ) {
	
	function doctreat_get_article_author( $profile_id=''){
		
		if( !empty( $profile_id ) ) {
			$auther_name	= get_the_title( $profile_id );
			$auther_name	= !empty( $auther_name ) ? $auther_name : '';
			$avatar_url 	= apply_filters(
							'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => 30, 'height' => 30), $profile_id), array('width' => 30, 'height' => 30) 
						);
			ob_start();
			?>
			<figcaption>
				<div class="dc-articlesdocinfo">
					<?php if( !empty( $avatar_url ) ) {?>
						<img src="<?php echo esc_url( $avatar_url );?>" alt="<?php echo esc_attr( $auther_name );?>">
					<?php } ?>
					<?php if( !empty( $auther_name ) ) {?><span><?php echo esc_html( $auther_name );?></span><?php } ?>
				</div>
			</figcaption>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_article_author', 'doctreat_get_article_author',10,1 );
}

/**
 * Post Likes
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_post_likes')) {

    function doctreat_post_likes() {
		$post_id	= !empty( $_POST['id'] ) ? $_POST['id'] : '';
		$json		= array();
		
        if (empty($post_id)) {
            $json['type'] 	 = 'error';
			$json['message'] = esc_html__('No kiddies please', 'doctreat');
			wp_send_json( $json );
        }

		$key	= 'post_liked_';
		
        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, $key, time() + ( 365 * 24 * 60 * 60));
            $view_key = 'post_likes';

            $count = get_post_meta($post_id, $view_key, true);

            if (empty($count)) {
                $count = 1;
                add_post_meta($post_id, $view_key, 1);
            } else {
                $count++;
                update_post_meta($post_id, $view_key, $count);
            }
			
			$json['html'] 	 = sprintf( _n( '<i class="ti-heart"></i>%s Like', '<i class="ti-heart"></i>%s Likes', $count, 'doctreat' ), $count );
		
			$json['type'] 	 = 'success';
			$json['message'] = esc_html__('Post has been liked', 'doctreat');
			wp_send_json( $json );
        } else{
			$json['type'] 	 = 'error';
			$json['message'] = esc_html__('You have already liked this post', 'doctreat');
			wp_send_json( $json );
		}
    }

    add_action('wp_ajax_doctreat_post_likes', 'doctreat_post_likes');
    add_action('wp_ajax_nopriv_doctreat_post_likes', 'doctreat_post_likes');
}

/**
 * Article meta data
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_article_sharing' ) ) {
	
	function doctreat_get_article_sharing( $post_id=''){
		
		if( !empty( $post_id ) ) {
			$post_likes		= get_post_meta($post_id,'post_likes',true);
			$post_likes		= !empty( $post_likes ) ? $post_likes : 0 ;
			$post_views		= get_post_meta($post_id,'set_blog_view',true);
			$post_views		= !empty( $post_views ) ? $post_views : 0 ;
			
			ob_start();
			?>
			<ul class="dc-moreoptions">
				<li class="dcget-likes" data-key="<?php echo esc_attr($post_id);?>"><a href="javascript:;"><i class="ti-heart"></i><?php echo sprintf( _n( '%s Like', '%s Likes', $post_likes, 'doctreat' ), $post_likes );?></a></li>
				<?php if( class_exists( 'DoctreatGlobalSettings' ) ) {?>
					<li><a href="javascript:;"><i class="ti-eye"></i><?php echo sprintf( _n( '%s View', '%s Views', $post_views, 'doctreat' ), $post_views );?></a></li>
				<?php }?>
				<li><a href="javascript:;"><i class="ti-comment"></i><?php echo sprintf( _n( '%s Comment', '%s Comments', get_comments_number($post_id), 'doctreat' ), get_comments_number($post_id) );?></a></li>
			</ul>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_article_sharing', 'doctreat_get_article_sharing',10,1 );
}

/**
 * Article meta data
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_article_meta' ) ) {
	
	function doctreat_get_article_meta( $post_id=''){
		
		if( !empty( $post_id ) ) {
			$post_likes		= get_post_meta($post_id,'post_likes',true);
			$post_likes		= !empty( $post_likes ) ? $post_likes : 0 ;
			$post_views		= get_post_meta($post_id,'post_views',true);
			$post_views		= !empty( $post_views ) ? $post_views : 0 ;
			
			ob_start();
			?>
			<ul class="dc-moreoptions dc-articlesmetadata">
				<li><?php doctreat_get_post_date($post_id);?></li>
				<li class="dcget-likes" data-key="<?php echo esc_attr($post_id);?>"><a href="javascript:;"><i class="ti-heart"></i><?php echo sprintf( _n( '%s Like', '%s Likes', $post_likes, 'doctreat' ), $post_likes );?></a></li>
				<li><i class="ti-comment"></i><?php echo sprintf( _n( '%s Comment', '%s Comments', get_comments_number($post_id), 'doctreat' ), get_comments_number($post_id) );?></li>
			</ul>
		<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_article_meta', 'doctreat_get_article_meta',10,1 );
}
/**
 * Get name bases
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_get_name_bases')) {

    function doctreat_get_name_bases($key='',$type='') {
		global $theme_settings;
		$name_base_doctors			= !empty($theme_settings['name_base_doctors']) ? $theme_settings['name_base_doctors'] : '';
		$name_base_users			= !empty($theme_settings['name_base_users']) ? $theme_settings['name_base_users'] : '';
		
		if($type === 'doctor' && !empty($name_base_doctors)){
			$name_bases = $name_base_doctors;
			$name_bases = array_filter($name_bases);
			$name_bases = array_combine(array_map('sanitize_title', $name_bases), $name_bases);
		} else if($type === 'user' && !empty($name_base_users)){
			$name_bases = $name_base_users;
			$name_bases = array_filter($name_base_users);
			$name_bases = array_combine(array_map('sanitize_title', $name_bases), $name_bases);
			
		} else {
			$name_bases = array(
							'mr'		=> esc_html__('Mr.','doctreat'),
							'miss'		=> esc_html__('Miss.','doctreat'),
							'dr'		=> esc_html__('Dr.','doctreat'),
							'prof'		=> esc_html__('Prof.','doctreat')
						);
		}

		if( !empty($key) ){
			$name_bases	= !empty( $name_bases[$key] ) ? $name_bases[$key] : '';
		}
		
		return $name_bases;
    }

    add_action('doctreat_get_name_bases', 'doctreat_get_name_bases');
}

/**
 * List Patient with Releansip 
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_patient_relationship')) {

    function doctreat_patient_relationship() {
        $fields = array ( 
							'brother'		=> esc_html__('Brother','doctreat'),
							'sister'		=> esc_html__('Sister','doctreat'),
							'wife'			=> esc_html__('Wife','doctreat'),
							'mother'		=> esc_html__('Mother','doctreat'),
							'daughter'		=> esc_html__('Daughter','doctreat'),
							'son'			=> esc_html__('Son','doctreat'),
							'others'		=> esc_html__('Others','doctreat')
						);
		
		$fields	= apply_filters('doctreat_filter_patient_relationship',$fields);
		
		return $fields;
    }
	add_action('doctreat_patient_relationship', 'doctreat_patient_relationship');
}

/**
 * List Order By
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_list_order_by')) {

    function doctreat_list_order_by( ) {
		$fields = array ( 
							'ID'		=>esc_html__('ID','doctreat'),
							'title'		=>esc_html__('Name','doctreat'),
							'date'		=>esc_html__('Date','doctreat')
						);
		
		$fields	= apply_filters('doctreat_filter_list_order_by',$fields);
		
		return $fields;
    }
	
	add_action('doctreat_list_order_by', 'doctreat_list_order_by');
}

/**
 * Search get text field
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_text_field' ) ) {
	
	function doctreat_get_search_text_field(){
		global $theme_settings;
		$keyword 			= !empty( $_GET['keyword']) ? $_GET['keyword'] : '';
		$searchby			= !empty($theme_settings['search_type']) ? $theme_settings['search_type'] : '';
		$searchby			= !empty( $theme_settings['system_access'] ) ? 'doctors' : $searchby;
		ob_start();
		?>
		<input type="hidden" name="searchby" class="form-control"  value="<?php echo esc_attr( $searchby );?>">
		<input type="text" name="keyword" class="form-control" placeholder="<?php esc_attr_e('Search doctors, clinics, hospitals, etc.','doctreat');?>" value="<?php echo esc_html( $keyword );?>">
		<?php 
		echo ob_get_clean();
	}
	add_action( 'doctreat_get_search_text_field', 'doctreat_get_search_text_field');
}

/**
 * Search get locations
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_locations' ) ) {
	
	function doctreat_get_search_locations(){
		$location = !empty( $_GET['location']) ? doctreat_get_term_by_type('slug',$_GET['location'],'locations','id') : '';
		ob_start(); 
		?>
			<select name="location" class="chosen-select">
				<option value=""><?php esc_html_e('Select a location','doctreat');?></option>
				<?php
					wp_list_categories( array(
							'taxonomy' 			=> 'locations',
							'hide_empty' 		=> false,
							'selected'			=> $location,
							'style' 			=> '',
							'walker' 			=> new Doctreat_Walker_Location_Dropdown,
						)
					);
				?>
			</select>
		<?php
		echo ob_get_clean();
	}
	add_action( 'doctreat_get_search_locations', 'doctreat_get_search_locations');
}

/**
 * Search get speciality
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_speciality' ) ) {
	
	function doctreat_get_search_speciality(){
		$speciality = !empty( $_GET['specialities']) ?  doctreat_get_term_by_type('slug',$_GET['specialities'],'specialities','id') : '';
		ob_start(); 
		?>
			<select name="specialities" class="chosen-select search_specialities">
				<option value=""><?php esc_html_e('Select a speciality','doctreat');?></option>
				<?php
					wp_list_categories( array(
							'taxonomy' 			=> 'specialities',
							'hide_empty' 		=> false,
							'selected'			=> $speciality,
							'style' 			=> '',
							'walker' 			=> new Doctreat_Walker_Location_Dropdown,
						)
					);
				?>
			</select>
		<?php
		echo ob_get_clean();
	}
	add_action( 'doctreat_get_search_speciality', 'doctreat_get_search_speciality');
}

/**
 * Search get Type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_type' ) ) {
	
	function doctreat_get_search_type(){
		global $theme_settings;
		$searchby 			= !empty( $_GET['searchby']) ?  $_GET['searchby'] : '';
		$system_access		= !empty( $theme_settings['system_access'] ) ? $theme_settings['system_access'] : '';
		if( empty($searchby) ){
			$searchby	= !empty($theme_settings['search_type']) ? $theme_settings['search_type'] : '';
		}
		
		if( !empty($system_access) ) {
			$searchby	= 'doctors';
		}
		ob_start(); 
		?>
		<select name="searchby" class="chosen-select search_type">
			<option value="" ><?php esc_html_e('Select search type','doctreat');?></option>
			<option value="doctors" <?php selected( $searchby, 'doctors'); ?>><?php esc_html_e('Doctors','doctreat');?></option>
			<?php if( empty($system_access) ){ ?>
				<option value="hospitals" <?php selected( $searchby, 'hospitals'); ?>><?php esc_html_e('Hospitals','doctreat');?></option>
			<?php } ?>
		</select>
		<?php
		echo ob_get_clean();
	}
	add_action( 'doctreat_get_search_type', 'doctreat_get_search_type');
}

/**
 * Search by gender
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_gender' ) ) {
	
	function doctreat_get_search_gender(){
		global $theme_settings;
		$searchby 			= !empty( $_GET['gender']) ?  $_GET['gender'] : '';

		ob_start(); 
		?>
		<select name="gender" class="chosen-select search_specialities">
			<option value="" ><?php esc_html_e('Search by gender','doctreat');?></option>
			<option value="male" <?php selected( $searchby, 'male'); ?>><?php esc_html_e('Male','doctreat');?></option>
			<option value="female" <?php selected( $searchby, 'female'); ?>><?php esc_html_e('Female','doctreat');?></option>
			<option value="all" <?php selected( $searchby, 'all'); ?>><?php esc_html_e('All','doctreat');?></option>
		</select>
		<?php
		echo ob_get_clean();
	}
	add_action( 'doctreat_get_search_gender', 'doctreat_get_search_gender');
}

/**
 * Search get services
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_search_services' ) ) {
	
	function doctreat_get_search_services(){
		
		$get_service 		= !empty( $_GET['services']) ? $_GET['services'] : '';
		$get_specialities 	= !empty( $_GET['specialities']) ? $_GET['specialities'] : '';
		$specialities		= doctreat_get_taxonomy_array('specialities');
		$specialities_json	= array();
		$selected_services	= array();
		
		if( !empty( $specialities ) ){
			
			foreach( $specialities as $speciality ) {
				$services_array				= doctreat_list_service_by_specialities($speciality->term_id);
				$json[$speciality->slug] 	= $services_array;
				if( !empty( $get_specialities )  && $get_specialities === $speciality->slug ) {
					$selected_services	= $services_array;
				}
			}
			$specialities_json['categories'] = $json;
		}
		
		if( !empty( $specialities_json['categories']  ) ){
			ob_start(); 
			?>
				<select name="services" class="chosen-select search_services">
					<option value=""><?php esc_html_e('Select a service','doctreat');?></option>
					<?php
						if( !empty( $selected_services ) ){
							foreach( $selected_services as $service ) { 

								if( !empty( $get_service ) && $get_service === $service->slug ) {
									$selected	= 'selected="selected"';
								} else{
									$selected	= '';
								}
							?>
								<option value="<?php echo esc_attr($service->slug);?>" <?php echo do_shortcode($selected);?></optio><?php echo esc_attr($service->name);?></option>
							<?php
							}
						}
					?>
				</select>

				<script type="text/template" id="tmpl-load-services-options">
					<option value=""><?php esc_html_e('Select a service','doctreat');?></option>
					<# if( !_.isEmpty(data['options']) ) {#>
						<# _.each( data['options'] , function(element, index, attr) {#>
								<option value="{{element["slug"]}}" data-id="{{element["slug"]}}">{{element["name"]}}</option>
							<#	});
						#>
					<# } #>
				</script>
				<?php
				wp_add_inline_script('doctreat-callback', "
						var DT_Editor = {};
						DT_Editor.elements = {};
						window.DT_Editor = DT_Editor;
						DT_Editor.elements = jQuery.parseJSON( '" . addslashes(json_encode($specialities_json['categories'])) . "' );
					", 'after');
				?>
			<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_search_services', 'doctreat_get_search_services');
}

/**
 * Get User info By ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_user_info_by_ID' ) ) {
	
	function doctreat_get_user_info_by_ID( $profile_id = ''){
		if( !empty($profile_id) ){
			$profile_url	= get_the_permalink( $profile_id );
			$avatar_url 	= apply_filters(
							'doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => 65, 'height' => 65), $profile_id), array('width' => 65, 'height' => 65) 
						);
			$display_name	= doctreat_full_name($profile_id);
			$sub_heading	= doctreat_get_post_meta( $profile_id ,'am_sub_heading');
			
			ob_start(); 
			?>
			<?php if( !empty( $avatar_url ) ){?>
				<figure class="dc-consultation-img ">
					<img src="<?php echo esc_url ($avatar_url);?>" alt="<?php echo esc_attr( $display_name );?>">
				</figure>
			<?php } ?>
			<?php if( !empty( $display_name ) || !empty( $avatar_url )) {?>
				<div class="dc-consultation-title">
					<h5>
						<?php if( !empty( $display_name ) ){?><a href="<?php echo esc_url( $profile_url );?>"><?php echo esc_html( $display_name );?></a><?php } ?>
						<?php if( !empty( $sub_heading ) ){?><em><?php echo esc_html( $sub_heading );?></em><?php } ?>
					</h5>
				</div>
			<?php } ?>
			<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_user_info_by_ID', 'doctreat_get_user_info_by_ID',10,1);
}

/**
 * Get Group Services by Auther ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_group_services_with_speciality' ) ) {
	
	function doctreat_get_group_services_with_speciality( $profile_id = '',$selected ='', $return = 'echo',$type='',$doctor_id=''){
		if( !empty($profile_id) ){
			if( empty($type) ){
				$am_specialities 		= doctreat_get_post_meta( $profile_id,'am_specialities');
			} else if( !empty($type) && $type === 'location' ){
				$am_specialities 		= get_post_meta( $profile_id,'_team_services',true);
			}

			if(!empty($doctor_id)){
				$doc_specialities = get_the_terms($doctor_id,'specialities');
				
				if(!empty($doc_specialities)){
					$doc_specialities	= wp_list_pluck($doc_specialities, 'term_id');
				}
			}

			if( !empty( $am_specialities ) ) {
				ob_start(); 
				?>
				<div id="dc-accordion" class="dc-accordion dc-accordion dc-moreservice" role="tablist" aria-multiselectable="true">
					<?php 
						foreach( $am_specialities as $key => $values ){ 
							$specialities_title	= doctreat_get_term_name($key ,'specialities');
							$services			= !empty( $specialities['services'] ) ? $specialities['services'] : '';
							$logo 				= get_term_meta( $key, 'logo', true );
							$current_logo		= !empty( $logo['url'] ) ? $logo['url'] : '';
							
							if(!empty($doc_specialities) && is_array($doc_specialities) && !in_array( $key , $doc_specialities ) ){
								continue;
							}
						?>
						<div class="dc-panel">
							<div class="dc-paneltitle">
								<?php if( !empty( $current_logo ) ){?>
									<figure class="dc-titleicon">
										<img src="<?php echo esc_url( $current_logo );?>" alt="<?php echo esc_attr( $specialities_title );?>">
									</figure>
								<?php } ?>
								<?php if( !empty( $specialities_title ) ){?>
									<span><?php echo esc_html( $specialities_title );?></span>
								<?php } ?>
							</div>
							<div class="dc-panelcontent dc-moreservice-content">
								<div class="dc-subtitle">
									<h4><?php esc_html_e('Services','doctreat');?></h4>
								</div>
								<?php if( !empty( $values ) ) {?>
									<div class="dc-checkbox-holder">
										<?php foreach( $values as $index => $val ) {
												$service_title	= doctreat_get_term_name($index ,'services');
												if( !empty( $service_title ) ) {
													if( !empty( $selected ) && !empty( $selected[$key][$index] ) &&  $selected[$key][$index] == $index ) {
														$checked='checked';
													} else {
														$checked='';
													}
													
													
													if( !empty($type) && $type === 'location' ){
														$doctor_id			= get_post_field('post_author',$profile_id);
														$doctor_profile_id 	= doctreat_get_linked_profile_id($doctor_id);
														$doctor_spec 		= doctreat_get_post_meta( $doctor_profile_id,'am_specialities');
														$doctor_spec_price	= !empty($doctor_spec[$key][$index]['price']) ? $doctor_spec[$key][$index]['price'] : 0;
														$price				= $doctor_spec_price;
													} else{
														$price				= !empty($val['price']) ? $val['price'] : 0;
													}
													
												?>
												<span class="dc-checkbox">
													<input <?php echo esc_attr( $checked );?> id="dc-mo-<?php echo intval($index);?>" type="checkbox" name="service[<?php echo intval($key);?>][<?php echo intval($index);?>]" data-price-formate="<?php doctreat_price_format($price);?>" data-title="<?php echo esc_attr( $service_title );?>" data-price='<?php echo esc_attr( $price );?>' value="<?php echo intval($index);?>" class="dc-checkbox-service">
													<label for="dc-mo-<?php echo intval($index);?>"><?php echo esc_html( $service_title );?></label>
												</span>
											<?php } ?>
										<?php } ?>
									</div>
								<?php } ?>
							</div>
						</div>
					<?php }?>
				</div>
			<?php
			}
			if($return === 'return') {
				return ob_get_clean();
			} else {
				echo ob_get_clean();
			}
				
		}
	}
	add_action( 'doctreat_get_group_services_with_speciality', 'doctreat_get_group_services_with_speciality',10,5);
	add_filter( 'doctreat_get_group_services_with_speciality', 'doctreat_get_group_services_with_speciality',10,5);
}

/**
 * Get Group Services by Auther ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_group_services_by_autherID' ) ) {
	
	function doctreat_get_group_services_by_autherID( $profile_id = '' , $name ='services',$selected ='', $return = 'echo'){
		if( !empty($profile_id) && !empty( $name ) ){
			$selected_text	= '';
			$am_specialities = doctreat_get_post_meta( $profile_id,'am_specialities');
				if( !empty( $am_specialities ) ) {
					ob_start(); 
				?>
					<select data-placeholder="<?php esc_attr_e('Select Services','doctreat');?>" multiple name="<?php echo esc_attr( $name );?>"  class="chosen-select">
						<?php 
							foreach ( $am_specialities as $key => $specialities) { 
								$specialities_title	= doctreat_get_term_name($specialities['speciality_id'] ,'specialities');
								$services			= !empty( $specialities['services'] ) ? $specialities['services'] : '';
								$service_count		= count($services);
								if( !empty( $specialities_title ) ){ ?>
									<optgroup label="<?php echo esc_attr( $specialities_title );?>" >
									<?php 
										foreach ( $services as $key => $service ) { 
											$service_title	= doctreat_get_term_name($service['service'] ,'services');
											$service_title	= !empty( $service_title ) ? $service_title : '';

											if( !empty( $selected ) && in_array( $service['service'],$selected ) ){
												$selected_text = 'selected';
											} else {
												$selected_text = '';
											}
										?>
											<option <?php echo esc_attr( $selected_text );?> value="<?php echo intval( $service['service'] ); ?>"><?php echo esc_html( $service_title ); ?></option>
										<?php } ?>
									</optgroup>
								<?php
								}
							}
						?>		
					</select>
				<?php
				}
			if($return === 'return') {
				return ob_get_clean();
			} else {
				echo ob_get_clean();
			}
				
		}
	}
	add_action( 'doctreat_get_group_services_by_autherID', 'doctreat_get_group_services_by_autherID',10,4);
	add_filter( 'doctreat_get_group_services_by_autherID', 'doctreat_get_group_services_by_autherID',10,4);
}

/**
 * Get Single Doctor Location
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_doctor_location' ) ) {
	
	function doctreat_doctor_location( $post_id = ''){
		
		$width 	= 80;
		$height	= 80;

		$thumbnail      = doctreat_prepare_thumbnail($post_id, $width, $height);
		$hospital_name	= get_the_title($post_id);
		$hospital_name	= !empty( $hospital_name ) ? $hospital_name : '';
		$am_week_days	= get_post_meta( $post_id,'am_slots_data',true);
		$am_week_days	= !empty( $am_week_days ) ? array_keys($am_week_days) : array();
		$is_verified 	= get_post_meta($post_id, '_is_verified',true);
		$post_status	= get_post_status ($post_id);
		$post_status	= !empty( $post_status ) ? $post_status	: '';
		
		ob_start(); 
		
		if( !empty($post_id) ){ ?>
			<div class="dc-clinics">
			<?php if( !empty( $thumbnail )) {?>
				<div>
					<figure class="dc-clinicsimg"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo esc_attr( $hospital_name );?>"></figure>
				</div>
			<?php } ?>
			<div class="dc-clinics-content">
				<div class="dc-clinics-title">
					<?php if( !empty( $hospital_name ) ){?>
						<h4>
							<?php echo esc_html( $hospital_name );?>
							<?php if(!empty( $is_verified ) && $is_verified === 'yes' ){ ?>
								<i class="fa fa-check-circle"></i>
							<?php } ?>
						</h4>
					<?php } ?>
					<?php if( !empty( $am_week_days )) {?>
						<span>
							<?php 
								$week_total	= count($am_week_days);
								$week_start	= 1;
								foreach( $am_week_days as $day) {
									$day_name	= doctreat_get_week_keys_translation($day);
									echo esc_html( ucfirst($day_name) );
									if( $week_total != $week_start ) echo ' ,';
									$week_start ++;
								}
							?>
						</span>
					<?php } ?>
				</div>
				<div class="dc-btnarea">
					<span class="dc-team-status dc-<?php echo esc_attr( $post_status );?>"><?php echo esc_html( $post_status );?></span>
				</div>
			</div>
		</div>
	<?php
	echo ob_get_clean();
		}
	}
	add_action( 'doctreat_doctor_location', 'doctreat_doctor_location', 10, 1 );
}

/**
 * Get Hospital team
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_hospital_team_byID' ) ) {
	
	function doctreat_get_hospital_team_byID( $post_id = '', $detail = '', $user_id=''){
		
		$width 	= 80;
		$height	= 80;
				
		$hospital_id	= get_post_meta($post_id, 'hospital_id', true);
		$thumbnail      = doctreat_prepare_thumbnail($hospital_id, $width, $height);
		$hospital_name	= get_the_title($hospital_id);
		$hospital_name	= !empty( $hospital_name ) ? $hospital_name : '';
		$am_week_days	= get_post_meta( $post_id,'am_slots_data',true);
		$am_week_days	= !empty( $am_week_days ) ? array_keys($am_week_days) : array();
		$is_verified 	= get_post_meta($hospital_id, '_is_verified',true);
		$post_status	= get_post_status ($post_id);
		$post_status	= !empty( $post_status ) ? $post_status	: '';
		
		ob_start(); 
		
		if( !empty($hospital_id) && !empty( $post_id ) ){ ?>
			<div class="dc-clinics">
			<?php if( !empty( $thumbnail )) {?>
				<div>
					<figure class="dc-clinicsimg"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo esc_attr( $hospital_name );?>"></figure>
				</div>
			<?php } ?>
			<div class="dc-clinics-content">
				<div class="dc-clinics-title">
					<a href="javascript:;"><?php esc_html_e('Hospital','doctreat');?></a>
					<?php if( !empty( $hospital_name ) ){?>
						<h4>
							<?php echo esc_html( $hospital_name );?>
							<?php if(!empty( $is_verified ) && $is_verified === 'yes' ){ ?>
								<i class="fa fa-check-circle"></i>
							<?php } ?>
						</h4>
					<?php } ?>
					<?php if( !empty( $am_week_days )) {?>
						<span>
							<?php 
								$week_total	= count($am_week_days);
								$week_start	= 1;
								foreach( $am_week_days as $day) {
									$day_name	= doctreat_get_week_keys_translation($day);
									echo esc_html( ucfirst($day_name) );
									if( $week_total != $week_start ) echo ' ,';
									$week_start ++;
								}
							?>
						</span>
					<?php } ?>
				</div>
				<div class="dc-btnarea">
					<span class="dc-team-status dc-<?php echo esc_attr( $post_status );?>"><?php echo esc_html( $post_status );?></span>
					<?php if( !empty( $detail ) ){?>
						<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('appointment', $user_id,'','details',$post_id); ?>" class="dc-btn dc-btn-sm">
							<?php esc_html_e('View Details','doctreat');?>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php
	echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_hospital_team_byID', 'doctreat_get_hospital_team_byID', 10, 3 );
}

/**
 * Get doctor info by team ID
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_doctor_info_by_teamID' ) ) {
	
	function doctreat_get_doctor_info_by_teamID( $post_id = '', $user_id=''){
		
		$width 	= intval(80);
		$height	= intval(80);
			
		$doctor_id 		= get_post_field( 'post_author', $post_id );
		$linked_doc_id 	= doctreat_get_linked_profile_id($doctor_id);
		$thumbnail      = doctreat_prepare_thumbnail($linked_doc_id, $width, $height);
		$doctor_name	= get_the_title($linked_doc_id);
		$doctor_name	= !empty( $doctor_name ) ? $doctor_name : '';
		$am_week_days	= get_post_meta( $post_id, 'am_slots_data', true);
		$am_week_days	= !empty( $am_week_days ) ? array_keys($am_week_days) : array();
		$is_verified 	= get_post_meta($linked_doc_id, '_is_verified',true);
		$post_status	= get_post_status ($post_id);
		$post_status	= !empty( $post_status ) ? $post_status	: '';
		
		ob_start(); 
		
		if( !empty($linked_doc_id) && !empty( $post_id ) ){ ?>
			<div class="dc-clinics">
			<?php if( !empty( $thumbnail )) {?>
				<div>
					<figure class="dc-clinicsimg"><img src="<?php echo esc_url($thumbnail);?>" alt="<?php echo esc_attr( $doctor_name );?>"></figure>
				</div>
			<?php } ?>
			<div class="dc-clinics-content">
				<div class="dc-clinics-title">
					<a href="javascript:;"><?php esc_html_e('Doctor', 'doctreat');?></a>
					<?php if( !empty( $doctor_name ) ){?>
						<h4>
							<?php echo esc_html( $doctor_name );?>
							<?php if(!empty( $is_verified ) && $is_verified === 'yes' ){ ?>
								<i class="fa fa-check-circle"></i>
							<?php } ?>
						</h4>
					<?php } ?>
					<?php if( !empty( $am_week_days )) {?>
						<span>
							<?php 
								$week_total	= count($am_week_days);
								$week_start	= 1;
								foreach( $am_week_days as $day) {
									echo esc_html( ucfirst($day) );
									if( $week_total != $week_start ) echo ' ,';
									$week_start ++;
								}
							?>
						</span>
					<?php } ?>
				</div>
				<div class="dc-btnarea">
					<span class="dc-team-status dc-<?php echo esc_attr( $post_status );?>"><?php echo esc_html( $post_status );?></span>
				</div>
			</div>
		</div>
	<?php
	echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_doctor_info_by_teamID', 'doctreat_get_doctor_info_by_teamID', 10, 3 );
}

/**
 * Boooking status
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_booking_statuses' ) ) {
    function doctreat_booking_statuses(){
        $list = array(
			'cancelled' 	=> esc_html__('Cancelled','doctreat'),
        );

        $list = apply_filters('doctreat_filters_booking_statuses', $list);         
        return $list;
    }
    add_filter('doctreat_booking_statuses', 'doctreat_booking_statuses', 10, 1);
}

/**
 * Register statuses
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists( 'doctreat_register_booking_statuses' ) ) {
	function doctreat_register_booking_statuses(){
		$statuses	= apply_filters('doctreat_booking_statuses','default');
		foreach( $statuses as $key => $val ){
			
			if( $key ==='completed' ) {
				$public	= true;
			} else {
				$public	= false;
			}
			
			register_post_status($key, array(
				'label'                     => $val,
				'public'                    => $public,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
			) );
		}

	}
	add_action( 'init', 'doctreat_register_booking_statuses' );
}

/**
 * @get Breadcrumbs
 * @return 
 */
if (!function_exists('doctreat_breadcrumbs')) {
	add_action('doctreat_breadcrumbs', 'doctreat_breadcrumbs',10,3);
	function doctreat_breadcrumbs($bg,$text,$title){
		global $post,$wp_query;
		$breadcrumClass		= 'dc-breadcrumb';
		$separator			= '';
		$home_title			= esc_html__('Home', 'doctreat');;
		$cus_texnomies		= 'specialities';
		
		echo '<div class="dc-breadcrumbarea" style="background:'.esc_attr( $bg ).'">';
		echo '<div class="container">';
		echo '<div class="row">';
		echo '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
		echo '<ol class="'.$breadcrumClass.'">';
		
		if ( is_front_page() ) {
			echo '<li class="dc-item-home">' . esc_html( $title ) . '</li>';
		} else{
			
			echo '<li class="dc-item-home"><a href="' . esc_url( get_home_url() ) . '" title="' . esc_attr($home_title) . '">' . esc_html( $home_title ) . '</a></li>';
			
			if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
				echo '<li class="dc-item-current dc-item-archive"><strong class="dc-bread-current dc-bread-archive">'.esc_html__('Archives','doctreat'). '</strong></li>';
			} else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
				$post_type = get_post_type();
				if(!empty( $post_type ) && $post_type != 'post') {

					$post_type_object 	= get_post_type_object($post_type);
					$post_type_archive 	= get_post_type_archive_link($post_type);
					if( $post_type === 'doctors' || $post_type === 'hospitals' ) {
						$post_type_archive		= doctreat_get_search_page('search_result_page').'?searchby='.$post_type;
						
					} 
					
					echo '<li class="dc-item-cat dc-item-custom-post-type-' . esc_attr( $post_type ) . '"><a class="dc-bread-cat dc-bread-custom-post-type-' . esc_attr( $post_type ) . '" href="' . esc_url( $post_type_archive ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . esc_html( $post_type_object->labels->name ). '</a></li>';
				}

				$custom_tax_name = get_queried_object()->name;
				echo '<li class="dc-item-current dc-item-archive"><strong class="dc-bread-current dc-bread-archive">' . esc_html( $custom_tax_name ) . '</strong></li>';

			} else if ( is_single() ) {
				$post_type = get_post_type();
				if($post_type != 'post') {
					$post_type_object 	= get_post_type_object($post_type);
					$post_type_archive 	= get_post_type_archive_link($post_type);
					
					if( $post_type === 'doctors' || $post_type === 'hospitals' ) {
						$post_type_archive		= doctreat_get_search_page('search_result_page').'?searchby='.$post_type;
						
					} else if( $post_type === 'healthforum' ){
						$pages = get_pages(array(
							'meta_key' => '_wp_page_template',
							'meta_value' => 'directory/healthforum.php'
						));
						$post_type_archive	= !empty($pages[0]->ID) ? get_page_link($pages[0]->ID) : '';
					}
					
					echo '<li class="dc-item-cat dc-item-custom-post-type-
					' . esc_attr( $post_type ). '"><a class="dc-bread-cat dc-bread-custom-post-type-' . esc_attr( $post_type ) . '" href="' . esc_url( $post_type_archive ) . '" title="' . esc_attr( $post_type_object->labels->name ). '">' . esc_html( $post_type_object->labels->name ) . '</a></li>';
				}

				// Get post category info
				$category = get_the_category();
				if( !empty($category) ) {
					$l_cat			= array_values( $category );
					$last_category 	= end( $l_cat );
					$get_cat_parents = rtrim( get_category_parents($last_category->term_id, true, ','),',' );
					$cat_parents 	= explode(',',$get_cat_parents);
					$cat_display = '';
					foreach($cat_parents as $parents) {
						$cat_display .= '<li class="dc-item-cat">'.do_shortcode( $parents ).'</li>';
					}
				}

				// Check if the post is in a category
				if(!empty($last_category)) {
					echo '<li class="dc-item-current item-' .intval( $post->ID ). '"><span class="dc-bread-current bread-' . intval( $post->ID ) . '" title="' . esc_attr( get_the_title() ). '">' . do_shortcode( get_the_title() ) . '</span></li>';

				} else {
					echo '<li class="dc-item-current item-' . intval( $post->ID ) . '"><span class="dc-bread-current bread-' . intval( $post->ID ) . '" title="' . esc_attr ( get_the_title() ) . '">' . do_shortcode( get_the_title() ) . '</span></li>';
				}

			} else if ( is_category() ) {
				echo '<li class="dc-item-current dc-item-cat"><span class="dc-bread-current bread-cat">' . do_shortcode( single_cat_title('', false) ) . '</span></li>';
			} else if ( is_page() ) {
				if( !empty( $post->post_parent ) ){
					$anc = get_post_ancestors( $post->ID );
					$anc = array_reverse($anc);

					// Parent page loop
					if ( !isset( $parents ) ) $parents = null;
					foreach ( $anc as $ancestor ) {
						$parents .= '<li class="dc-item-parent dc-item-parent-' . esc_attr( $ancestor ) . '"><a class="dc-bread-parent bread-parent-' . esc_attr( $ancestor ) . '" href="' . esc_url( get_permalink($ancestor) ). '" title="' . esc_attr( get_the_title($ancestor) ). '">' . do_shortcode( get_the_title($ancestor) ) . '</a></li>';
						$parents .= '<li class="dc-separator dc-separator-' . esc_attr( $ancestor ) . '"> ' . esc_html( $separator ) . ' </li>';
					}

					// Display parent pages
					echo do_shortcode( $parents );

					echo '<li class="dc-item-current dc-item-' .intval( $post->ID ) . '"><span title="' . esc_attr( get_the_title() ). '"> ' . do_shortcode( get_the_title() ). '</span></li>';
				} else {
					echo '<li class="dc-item-current dc-item-' . intval( $post->ID ) . '"><span class="dc-bread-current bread-' . intval( $post->ID ). '"> ' . do_shortcode( get_the_title() ) . '</span></li>';
				}

			} elseif ( is_day() ) {
				// Year link
				echo '<li class="dc-item-year item-year-' . esc_attr( get_the_time('Y') ) . '"><a class="dc-bread-year bread-year-' . get_the_time('Y') . '" href="' .esc_url( get_year_link( get_the_time('Y') ) ) . '" title="' . esc_attr( get_the_time('Y') ). '">' . esc_html( get_the_time('Y') ) .' &npbs'.esc_html__('Archives','doctreat'). ' </a></li>';

				// Month link
				echo '<li class="dc-item-month item-month-' . esc_attr( get_the_time('m') ) . '"><a class="dc-bread-month bread-month-' . esc_attr ( get_the_time('m') ). '" href="' . esc_url( get_month_link( get_the_time('Y'), get_the_time('m') ) ). '" title="' .esc_attr( get_the_time('M') ). '">' . esc_html( get_the_time('M') ) . ' '.esc_html__('Archives','doctreat').' </a></li>';

				// Day display
				echo '<li class="dc-item-current item-' . esc_attr( get_the_time('j') ) . '"><span class="dc-bread-current bread-' . esc_attr( get_the_time('j') ) . '"> ' . esc_html( get_the_time('jS') ). ' ' . esc_html( get_the_time('M') ) .esc_html__('Archives','doctreat').'</span></li>';

			} else if ( is_tag() ) {
				$tag_id	= get_query_var('tag_id');
				if( empty( $tag_id ) ){return;}
				$term_tax	= 'post_tag';
				$term_query	= 'include='.$tag_id;
				$get_term	= get_terms($term_tax,$term_query); 

				if( !empty( $get_term[0]->name ) ){
					echo '<li><span class="dc-bread-current">'.esc_html($get_term[0]->name).'</span></li>';
				}

			} else if ( is_month() ) {
				// Year link
				echo '<li class="dc-item-year item-year-' . esc_attr( get_the_time('Y') ) . '"><a class="dc-bread-year bread-year-' . esc_attr( get_the_time('Y') ) . '" href="' . esc_url( get_year_link( get_the_time('Y') ) ) . '" title="' . esc_attr( get_the_time('Y') ) . '">' . esc_html( get_the_time('Y') ).' '.esc_html__('Archives','doctreat'). '</a></li>';
				
				// Month display
				echo '<li class="dc-item-month item-month-' . esc_attr( get_the_time('m') ) . '"><span class="dc-bread-month bread-month-' . esc_attr( get_the_time('m') ) . '" title="' . esc_attr( get_the_time('M') ) . '">' . esc_html( get_the_time('M') ).' '.esc_html__('Archives','doctreat').  '</span></li>';

			} else if ( is_year() ) {
				echo '<li class="dc-item-current item-current-' . esc_attr( get_the_time('Y') ). '"><span class="dc-bread-current bread-current-' . esc_attr( get_the_time('Y') ). '" title="' . esc_attr( get_the_time('Y') ) . '">' . esc_html( get_the_time('Y') ).' '.esc_html__('Archives','doctreat'). '</span></li>';

			} else if ( is_author() ) {
				// Get the author information
				global $author;
				$userdata = get_userdata( $author );

				echo '<li class="dc-item-current item-current-' . esc_attr( $userdata->user_nicename ) . '"><span class="dc-bread-current bread-current-' . esc_attr( $userdata->user_nicename ) . '" title="' . esc_attr( $userdata->display_name ) . '">' . esc_html__('Author: ','doctreat') . esc_html( $userdata->display_name ). '</span></li>';
			} else if ( get_query_var('paged') ) {
				echo '<li class="dc-item-current item-current-' . esc_attr( get_query_var('paged') ). '"><span class="dc-bread-current bread-current-' . esc_attr( get_query_var('paged') ) . '" title="' .esc_attr__('Page ','doctreat').esc_attr( get_query_var('paged') ). '">'.esc_html__('Page','doctreat') . ' ' . esc_html( get_query_var('paged') ). '</span></li>';
			} else if ( is_search() ) {
				echo '<li class="dc-item-current item-current-' . esc_attr( get_search_query() ) . '"><span class="dc-bread-current bread-current-' . esc_attr( get_search_query() ) . '" title="'.esc_attr__('Search results for:','doctreat') . esc_attr( get_search_query() ) . '">'.esc_html( get_search_query() ). '</span></li>';
			} elseif ( is_404() ) {
				echo '<li>' . esc_html__('Error 404','doctreat') . '</li>';
			}
		}
		
		echo '</ol>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
}

/**
 * Get offline payment method array
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('doctreat_get_offline_method_array')) {

    function doctreat_get_offline_method_array() {

		$list 	= array('cod','bacs','cheque');
		$list	= apply_filters('doctreat_filter_offline_method_array',$list);
		return $list;

    }

    add_action('doctreat_get_offline_method_array', 'doctreat_get_offline_method_array');
}

/**
 * Check bookig setting enable
 * Get texnomy list
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_texnomy_select' ) ) {
	
	function doctreat_get_texnomy_select( $texnomy_name = '', $name='',$defult_text='',$values ='',$id="",$class=''){
		if( !empty($texnomy_name) ){
			$id			= !empty($id) ? 'id="'.$id.'"' : '';
			$class		= !empty($class) ? ' class="'.$class.'"'  : '';
			$terms		= get_terms($texnomy_name, array('orderby' => 'slug', 'hide_empty' => false));
			ob_start(); 
			?>
			<span class="dc-select">
				<select name="<?php echo esc_attr($name);?>" <?php echo do_shortcode($id);?> <?php echo do_shortcode($class);?>>
					<option value=""><?php echo esc_html($defult_text);?></option>
					<?php
						if( !empty($terms) ){
							foreach($terms as $term ){
								$selected = '';
								if (!empty($values) && is_array($values) && in_array($term->term_id, $values)) {
									$selected = 'selected';
								} else if (!empty($values) && !is_array($values) && $term->term_id == $values) {
									$selected = 'selected';
								}
								?>
								<option value="<?php echo intval($term->term_id);?>" <?php echo esc_attr($selected);?>><?php echo esc_html($term->name);?></option>
							<?php
							}
						}
					?>
				</select>
			</span>
			<?php
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_texnomy_select', 'doctreat_get_texnomy_select',10,6);
}

/**
 * Get texnomy checkbox
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if (!function_exists('doctreat_doctor_appointment_settings')) {
    add_filter('doctreat_doctor_appointment_settings', 'doctreat_doctor_appointment_settings', 10, 1);

    function doctreat_doctor_appointment_settings($post_id) {   
		$doctor_location	= false;
		$system_access		= doctreat_theme_option('system_access');  
		if( !empty($system_access) ){
			$doctor_location	= get_post_meta( $post_id, '_doctor_location', true );
			$doctor_location	= !empty($doctor_location) ? true : false;
		} else {
			$post_author	= doctreat_get_linked_profile_id($post_id,'post');
			$post_author	= !empty($post_author) ? $post_author : 0;
			$doctor_location	= count_user_posts($post_author,'hospitals_team');
			$doctor_location	= !empty($doctor_location) ? true : false;
		}
		
        return $doctor_location;
    }

}

/**
 * Get texnomy radio
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
if( !function_exists(  'doctreat_get_texnomy_checkbox' ) ) {
	
	function doctreat_get_texnomy_checkbox( $texnomy_name = '', $name='',$values =array(),$ids=array()){
		if( !empty($texnomy_name) ){
			$arg_array	=  array('orderby' => 'slug', 'hide_empty' => false);
			if( !empty($ids) ){
				$arg_array['include']	= $ids;
			}
			$terms	= get_terms($texnomy_name,$arg_array);
			ob_start(); 
			if( !empty($terms) ){ ?>
				<div class="dc-checkbox-holder">
					<?php
						
						foreach($terms as $term ){ 
							$checked = '';
							if (!empty($values) && is_array($values) && in_array($term->term_id, $values)) {
								$checked = 'checked';
							} else if (!empty($values) && !is_array($values) && $term->term_id == $values) {
								$checked = 'checked';
							}
							
							?>
							<span class="dc-checkbox">
								<input id="dc-mo-<?php echo esc_attr($term->term_id);?>" type="checkbox" name="<?php echo esc_attr($name);?>" value="<?php echo esc_attr($term->term_id);?>" <?php echo esc_attr($checked);?>>
								<label for="dc-mo-<?php echo esc_attr($term->term_id);?>"><?php echo esc_html($term->name);?></label>
							</span>
					<?php } ?>
				</div>
			<?php }
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_texnomy_checkbox', 'doctreat_get_texnomy_checkbox',10,4);
}

/**
 * Get texnomy radio
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */

if( !function_exists(  'doctreat_get_payouts_lists' ) ) {
	function doctreat_get_payouts_lists(){
		global $theme_settings;
		$payout_setting			= !empty($theme_settings['payout_setting']) ? $theme_settings['payout_setting'] : array();

		$list	= array(
					'paypal' => array(
						'id'		=> 'paypal',
						'title'		=> esc_html__('Paypal', 'doctreat'),
						'img_url'	=> esc_url(get_template_directory_uri().'/images/payouts/paypal.png'),
						'status'	=> 'enable',
						'desc'		=> wp_kses( __( 'You need to add your PayPal ID below in the text field. For more about <a target="_blank" href="https://www.paypal.com/"> PayPal </a> | <a target="_blank" href="https://www.paypal.com/signup/">Create an account</a>', 'doctreat' ),array(
													'a' => array(
														'href' => array(),
														'target' => array(),
														'title' => array()
													),
													'br' => array(),
													'em' => array(),
													'strong' => array(),
												)),
						'fields'	=> array(
							'paypal_email' => array(
								'type'			=> 'text',
								'classes'		=> '',
								'required'		=> true,
								'placeholder'	=> esc_html__('Add PayPal Email Address','doctreat'),
								'message'	=> esc_html__('PayPal Email Address is required','doctreat'),
							)
						)
					),
				'bacs' => array(
					'id'		=> 'bacs',
					'title'		=> esc_html__('Direct Bank Transfer (BACS)', 'doctreat'),
					'img_url'	=> esc_url(get_template_directory_uri().'/images/payouts/bank.png'),
					'status'	=> 'enable',
					'desc'		=> wp_kses( __( 'Please add all required settings for the bank transfer.', 'doctreat' ),array(
												'a' => array(
													'href' => array(),
													'target' => array(),
													'title' => array()
												),
												'br' => array(),
												'em' => array(),
												'strong' => array(),
											)),
					'fields'	=> array(
						'bank_account_name' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> true,
							'placeholder'	=> esc_html__('Bank Account Name','doctreat'),
							'message'		=> esc_html__('Bank Account Name is required','doctreat'),
						),
						'bank_account_number' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> true,
							'placeholder'	=> esc_html__('Bank Account Number','doctreat'),
							'message'		=> esc_html__('Bank Account Number is required','doctreat'),
						),
						'bank_name' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> true,
							'placeholder'	=> esc_html__('Bank Name','doctreat'),
							'message'		=> esc_html__('Bank Name is required','doctreat'),
						),
						'bank_routing_number' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> false,
							'placeholder'	=> esc_html__('Bank Routing Number','doctreat'),
							'message'		=> esc_html__('Bank Routing Number is required','doctreat'),
						),
						'bank_iban' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> false,
							'placeholder'	=> esc_html__('Bank IBAN','doctreat'),
							'message'		=> esc_html__('Bank IBAN is required','doctreat'),
						),
						'bank_bic_swift' => array(
							'type'			=> 'text',
							'classes'		=> '',
							'required'		=> false,
							'placeholder'	=> esc_html__('Bank BIC/SWIFT','doctreat'),
							'message'		=> esc_html__('Bank BIC/SWIFT is required','doctreat'),
						)
					)
				),
			);
		
		$all_methods	= $list;
		
		if(!empty($payout_setting)) {
			$array_list	= array();
			foreach($payout_setting as $item){
				$array_list[$item]	= !empty($list[$item]) ? $list[$item] : array();
			}
			$list	= $array_list;
			
		} else {
			unset($list['bacs']);
		}
		
		$list	= apply_filters('doctreat_filter_payouts_lists',$list);
		return $list;
	}
}
if( !function_exists(  'doctreat_get_texnomy_radio' ) ) {
	
	function doctreat_get_texnomy_radio( $texnomy_name = '', $name='',$value =''){
		if( !empty($texnomy_name) ){
			$terms	= get_terms($texnomy_name, array('orderby' => 'slug', 'hide_empty' => false));
			ob_start(); 
			if( !empty($terms) ){ ?>
				<div class="dc-radio-holder">
					<?php
						foreach($terms as $terms ){
							$checked	= !empty($value) && $value == $terms->term_id ? 'checked' : '';
							?>
							<span class="dc-radio">
								<input id="dc-mo-<?php echo esc_attr($terms->term_id);?>" type="radio" name="<?php echo esc_attr($name);?>" value="<?php echo esc_attr($terms->term_id);?>" <?php echo esc_attr($checked);?>>
								<label for="dc-mo-<?php echo esc_attr($terms->term_id);?>"><?php echo esc_html($terms->name);?></label>
							</span>
					<?php } ?>
				</div>
			<?php }
			echo ob_get_clean();
		}
	}
	add_action( 'doctreat_get_texnomy_radio', 'doctreat_get_texnomy_radio',10,3);
}

