<?php

namespace Models;

use Octopus\Model\AbstractModel;

class Book extends AbstractModel
{
    
    protected $title;
    protected $author;
    
    public function toArray() 
    {
        return array(
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'author' => $this->getAuthor(),
        );
    }
    
    /**
     * @param array $data
     */
    public function hydrate(array $data) 
    {
       $this->setId($data['id']);
       $this->setAuthor($data['author']);
       $this->setTitle($data['title']);       
    }
    
    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }



}
