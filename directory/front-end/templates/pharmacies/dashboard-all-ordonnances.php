
<?php

$args = array(
											'posts_per_page' 	=> -1,
											'post_type' 		=> 'pharmacies',
											'post_status'    =>  'publish',
											'post_author'			=> '_pharmacie_id'
										);
								$query 	= new WP_Query( $args );
								$posts = $query->posts;
								//$all_doctors_ids = array();
								foreach( $posts as $post ) {
                                    //$all_doctors_ids[]= get_post_meta( $post->ID,'_pharmacie_id',true);
                                    $post -> $post->name;
								}
								// $doctors_ids = array_unique($all_doctors_ids);
								// $count_doctors = count ($doctors_ids);

			  var_dump($posts);
?>