<?php

namespace Models;

use Octopus\Model\AbstractModel;
use Octopus\Annotation as Octopus;

class Book extends AbstractModel
{
    /**
     * @Octopus\PropertyName
     */
    protected $title;
    
    /**
     * @Octopus\PropertyName
     */
    protected $author;
    
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
