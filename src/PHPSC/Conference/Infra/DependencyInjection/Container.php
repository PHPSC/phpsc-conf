<?php
namespace PHPSC\Conference\Infra\DependencyInjection;

use \Doctrine\Common\Cache\ArrayCache;
use \Doctrine\Common\Cache\ApcCache;
use \Doctrine\ORM\Configuration;
use \Doctrine\ORM\EntityManager;

class Container extends \Lcobucci\ActionMapper2\DependencyInjection\Container
{
    /**
     * Gets the 'entityManager' service.
     *
     * @return \Symfony\Component\HttpFoundation\Session
     */
    protected function getEntityManagerService()
    {
        return $this->services['entitymanager'] = EntityManager::create(
            array(
                'host' => $this->getParameter('db.host'),
                'dbname' => $this->getParameter('db.schema'),
                'user' => $this->getParameter('db.user'),
                'password' => $this->getParameter('db.password'),
                'driver' => $this->getParameter('db.driver'),
                'charset' => $this->getParameter('db.charset')
            ),
            $this->get('doctrine.config')
        );
    }

    /**
     * @return \Doctrine\ORM\Configuration
     */
    protected function getDoctrine_ConfigService()
    {
        $this->services['doctrine.config'] = $instance = new Configuration();

        $baseDir = realpath(__DIR__ . '/../../../../../') . '/';
        $cache = $this->getParameter('doctrine.cache') == 'apc'
                 ? new ApcCache()
                 : new ArrayCache();

        $instance->setMetadataCacheImpl($cache);
        $instance->setQueryCacheImpl($cache);
        $instance->setResultCacheImpl($cache);

        $instance->setProxyDir(
            $baseDir . $this->getParameter('doctrine.proxy.dir')
        );

        $instance->setProxyNamespace(
            $this->getParameter('doctrine.proxy.namespace')
        );

        $instance->setAutoGenerateProxyClasses(
            $this->getParameter('doctrine.proxy.auto')
        );

        $instance->setMetadataDriverImpl(
            $instance->newDefaultAnnotationDriver(
                $baseDir . $this->getParameter('doctrine.entity.dir')
            )
        );

        return $instance;
    }

    /**
     * @see \Symfony\Component\DependencyInjection\Container::getParameter()
     */
    public function getParameter($name)
    {
        if ($name == 'twitter.callback') {
            return $this->application->getRequest()->getUriForPath(
                parent::getParameter($name)
            );
        }

        return parent::getParameter($name);
    }
}