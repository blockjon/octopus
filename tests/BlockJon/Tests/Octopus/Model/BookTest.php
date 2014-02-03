<?php

namespace BlockJon\Tests\Octopus\Model;

use Models\Book;

class BookTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testCanInstantiateBook()
    {
        $book = new Book;
        $this->assertEquals('Models\Book', get_class($book));
    }
    
    public function testGetterSetterMethodsWork()
    {
        $book = new Book;
        $title = uniqid();
        $author = uniqid();
        $book->setTitle($title);
        $book->setAuthor($author);
        $this->assertEquals($title, $book->getTitle());
        $this->assertEquals($author, $book->getAuthor());
    }
    
}
