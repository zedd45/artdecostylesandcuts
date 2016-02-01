<?php
  $this->load->view('fragments/viewAllLink');
?>
  <div class="stylist-wrapper">
    <?php 
      for ($i=0; $i<$stylistCount; $i++){
        $this->load->view('fragments/stylistPortoflio',array('i'=>$i));
      }
    ?>
  </div>
<?php 
  $this->load->view('fragments/viewAllLink');
?>