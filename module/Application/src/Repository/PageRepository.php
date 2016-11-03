<?php
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Page;

/**
 * This is the custom repository class for Page entity.
 */
class PageRepository extends EntityRepository
{
    /**
     * Finds all published pages having any tag.
     * @return array
     */
    public function findPagesHavingAnyTag()
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Page::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Page::STATUS_PUBLISHED);
        
        $pages = $queryBuilder->getQuery()->getResult();
        
        return $pages;
    }
    
    /**
     * Finds all published pages having the given tag.
     * @param string $tagName Name of the tag.
     * @return array
     */
    public function findPagesByTag($tagName)
    {
        $entityManager = $this->getEntityManager();
        
        $queryBuilder = $entityManager->createQueryBuilder();
        
        $queryBuilder->select('p')
            ->from(Page::class, 'p')
            ->join('p.tags', 't')
            ->where('p.status = ?1')
            ->andWhere('t.name = ?2')
            ->orderBy('p.dateCreated', 'DESC')
            ->setParameter('1', Page::STATUS_PUBLISHED)
            ->setParameter('2', $tagName);
        
        $pages = $queryBuilder->getQuery()->getResult();
                
        return $pages;
    }        
}