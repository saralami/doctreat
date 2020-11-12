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
global $current_user, $wp_roles, $userdata, $post;
$user_identity 	 = $current_user->ID;
$linked_profile  = doctreat_get_linked_profile_id($user_identity);
$post_id 		 = $linked_profile;

$am_downloads	= doctreat_get_post_meta( $post_id,'am_downloads');
$default_img 	= get_template_directory_uri().'/images/file-icon.png';
$rand 			= rand(9999, 999);
?>
<div class="dc-profilephoto dc-tabsinfo">
	<div class="dc-tabscontenttitle">
		<h3><?php esc_html_e('Downloads', 'doctreat'); ?></h3>
	</div>
	<div class="dc-profilephotocontent">	
		<div class="dc-formtheme dc-formprojectinfo dc-formcategory" id="dc-img-<?php echo esc_attr( $rand ); ?>">
			<fieldset>
				<div class="form-group form-group-label" id="dc-download-container">
					<div class="dc-labelgroup" id="download-drag">
						<label for="file" class="dc-download-file">
							<span class="dc-btn" id="download-btn"><?php esc_html_e('Select File', 'doctreat'); ?></span>								
						</label>
						<span><?php esc_html_e('Drop files here to upload', 'doctreat'); ?></span>
						<em class="dc-fileuploading"><?php esc_html_e('Uploading', 'doctreat'); ?><i class="fa fa-spinner fa-spin"></i></em>
					</div>
				</div>
				<div class="form-group uploaded-placeholder dc-downloads-files">
					<ul class="dc-attachfile dc-attachfilevtwo uploaded-placeholder">
					<?php if( !empty( $am_downloads ) ){ ?>	
						<?php foreach( $am_downloads as $key => $am_download ) {
							$image				= !empty( $am_download['media']) ?  $am_download['media'] : '';
							$attachment_id		= !empty($am_download['id']) ? $am_download['id'] : '';
							$file_size 			= !empty( $attachment_id) ? filesize(get_attached_file($attachment_id)) : '';	
							$document_name   	= !empty( $attachment_id ) ? get_the_title( $attachment_id ) : '';
							$filetype        	= !empty( $image ) ? wp_check_filetype( $image	 ) : '';
							$extension       	= !empty( $filetype['ext'] ) ? $filetype['ext'] : '';
							?>				
							<li class="dc-uploadingholder dc-companyimg-user">
								<div class="dc-files-content">
									<img class="img-thumb" src="<?php echo esc_url( $default_img ); ?>" alt="<?php echo esc_attr($document_name); ?>">
									<div class="dc-filecontent">
										<span>
											<?php echo esc_html( $document_name ); ?>
											<em>
												<?php esc_html_e('File size:', 'doctreat'); ?> <?php echo esc_html( size_format($file_size, 2) ); ?>
											</em>
										</span>
										<a href="javascript:;" class="dc-closediv"><i class="lnr lnr-cross"></i></a>
									</div>	
									<input type="hidden" name="am_downloads[<?php echo intval( $key );?>][attachment_id]" value="<?php echo esc_attr( $attachment_id ); ?>">
									<input type="hidden" name="am_downloads[<?php echo intval( $key );?>][media]" value="<?php echo esc_url( $image ); ?>">	
								</div>
							</li>
						<?php } ?>				
					<?php } ?>
					</ul>
				</div>
			</fieldset>
		</div>
	</div>
</div>

<?php
	$inline_script = 'jQuery(document).on("ready", function() { init_uploader_downloads(); });';
	wp_add_inline_script( 'doctreat-dashboard', $inline_script, 'after' );
?>
<script type="text/template" id="tmpl-load-download-attachments">
	<li class="dc-uploadingholder dc-companyimg-user" >
		<div class="dc-files-content">
			<img class="img-thumb" src="<?php echo esc_url( $default_img ); ?>" alt="{{data.name}}">
			<div class="dc-filecontent">
				<span>
					{{data.name}}
					<em>
						<?php esc_html_e('File size:', 'doctreat'); ?> {{data.size}}						
					</em>
				</span>
				<a href="javascript:;" class="dc-closediv"><i class="lnr lnr-cross"></i></a>
			</div>	
			<input type="hidden" id="thumb-{{data.id}}" name="am_downloads[{{data.counter}}][media]" value="{{data.url}}">	
		</div>
	</li>
</script>