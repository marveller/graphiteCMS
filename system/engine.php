<?php
defined('VERSION') or die('No direct script access.');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
include('functions.php');
Mustache_Autoloader::register();
$arr = getDirectory(CONTENT);
ksort($arr);
$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));
$pageTpl = $mustache->loadTemplate('page');
$nav = array();


$addr = $_SERVER['REQUEST_URI'];
$addr = substr($addr, strlen(BASE)+1);
$addr = rtrim($addr, "/");

//if(strlen($addr)==0)
//	homepage;
//else
print_r(split("/",$addr));
//echo $addr;
//$addr = ltrim($addr , BASE);



//url construction/analysis
//check if cached
//serve or:

//menu construction

$nav = arr2nav($arr);
//content selection:
reset($arr);
$key = key($arr);
$post_title = $arr[$key]['title'];
$pageVars['base']=BASE;
$pageVars['post_title'] = $post_title;
$pageVars['page_title'] = TITLE;
$pageVars['content'] = $arr[$key]['content'];
$pageVars['nav'] = $nav;

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