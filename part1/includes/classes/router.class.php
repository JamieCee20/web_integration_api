<?php

/**
 * This router will return a main, documentation or about page
 * 
 * @author Jamie Collins
 *
 */
class Router
{
	private $page;
	private $type = "HTML";

	/**
	 * @param $pageType - Can be any page that exists in routes.ini
	 */
	public function __construct($recordset)
	{
		$url = $_SERVER["REQUEST_URI"];
		$path = parse_url($url)['path'];

		$path = str_replace(BASEPATH, "", $path);
		$pathArr = explode('/', $path);
		$path = (empty($pathArr[0])) ? "documentation" : $pathArr[0];

		($path == "api")
			? $this->api_route($pathArr, $recordset)
			: $this->html_route($path);
	}

	/**
	 * Api route to display json data
	 * @param - pass in array of information
	 * @param - recordset with data results
	 */
	public function api_route($pathArr, $recordset)
	{
		$this->type = "JSON";
		$this->page = new JSONpage($pathArr, $recordset);
	}

	/**
	 * Pass in information into html the data from the routes.ini
	 */
	public function html_route($path)
	{
		$ini['routes'] = parse_ini_file("includes/config/routes.ini", true);
		$pageInfo = isset($path, $ini['routes'][$path]) ? $ini['routes'][$path] : $ini['routes']['error'];
		// print_r($pageInfo);
		$this->page = new WebPageWithNav($pageInfo['title'], $pageInfo['heading1'], $pageInfo['footer']);
		$this->page->addToBody($pageInfo['text'], $pageInfo['endpoints']);
	}

	public function get_type()
	{
		return $this->type;
	}

	public function get_page()
	{
		return $this->page->get_page();
	}
}
?>
