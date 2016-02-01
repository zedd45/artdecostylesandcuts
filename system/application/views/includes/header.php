
<head>
	<title><?php echo $pageTitle . ' - ' . SITE_NAME; ?></title>
	<link rel="stylesheet" href="/presentation/css/site.css"/>
	<?php 
    $this->load->view('includes/IEFix');
	  if (isSet($pageStyle)) { echo "<link rel=\"stylesheet\" href=\"/presentation/css/$pageStyle.css\"/>\n"; } 
	  if (isSet($headerInc)) { $this->load->view($headerInc); } 
	?>	
</head>
