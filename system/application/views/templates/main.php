<!DOCTYPE html>
<html>
  <?php
    $this->load->view('includes/header'); ?>
  <body<?php if (isSet($bodyId)) { echo " id=\"$bodyId\""; }  ?>>
    <div id="main-container">
      <div id="content-container">
        <div id="header">
          <?php $this->load->view('includes/primaryNav') ?>
          <?php $this->load->view('includes/logo') ?>
        </div>
        <?php $this->load->view('includes/main-content'); ?>
      </div>
      <?php $this->load->view('includes/footer'); ?>
    </div>
  </body>
</html>