<?php
namespace Lcobucci\Common\DependencyInjection;

use \Symfony\Component\Config\FileLocator;
use \Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class XmlContainerBuilder
{
	/**
	 * @param string $file
	 * @param array $path
	 * @return \Symfony\Component\DependencyInjection\ContainerBuilder
	 */
	public static function build($file, array $path = array())
	{
		$builder = new self();
		$file = realpath($file);

		return $builder->getContainer($file, $path);
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
			$container = new ContainerBuilder();
			$this->getLoader($container, $path)->load($file);

			$this->createDump($container, $dumpClass);
		} else {
			$container = $this->loadFromDump($dumpClass);
		}

		return $container;
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
		return sys_get_temp_dir() . '/' . $className . '.php';
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
	protected function createDump(ContainerBuilder $container, $className)
	{
		$dumper = new PhpDumper($container);
		$dumpFile = $this->getDumpFileName($className);

		file_put_contents(
			$dumpFile,
			$dumper->dump(array('class' => $className))
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
	protected function getLoader(ContainerBuilder $container, array $path)
	{
		return new XmlFileLoader(
			$container,
			new FileLocator($path)
		);
	}
}
