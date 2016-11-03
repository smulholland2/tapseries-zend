<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a comment related to a page.
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment 
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="content")  
     */
    protected $content;

    /** 
     * @ORM\Column(name="author")  
     */
    protected $author;
    
    /** 
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Entity\Page", inversedBy="comments")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;
    
    /**
     * Returns ID of this comment.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets ID of this comment.
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }
    
    /**
     * Returns comment text.
     * @return string
     */
    public function getContent() 
    {
        return $this->content;
    }

    /**
     * Sets comment text.
     * @param string $comment
     */
    public function setContent($comment) 
    {
        $this->content = $comment;
    }
    
    /**
     * Returns author's name.
     * @return string
     */
    public function getAuthor() 
    {
        return $this->author;
    }

    /**
     * Sets author's name.
     * @param string $author
     */
    public function setAuthor($author) 
    {
        $this->author = $author;
    }

    /**
     * Returns the date when this page was created.
     * @return string
     */
    public function getDateCreated() 
    {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this page was created.
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated) 
    {
        $this->dateCreated = (string)$dateCreated;
    }
    
    /*
     * Returns associated page.
     * @return \Application\Entity\Page
     */
    public function getPage() 
    {
        return $this->page;
    }
    
    /**
     * Sets associated page.
     * @param \Application\Entity\Page $page
     */
    public function setPage($page) 
    {
        $this->page = $page;
        $page->addComment($this);
    }
}

