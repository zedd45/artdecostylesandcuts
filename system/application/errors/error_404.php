<?php
$this->load->view('includes/header');
?>

<section id="error-404">
  <p>We could not find the page you specified or were linked to!</p>
	<p>Please try <a href="javascript:history.go(-1);">going back</a>.</p>
	<p>You can also start over at our <a href="<?php //echo $this->sitemapmanager->getPageURIByName('home'); ?>">homepage</a>.</p>
</section>