<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends KWC_Controller {

	public function Main() {
		parent::__construct();
	}
	
	public function index() {
		$this->art_deco_salon_styles_and_cuts();
	}
	
	public function art_deco_salon_styles_and_cuts(){
    $this->load->helper('url');
	  $this->processView('home',array(
        'footerInc' => 'fragments/introMusic'
      ));
	}
		
	public function contact(){
    $this->load->helper('url');
	  $this->processView('contact',array(
	    'secondaryView' => 'pages/secondary/contact'
	  ));
	}
	
	public function products_services(){
	  $this->processView('products');
	}
	
	public function hair_stylists(){
    $this->load->model('Stylist_model');
    $stylistCount = $this->Stylist_model->getStylistCount();
    $stylists  = $this->Stylist_model->getThumbnails();
    
	  $this->processView('stylists',array(
	    'stylists' => $stylists, 
	    'stylistCount' => $stylistCount,
	    'footerInc' => 'fragments/stylist_script',
	    'pageStyle' => 'stylist',
	    'secondaryView' => 'pages/secondary/stylists'
	  ));
	}
}