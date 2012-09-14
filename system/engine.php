<?php
defined('VERSION') or die('No direct script access.');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
include('functions.php');
Mustache_Autoloader::register();
$arr = getDirectory(CONTENT);
//readDirectory ($arr, $menu);
ksort($arr);
$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));
$pageTpl = $mustache->loadTemplate('page');
$nav = array();
//echo "[".BASE."]";
$addr = $_SERVER['REQUEST_URI'];
$addr = substr($addr, strlen(BASE)+1);
$addr = "/".rtrim($addr, "/");
//print($addr);
$cached = false;

if($cached)
{
	//show cached version
}
else
{
	$pageVars = array();
	$pageVars['page_title'] = TITLE;
	$pageVars['base']=BASE;
	$pageVars['nav'] = arr2nav($arr);
	
	if($addr == "/")
	{
		reset($arr);
		$key = key($arr);

		$pageVars['post_title'] = $arr[$key]['title'];
		$pageVars['content'] = $arr[$key]['content'];
	}
	else
	{
		//
		$pageVars['post_title'] = "tmp title";	
		$pageVars['content'] = "content";	
		//not home
	}
}

//print_r(split("/",$addr));
//echo $addr;
//$addr = ltrim($addr , BASE);
//url construction/analysis
//check if cached
//serve or:

//menu construction
//content selection:



echo $pageTpl->render($pageVars);
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
//crap to throw away:
function arr2nav($array) {
    $out='<ul>'."\n";
    foreach($array as $key => $elem){
        if(!is_array($elem)){
          //$out=$out.'<li>'. $elem['title'].'</li>'."\n";
        }
        else $out=$out.'<li>'.$elem['title'];
		//if()
			//$out = $out . arr2nav($elem).'</li>'."\n";
		$out = $out . '</li>'."\n";
    }
    $out=$out.'</ul>'."\n";
    return $out; 
}
?>