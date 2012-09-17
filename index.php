<?
  //Good Morning, We are all going to die!
  //graphite CMS v 0.0.4.9
  //&copy; 2012 golinski.org
  define("TITLE","graphiteCMS v.0.4.9");
  define("CONTENT404", "404.md");
  define("DATE_FORMAT","j.n.Y");
  define("MAX_PAGE_COUNT",20);
  /* ----- you probably shouldn't touch anything below ----- */

  define("DEFAULT_TYPE","post"); //default page type
  define("TEMPLATE", "template");//templates dir
  define("CONTENT","content"); //content files dir
  define("VERSION",	0.049); //version
  define("PATH",trim(dirname($_SERVER['PHP_SELF']),"/"));
  define("BASE", (strlen(PATH)>0 ? "/":"") . PATH );
  include 'system/engine.php'; //go go go!
?>