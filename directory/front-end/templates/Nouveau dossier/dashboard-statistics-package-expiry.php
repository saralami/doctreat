<?php
/**
 *
 * The template part for displaying the dashboard statistics
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user,$theme_settings;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$icon				= 'lnr lnr-hourglass';
$expiry_string			= doctreat_get_subscription_metadata( 'subscription_package_string',intval( $user_identity ) );
$package_expiry_img		= !empty( $theme_settings['package_expiry']['url'] ) ? $theme_settings['package_expiry']['url'] : '';
$formatted_date			= ''; 

if( $expiry_string != false ){
	$formatted_date = date("Y, n, d, H, i, s", strtotime("-1 month",intval($expiry_string))); 
}
?>
<div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-3">
	<div class="dc-insightsitem dc-dashboardbox">
		<ul class="dc-countersoon" data-date="<?php echo esc_attr($formatted_date);?>">
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('d', 'doctreat'); ?></p> <span class="days" data-days></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('h', 'doctreat'); ?></p> <span class="hours" data-hours></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('m', 'doctreat'); ?></p> <span class="minutes" data-minutes></span>
				</div>
			</li>
			<li>
				<div class="dc-countdowncontent">
					<p><?php esc_html_e('s', 'doctreat'); ?></p> <span class="seconds" data-seconds></span>
				</div>
			</li>
		</ul>
		<figure class="dc-userlistingimg">
			<?php if( !empty($package_expiry_img) ) {?>
				<img src="<?php echo esc_url($package_expiry_img);?>" alt="<?php esc_attr_e('Pakckage expiry', 'doctreat'); ?>">
			<?php } else {?>
					<span class="<?php echo esc_attr($icon);?>"></span>
			<?php }?>
		</figure>
		<div class="dc-insightdetails">
			<div class="dc-title">
				<h3><?php esc_html_e('Check Package Detail', 'doctreat'); ?></h3>
				<a href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('package', $user_identity); ?>"><?php esc_html_e('Upgrade Now', 'doctreat'); ?></a>
				<?php if( !empty( $expiry_string ) ) {?>
						| <a data-toggle="modal" data-target="#dc-package-details" href="javascript:;"><?php esc_html_e('View More', 'doctreat'); ?></a>
				<?php } ?>
			</div>													
		</div>	
	</div>
</div>	
<?php
	$script = "
            (function($) {
                var launch = new Date(".esc_js($formatted_date).");
                var days 	= jQuery('.days');
                var hours 	= jQuery('.hours');
                var minutes = jQuery('.minutes');
                var seconds = jQuery('.seconds');
                setDate();
                function setDate(){
                    var now = new Date();
                    if( launch < now ){
                        days.html('0');
                        hours.html('0');
                        minutes.html('0');
                        seconds.html('0');
                    }
                    else{
                        var s = -now.getTimezoneOffset()*60 + (launch.getTime() - now.getTime())/1000;
                        var d = Math.floor(s/86400);
                        days.html(d);
                        s -= d*86400;
                        var h = Math.floor(s/3600);
                        hours.html(h);
                        s -= h*3600;
                        var m = Math.floor(s/60);
                        minutes.html(m);
                        s = Math.floor(s-m*60);
                        seconds.html(s);
                        setTimeout(setDate, 1000);
                    }
                }
            })(jQuery);
        ";
    wp_add_inline_script('doctreat-callback', $script, 'after');