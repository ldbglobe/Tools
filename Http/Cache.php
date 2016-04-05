<?php
namespace ldbglobe\tools\Http;

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
	}
}