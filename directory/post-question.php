<!-- Popup Start-->
<?php $speciality 	= !empty( $_GET['specialities']) ? $_GET['specialities'] : '';?>
<div class="modal fade dc-offerpopup" tabindex="-1" role="dialog" id="freequery">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="dc-modalcontent modal-content">
			<div class="dc-popuptitle">
				<h3><?php esc_html_e('Give Category to Your Query','doctreat');?></h3>
				<a href="javascript:;" class="dc-closebtn close" data-dismiss="modal" aria-label="<?php esc_attr_e('Close','doctreat');?>"><i class="lnr lnr-cross"></i></a>
			</div>
			<div class="modal-body">
				<form class="dc-formtheme dc-formhelp">
					<fieldset>
						<div class="form-group">
							<span class="dc-select">
								<select data-placeholder="<?php esc_attr_e('Select a speciality*','doctreat');?>" name="speciality">
									<option value=""><?php esc_html_e('Select a speciality','doctreat');?></option>
									<?php
										wp_list_categories( array(
												'taxonomy' 			=> 'specialities',
												'hide_empty' 		=> false,
												'current_category'	=> $speciality,
												'style' 			=> '',
												'walker' 			=> new Doctreat_Walker_Location_Dropdown,
											)
										);
									?>
								</select>
							</span>
						</div>
						<div class="form-group">
							<input type="text" name="title" value="" class="form-control" placeholder="<?php esc_attr_e('Title Your Query','doctreat');?>*" required="">
						</div>
						<div class="form-group">
							<textarea class="form-control" name="description" placeholder="<?php esc_attr_e('Type Your Query','doctreat');?>*"></textarea>
						</div>
						<?php wp_nonce_field('dc_question_nonce', 'question_submit'); ?>
						<div class="form-group dc-btnarea">
							<a href="javascript:;" class="dc-btn submit-question"><?php esc_html_e('Ask Free Query','doctreat');?></a>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>



