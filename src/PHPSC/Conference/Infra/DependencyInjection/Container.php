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
use Doctrine\Fixture\Executor\BulkExecutorEventSubscriber;

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

        $instance->setProxyDir(
            $baseDir . $this->getParameter('doctrine.proxy.dir')
        );

        $instance->setProxyNamespace(
            $this->getParameter('doctrine.proxy.namespace')
        );

        $instance->setAutoGenerateProxyClasses(
            $this->getParameter('doctrine.proxy.auto')
        );

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
