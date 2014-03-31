<?php
namespace PHPSC\Conference\Infra\DependencyInjection;

use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Annotations\SimpleAnnotationReader;
use Doctrine\Fixture\Loader\DirectoryLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Lcobucci\Fixture\Persistence\EntityManagerEventSubscriber;

/**
 * @codingStandardsIgnoreFile
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class Container extends \Lcobucci\ActionMapper2\DependencyInjection\Container
{
    /**
     * Gets the 'entityManager' service.
     *
     * @return \Symfony\Component\HttpFoundation\Session
     */
    protected function getEntityManagerService()
    {
        $em = $this->services['entitymanager'] = EntityManager::create(
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

        $em->getConnection()
           ->getDatabasePlatform()
           ->registerDoctrineTypeMapping('enum', 'string');

        return $em;
    }

    /**
     * @return \Doctrine\ORM\Configuration
     */
    protected function getDoctrine_ConfigService()
    {
        $this->services['doctrine.config'] = $instance = new Configuration();

        $baseDir = $this->getParameter('app.baseDir');

        $cache = $this->get('app.cache');
        $instance->setMetadataCacheImpl($cache);
        $instance->setQueryCacheImpl($cache);
        $instance->setResultCacheImpl($cache);

        $instance->setProxyDir($baseDir . $this->getParameter('doctrine.proxy.dir'));
        $instance->setProxyNamespace($this->getParameter('doctrine.proxy.namespace'));
        $instance->setAutoGenerateProxyClasses($this->getParameter('app.devmode'));

        AnnotationRegistry::registerFile(
            $baseDir . 'vendor/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php'
        );

        $reader = new SimpleAnnotationReader();
        $reader->addNamespace('Doctrine\ORM\Mapping');

        $instance->setMetadataDriverImpl(
            new AnnotationDriver(
                new CachedReader($reader, $cache),
                array($baseDir . $this->getParameter('doctrine.entity.dir'))
            )
        );

        return $instance;
    }

    /**
     * @return \Doctrine\Fixture\Configuration
     */
    protected function getFixtures_ConfigService()
    {
        $config = new \Doctrine\Fixture\Configuration();

        $config->getEventManager()->addEventSubscriber(
            new EntityManagerEventSubscriber($this->get('entityManager'))
        );

        return $config;
    }

    /**
     * @return DirectoryLoader
     */
    protected function getFixtures_LoaderService()
    {
        return new DirectoryLoader(__DIR__ . '/../Fixtures');
    }

    /**
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function getApp_CacheService()
    {
        $this->services['app.cache'] = $cache = $this->getCacheDriver();

        $cache->setNamespace($this->getParameter('cache.prefix'));

        return $cache;
    }

    /**
     * @return \Doctrine\Common\Cache\CacheProvider
     */
    protected function getCacheDriver()
    {
        if ($this->getParameter('app.devmode')) {
            return new \Doctrine\Common\Cache\ArrayCache();
        }

        if (!$this->hasParameter('cache.host')) {
            return new \Doctrine\Common\Cache\ApcCache();
        }

        $memcached = new \Memcached();
        $memcached->addServer(
            $this->getParameter('cache.host'),
            $this->getParameter('cache.port')
        );

        $cache = new \Doctrine\Common\Cache\MemcachedCache();
        $cache->setMemcached($memcached);

        return $cache;
    }

    /**
     * @return \Swift_Transport
     */
    protected function getSwiftmailer_TransportService()
    {
        if ($this->getParameter('app.devmode')) {
            return $this->services['swiftmailer.transport'] = \Swift_NullTransport::newInstance();
        }

        $transport = \Swift_SmtpTransport::newInstance(
        	$this->getParameter('smtp.host'),
        	$this->getParameter('smtp.port'),
        	$this->getParameter('smtp.encryption')
        );

        $transport->setUsername($this->getParameter('smtp.username'));
        $transport->setPassword($this->getParameter('smtp.password'));
        $transport->setAuthMode($this->getParameter('smtp.auth_mode'));

        return $this->services['swiftmailer.transport'] = $transport;
    }

    /**
     * @see \Symfony\Component\DependencyInjection\Container::getParameter()
     */
    public function getParameter($name)
    {
        if ($name == 'app.baseuri') {
            return $this->application->getRequest()->getUriForPath('/');
        }

        return parent::getParameter($name);
    }
}
