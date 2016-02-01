<div id="primary-content">
  <?php $this->load->view("pages/$contentView"); ?>
</div>
<div id="secondary-content">
<?php  if (isSet($secondaryView)) { $this->load->view($secondaryView); } ?>
</div>