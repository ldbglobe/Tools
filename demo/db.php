<?php
// Database connection and initialisation
// ldbglobe\tools\Entity is base on https://j4mie.github.io/idiormandparis/

$dbfile = __DIR__.'/sample.db';
if(!file_exists($dbfile))
{
	ORM::configure('sqlite:'.$dbfile);
	$db = ORM::get_db();
	$db->query("CREATE TABLE contact (id Varchar, name Varchar);");
}
else
{
	ORM::configure('sqlite:'.$dbfile);
}
