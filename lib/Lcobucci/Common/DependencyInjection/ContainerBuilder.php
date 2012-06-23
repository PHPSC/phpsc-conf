<?php
namespace Lcobucci\Common\DependencyInjection;

use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use \Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyBuilder;

class ContainerBuilder
{
    /**
     * @var string
     */
    protected $baseClass;

    /**
     * @var string
     */
    protected $cacheDirectory;

	/**
	 * @param string $file
	 * @param array $path
	 * @return \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	public static function build(
        $file,
        array $path = array(),
        $baseClass = null,
        $cacheDirectory = null
    ) {
		$builder = new static($baseClass, $cacheDirectory);

		return $builder->getContainer(realpath($file), $path);
	}

	/**
	 * @param string $baseClass
	 * @param string $cacheDirectory
	 */
	public function __construct($baseClass = null, $cacheDirectory = null)
	{
	    if ($baseClass !== null) {
	        $this->baseClass = $baseClass;
	    }

	    if ($cacheDirectory === null) {
	        $this->cacheDirectory = sys_get_temp_dir();

	        return ;
	    }

	    $this->cacheDirectory = rtrim($cacheDirectory, '/');
	}

	/**
	 * @param string $file
	 * @param array $path
	 * @return \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	public function getContainer($file, array $path = array())
	{
		$dumpClass = $this->createDumpClassName($file);

		if ($this->hasToCreateDumpClass($file, $dumpClass)) {
			$container = new SymfonyBuilder();

			$this->getLoader($container, $path)->load($file);
			$this->createDump($container, $dumpClass);
		}

		return $this->loadFromDump($dumpClass);
	}

	/**
	 * @param string $file
	 * @return string
	 */
	protected function createDumpClassName($file)
	{
		return 'Project' . md5($file) . 'ServiceContainer';
	}

	/**
	 * @param string $className
	 * @return string
	 */
	protected function getDumpFileName($className)
	{
		return $this->cacheDirectory . '/' . $className . '.php';
	}

	/**
	 * @param string $file
	 * @param string $className
	 * @return boolean
	 */
	protected function hasToCreateDumpClass($file, $className)
	{
		$dumpFile = $this->getDumpFileName($className);

		if (file_exists($dumpFile) && filemtime($dumpFile) >= filemtime($file)) {
			return false;
		}

		return true;
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @param string $className
	 */
	protected function createDump(SymfonyBuilder $container, $className)
	{
	    $config = array('class' => $className);

	    if ($this->baseClass !== null) {
	        $config['base_class'] = $this->baseClass;
	    }

		$dumper = new PhpDumper($container);
		$dumpFile = $this->getDumpFileName($className);

		file_put_contents(
			$dumpFile,
			$dumper->dump($config)
		);

		chmod($dumpFile, 0777);
	}

	/**
	 * @param string $className
	 * @return \Symfony\Component\DependencyInjection\Container
	 */
	protected function loadFromDump($className)
	{
		require_once $this->getDumpFileName($className);
		$className = '\\' . $className;

		return new $className();
	}

	/**
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \Symfony\Component\DependencyInjection\Loader\XmlFileLoader
	 */
	protected function getLoader(SymfonyBuilder $container, array $path)
	{
		return new XmlFileLoader(
			$container,
			new FileLocator($path)
		);
	}
}