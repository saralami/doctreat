<?php
/**
 *
 * Doctreat function for menu
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://themeforest.net/user/amentotech/portfoliot
 * @since 1.0
 */

if (!class_exists('Doctreat_Profile_Menu')) {

    class Doctreat_Profile_Menu {

        protected static $instance = null;

        public function __construct() {
            //Do something
        }

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function getInstance() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

		/**
		 * Profile Menu top
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_profile_menu_top() {
            global $current_user, $wp_roles, $userdata, $post;
			$user_identity 	= $current_user->ID;
			$link_id		= doctreat_get_linked_profile_id( $user_identity );
			$post_type		= !empty($link_id) ? get_post_type($link_id) : '';
			
			if( !empty( $user_identity ) && !empty($post_type) && ( $post_type === 'hospitals' || $post_type === 'doctors' || $post_type === 'regular_users') ){
            	ob_start();
				$username 		= doctreat_get_username($current_user->ID);

				
				$display_name	= get_the_title( $link_id );
				$post_meta		= get_post_meta($link_id, 'am_' . $post_type . '_data',true);
				$user_meta		= !empty( $post_meta ) ? $post_meta : array();
				$tag_line		= !empty( $user_meta['am_sub_heading'] ) ? $user_meta['am_sub_heading'] : '';
				$avatar	= '';
				if(!empty($post_type) && $post_type === 'hospitals' ){
					$avatar 		= apply_filters('doctreat_hospitals_avatar_fallback', doctreat_get_hospital_avatar(array('width' => 42, 'height' => 42), $link_id), array('width' => 42, 'height' => 42) 
										);
				} else if(!empty($post_type) && $post_type === 'doctors' ){
					$avatar 		= apply_filters('doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => 42, 'height' => 42), $link_id), array('width' => 42, 'height' => 42) 
										);
				} else {
					$avatar 		= apply_filters('doctreat_doctor_avatar_fallback', doctreat_get_others_avatar(array('width' => 42, 'height' => 42), $link_id), array('width' => 42, 'height' => 42) 
										);
				}
				?>
				<div class="dc-userlogin dc-userlogedin sp-top-menu">
					<figure class="dc-userimg">
						<img src="<?php echo esc_url($avatar); ?>" alt="<?php esc_attr_e('Profile Avatar', 'doctreat'); ?>">
					</figure>
					<div class="dc-username">
						<?php if( !empty( $display_name ) ) {?><h4><?php echo esc_html( $display_name ); ?></h4><?php } ?>
						<?php if( !empty( $tag_line ) ){?>
							<span><?php echo esc_html( $tag_line ); ?></span>
						<?php }?>
					</div>
					<nav class="dc-usernav">
						<?php self::doctreat_profile_menu('dashboard-menu-top'); ?>
					</nav>
				</div>
				<?php
            	echo ob_get_clean();
			}
        }

		/**
		 * Profile Menu Left
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_profile_menu_left() {
            global $current_user, $wp_roles, $userdata,$theme_settings, $post;
			$footer_copyright	= !empty( $theme_settings['copyright'] ) ? $theme_settings['copyright'] : esc_html__('Copyright','doctreat').' &copy; ' . date('Y') . '&nbsp;' . esc_html__('Doctreat. All rights reserved.', 'doctreat').get_bloginfo();
            ob_start();
            ?>
            <div id="dc-sidebarwrapper" class="dc-sidebarwrapper">
				<div id="dc-btnmenutoggle" class="dc-btnmenutoggle">
					<i class="ti-arrow-right"></i>
				</div>
				<div id="dc-verticalscrollbar" class="dc-verticalscrollbar">
					<?php self::doctreat_do_process_userinfo(); ?>
					<nav id="dc-navdashboard" class="dc-navdashboard">
						<?php self::doctreat_profile_menu('dashboard-menu-left'); ?>
					</nav>
					<?php if( !empty( $footer_copyright ) ){ ?>
						<div class="dc-navdashboard-footer">
							<span><?php echo do_shortcode( $footer_copyright );?></span>
						</div>
					<?php } ?>
				</div>
			</div>
            <?php
            echo ob_get_clean();
        }

		/**
		 * Profile Menu
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_profile_menu($menu_type = "dashboard-menu-left") {
            global $current_user, $wp_roles, $userdata, $post;
			$reference 		 = (isset($_GET['ref']) && $_GET['ref'] <> '') ? $_GET['ref'] : '';
			$mode 			 = (isset($_GET['mode']) && $_GET['mode'] <> '') ? $_GET['mode'] : '';
			$user_identity 	 = $current_user->ID;

			$url_identity = $user_identity;
			if (isset($_GET['identity']) && !empty($_GET['identity'])) {
				$url_identity = $_GET['identity'];
			}

			$menu_list 	= doctreat_get_dashboard_menu();
            ob_start();
            ?>
            <ul class="<?php echo esc_attr($menu_type); ?>">
                <?php
					if ( $url_identity == $user_identity ) {
						if( !empty( $menu_list ) ){
							foreach($menu_list as $key => $value){
								if( !empty( $value['type'] ) && ( $value['type'] == apply_filters('doctreat_get_user_type', $user_identity ) ) ){
									get_template_part('directory/front-end/dashboard-menu-templates/'.$value['type'].'/profile-menu', $key);
								} else{
									get_template_part('directory/front-end/dashboard-menu-templates/profile-menu', $key);
								}
							}
						}
					} 
                ?>
            </ul>
            <?php
            echo ob_get_clean();
        }

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_profile_menu_link($slug = '', $user_identity = '', $return = false, $mode = '', $id = '') {
			$profile_page = ''; 
			$profile_page = doctreat_get_search_page_uri('dashboard');  
			
            if ( empty( $profile_page ) ) {
                $permalink = home_url('/');
            } else {
                $query_arg['ref'] = urlencode($slug);

                //mode
                if (!empty($mode)) {
                    $query_arg['mode'] = urlencode($mode);
                }
				
                //id for edit record
                if (!empty($id)) {
                    $query_arg['id'] = urlencode($id);
                }

                $query_arg['identity'] = urlencode($user_identity);

                $permalink = add_query_arg(
                        $query_arg, esc_url( $profile_page  )
                );
				
            }

            if ($return) {
                return esc_url_raw($permalink);
            } else {
                echo esc_url_raw($permalink);
            }
        }

		/**
		 * Generate Profile Avatar Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_get_avatar() {
            global $current_user, $wp_roles, $userdata, $post;
            $user_identity 	= $current_user->ID;
			$link_id		= doctreat_get_linked_profile_id( $user_identity );
			$post_type		= !empty($link_id) ? get_post_type($link_id) : '';
			
			if(!empty($post_type) && $post_type === 'hospitals' ){
				$avatar 		= apply_filters('doctreat_hospitals_avatar_fallback', doctreat_get_hospital_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
									);
			} else if(!empty($post_type) && $post_type === 'doctors' ){
				$avatar 		= apply_filters('doctreat_doctor_avatar_fallback', doctreat_get_doctor_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
									);
			} else {
				$avatar 		= apply_filters('doctreat_doctor_avatar_fallback', doctreat_get_others_avatar(array('width' => 100, 'height' => 100), $link_id), array('width' => 100, 'height' => 100) 
									);
			}
			
            ?>
            <figure><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_attr_e('avatar', 'doctreat'); ?>"></figure>
            <?php
        }

		/**
		 * Generate Profile Banner Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_get_banner() {
			global $theme_settings;
			$avatar 		= !empty( $theme_settings['default_doctor_banner']['url'] ) ? $theme_settings['default_doctor_banner']['url'] : get_template_directory_uri().'/images/drbanner-270x150.jpg';
			
            ?>
            <figure class="dc-companysimg"><img src="<?php echo esc_url( $avatar );?>" alt="<?php esc_attr_e('avatar', 'doctreat'); ?>"></figure>
            
            <?php
        }
		
		/**
		 * Generate Profile Information
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_get_user_info() {
            global $current_user;
            $user_identity = $current_user->ID;
            $user_identity = $user_identity;
            if (isset($_GET['identity']) && !empty($_GET['identity'])) {
                $user_identity = $_GET['identity'];
            }
			
			$user_type		= doctreat_get_user_type( $user_identity );
			$link_id		= doctreat_get_linked_profile_id( $user_identity );
            $get_username 	= doctreat_get_username($user_identity);
			$post_slug		= doctreat_get_slug( $link_id );
			$display_name	= get_the_title( $link_id );
			$profile_link	= !empty($user_type) && $user_type === 'doctors' ? get_the_permalink($link_id) : '#';
            ?>
            <div class="dc-title">
				<?php if (!empty($display_name)) { ?><h2><a href="<?php echo esc_url( $profile_link );?>"><?php echo esc_html($display_name); ?></a></h2><?php } ?>
				<?php if (!empty($post_slug)) { ?>
					<input type="hidden" id="dc-profile-url" value="<?php echo esc_url( $profile_link );?>">
					<span>@<?php echo esc_html($post_slug); ?><i id="dc-clone" class="fa fa-clone"></i></span>
				<?php } ?>
			</div>
            <?php
        }
		
		/**
		 * Get user info
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return 
		 */
        public static function doctreat_do_process_userinfo() {?>
            <div class="dc-companysdetails dc-usersidebar">
				<?php self::doctreat_get_banner(); ?>
				<div class="dc-companysinfo">
					<?php self::doctreat_get_avatar(); ?>
					<?php self::doctreat_get_user_info(); ?>
				</div>
			</div>
            <?php
        }

    }

    new Doctreat_Profile_Menu();
}
