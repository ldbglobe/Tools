<?php
// feel free to tweak thoses helpers to handle additionnal url element
// eg. language code injection in url
// don't forget to update your routes definition accordingly
function page_link($page,$query=null)
{
	return BASEPATH
		.'/'.$page
		.($query!==null ? '?'.(is_string($query) ? $query:http_build_query($query)) : '')
		;
}
function page_go($page,$query=null)
{
	header('location:'.page_link($page,$query));
	exit();
}