<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Service\MailSender;
use Application\Entity\Page;
use Zend\Barcode\Barcode;
use Zend\Mvc\MvcEvent;
use User\Entity\User;


/**
 * This is the main controller class of the application. The 
 * controller class is used to receive user input,  
 * pass the data to the models and pass the results returned by models to the 
 * view for rendering.
 */
class IndexController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    public $entityManager;
    
    /**
     * Page manager.
     * @var Application\Service\PageManager 
     */
    private $pageManager;
    
    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager, $pageManager) 
    {
        $this->entityManager = $entityManager;
        $this->pageManager = $pageManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * Recent Pages page containing the recent pages.
     */
    public function indexAction() 
    {
        $tagFilter = $this->params()->fromQuery('tag', null);
        
        if ($tagFilter) {
         
            // Filter pages by tag
            $pages = $this->entityManager->getRepository(Page::class)
                    ->findPagesByTag($tagFilter);
            
        } else {
            // Get recent pages
            $pages = $this->entityManager->getRepository(Page::class)
                    ->findBy(['status'=>Page::STATUS_PUBLISHED], 
                             ['dateCreated'=>'DESC']);
        }
        
        // Get popular tags.
        $tagCloud = $this->pageManager->getTagCloud();
        
        // Render the view template.
        return new ViewModel([
            'pages' => $pages,
            'pageManager' => $this->pageManager,
            'tagCloud' => $tagCloud
        ]);
    }
    
    /**
     * This action displays the About page.
     */
    public function aboutAction() 
    {   
        $appName = 'Tap Series Website';
        $appDescription = 'A simple blog application for the Using Zend Framework 3 book';
        
        return new ViewModel([
            'appName' => $appName,
            'appDescription' => $appDescription
        ]);
    }
    
    public function settingsAction()
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmail($this->identity());
        
        if ($user==null) {
            throw new \Exception('Not found user with such email');
        }
        
        return new ViewModel([
            'user' => $user
        ]);
    }
}
