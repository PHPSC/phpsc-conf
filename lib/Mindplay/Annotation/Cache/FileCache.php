<?php
namespace Mindplay\Annotation\Cache;

use \Mindplay\Annotation\Core\AnnotationException;
use \ReflectionClass;

class FileCache implements CacheStorage
{
	/**
	 * @var string The PHP opening tag (used when writing cache files)
	 */
	const PHP_TAG = "<?php\n";

	/**
	 * @var int The file mode used when creating cache files
	 */
	public $fileMode;

	/**
	 * @var string Absolute path to a folder where cache files may be saved
	 */
	public $path;

	/**
	 * @var string Cache seed (can be used to disambiguate, if using multiple AnnotationManager instances with the same $cachePath)
	 */
	public $seed;

	/**
	 * @param string $path
	 * @param string $seed
	 * @param int $fileMode
	 */
	public function __construct($path, $seed = '', $fileMode = 0777)
	{
		$this->path = $path;
		$this->seed = $seed;
		$this->fileMode = $fileMode;
	}

	/**
	 * Returns if the identifier exists on the storage
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function exists($id)
	{
		return file_exists($this->resolveCacheFile($id));
	}

	/**
	 * Stores the content
	 *
	 * @param string $id
	 * @param string $content
	 */
	public function store($id, $content)
	{
		$file = $this->resolveCacheFile($id);

		if (@file_put_contents($file, self::PHP_TAG . $content, LOCK_EX) == false || @chmod($file, $this->fileMode) == false) {
			throw new AnnotationException(__METHOD__ . ' : error writing cache file ' . $file);
		}
	}

	/**
	 * Retrieves the content
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function get($id)
	{
		return include $this->resolveCacheFile($id);
	}

	/**
	 * Returns the last change time
	 *
	 * @param string $id
	 * @return int
	 */
	public function getLastChangeTime($id)
	{
		return filemtime($this->resolveCacheFile($id));
	}

	/**
	 * Creates an ID for the storage from class name
	 *
	 * @param string $className
	 * @return string
	 */
	public function createId(ReflectionClass $class)
	{
		$path = $class->getFileName();

		return basename($path) . '-' . sprintf('%x', crc32($path . $this->seed)) . '.annotations.php';
	}

	/**
	 * @param string $id
	 * @return string
	 */
	protected function resolveCacheFile($id)
	{
		return $this->path . DIRECTORY_SEPARATOR . $id;
	}
}