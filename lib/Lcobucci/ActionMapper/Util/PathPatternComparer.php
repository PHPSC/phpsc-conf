<?php
namespace Lcobucci\ActionMapper\Util;

class PathPatternComparer
{
	/**
	 * @param string $uri
	 * @param string $pattern
	 * @return boolean
	 */
	public static function patternMatches($path, $pattern)
	{
		return preg_match(self::patternToRegex($pattern), $path) == 1;
	}

	/**
	 * @param string $uri
	 * @return string
	 */
	public static function patternToRegex($pattern)
	{
		$regex = str_replace('.', '\\.', $pattern);
		$regex = str_replace(array('*', '/'), array('.*', '\\/'), $regex);

		return '/^' . $regex . '$/';
	}
}