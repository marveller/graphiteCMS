<?php

defined('VERSION') or die('No direct script access.');
include('functions.php');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
require 'lib/klein.php';
Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));
$path = CONTENT;
$arr = getDirectory($path);
ksort($arr);

$pageVars;
$postVars;

//$allTags = array_unique($allTags);
//print_r($allTags);

//$postTpl->render($post)

$postTpl = $mustache->loadTemplate('post');
$pageTpl = $mustache->loadTemplate('page');

/*
$page = array();
$page['nav'] = array('raz','dwa','trzy');
foreach ($posts as &$post) {
	$page['posts'].=$postTpl->render($post);
}
*/
//$page['posts']=rtrim($page['posts']);
//print_r($page);

echo $pageTpl->render(json_encode($pageVars));
?>