<?php
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