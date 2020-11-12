<?php
/**
 *
 * The template used for doctors award
 *
 * @package   Doctreat
 * @author    amentotech
 * @link      https://amentotech.com/user/amentotech/portfolio
 * @version 1.0
 * @since 1.0
 */

global $post;
$post_id = $post->ID;
$am_awards		= doctreat_get_post_meta( $post_id,'am_award');
if( !empty( $am_awards ) ){?>
	<div class="dc-awards-holder dc-aboutinfo">
		<div class="dc-infotitle">
			<h3><?php esc_html_e('Awards and Recognitions','doctreat');?></h3>
		</div>
		<ul class="dc-expandedu">
			<?php 
				foreach( $am_awards as $award ){
					if( !empty( $award['title'] ) ) { ?>
						<li><span><?php echo esc_html( $award['title'] );?> <?php if( !empty( $award['year'] ) ) { ?><em>(&nbsp;<?php echo intval( $award['year'] );?>&nbsp;)</em><?php } ?></span></li>
					<?php } ?>
			<?php } ?>
		</ul>
	</div>
<?php  }

