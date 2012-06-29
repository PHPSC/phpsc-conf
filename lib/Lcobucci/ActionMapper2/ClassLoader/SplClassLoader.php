<?php
namespace Lcobucci\ActionMapper2\ClassLoader;

require 'Lcobucci/Common/ClassLoader/SplClassLoader.php';

use \Doctrine\Common\Annotations\AnnotationRegistry;

class SplClassLoader extends \Lcobucci\Common\ClassLoader\SplClassLoader
{
    /**
     * @see \Lcobucci\Common\ClassLoader\SplClassLoader::register()
     */
    public function register()
    {
        parent::register();
        AnnotationRegistry::registerLoader(array($this, 'loadClass'));
    }

    /**
     * @see \Lcobucci\Common\ClassLoader\SplClassLoader::loadClass()
     */
    public function loadClass($className)
    {
        return parent::loadClass(ltrim($className, '\\'));
    }
}