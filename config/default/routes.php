<?php
/* @var $Rc \FastRoute\RouteCollector */
/* @var $this \Rdb\System\Router */


// front pages routes. ------------------------------------------------------------------------------------
$Rc->addRoute($this->filterMethod('any'), '/', '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Index:index');

// taxonomies.
$Rc->addRoute('GET', '/taxonomies/{t_type:[0-9a-z\-_]+}/{tid:\d+}', '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Taxonomies\\View:index'); // must matched in URLResolverController.

// posts.
$Rc->addRoute('GET', '/posts/{post_type:[0-9a-z\-_]+}/{post_id:\d+}', '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Posts\\View:index'); // must matched in URLResolverController.

// files.
$Rc->addRoute('GET', '/files/{file_id:\d+}', '\\Rdb\\Modules\\RdbCMSF\\Controllers\\Front\\Files\\View:index');
// end front pages routes. -------------------------------------------------------------------------------