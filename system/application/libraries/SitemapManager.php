<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SitemapManager {
	private $CI = null;
	private $siteXML = null;
	private $siteName = null;
	
	public function __construct(){
		$this->CI =& get_instance();
		$this->siteXML = simplexml_load_file('presentation/xml/sitemap.xml');
		$this->siteName = $this->CI->config->item('SITE_NAME');
	}
	
	/**
	 * provide the friendly name and xpath gets the URI for you
	 * @param string $name - the system <name> element in sitemap.xml
	 */
	public function getPageURIByName($name){
	    $page = $this->siteXML->xpath('//page');
	    foreach($page as $item){
	        if ($item->name == $name){
	            return $item->link;
	        }
	    }
	}

	/**
	 * returns a string representing the page title by parsing through the XML to find the current page
	 * @param simplexml_string xmlStringInput variable should contain a simpleXML String object loaded via <CODE>simplexml_load_string($str);</CODE>
	 * @return String pageTitle
	 */ 
	public function getPageTitle($currentPage){
		$page = $this->siteXML->xpath('//page');
		foreach($page as $item){
		    if ($currentPage == $item->name){
		        return $item->title;
		    }
		}
		//if the page title isn't found, default to the site name.
		return $this->siteName;
	}
	
	/**
	 * returns the string representation of the navigation set whose "tier" attribute matches the supplied parameter
	 * @param string $tier - typically "primary" or "utility", but could be any string input into the "tier" XML attribute 
	 * @return string $nav - a group of list item elements representing the nav you requested
	 */
	public function getNavByTier($tier){
		$nav = '';
		//$navItem = $this->siteXML->xpath("/nav[@tier=$tier]//page");
		$navItem = $this->siteXML->xpath("/nav//page");
		foreach($navItem as $item){
			 $nav .= "<li><a href=\"/{$item->link}\" title=\"{$item->title}\">{$item->friendlyname}</a></li>";
		}
		return $nav;
	}
}
