<?php

/**
 * Creates an HTML webpage using the given params
 * 
 * @author Jamie Collins
 * 
 */
abstract class WebPage
{
	private $main;
	private $endpoints;
	private $pageStart;
	protected $header;
	private $css;
	private $footer;
	private $pageEnd;

	/**
	 *
	 * @param $pageTitle - A string to appear as web page title
	 * @param $css - link for a css file
	 * @param $pageHeading1 - a string to appear as an <h1>
	 * @param $footerText - footer text should include any html tags
	 *
	 */
	public function __construct($pageTitle, $pageHeading1, $footerText)
	{
		$this->main = "";
		$this->set_css();
		$this->set_pageStart($pageTitle, $this->css);
		$this->set_header($pageHeading1);
		$this->set_footer($footerText);
		$this->set_pageEnd();
	}

	private function set_pageStart($pageTitle, $css)
	{
		$this->pageStart = <<<PAGESTART
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>$pageTitle</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<link rel="stylesheet" href="$css">
</head>
<body>
PAGESTART;
	}

	private function set_css()
	{
		$this->css = BASEPATH . CSSPATH;
	}

	protected function set_header($pageHeading1)
	{
		$this->header = <<<HEADER
<div class="container">
		<header>
			<h1>$pageHeading1</h1>
		</header>
</div>
HEADER;
	}

	private function set_main($main, $endpoints)
	{
		$this->main = <<<MAIN
<main>
	<div class="container">
		$main
	</div>

	<div class="container">
		<div class="row">
			<div class="col-12 rounded bg-secondary p-5">
				$endpoints
			</div>
		</div>
	</div>
</main>
MAIN;
	}

	private function set_footer($footerText)
	{
		$this->footer = <<<FOOTER
	<div class="mt-5">
        <div class="container">
            <div class="fixed-bottom">
                <footer class="py-2 bg-secondary text-center">
                        $footerText
                </footer>
            </div>
        </div>
    </div>
FOOTER;
	}

	private function set_pageEnd()
	{
		$this->pageEnd = <<<PAGEEND
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
PAGEEND;
	}

	public function addToBody($text, $endpoint)
	{
		$this->main .= $text;
		$this->endpoints .= $endpoint;
	}

	public function get_page()
	{
		$this->set_main($this->main, $this->endpoints);
		return
			$this->pageStart .
			$this->header .
			$this->main .
			$this->footer .
			$this->pageEnd;
	}
}
