<?php
namespace Application\Service;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Application\Entity\Page;
use Application\Entity\Comment;
use Application\Entity\Tag;
use Zend\Filter\StaticFilter;

/**
 * The PageManager service is responsible for adding new pages, updating existing
 * pages, adding tags to page, etc.
 */
class PageManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * This method adds a new page.
     */
    public function addNewPage($data) 
    {
        // Create new Page entity.
        $page = new Page();
        $page->setTitle($data['title']);
        $page->setContent($data['content']);
        $page->setStatus($data['status']);
        $currentDate = date('Y-m-d H:i:s');
        $page->setDateCreated($currentDate);        
        
        // Add the entity to entity manager.
        $this->entityManager->persist($page);
        
        // Add tags to page
        $this->addTagsToPage($data['tags'], $page);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }
    
    /**
     * This method allows to update data of a single page.
     */
    public function updatePage($page, $data) 
    {
        $page->setTitle($data['title']);
        $page->setContent($data['content']);
        $page->setStatus($data['status']);
        
        // Add tags to page
        $this->addTagsToPage($data['tags'], $page);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }

    /**
     * Adds/updates tags in the given page.
     */
    private function addTagsToPage($tagsStr, $page) 
    {
        // Remove tag associations (if any)
        $tags = $page->getTags();
        foreach ($tags as $tag) {            
            $page->removeTagAssociation($tag);
        }
        
        // Add tags to page
        $tags = explode(',', $tagsStr);
        foreach ($tags as $tagName) {
            
            $tagName = StaticFilter::execute($tagName, 'StringTrim');
            if (empty($tagName)) {
                continue; 
            }
            
            $tag = $this->entityManager->getRepository(Tag::class)
                    ->findOneByName($tagName);
            if ($tag == null)
                $tag = new Tag();
            
            $tag->setName($tagName);
            $tag->addPage($page);
            
            $this->entityManager->persist($tag);
            
            $page->addTag($tag);
        }
    }    
    
    /**
     * Returns status as a string.
     */
    public function getPageStatusAsString($page) 
    {
        switch ($page->getStatus()) {
            case Page::STATUS_DRAFT: return 'Draft';
            case Page::STATUS_PUBLISHED: return 'Published';
        }
        
        return 'Unknown';
    }
    
    /**
     * Converts tags of the given page to comma separated list (string).
     */
    public function convertTagsToString($page) 
    {
        $tags = $page->getTags();
        $tagCount = count($tags);
        $tagsStr = '';
        $i = 0;
        foreach ($tags as $tag) {
            $i ++;
            $tagsStr .= $tag->getName();
            if ($i < $tagCount) 
                $tagsStr .= ', ';
        }
        
        return $tagsStr;
    }    

    /**
     * Returns count of comments for given page as properly formatted string.
     */
    public function getCommentCountStr($page)
    {
        $commentCount = count($page->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1) 
            return '1 comment';
        else
            return $commentCount . ' comments';
    }


    /**
     * This method adds a new comment to page.
     */
    public function addCommentToPage($page, $data) 
    {
        // Create new Comment entity.
        $comment = new Comment();
        $comment->setPage($page);
        $comment->setAuthor($data['author']);
        $comment->setContent($data['comment']);        
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($comment);

        // Apply changes.
        $this->entityManager->flush();
    }
    
    /**
     * Removes page and all associated comments.
     */
    public function removePage($page) 
    {
        // Remove associated comments
        $comments = $page->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }
        
        // Remove tag associations (if any)
        $tags = $page->getTags();
        foreach ($tags as $tag) {
            
            $page->removeTagAssociation($tag);
        }
        
        $this->entityManager->remove($page);
        
        $this->entityManager->flush();
    }
    
    /**
     * Calculates frequencies of tag usage.
     */
    public function getTagCloud()
    {
        $tagCloud = [];
                
        $pages = $this->entityManager->getRepository(Page::class)
                    ->findPagesHavingAnyTag();
        $totalPageCount = count($pages);
        
        $tags = $this->entityManager->getRepository(Tag::class)
                ->findAll();
        foreach ($tags as $tag) {
            
            $pagesByTag = $this->entityManager->getRepository(Page::class)
                    ->findPagesByTag($tag->getName());
            
            $pageCount = count($pagesByTag);
            if ($pageCount > 0) {
                $tagCloud[$tag->getName()] = $pageCount;
            }
        }
        
        $normalizedTagCloud = [];
        
        // Normalize
        foreach ($tagCloud as $name=>$pageCount) {
            $normalizedTagCloud[$name] =  $pageCount/$totalPageCount;
        }
        
        return $normalizedTagCloud;
    }
}



