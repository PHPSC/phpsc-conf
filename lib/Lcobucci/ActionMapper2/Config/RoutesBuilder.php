<?php
namespace Lcobucci\ActionMapper2\Config;

use Lcobucci\ActionMapper2\Routing\RouteDefinitionCreator;

use Lcobucci\ActionMapper2\Routing\RouteManager;

use \stdClass;

class RoutesBuilder
{
    /**
     * @var string
     */
    protected $cacheDirectory;

    /**
     * @param string $cacheDirectory
     */
    public function __construct($cacheDirectory = null)
    {
        if ($cacheDirectory !== null) {
            $this->cacheDirectory = rtrim($cacheDirectory, '/');
            return ;
        }

        $this->cacheDirectory = sys_get_temp_dir();
    }

    /**
     * @param string $fileName
     * @return \Lcobucci\ActionMapper2\Routing\RouteManager
     */
    public function build($fileName)
    {
        $cacheFile = $this->getCachePath(
            $this->createCacheName($fileName)
        );

        if ($this->hasToCreateCache($fileName, $cacheFile)) {
            $metadata = $this->createMetadata($fileName, $cacheFile);
        } else {
            $metadata = $this->loadMetadata($cacheFile);
        }

        return $this->createManager($metadata);
    }

    /**
     * @param \stdClass $metadata
     * @return \Lcobucci\ActionMapper2\Routing\RouteManager
     */
    protected function createManager(stdClass $metadata)
    {
        if (isset($metadata->definitionBaseClass)) {
            RouteDefinitionCreator::setBaseClass(
                $metadata->definitionBaseClass
            );
        }

        $manager = new RouteManager();

        foreach ($metadata->routes as $route) {
            $manager->addRoute($route->pattern, $route->handler);
        }

        if (isset($metadata->filters)) {
            foreach ($metadata->filters as $filter) {
                $manager->addFilter(
                    $filter->pattern,
                    $filter->handler,
                    $filter->before
                );
            }
        }

        return $manager;
    }

	/**
	 * @param string $fileName
	 * @return string
	 */
	protected function createCacheName($fileName)
	{
		return 'Project' . md5($fileName) . 'Routes';
	}

	/**
	 * @param string $cacheName
	 * @return string
	 */
	protected function getCachePath($cacheName)
	{
		return $this->cacheDirectory . '/' . $cacheName . '.json';
	}

	/**
	 * @param string $fileName
	 * @param string $cacheFile
	 * @return boolean
	 */
	protected function hasToCreateCache($fileName, $cacheFile)
	{
		if (file_exists($cacheFile) && filemtime($cacheFile) >= filemtime($fileName)) {
			return false;
		}

		return true;
	}

	/**
	 * @param string $fileName
	 * @param string $cacheFile
	 * @return \stdClass
	 */
	protected function createMetadata($fileName, $cacheFile)
	{
        $loader = new XmlRoutesLoader();
        $metadata = $loader->load($fileName);

        $this->saveMetadata($cacheFile, $metadata);

        return $metadata;
	}

	/**
	 * @param string $cacheName
	 */
	protected function saveMetadata($cacheFile, stdClass $metadata)
	{
		file_put_contents(
			$cacheFile,
			json_encode($metadata)
		);

		chmod($cacheFile, 0777);
	}

	/**
	 * @param string $cacheFile
	 * @return \stdClass
	 */
	protected function loadMetadata($cacheFile)
	{
	    return json_decode(file_get_contents($cacheFile));
	}
}