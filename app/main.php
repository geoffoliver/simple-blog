<?php

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

include 'classes.php';

define('TEMPLATES_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'templates');
define('CONTENT_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'content');

$url = '';
if (isset($_GET['path'])) {
	$url = str_replace('..', '', $_GET['path']);
}

if ($url) {
	$pageReader = new PageReader(CONTENT_DIR, TEMPLATES_DIR);
	$pageReader->showPage($url);
} else {
	$pageReader = new PageReader(CONTENT_DIR . DIRECTORY_SEPARATOR . 'posts', TEMPLATES_DIR);
	$pageReader->listPages();
}
