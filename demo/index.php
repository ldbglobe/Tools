<?php
session_start();
require_once(__DIR__.'/vendor/autoload.php');
require_once(__DIR__.'/helpers.php');

# Install PSR-0-compatible autoloader for custom classes
spl_autoload_register(function($class){
	$file = __DIR__.'/classes/'.preg_replace('{\\\\|_(?!.*\\\\)}', DIRECTORY_SEPARATOR, ltrim($class, '\\')).'.php';
	if(file_exists($file))
		require_once($file);
	else
		return false;
});

use \ldbglobe\tools\Page;
use \ldbglobe\tools\PageComponent;
use \ldbglobe\tools\Http\Request;

Page::$pageRoot = __DIR__.'/pages';
PageComponent::$componentRoot = __DIR__.'/components';

try {
	// Request object available in the Page
	$request = Request::createFromGlobals();

	if(!defined('ROOTPATH')) define('ROOTPATH', realpath(__DIR__));
	if(!defined('BASEPATH')) define('BASEPATH',$request->getBaseUrl());
	if(!defined('ENVPATH')) define('ENVPATH',$request->getPathInfo());
	if(!defined('ENVQUERY')) define('ENVQUERY',$request->getQueryString());

	// routes extraction (using symfony/routing library)
	$route = require(__DIR__.'/routes.php');

	// page load if available
	$page = (new Page($route,$request));
	if($route && $page->exist())
	{
		require(__DIR__.'/db.php');
		$page->flush();
	}
	else if(!$page->exist())
	{
		die('Expected page source does not exists!');
	}
}
catch (Exception $e)
{
	?>
	<h2>Exception</h2>
	<pre><?=$e->getMessage();?></pre>
	<?php
	$code = $e->getCode();
	$traces = $e->getTrace();
	foreach($traces as $trace)
	{
		if(isset($trace['file']))
		{
			$uid = 'section'.uniqid();
			echo '<pre>';
			echo '<strong>'.$trace['file'].' @ line '.$trace['line'].'</strong>'."\n";
			echo isset($trace['class']) ? $trace['class'].$trace['type'].$trace['function']."\n":'<strong>Function</strong> : '.$trace['function'].'()'."\n";
			echo isset($trace['args']) ? '<strong>Parameters</strong> :'."\n".print_r($trace['args'],1):'';
		}
	}
}