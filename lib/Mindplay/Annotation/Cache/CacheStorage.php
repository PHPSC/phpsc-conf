<?php
namespace Mindplay\Annotation\Cache;

use \ReflectionClass;

interface CacheStorage
{
	/**
	 * Returns if the identifier exists on the storage
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function exists($id);

	/**
	 * Stores the content
	 *
	 * @param string $id
	 * @param string $content
	 */
	public function store($id, $content);

	/**
	 * Retrieves the content
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function get($id);

	/**
	 * Returns the last change time
	 *
	 * @param string $id
	 * @return int
	 */
	public function getLastChangeTime($id);

	/**
	 * Creates an ID for the storage from class name
	 *
	 * @param \ReflectionClass $className
	 * @return string
	 */
	public function createId(ReflectionClass $class);
}