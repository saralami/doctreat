<!-- Modal -->
<div class="modal fade" id="<?php echo $prescription_id; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php 
      //var_dump( $laboratory_tests_obj_list);
      $laboratory_tests_name	= ( wp_list_pluck($laboratory_tests_obj_list, 'name'));
      //var_dump($laboratory_tests_name);
      ?>
      <form enctype="multipart/form-data" action="" method="post">
      <?php 
         foreach($laboratory_tests_name as $test){
           //echo $test;
        
      ?>
      <label for=""><?php echo  $test; ?></label>
      
        <input name="testfile" type="file" class="form-control">
         <?php 
       }
      //echo $prescription_id; 
      
      ?>
       <input type="hidden" name="MAX_FILE_SIZE" value="30000" />

       <button type="submit" name="fileToUpload" class="btn btn-primary">Enregistrer</button>
      </form>
     


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
       
      </div>
    </div>
  </div>
</div>

<?php  
$target_path = "e:/";  
$target_path = $target_path.basename( $_FILES['fileToUpload']['name']);   
  
if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_path)) {  
    echo "File uploaded successfully!";  
} else{  
    echo "Sorry, file not uploaded, please try again!";  
}  
?>  
