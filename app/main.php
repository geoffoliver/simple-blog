<?php

// so we can see where we fuck up
ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

// this is where everything lives
define('TEMPLATES_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'templates');
define('CONTENT_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'content');

// this is what we use to find and render pages
include 'classes.php';

// this is the page the user is trying to view. empty = homepage
$url = '';

// figure out if the user is trying to view a particular page
if (isset($_GET['path'])) {
	// when we're running through apache and using mod_rewrite
	$url = $_GET['path'];
} elseif (isset($_SERVER['REQUEST_URI'])) {
	// when we're running the PHP server, and maybe other stuff? :shrug:
	$url = $_SERVER['REQUEST_URI'];
}

// make sure there's no leading slash on the URL
$url = ltrim(str_replace('..', '', $url), '/');

if ($url) {
	// display the page a user is requesting (hopefully)
	$pageReader = new PageReader(CONTENT_DIR, TEMPLATES_DIR);
	$pageReader->showPage($url);
} else {
	// display the homepage, which for now is just a list of blog posts
	$pageReader = new PageReader(CONTENT_DIR . DIRECTORY_SEPARATOR . 'posts', TEMPLATES_DIR);
	$pageReader->listPages();
}
