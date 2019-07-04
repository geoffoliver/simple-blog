<?php

use Mni\FrontYAML\Parser;

class Renderable {

	// YAML parser
	protected $parser;

	// Twig template engine
	private $twig;

	public function __construct($path = null) {
		// setup the yaml parser
		$this->parser = new Parser();

		// setup twig
		$loader = new Twig_Loader_Filesystem($path);
		$this->twig = new Twig_Environment($loader, array(
			'cache' => false,//"{$path}/compiled",
			'debug' => true,
			'autoescape' => false
		));
		$this->twig->addExtension(new Twig_Extensions_Extension_Text());
	}

	public function render($template, $data = []) {
		// load up the template
		$twigTpl = $this->twig->load($template);

		// return the rendered template
		echo $twigTpl->render($data);
	}
}


class PageReader extends Renderable {

	// this is where the pages live that we'll be reading
	private $path = null;

	public function __construct($postsPath = null, $templatesPath = null) {
		parent::__construct($templatesPath);
		// make sure the path ends with a slash
		$this->path = rtrim($postsPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}


	// displays a list of pages in the current directory and sorts them by date in descending order
	public function listPages($template = 'index.html') {
		// get the pages
		$pages = $this->getPages();

		if ($pages) {
			// sort pages newest to oldest
			usort($pages, function ($a, $b) {
				if (isset($a['yaml']['date']) && isset($b['yaml']['date'])) {
					return strtotime($b['yaml']['date']) - strtotime($a['yaml']['date']);
				}

				return 0;
			});
		}

		// render the list of pages
		$this->render($template, ['pages' => $pages]);
	}

	// displays a single page
	public function showPage($page = null, $template = 'post.html') {
		// try to find the page
		$page = $this->getPage($page);

		// page not found, throw 404
		if (!$page) {
			$this->render('404.html');
			return;
		}

		if (isset($page['yaml']['template'])) {
			$template = $page['yaml']['template'];
		}

		// render the page
		$this->render($template, ['page' => $page]);
	}

	// gets a list of pages
	private function getPages() {
		$pages = [];
		// get a list of the files in the pages directory
		$files = scandir($this->path);
		if ($files) {
			foreach ($files as $file) {
				if ($file === '.' || $file === '..') {
					continue;
				}
				// the current file being evaluated
				$f = $this->path . $file;
				// we only care about directories
				if (!is_dir($f)) {
					continue;
				}
				// try to get the page object
				if ($p = $this->getPage($file)) {
					$pages[]= $p;
				}
			}
		}
		return $pages;
	}

	// gets data for a page
	private function getPage($page = null) {
		// no page, no data
		if (!$page) {
			return null;
		}

		// this is where the page file _should_ be
		$pageFile = $this->path . $page . DIRECTORY_SEPARATOR . 'post.md';

		// make sure we can do stuff with the page file
		if (file_exists($pageFile) && is_readable($pageFile)) {
			// parse the page
			$p = $this->parser->parse(file_get_contents($pageFile));
			// return the page data
			return [
				'yaml' => $p->getYAML(),
				'content' => $p->getContent(),
				'file' => $page,
				'path' => str_replace(CONTENT_DIR, '', $this->path . $page)
			];
		}

		return null;
	}
}
