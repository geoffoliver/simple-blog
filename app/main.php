<?php

ini_set('display_errors', 'on');
ini_set('error_reporting', E_ALL);

include 'classes.php';

$url = '';
if (isset($_GET['path'])) {
	$url = str_replace('..', '', $_GET['path']);
}

$templatesDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'templates';
$pagesDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'content';

$pageReader = new PageReader($pagesDir, $templatesDir);

$pageReader->showPage($url);
