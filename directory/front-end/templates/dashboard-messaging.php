<?php 
/**
 * Messaging template
 **/
global $current_user;
$user_identity = $current_user->ID;
?>
<section class="dc-haslayout am-chat-module">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
	    <div class="dc-dashboardbox dc-messages-holder">
	        <div class="dc-dashboardboxtitle">
	           <h2><?php esc_html_e('Messages', 'doctreat'); ?></h2>
			</div>
			<div class="dc-dashboardboxtitle dc-titlemessages chat-current-user"></div>
			<div class="dc-dashboardboxcontent dc-dashboardholder dc-offersmessages">	
				<?php
					if (isset($_GET['ref']) && $_GET['ref'] == 'chat' && $_GET['identity'] == $user_identity) {
						do_action('fetch_users_threads', $user_identity);
					}
				?>
			</div>
		</div>
	</div>
</section>
<script type="text/template" id="tmpl-load-chat-replybox">
<div class="dc-messages dc-verticalscrollbar dc-dashboardscrollbar"></div>
<div class="dc-replaybox">
	<div class="form-group">
		<textarea class="form-control reply_msg" name="reply" placeholder="<?php esc_attr_e('Type message here', 'doctreat'); ?>"></textarea>
	</div>
	<div class="dc-iconbox">
		<div id="container"></div>
		<a href="javascript:;" class="dc-btnsendmsg dc-send" data-status="unread" data-receiver_id="{{data.receiver_id}}"><?php esc_html_e('Send', 'doctreat'); ?></a>
	</div>
</div>
</script>
<script type="text/template" id="tmpl-load-chat-messagebox">
<# if( !_.isEmpty(data.chat_nodes) ) { #>
<# 
_.each( data.chat_nodes , function( element, index ) { 
	var chat_class = 'dc-offerermessage dc-msg-thread';
	if(element.chat_is_sender === 'yes'){
		chat_class = 'dc-memessage dc-readmessage dc-msg-thread';
	}
	
	load_message	= element.chat_message;
#>
<div class="{{chat_class}}" data-id="{{element.chat_id}}">
	<figure><img src="{{element.chat_avatar}}" alt="{{element.chat_username}}"></figure>
	<div class="dc-description">
		<p>{{load_message}}</p>
		<div class="clearfix"></div>
		<time datetime="2017-08-08">{{element.chat_date}}</time>
		<div class="clearfix"></div>
		<# if(element.chat_is_sender === 'yes'){ #>
		<!-- <a href="javascript:;" class="dc-delete-message" data-id="{{element.chat_id}}" data-user="{{element.chat_current_user_id}}">
			<i class="fa fa-trash" aria-hidden="true"></i>
		</a> -->
		<# } #>
	</div>
</div>
<# }); #>
<# } #>
</script>
<script type="text/template" id="tmpl-load-chat-recentmsg-data">
	{{data.desc}}
</script>
<script type="text/template" id="tmpl-load-user-details">
<a href="javascript:;" class="dc-back back-chat"><i class="ti-arrow-left"></i></a>
<div class="dc-userlogedin">
	<figure class="dc-userimg">
		<img src="{{data.chat_img}}" alt="{{data.chat_name}}">
	</figure>
	<div class="dc-username">
		<h3>{{data.chat_name}}</h3>
		<a target="_blank" href="{{data.chat_url}}"><?php esc_html_e('View Profile', 'doctreat'); ?></a>
	</div>
</div>
<a href="{{data.chat_url}}" class="dc-viewprofile"><img class="viewprofile" src="<?php echo esc_url(get_template_directory_uri());?>/images/viewprofile.jpg"></a>
</script>