<?php
// Author http://stackoverflow.com/users/1714705/jasper
// Source http://stackoverflow.com/questions/1971721/how-to-use-http-cache-headers-with-php

namespace ldbglobe\tools\Http;

if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = [];
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
}

class Cache {

	public static function Init($lastModifiedTimestamp, $maxAge)
	{
		if (self::IsModifiedSince($lastModifiedTimestamp))
		{
			self::SetLastModifiedHeader($lastModifiedTimestamp, $maxAge);
		}
		else
		{
			self::SetNotModifiedHeader($maxAge);
		}
	}

	private static function IsModifiedSince($lastModifiedTimestamp)
	{
		$allHeaders = getallheaders();

		if (array_key_exists("If-Modified-Since", $allHeaders))
		{
			$gmtSinceDate = $allHeaders["If-Modified-Since"];
			$sinceTimestamp = strtotime($gmtSinceDate);

			// Can the browser get it from the cache?
			if ($sinceTimestamp != false && $lastModifiedTimestamp <= $sinceTimestamp)
			{
				return false;
			}
		}

		return true;
	}

	private static function SetNotModifiedHeader($maxAge)
	{
		// Set headers
		header("HTTP/1.1 304 Not Modified", true);
		header("Cache-Control: public, max-age=$maxAge", true);
		header("Expires: ".gmdate('D, d M Y H:i:s', time() + 3600*24*30)." GMT", true);
		header("Pragma: cache", true);
		die();
	}

	private static function SetLastModifiedHeader($lastModifiedTimestamp, $maxAge)
	{
		// Fetching the last modified time of the XML file
		$date = gmdate("D, j M Y H:i:s", $lastModifiedTimestamp)." GMT";

		// Set headers
		header("HTTP/1.1 200 OK", true);
		header("Cache-Control: public, max-age=$maxAge", true);
		header("Last-Modified: $date", true);
		header("Expires: ".gmdate('D, d M Y H:i:s', time() + 3600*24*30)." GMT", true);
		header("Pragma: cache", true);
	}

	public static function Expire($maxAge)
	{
		// set a simple expire cache control (no serveur validation)
		header("HTTP/1.1 200 OK", true);
		header("Cache-Control: public, max-age=$maxAge", true);
		header("Expires: ".gmdate('D, d M Y H:i:s', time() + $maxAge)." GMT", true);
		header("Pragma: cache", true);
	}

}