<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a tag.
 * @ORM\Entity
 * @ORM\Table(name="tag")
 */
class Tag 
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="name") 
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Page", mappedBy="tags")
     */
    protected $pages;
    
    /**
     * Constructor.
     */
    public function __construct() 
    {        
        $this->pages = new ArrayCollection();        
    }

    /**
     * Returns ID of this tag.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets ID of this tag.
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Returns name.
     * @return string
     */
    public function getName() 
    {
        return $this->name;
    }

    /**
     * Sets name.
     * @param string $name
     */
    public function setName($name) 
    {
        $this->name = $name;
    }
    
    /**
     * Returns pages which have this tag.
     * @return type
     */
    public function getPages() 
    {
        return $this->pages;
    }
    
    /**
     * Adds a page which has this tag.
     * @param type $page
     */
    public function addPage($page) 
    {
        $this->pages[] = $page;        
    }
}

