<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom Controller Class for processing templates and views
 * @author ChrisKeen noSpam @ KWS
 */
class KWC_Controller extends Controller {
  private $section = null;

  public function KWC_Controller(){
      parent::Controller();
      //in order to not break on production 5.2 / 5.3 servers, all libraries must match the case of their class def. / file name
      $this->load->library('SitemapManager');
  }
  
  /**
	 * This does all the heavy lifting, like looking up the title, processing the various templates, etc.
	 * Do NOT modify this without approval from Chris.
	 * @param string $pageName - the name of the view PHP file in views/ you want to include as the primary content
	 * @param array $pageData - any data you want to push into the processing.  If you do not provide any, the method will calculate default values for vital compontents
	 * @param string $templateView - the name of the template you want to include the content into. 
	 * TODO: abstract this so the various controllers share this method
	 */
	protected function processView($pageName, $pageData = array(), $templateView = 'main'){
	  
		$pageDefaults = array(
		  'bodyId' => $pageName,
		  'contentView' => $this->buildContentView($pageName),
		  'pageTitle'=> $this->sitemapmanager->getPageTitle($pageName), 
		  'primaryNavItems' => $this->sitemapmanager->getNavByTier('primary'),
		  'utilityNavItems' => $this->sitemapmanager->getNavByTier('utility')
		);
		  
	  $pageData = is_array($pageData) ? array_merge($pageDefaults,$pageData) : $pageDefaults;
	  
		$this->load->vars($pageData);
		$this->load->view("templates/$templateView");
		unset($pageDefaults, $pageData);
	}
	
	private function buildContentView($pageName){
	  $view = null == $this->section ? '' : $this->section . '/';
	  $view .= $pageName;
	  return $view;
	}

	/**
   *
   *  PHP password protect
   *  http://www.webtoolkit.info/
   *
   **/
   protected function passwordProtect($username, $password){
   	if (
   			(
   				!isset($_SERVER['PHP_AUTH_USER']) ||
   				(
   					isset($_SERVER['PHP_AUTH_USER']) &&
   					$_SERVER['PHP_AUTH_USER'] != $username
   				)
   			) &&
   			(
   				!isset($_SERVER['PHP_AUTH_PW']) ||
   				(
   					isset($_SERVER['PHP_AUTH_PW']) &&
   					$_SERVER['PHP_AUTH_PW'] != $password
   				)
   			)
   		)
   	{
   		header('WWW-Authenticate: Basic realm="Login"');
   		header('HTTP/1.0 401 Unauthorized');
   		echo 'Please login to continue.';
   		exit;
   	}
   }
  
}
