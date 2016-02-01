  
  <div id="footer">
    Design: <a href="http://www.poweronstudio.com/" target="_blank" title="Power On Studio will open in a new window">Power On Studio</a>.  Development: <a href="http://keenwebconcepts.com" target="_blank" title="Keen Web Concepts will open in a new window">Chris Keen</a>
	</div>

<?php 
    if( isSet($footerInc) ) {
      $this->load->view($footerInc);
    } 
?>
