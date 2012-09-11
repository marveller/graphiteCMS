<?
  //Good Morning, We are all going to die!
  //graphite CMS v 0.43
  //&copy; 2012 golinski.org
  define("TITLE","graphiteCMS v.043");
  define("CONTENT404", "{'title':'404','content':'This is not the page you are looking for.'}");//?
  
  //define dateformat 4 blogs etc.
  //types
  //
  
  /* ----- you probably shouldn't touch anything below ----- */
  define("TEMPLATE", "template");//templates dir
  define("CONTENT","content"); //content files dir
  define("VERSION",	0.43); //version
  define("BASE", dirname($_SERVER['PHP_SELF'])); //for redirects
  include 'system/engine.php'; //go go go!

/* .htaccess:
	RewriteEngine On
	RewriteBase /
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule .* index.php [L,QSA]
*/
?>