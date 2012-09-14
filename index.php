<?
  //Good Morning, We are all going to die!
  //graphite CMS v 0.0.4.5
  //&copy; 2012 golinski.org
  define("TITLE","graphiteCMS v.0.4.5");
  define("CONTENT404", "{'title':'404','content':'This is not the page you are looking for.'}");//? 
  /* ----- you probably shouldn't touch anything below ----- */
  define("TEMPLATE", "template");//templates dir
  define("CONTENT","content"); //content files dir
  define("VERSION",	0.045); //version
  define("BASE", trim(dirname($_SERVER['PHP_SELF']),"/")); //for redirects - graphite?
  include 'system/engine.php'; //go go go!
?>