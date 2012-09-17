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
/*$nav = '{
	items:[
	item: {title:"tytul1",link:"http://link1"},
	item: {title:"tytul2",link:"http://link2",
			 children:[
					item:{title:"childtytul1",link:"http://childlink1"},
					item:{title:"childtytul2",link:"http://childlink2"}
					 ]
			}
	]
}';*/
$nav = array(
    'foo' => array(
        'bar' => array(
            'baz' => 'qux',
        ),
    ),
);
if($cached)
{
	//show cached version
}
else
{
	$pageVars = array();
	$pageVars['page_title'] = TITLE;
	$pageVars['base']=BASE;
	$pageVars['nav'] = $nav; // arr2nav($arr);
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
		$pageVars['content'] = "content to get for that path";	
	}
	else //404
	{
		$four04 = parseFile(CONTENT."/".CONTENT404);
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
	//przepisanie do tablicy gdzie kluczem jest addr
	
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
	return $post;
}
function parseDir($dirname)
{
	//make array of files, sort, get first file - analyze... //title //type
	$files = array();
	if($dirHandler = opendir($dirname)) {   
		while (($sub = readdir($dirHandler)) !== FALSE) {
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != ".htaccess" && $sub != ".DS_Store") {
if(is_file($dirname."/".$sub) && $sub != CONTENT404 && (pathinfo($sub, PATHINFO_EXTENSION) == "md" || pathinfo($sub, PATHINFO_EXTENSION) == "markdown" || pathinfo($sub, PATHINFO_EXTENSION) == "txt")) {
					$files[$sub] = parseFile($dirname,$sub);
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
	$files.
	//$post = array();
	$post['title']=$first['title'];
	$post['type']=$first['type'];
	$children = array_slice($files,1);
	$keys = array_keys($children);
	$values = array_values($children); 
	foreach ($keys as $k => $v) { 
		$keys[$k] = $values[$k]['id'];
	}
	$children = array_combine($keys, $values);	
	//process type
	//plugin
	$post['children']=$children;
	$post['id']= cleanURL($post['title'],true);
	return $post;
}
//TARGET NAV STRUCTURE
/*
'{
	"items":[
	"item": {"title":"tytul1","link":"http://link1"},
	"item": {"title":"tytul2","link":"http://link2","
			 children":[
					"item":{"title":"childtytul1","link":"http://childlink1"},
					"item":{"title":"childtytul2","link":"http://childlink2"}
					 ]
			}
	]
}'


*/
//crap to throw away(so ugly):
function arr2nav($array) { //this whole mess to templates //it should generate json with pairs link:title and children
    $out='<ul>'."\n";
    foreach($array as $key => $elem) {
        //$out.='<li>'.$elem['title'];
		//$out.=sizeOf($elem['children']);
		if( sizeOf($elem['children']) > 0 )
		{
			$out.='<li><span class="section-title">'.$elem['title'] ."</span>";
			$out.= arr2nav($elem['children'])."\n";
			$out.= '</li>'."\n";
		}
		else
			$out.="<li>".$elem['title']."</li>";
    }
    $out=$out.'</ul>'."\n";
    return $out;
}
if (!function_exists('array_combine')) { // ONLY EXISTS IN PHP5 
    function array_combine($keys, $values) { 
        if (count($keys) != count($values)) { 
    return false; } 
        foreach($keys as $key) { $array[$key] = array_shift($values); } 
    return $array; }    
} // END IF FUNCTION EXISTS
?>