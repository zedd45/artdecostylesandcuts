
<div class="stylist-container">
  <?php 
    //FIXME: this is muddying the MVC.  Put this in a DB.
    $stylistPhoto = array_shift($stylists[$i]['photos']);
    
    $this->load->view('fragments/stylist_thumb',array(
      'i' => $i, 
      'stylistPhoto' => $stylistPhoto, 
      'stylistName' => $stylists[$i]['stylistName'],
      'thumbImg' => $stylists[$i]['thumbImg'],
     )
    );
    
    foreach($stylists[$i]['photos'] as $photo):
  ?>
  <a class="lightbox-photo hidden" rel="<?php echo $i; ?>" href="<?php echo $photo; ?>">&#138;</a>
  <?php endforeach; ?>
</div>  
