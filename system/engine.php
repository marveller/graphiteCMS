<?php
defined('VERSION') or die('No direct script access.');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
include('functions.php');
Mustache_Autoloader::register();
$arr = getContent(CONTENT);
$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));
$pageTpl = $mustache->loadTemplate('page');
//$nav = array();
$addr = $_SERVER['REQUEST_URI'];
$addr = substr($addr, strlen(BASE)+1);
$addr = rtrim($addr, "/");
//print_r(split("/",$addr));
//print_r($arr);
$cached = false;


/*
$nav = array (
  'items' => array(
    array('item' => array('link'=>"http1",'title'=>"title1", 'hasChildren'=>true, 'children'=>array(
										array('item' => array('link' => "childhttp1",'title' => "childtitle1")),
										array('item' => array('link' => "childhttp2",'title' => "childtitle2"))
										))),
    array('item' => array('link'=>"http2",'title'=>"title2", 'hasChildren'=>false )),
    array('item' => array('link'=>"http3",'title'=>"title3", 'hasChildren'=>false ))
));

print_r($nav);
*/
//print_r(arr2nav($arr));
if($cached) {
	//show cached version
}
else
{
	$pageVars = array();
	$pageVars['page_title'] = TITLE;
	$pageVars['base']=BASE;
	$pageVars['nav'] = arr2nav($arr); // arr2nav($arr);
	if($addr == "") //home!
	{
		reset($arr);
		$key = key($arr);
		$pageVars['post_title'] = $arr[$key]['title'];
		$pageVars['content'] = $arr[$key]['content'];
	}
	else if(array_key_exists($addr,$arr))//other //check if there is sth //on main level //what if lower?
	{
		$pageVars['post_title'] = "tmp title";	
		$pageVars['content'] = $arr[$addr]['content'];	
	}
	else //404
	{
		$four04 = parseFile(CONTENT,CONTENT404);
		$pageVars['post_title'] = $four04['title'];
		$pageVars['content'] = $four04['content'];
	}	
	//cache!
}

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
function getContent($path,$listDir = array()) {
	if($handler = opendir($path)) {
    	while (($sub = readdir($handler)) !== FALSE) {
        	if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != ".htaccess" && $sub != ".DS_Store") {
            	if(is_file($path."/".$sub) && $sub != CONTENT404 && (pathinfo($sub, PATHINFO_EXTENSION) == "md" || pathinfo($sub, PATHINFO_EXTENSION) == "markdown" || pathinfo($sub, PATHINFO_EXTENSION) == "txt")) {
					$listDir[$sub] = parseFile($path,$sub);
				} elseif(is_dir($path."/".$sub)) {
                    $listDir[$sub]=parseDir($path."/".$sub);
                }
            }
        }
        closedir($handler); 
    }
	ksort($listDir);
	//rewrite to an array where addr is the key
	
	$keys = array_keys($listDir); 
	$values = array_values($listDir); 
	foreach ($keys as $k => $v) {
		$keys[$k] = $values[$k]['id'];
	} 
	$listDir = array_combine($keys, $values);
	
    return $listDir;
}
function parseFile($path,$filename)
{
	$filename = $path . "/" . $filename;
	$text = file_get_contents($filename);
	$post = spyc_load(substr($text,0,stripos ($text , "---")));
	if(isset($post['tags'])) {
		$post['tags'] = split(",",$post['tags']);
		foreach($post['tags'] as $i => $tag) {
			$post['tags'][$i] = trim($tag);
		}
	}
	$post['content'] = trim(str_replace('<img src="', '<img src="'.BASE."/".$path."/",Markdown(substr($text,stripos ($text , "---")+3))));
	$post['id'] = cleanURL($post['title'],true);
	if(!isset($post['type']))
		$post['type']=DEFAULT_TYPE;
	//$post['link'] = $post['id'];//TODO
	return $post;
}
function parseDir($dirname)
{
	$files = array();
	if($dirHandler = opendir($dirname)) {   
		while (($sub = readdir($dirHandler)) !== FALSE) {
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != ".htaccess" && $sub != ".DS_Store") {
if(is_file($dirname."/".$sub) && $sub != CONTENT404 && (pathinfo($sub, PATHINFO_EXTENSION) == "md" || pathinfo($sub, PATHINFO_EXTENSION) == "markdown" || pathinfo($sub, PATHINFO_EXTENSION) == "txt")) {
					$files[$sub] = parseFile($dirname,$sub,"");
					//echo $path."/".$sub;
				}
            }
        }
        closedir($dirHandler);
	}
	ksort($files);
	reset($files);
	$key = key($files);
	$first = $files[$key];
	//$files.
	//$post = array();
	$post['title']=$first['title'];
	$post['type']=$first['type'];
	$post['id']= cleanURL($post['title'],true);
	$children = array_slice($files,1);
	$keys = array_keys($children);
	$values = array_values($children); 
	foreach ($keys as $k => $v) { 
		$values[$k]['id']=$post['id']."/".$values[$k]['id'];
		$keys[$k] = $values[$k]['id'];
	}
	$children = array_combine($keys, $values);	
	//process type
	//plugin
	$post['children']=$children;
	
	
	//link depending on type! [aha]
	
	return $post;
}
function arr2nav($array) {
    $nav = array();
	$nav['items'] = array();
	$id=0;
    foreach($array as $key => $elem) {
		$navElem = array();
		$navElem['link'] = $elem['id'];
		$navElem['title'] = $elem['title'];
		if(sizeOf($elem['children']) > 0 ) {
			$children = array();
				$jd=0;
				foreach($elem['children'] as $child) {
					$ne = array();
					$ne['link'] = $child['id'];
					$ne['title'] = $child['title'];
					$children[$jd] = array();
					$children[$jd]['item']=$ne;
					$jd++;
				}
			$navElem['hasChildren']=true;
			$navElem['children']=$children;
		}
		else
			$navElem['hasChildren']=false;
		$nav['items'][$id] = array();
		$nav['items'][$id]['item'] = $navElem;
		$id++;
    }
    return $nav;
}
if (!function_exists('array_combine')) { // ONLY EXISTS IN PHP5 
    function array_combine($keys, $values) { 
        if (count($keys) != count($values)) { 
    return false; } 
        foreach($keys as $key) { $array[$key] = array_shift($values); } 
    return $array; }    
} // END IF FUNCTION EXISTS
?>