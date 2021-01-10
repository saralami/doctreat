
  
<?php
/**
 *
 * The template part for displaying appointment in listing
 *
 * @package   Doctreat
 * @author    Amentotech
 * @link      http://amentotech.com/
 * @since 1.0
 */

global $current_user, $wpdb, $post;

$query = $wpdb->get_results("SELECT * FROM info_patient WHERE (patient_id = '$current_user->ID')");
//var_dump($query);
//if(empty($query)){
    if(isset($_POST['insert'])){
        $id =  $current_user->ID;
        $nom = $current_user->display_name;
        $now = date('Y-m-d');
        $datenaiss = $_POST['datenaiss'];
        $sanguin = $_POST['sanguin'];
       // var_dump($_POST);
        $add = $wpdb->insert("info_patient", array(
          "patient_id" =>$id,
           "nom" => $nom,
           "date_naissance" => $datenaiss,
           "groupe_sanguin" => $sanguin,
           "date_send" => $now,
         ));
         if($add > 0){
            ?>
   <div class="alert alert-sucess fade show" role="alert" style="background-color:#d1ecf1">
      <strong>Hey <?php echo $current_user->display_name;?>!</strong> Votre groupe sanguin été ajouté avec succés.
  </div>
            <?php
         echo "<meta http-equiv='refresh' content='0'>";
         
          //window.history.back();
        } 
    }
//} else {
    if(isset($_POST['update'])){

       
        //var_dump($_POST);

        $id =  $current_user->ID;
      //  $nom = $current_user->display_name;
        //$now = date('Y-m-d');
        $datenaiss = $_POST['datenaiss'];
        $sanguin = $_POST['sanguin'];
       // var_dump($_POST);
      $modif = $wpdb->query("UPDATE info_patient SET date_naissance='$datenaiss', groupe_sanguin='$sanguin' WHERE patient_id = '$id' ");
        // $wpdb->update("info_patient", array(
          
          if($modif > 0){
              ?>
     <div class="alert alert-sucess fade show" role="alert" style="background-color:#d1ecf1">
        <strong>Hey <?php echo $current_user->display_name;?>!</strong> Vous venez de mettre à jours vos informations.
    </div>
              <?php
           echo "<meta http-equiv='refresh' content='0'>";
           
            //window.history.back();
          } else {
              ?>
               <div class="alert alert-sucess fade show" role="alert" style="background-color:#d1ecf1">
                 <strong>Hey <?php echo $current_user->display_name;?>!</strong> Vous n'avez fait aucune modification.
               </div>
              <?php
              echo "<meta http-equiv='refresh' content='0'>";
          }
           // get_template_part('directory/front-end/templates/regular_users/dashboard', 'info-medical-listing');
         // echo "<meta http-equiv='refresh' content='0'>";
        //   "patient_id" =>$id,
        //    "nom" => $nom,
        //    "date_naissance" => $datenaiss,
        //    "groupe_sanguin" => $sanguin,
        //    "date_send" => $now,
        //  ))  ;
        //window.history.back()
        
    }
  
//}

?>


 
<div class="col-lg-3">
<a class="dc-btn dc-btn-sm mb-3" href="<?php Doctreat_Profile_Menu::doctreat_profile_menu_link('info-medical', $user_identity,''); ?>">
        <?php esc_html_e('Retour au tableau de bord', 'doctreat'); ?> 
    </a> 
</div>




 
    <div class="card text-center">
   
      <div class="card-body">
     
          <!-- ADD INFORMATIONS -->
        <?php if(empty($query)){ ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="">Date de naissance</label>
                    <input class="form-control" type="date" name="datenaiss" required>
                </div>
                <div class="form-group">
                    <label for="">Groupe sanguin</label>
                    <input type="text" class="form-control" name="sanguin" required>
                </div>
                    <button type="submit" name="insert" class="btn  btn-primary">Enregistrer</button>
            </form>
            <?php } ?> 

            <!-- UPDATE INFORMATIONS -->
            <?php if(!empty($query)){ ?>
                <form action="" method="POST">
                    <?php foreach($query as $info){ ?>
                        <div class="form-group">
                            <label for="">Date de naissance</label>
                            <input type="date" class="form-control" value="<?php echo $info->date_naissance; ?>" name="datenaiss"> 
                        </div>
                        <div class="form-group">
                            <label for="">Groupe sanguin</label>
                            <input type="text" class="form-control" value="<?php echo $info->groupe_sanguin; ?>" name="sanguin">
                        </div>
                            <button type="submit" name="update" class="btn btn-primary">Modifier</button>
                    <?php }  ?> 
                </form>  
            <?php } ?>   
      </div>
    </div>


 