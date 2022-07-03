<?php

/**
 * Replace a given string within a given file.
 *
 * @param  string  $search
 * @param  string  $replace
 * @param  string  $path
 * @return void
 */
if (! function_exists('replaceInFile')) {
    function replaceInFile($search, $replace, $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}

if (! function_exists('putInString')) {
    function putInString($str, $insert, $pos): string
    {
        return substr($str, 0, $pos) . $insert . substr($str, $pos);
    }
}


if (! function_exists('convertUploadedFileSizeToHumanReadable')) {
	function convertUploadedFileSizeToHumanReadable($size, $precision = 2): string
	{
		if ( $size > 0 ) {
			$size = (int) $size;
			$base = log($size) / log(1024);
			$suffixes = array(' bytes', ' KB', ' MB', ' GB', ' TB');
			return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
		}
		return $size;
	}
}




