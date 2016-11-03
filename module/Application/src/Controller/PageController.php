<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\PageForm;
use Application\Entity\Page;
use Application\Form\CommentForm;

/**
 * This is the Page controller class of the application. 
 * This controller is used for managing pages (adding/editing/viewing/deleting).
 */
class PageController extends AbstractActionController 
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
     * This action displays the "New Page" page. The page contains a form allowing
     * to enter page title, content and tags. When the user clicks the Submit button,
     * a new Page entity will be created.
     */
    public function addAction() 
    {
        // Administration section uses alternate dashboard layout.
        $this->layout()->setTemplate('layout/dashboard');

        // Create the form.
        $form = new PageForm();
        
        // Check whether this page is a POST request.
        if ($this->getRequest()->isPage()) {
            
            // Get POST data.
            $data = $this->params()->fromPage();
            
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                // Use page manager service to add new page to database.                
                $this->pageManager->addNewPage($data);
                
                // Redirect the user to "index" page.
                return $this->redirect()->toRoute('application');
            }
        }
        
        // Render the view template.
        return new ViewModel([
            'form' => $form
        ]);
    }    
    
    /**
     * This action displays the "View Page" page allowing to see the page title
     * and content. The page also contains a form allowing
     * to add a comment to page. 
     */
    public function viewAction() 
    {       
        $pageId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($pageId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find the page by ID
        $page = $this->entityManager->getRepository(Page::class)
                ->findOneById($pageId);
        
        if ($page == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        
        // Create the form.
        $form = new CommentForm();
        
        // Check whether this page is a POST request.
        if($this->getRequest()->isPage()) {
            
            // Get POST data.
            $data = $this->params()->fromPage();
            
            // Fill form with data.
            $form->setData($data);
            if($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData();
                
                // Use page manager service to add new comment to page.
                $this->pageManager->addCommentToPage($page, $data);
                
                // Redirect the user again to "view" page.
                return $this->redirect()->toRoute('pages', ['action'=>'view', 'id'=>$pageId]);
            }
        }
        
        // Render the view template.
        return new ViewModel([
            'page' => $page,
            'form' => $form,
            'pageManager' => $this->pageManager
        ]);
    }  
    
    /**
     * This action displays the page allowing to edit a page.
     */
    public function editAction() 
    {
        // Administration section uses alternate dashboard layout.
        $this->layout()->setTemplate('layout/dashboard');

        // Create form.
        $form = new PageForm();
        
        // Get page ID.
        $pageId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($pageId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        // Find the existing page in the database.
        $page = $this->entityManager->getRepository(Page::class)
                ->findOneById($pageId);        
        if ($page == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
        
        // Check whether this page is a POST request.
        if ($this->getRequest()->isPage()) {
            
            // Get POST data.
            $data = $this->params()->fromPage();
            
            // Fill form with data.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Get validated form data.
                $data = $form->getData(18);
                
                // Use page manager service update existing page.                
                $this->pageManager->updatePage($page, $data);
                
                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('pages', ['action'=>'admin']);
            }
        } else {
            $data = [
                'title' => $page->getTitle(),
                'content' => $page->getContent(),
                'tags' => $this->pageManager->convertTagsToString($page),
                'status' => $page->getStatus()
            ];
            
            $form->setData($data);
        }
        
        // Render the view template.
        return new ViewModel([
            'form' => $form,
            'page' => $page
        ]);  
    }
    
    /**
     * This "delete" action deletes the given page.
     */
    public function deleteAction()
    {
        // Administration section uses alternate dashboard layout.
        $this->layout()->setTemplate('layout/dashboard');

        $pageId = (int)$this->params()->fromRoute('id', -1);
        
        // Validate input parameter
        if ($pageId<0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $page = $this->entityManager->getRepository(Page::class)
                ->findOneById($pageId);        
        if ($page == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        
        $this->pageManager->removePage($page);
        
        // Redirect the user to "admin" page.
        return $this->redirect()->toRoute('pages', ['action'=>'admin']);        
                
    }
    
    /**
     * This "admin" action displays the Manage Pages page. This page contains
     * the list of pages with an ability to edit/delete any page.
     */
    public function adminAction()
    {
        // Administration section uses alternate dashboard layout.
        $this->layout()->setTemplate('layout/dashboard');

        // Get recent pages
        $pages = $this->entityManager->getRepository(Page::class)
                ->findBy([], ['dateCreated'=>'DESC']);
        
        // Render the view template
        return new ViewModel([
            'pages' => $pages,
            'pageManager' => $this->pageManager
        ]);        
    }
}
