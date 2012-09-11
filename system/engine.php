<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
</head>
<body>
<?php

defined('VERSION') or die('No direct script access.');
require 'lib/Mustache/Autoloader.php';
include_once "lib/markdown.php";
include('lib/spyc.php');
Mustache_Autoloader::register();
$mustache = new Mustache_Engine(array('loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__).'/../'.TEMPLATE)));
$path = CONTENT . "/";
/*
$directory = opendir($path);
$posts = array();
$allTags = array();
while($entryname = readdir($directory))//for every element in directory
{
	//directory structure to JSON
	//recursive
	//
	if($entryname!="." && $entryname!="..")
	{
		echo $entryname . "<br/>";
		//(pathinfo($entryname, PATHINFO_EXTENSION) == "md" || pathinfo($entryname, PATHINFO_EXTENSION) == "markdown" || pathinfo($entryname, PATHINFO_EXTENSION) == "txt")
		$text  =  file_get_contents($path . $entryname);
		$post = spyc_load(substr($text,0,stripos ($text , "---")));
		$post['filename']=$entryname;
		//photo in subdir?
		$post['content']=trim(str_replace('<img src="', '<img src="content/',Markdown(substr($text,stripos ($text , "---")+3))));
		$post['id'] = str_replace(" ","-",strtolower($post['title']));
		$post['class']="page";//chyba ze katalog
		$posts[$post['filename']]=$post;
		//allTags append;
	}
}
closedir($directory);
*/
$arr = getDirectory($path);
ksort($arr);//verify:)
print_r($arr);
//echo json_encode($arr);

//$allTags = array_unique($allTags);
//print_r($allTags);

/*
$postTpl = $mustache->loadTemplate('post');
$pageTpl = $mustache->loadTemplate('page');
$page = array();
$page['nav'] = array('raz','dwa','trzy');
foreach ($posts as &$post) {
	$page['posts'].=$postTpl->render($post);
}
$page['posts']=rtrim($page['posts']);
//print_r($page);
//echo $pageTpl->render($page) . "\n";
*/
function getDirectory($path,$listDir = array()) {
    //$listDir = array();
    if($handler = opendir($path)) {
        while (($sub = readdir($handler)) !== FALSE) {
            if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != ".htaccess" && $sub != ".DS_Store") {
                if(is_file($path."/".$sub) && 
				   (pathinfo($sub, PATHINFO_EXTENSION) == "md" ||
				   pathinfo($sub, PATHINFO_EXTENSION) == "markdown" ||
				   pathinfo($sub, PATHINFO_EXTENSION) == "txt")) {
                    //post! 
					$text  =  file_get_contents($path ."/". $sub);
					$post = spyc_load(substr($text,0,stripos ($text , "---")));
					$post['content']=trim(str_replace('<img src="', '<img src="content/',Markdown(substr($text,stripos ($text , "---")+3))));//so baaad!
					$post['id'] = cleanURL($post['title'],true);
					$listDir[$sub] = $post;
                } elseif(is_dir($path."/".$sub)) {
					//directory - view!
                    $listDir[$sub] = getDirectory($path."/".$sub); 
                }
            }
        }    
        closedir($handler); 
    } 
    return $listDir;    
}
function cleanURL( $sText, $tolower)
{
		  $sText = html_entity_decode($sText);
          $aFind = array('ć','Ć','ś','Ś','ą','Ą','ż','Ż','ó','Ó','ł','Ł','ś','Ś','ź','Ź','ń','Ń','ę','Ę', " ");
		  $aReplace = array('c','C','s','S','a','A','z','Z','o','O','l','L','s','S','z','Z','n','N','e','E', "-");
		  $sOK = "abcdefghijklmnopqrstuvwxyz";
          $sOK .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		  $sOK .= "-_()[]0123456789";
 
          $sText = str_replace($aFind, $aReplace, $sText);
          $sTextN = "";
          for ( $i = 0; $i < strlen($sText); $i++ )
          {
                // if ( strpos($sOK,$sText[$i]) === false )
                 //   $sTextN .= "";
               //else
                    $sTextN .= $sText[$i];
          }
 		  if($tolower)
          	return strtolower($sTextN);
		  else
			return $sTextN;
}
?>
</body>