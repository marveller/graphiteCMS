#graphiteCMS v0.4.5

TODO:
*  define dateformat 4 blogs etc.
*  types: page, gallery, view, blog? //if type empty = page
*  analytics
*  black/dark gray semi crystal for logo.
*  subpages
*  404
*  views
*  router (at least some nice urls)
*  tags
*  link active w menu
*  RSS in views
*  Paginacja w viewsach
*  Caching
*  Slir(thumbnailing?)
*  Disqs?
*  If type: -> plugin (include if exists)
*  Template for plugin
*  If !match => 404
*  Dropbox sync
*  if no 0.xxx file in a dir then assume it is a projects directory and link first one
*  what about the stock market?
*  no, seriously... what about the section name when no 0.xxx file? Name of the dir? - worst case scenario
*  secondary menu in template must be visible
*  where to store the images?


##You should include following .htaccess file:

		RewriteEngine On
		RewriteBase /
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule .* index.php [L,QSA]
		
Changing the RewriteBase to proper dir on the server (/ - root)