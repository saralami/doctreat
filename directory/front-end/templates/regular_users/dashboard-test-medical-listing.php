<!-- <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">    -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css"></style>
<script type="text/javascript" src="http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>
<!-- <script type="text/javascript" src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script> -->

<?php
 global $current_user, $post;
 $user_identity 	 	= $current_user->ID;
 $linked_profile  	= doctreat_get_linked_profile_id($user_identity);
 $post_id 		 	= $linked_profile;

 $date_format		= get_option('date_format');
 $appointment_date 	= !empty( $_GET['appointment_date']) ? $_GET['appointment_date'] : '';
 $show_posts 		= get_option('posts_per_page') ? get_option('posts_per_page') : 10;
 $pg_page 			= get_query_var('page') ? get_query_var('page') : 1;
 $pg_paged 			= get_query_var('paged') ? get_query_var('paged') : 1;
 $paged 				= max($pg_page, $pg_paged);
 $order 	 			= 'DESC';
 $sorting 			= 'ID';
 //$tests = doctreat_get_taxonomy_array('laboratory_tests') ? doctreat_get_taxonomy_array('laboratory_tests') : '';
 $args = array(
     'posts_per_page' 	=> $show_posts,
     'post_type' 		=> 'booking',
     'orderby' 			=> $sorting,
     'order' 			=> $order,
     'author'			=> $user_identity,
     'post_status' 		=> array('publish'),
     'paged' 			=> $paged,
     'suppress_filters'  => false,
   //  'laboratory_tests' => $tests,
 );

 if( !empty( $appointment_date ) ) {
     $meta_query_args[] = array(
                             'key' 		=> '_appointment_date',
                             'value' 	=>  $appointment_date,
                             'compare' 	=> '='
                         );
     $query_relation 	= array('relation' => 'AND',);
     $args['meta_query'] = array_merge($query_relation, $meta_query_args);
 }

 $query 		= new WP_Query($args);
 $count_post = $query->found_posts;

 $width		= 40;
 $height		= 40;
 $flag 		= rand(9999, 999999);

 //var_dump($query);
$tests = array();
?>

<div class="table-responsive">
    <table id="myTable" class="table table-hover table-striped table-bordered">
    <thead>
        <tr>
        <th scope="col">doctor</th>
        <th scope="col">date</th>
        <th scope="col">analyse à faire</th>
        <th scope="col">Liste des résultats</th>
        </tr>
    </thead>
    <tbody>
    <?php
        if( $query->have_posts() ){
            while ($query->have_posts()) : $query->the_post();
            global $post;

            $doctor_id	= get_post_meta( $post->ID,'_doctor_id',true);
            $name		= doctreat_full_name( $doctor_id );
            $name		= !empty( $name ) ? $name : '';

            $thumbnail   	= doctreat_prepare_thumbnail($doctor_id, $width, $height);
            $post_status	= get_post_status( $post->ID );

            if($post_status === 'pending'){
                $post_status	= esc_html__('Pending','doctreat');
            } elseif($post_status === 'publish'){
                $post_status	= esc_html__('Confirmed','doctreat');
            } elseif($post_status === 'draft'){
                $post_status	= esc_html__('Pending','doctreat');
            }

            $doctor_url		= get_the_permalink( $doctor_id );
            $doctor_url		= !empty( $doctor_url ) ? $doctor_url : '';
            $ap_date		= get_post_meta( $post->ID,'_appointment_date',true);
            $ap_date		= !empty( $ap_date ) ? strtotime($ap_date) : '';

            $booking_id	= !empty( $post->ID ) ? intval( $post->ID ) : '';
            $prescription_id	= get_post_meta( $booking_id, '_prescription_id', true );
            //$prescription	= get_post_meta( $prescription_id, '_detail', true );


                        // $today_date	= date('2020-10-26');
                        //  $today_date	= date('Y-m-d');
            $laboratory_tests_obj_list 	= get_the_terms( $prescription_id, 'laboratory_tests' );
           // var_dump($laboratory_tests_obj_list);

            if (is_array($laboratory_tests_obj_list) || is_object($laboratory_tests_obj_list)) {
                $laboratory_tests_name	= join(' ,  ', wp_list_pluck($laboratory_tests_obj_list, 'name'));
                //var_dump($laboratory_tests_name);
               // echo $prescription_id;
            }

                        // $today_date	= date('2020-10-26');
                        //  $today_date	= date('Y-m-d');
            //$laboratory_tests = doctreat_get_taxonomy_array('laboratory_tests');



                        // $today_day     = date_i18n('d',$today_date);
                        // $today_month     = date_i18n('m',$today_date);
                        // $today_year     = date_i18n('Y',$today_date);
    ?>
         <?php if( !empty( $laboratory_tests_obj_list )  ){ ?>

        <tr>
        <th scope="row"><?php echo $name; ?></th>
        <td><?php echo date_i18n('d',$ap_date)."-".date_i18n('M',$ap_date)."-".date_i18n('Y',$ap_date); ?></td>
        <th scope="row">
           <?php echo $laboratory_tests_name; ?>
        </th>
        <td>
            <!-- Button trigger modal -->
<button class="dc-btn dc-btn-sm dc-rightarea" data-toggle="modal" data-target="#<?php echo $prescription_id?>">
  Launch demo modal
</button>
<?php include("ModalTest.php");?>

            <!-- <a href=""></a>
            <a href="<?php //Doctreat_Profile_Menu::doctreat_profile_menu_link('test-results', $user_identity,''); ?>" class="dc-btn dc-btn-sm dc-rightarea ">résultats de tests</a> -->
        </td>
        </tr>
    <?php }
       endwhile;
       }

     ?>
    </tbody>
    </table>

</div>


    <script>
$(document).ready(function(){
    $('#myTable').dataTable();
});


</script>
