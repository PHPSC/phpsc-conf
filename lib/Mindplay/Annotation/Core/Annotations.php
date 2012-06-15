<?php

/*
 * This file is part of the php-annotation framework.
 *
 * (c) Rasmus Schultz <rasmus@mindplay.dk>
 *
 * This software is licensed under the GNU LGPL license
 * for more information, please see:
 *
 * <http://code.google.com/p/php-annotations>
 */

namespace Mindplay\Annotation\Core;

use Mindplay\Annotation\Core\AnnotationException;

/**
 * Thin, static class with shortcut methods for inspection of Annotations
 */
abstract class Annotations
{
	/**
	 * @var array Configuration for any public property of AnnotationManager.
	 */
	public static $config;

	/**
	 * @var AnnotationManager Singleton AnnotationManager instance
	 */
	private static $manager;

	/**
	 * @return AnnotationManager a singleton instance
	 */
	public static function getManager()
	{
		if (!isset(self::$manager)) {
			self::$manager = new AnnotationManager();
		}

		if (is_array(self::$config)) {
			foreach (self::$config as $key => $value) {
				self::$manager->$key = $value;
			}
		}

		return self::$manager;
	}

	/**
	 * Returns the UsageAnnotation for the annotation with the given class-name.
	 * @see AnnotationManager::getUsage()
	 */
	public static function getUsage($class)
	{
		return self::getManager()->getUsage($class);
	}

	/**
	 * Inspects class Annotations
	 * @see AnnotationManager::getClassAnnotations()
	 */
	public static function ofClass($class, $type = null)
	{
		return self::getManager()->getClassAnnotations($class, $type);
	}

	/**
	 * Inspects method Annotations
	 * @see AnnotationManager::getMethodAnnotations()
	 */
	public static function ofMethod($class, $method = null, $type = null)
	{
		return self::getManager()->getMethodAnnotations($class, $method, $type);
	}

	/**
	 * Inspects property Annotations
	 * @see AnnotationManager::getPropertyAnnotations()
	 */
	public static function ofProperty($class, $property = null, $type = null)
	{
		return self::getManager()->getPropertyAnnotations($class, $property, $type);
	}

	/**
	 * Creates a new annotation alias
	 *
	 * @param string $alias
	 * @param string $class
	 * @throws Mindplay\Annotation\Core\AnnotationException
	 */
	public static function addAlias($alias, $class)
	{
	    $manager = self::getManager();

	    $alias = lcfirst($alias);

	    if (isset($manager->registry[$alias])) {
	        throw new AnnotationException('This alias is alredy being used.');
	    }

        self::getManager()->registry[$alias] = $class;
	}
}
