<?php 
/**
 *
 * The template part for displaying the user profile avatar
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user, $theme_settings;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$location_id	= get_post_meta( $post_id, '_doctor_location', true );
$location_id	= !empty($location_id) ? $location_id : 0;

if ( 'publish' !== get_post_status ( $location_id ) ) {
	$location_id = '';
}

if( !empty($location_id) && has_post_thumbnail($location_id) ){
	$attachment_id 			= get_post_thumbnail_id($location_id);
	$image_url 				= !empty( $attachment_id ) ? wp_get_attachment_image_src( $attachment_id, 'doctreat_doctors_type', true ) : '';
	$file_size 				= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
	$document_name   		= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
	$filetype        		= !empty( $image_url[0] ) ? wp_check_filetype( $image_url[0] ) : '';
	$extension       		= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
}

$location_title		= !empty($location_id) ? get_the_title($location_id) : '';
$rand 				= rand(9999, 999);
?>

<div class="dc-location dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Your Location Details', 'doctreat'); ?></h3>
	</div>
	<div class="dc-formtheme dc-userform">
		<fieldset>
			<div class="form-group toolip-wrapo">
				<input type="text" name="location_title" class="form-control" value="<?php echo esc_attr($location_title); ?>" placeholder="<?php esc_attr_e('Location title', 'doctreat'); ?>">
				<?php do_action('doctreat_get_tooltip','element','location_title');?>
			</div>
		</fieldset>
	</div>
</div>
<div class="dc-profilephoto dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Location Photo', 'doctreat'); ?></h3>
	</div>
	<div class="dc-profilephotocontent">	
		<div class="dc-formtheme dc-formprojectinfo dc-formcategory" id="dc-img-<?php echo esc_attr( $rand ); ?>">
			<fieldset>
				<div class="form-group form-group-label" id="dc-image-container-<?php echo esc_attr( $rand ); ?>">
					<div class="dc-labelgroup"  id="image-drag-<?php echo esc_attr( $rand ); ?>">
						<label for="file" class="dc-image-file">
							<span class="dc-btn" id="image-btn-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e('Select File', 'doctreat'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'doctreat'); ?></span>
						<em class="dc-fileuploading"><?php esc_html_e('Uploading', 'doctreat'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder">
					<?php if( !empty( $image_url[0] ) ){ ?>
						<ul class="dc-attachfile dc-attachfilevtwo">						
							<li class="dc-uploadingholder dc-companyimg-user">
								<div class="dc-uploadingbox">
									<figure><img class="img-thumb" src="<?php echo esc_url( $image_url[0] ); ?>" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"></figure>
									<div class="dc-uploadingbar">
										<span class="uploadprogressbar"></span>
										<span><?php echo esc_attr( $document_name ); ?>.<?php echo esc_attr( $extension ); ?></span>
										<em><?php esc_html_e('File size:', 'doctreat'); ?> <?php echo esc_attr( size_format($file_size, 2) ); ?><a href="javascript:;" class="dc-remove-image lnr lnr-cross"></a></em>
									</div>	
									<input type="hidden" name="basics[avatar][attachment_id]" value="<?php echo esc_attr( $attachment_id ); ?>">	
								</div>
							</li>						
						</ul>						
					<?php } ?>
				</div>	
			</fieldset>
		</div>
	</div>
</div>
<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_image_uploader_v2("' . esc_js( $rand ). '", "profile"); });';
	wp_add_inline_script( 'doctreat-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-default-image">
	<ul class="dc-attachfile dc-attachfilevtwo">
		<li class="award-new-item dc-uploadingholder dc-doc-parent" id="thumb-{{data.id}}">
			<div class="dc-uploadingbox">
				<figure><img class="img-thumb" src="<?php echo get_template_directory_uri();?>/images/profile.jpg" alt="<?php echo esc_attr( get_the_title( $post_id )); ?>"></figure>
				<div class="dc-uploadingbar dc-uploading">
					<span class="uploadprogressbar" style="width:{{data.percentage}}%"></span>
					<span>{{data.name}}</span>
					<em><?php esc_html_e('File size:', 'doctreat'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-image lnr lnr-cross"></a></em>	
				</div>	
			</div>
		</li>
	</ul>	
</script>
<script type="text/template" id="tmpl-load-profile-image">
	<div class="dc-uploadingbox">
		<figure><img class="img-thumb" src="{{data.url}}" alt="<?php echo esc_attr( get_the_title( $post_id ) ); ?>"></figure>
		<div class="dc-uploadingbar">
			<span class="uploadprogressbar"></span>
			<span>{{data.name}}</span>
			<em><?php esc_html_e('File size:', 'doctreat'); ?> {{data.size}}<a href="javascript:;" class="dc-remove-image lnr lnr-cross"></a></em>
			<input type="hidden" name="basics[avatar]" value="{{data.url}}">	
		</div>	
	</div>	
</script>