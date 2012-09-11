<?php
defined('VERSION') or die('No direct script access.');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
include('functions.php');
require 'lib/klein.php';
Mustache_Autoloader::register();
$arr = getDirectory(CONTENT);
ksort($arr);

respond(function ($request, $response) { //probably not the best way to do this:
	$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));    
	$response->renderTemplate = function ($tmpl, $content) use ($mustache) {
		$template = $mustache->loadTemplate($tmpl);
        echo $template->render($content);
    };
});

//HOME - first element in the array
respond(BASE."/",function ($request, $response) use ($arr){
	reset($arr);
	$key = key($arr);
	$post_title = $arr[$key]['title'];
	$pageVars = array();
	$pageVars['title'] = $post_title;
	$pageVars['page_title'] = TITLE . ' ' . $post_title;
	$pageVars['content'] = $arr[$key]["content"];	
	$response->renderTemplate('page', $pageVars);
});

/*
respond(BASE.'/[:name]', function ($request) {
	$post_title = 'Post Title';
	$pageVars = array();
	$pageVars['title'] = $post_title;
	$pageVars['page_title'] = TITLE . ' ' . $post_title;
	$page['nav'] = array('one','two','three');
	$pageVars['content'] = 'content';
	
	$response->renderTemplate($pageTpl, $pageVars);
});
*/
//home:


dispatch();

//maybe for later:?
//$allTags = array_unique($allTags);
/*
$page = array();
$page['nav'] = array('raz','dwa','trzy');
foreach ($posts as &$post) {
$page['posts'].=$postTpl->render($post);
}
*/
//$page['posts']=rtrim($page['posts']);
//print_r($page);

//echo $pageTpl->render($pageVars);

//respond('/klein/posts', function() { echo 'Wszyscy umrzemy'; });
?>