<?php
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

/*
http://symfony.com/doc/current/components/routing/introduction.html
$route = new Route(
    '/archive/{month}', // path
    array('controller' => 'showArchive'), // default values
    array('month' => '[0-9]{4}-[0-9]{2}', 'subdomain' => 'www|m'), // requirements
    array(), // options
    '{subdomain}.example.com', // host
    array(), // schemes
    array() // methods
);
*/

// routes declaration

$routes->add('test', new Route(
		'/test/{param}',
		array('_page' => 'test','param'=>'default-route-param'), // default value
		array(
			'rubrique'=>'[a-zA-Z_-]+',
			), // requirements
		array(),
		null,
		array(),
		array('GET','POST'))
	);


$routes->add('index', new Route(
		'/',
		array('_page' => 'index'), // default value
		array(), // requirements
		array(),
		null,
		array(),
		array('GET','POST'))
	);

$context = new RequestContext($request);
$matcher = new UrlMatcher($routes, $context);

try {
	$parameters = $matcher->match($request->getPathInfo());
	return (object)$parameters;
}
catch (Exception $e)
{
	return (object)array('_page'=>404);
}
