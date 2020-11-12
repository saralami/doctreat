<?php
/**
 *
 * The template part for payments
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */
global $current_user;
$user_identity 	 	= $current_user->ID;
$linked_profile  	= doctreat_get_linked_profile_id($user_identity);
$post_id 		 	= $linked_profile;
$payments			= doctreat_get_payments_doctreat($user_identity);

$table_name 		= $wpdb->prefix . "dc_payouts_history";
$earning_sql		= "SELECT * FROM $table_name where ( user_id =".$user_identity." And status= 'completed')";
$total_query 		= "SELECT COUNT(1) FROM (${earning_sql}) AS combined_table";
$total 				= $wpdb->get_var( $total_query );
$items_per_page 	= get_option('posts_per_page');
$page 				= isset( $_GET['epage'] ) ? abs( (int) $_GET['epage'] ) : 1;
$offset 			= ( $page * $items_per_page ) - $items_per_page;
$payments 			= $wpdb->get_results( $earning_sql . " ORDER BY id DESC LIMIT ${offset}, ${items_per_page}" );
$total_pages		= ceil($total / $items_per_page);
$date_formate		= get_option('date_format');
$payrols_list		= doctreat_get_payouts_lists();
?>
<div class="dc-userexperience dc-followcompomy">
	<div class="dc-tabscontenttitle dc-addnew">
		<h3><?php esc_html_e('Your Payments','doctreat');?></h3>
	</div>
	<div class="dc-dashboardboxcontent dc-categoriescontentholder dc-categoriesholder dc-emptydata-holder">
		<?php if( !empty($payments) ) {?>
			<table class="dc-tablecategories">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Date', 'doctreat' ); ?></th>
						<th><?php esc_html_e( 'Payout Details', 'doctreat' ); ?></th>
						<th><?php esc_html_e( 'Amount', 'doctreat' ); ?></th>
						<th><?php esc_html_e( 'Payment Method', 'doctreat' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach( $payments as $payment ) {
						$payment_mode	= !empty( $payment->payment_method ) ? $payment->payment_method : 'paypal';
						$payrol_title 	= !empty( $payrols_list[$payment_mode]['title'] ) ? $payrols_list[$payment_mode]['title'] : '';
						$sc_info		= '';
	
						if( !empty($payment->payment_method) && $payment->payment_method === 'bacs'){
							$paymentdetails	 = '';
							$bank_detail	= !empty( $payment->payment_details ) ? maybe_unserialize($payment->payment_details) : array();
							$sc_info	= !empty($bank_detail['bank_account_number']) ? '*****'.substr($bank_detail['bank_account_number'],'-4') : '';
						} else if( $contents['type'] === 'paypal' ){
							$sc_info	= !empty($payment->paypal_email) ? substr($payment->paypal_email,0,4).'*****' : '';
	
						}
						?>
						<tr>
							<td><?php echo esc_html($sc_info);?></td>
							<td><?php echo do_shortcode($paymentdetails);?></td>
							<td><?php doctreat_price_format($payment->amount);?></td>
							<td><?php echo esc_html($payrol_title);?></td>
						</tr>
					<?php }?>
				</tbody>
			</table>
			<?php if( !empty( $total_pages ) && $total_pages > 0 ) { ?>
				<nav class="dc-pagination woo-pagination">
					<?php 
						echo paginate_links( array(
							'base' 		=> add_query_arg( 'epage', '%#%' ),
							'format' 	=> '',
							'prev_text' => '<i class="lnr lnr-chevron-left"></i>',
							'next_text' => '<i class="lnr lnr-chevron-right"></i>',
							'total' 	=> $total_pages,
							'current' 	=> $page
						));
					?>
				</nav>
				<?php } ?>
			<?php 
			} else {
				do_action('doctreat_empty_records_html','dc-empty-payouts',esc_html__( 'No payments found yet.', 'doctreat' ));
			} ?>
	</div>									
</div>