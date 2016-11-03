<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a single page in a website.
 * @ORM\Entity(repositoryClass="\Application\Repository\PageRepository")
 * @ORM\Table(name="page")
 */
class Page 
{
    // Page status constants.
    const STATUS_DRAFT       = 1; // Draft.
    const STATUS_PUBLISHED   = 2; // Published.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="title")  
     */
    protected $title;

    /** 
     * @ORM\Column(name="content")  
     */
    protected $content;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;

    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
    
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="page")
     * @ORM\JoinColumn(name="id", referencedColumnName="page_id")
     */
    protected $comments;
    
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Tag", inversedBy="pages")
     * @ORM\JoinTable(name="page_tag",
     *      joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *      )
     */
    protected $tags;
    
    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->comments = new ArrayCollection();        
        $this->tags = new ArrayCollection();        
    }

    /**
     * Returns ID of this page.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets ID of this page.
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Returns title.
     * @return string
     */
    public function getTitle() 
    {
        return $this->title;
    }

    /**
     * Sets title.
     * @param string $title
     */
    public function setTitle($title) 
    {
        $this->title = $title;
    }

    /**
     * Returns status.
     * @return integer
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Sets status.
     * @param integer $status
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }   
    
    /**
     * Returns page content.
     */
    public function getContent() 
    {
       return $this->content; 
    }
    
    /**
     * Sets page content.
     * @param type $content
     */
    public function setContent($content) 
    {
        $this->content = $content;
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
        $this->dateCreated = $dateCreated;
    }
    
    /**
     * Returns comments for this page.
     * @return array
     */
    public function getComments() 
    {
        return $this->comments;
    }
    
    /**
     * Adds a new comment to this page.
     * @param $comment
     */
    public function addComment($comment) 
    {
        $this->comments[] = $comment;
    }
    
    /**
     * Returns tags for this page.
     * @return array
     */
    public function getTags() 
    {
        return $this->tags;
    }      
    
    /**
     * Adds a new tag to this page.
     * @param $tag
     */
    public function addTag($tag) 
    {
        $this->tags[] = $tag;        
    }
    
    /**
     * Removes association between this page and the given tag.
     * @param type $tag
     */
    public function removeTagAssociation($tag) 
    {
        $this->tags->removeElement($tag);
    }
}

