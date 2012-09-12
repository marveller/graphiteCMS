<?php function getDirectory($path,$listDir = array()) {
	if($handler = opendir($path)) {
    	while (($sub = readdir($handler)) !== FALSE) {
        	if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != ".htaccess" && $sub != ".DS_Store") {
            	if(is_file($path."/".$sub) && 
				  (pathinfo($sub, PATHINFO_EXTENSION) == "md" ||
				   pathinfo($sub, PATHINFO_EXTENSION) == "markdown" ||
				   pathinfo($sub, PATHINFO_EXTENSION) == "txt")) {
                    	$text  =  file_get_contents($path ."/". $sub);
						//echo($path ."/". $sub . ",");
						$post = spyc_load(substr($text,0,stripos ($text , "---")));
						$post['content']=trim(str_replace('<img src="', '<img src="'.BASE."/".$path."/",Markdown(substr($text,stripos ($text , "---")+3))));
						$post['id'] = cleanURL($post['title'],true);
						$listDir[$sub] = $post;
                } elseif(is_dir($path."/".$sub)) {
					//directory - view(?)
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
	$sOK  = "abcdefghijklmnopqrstuvwxyz";
    $sOK .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$sOK .= "-_()[]0123456789";

	$sText = str_replace($aFind, $aReplace, $sText);
	$sTextN = "";
	for( $i = 0; $i < strlen($sText); $i++ )
    {
    	if ( strpos($sOK,$sText[$i]) === false )
        	$sTextN .= "";
        else
            $sTextN .= $sText[$i];
    }
	if($tolower)
	    return strtolower($sTextN);
	else
		return $sTextN;
}
?>