<?php
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