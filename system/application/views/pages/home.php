
<div class="why-choose-us">
  <h2>Welcome to <?php echo SITE_NAME; ?></h2>
    <p>We are conveniently located off Memorial Drive in Stone Mountain. Come and visit our upscale <?php echo anchor('main/contact','location','title="view information about our location, including a map"') ?>, where we provide professional service. It is a very friendly and comfortable environment where we have free WiFi. We also use a professional line of <?php echo anchor('main/products_services','products','title="view a list of our products and services"') ?>. <?php echo SITE_NAME; ?> <?php echo anchor('main/hair_stylists','team','title="view our stylists\' gallery"') ?> studies healthy hair, and we aim to please. Walk-ins are welcome!</p>
</div>

<?php 
  $this->load->view('fragments/hours');
  $this->load->view('fragments/address');
?>