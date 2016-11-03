<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PageManager;
use Application\Controller\PageController;

/**
 * This is the factory for PageController. Its purpose is to instantiate the
 * controller.
 */
class PageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $pageManager = $container->get(PageManager::class);
        
        // Instantiate the controller and inject dependencies
        return new PageController($entityManager, $pageManager);
    }
}


