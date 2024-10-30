<?php
/**
@param: $petObject
 */
//  print_r($petObject);
//  die
?>




<div class="col-sm-6 puppysec">
    <div class="puppyset">
    <div class="puppyimg"><img src="<?php echo $petObject->images[0]->url; ?>"></div>
    <div class="puppycont">
        <h3> 
        <a href="<?php echo get_home_url(). "/pet-detail/". Helper::slugify($petObject->breed) ."-" . $petObject->_id ?>">
          <?php echo $petObject->breed; ?>
        </a> </h3>
<p>  <?php echo $petObject->description; ?> </p>

</div>
       
    </div>

</div>




<!--  -->