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
    
    public function testToArray()
    {
        $book = new Book;
        $title = 'test title';
        $author = 'test author';
        $book->setTitle($title);
        $book->setAuthor($author);
        $result = $book->toArray();
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('id', $result));
        $this->assertTrue(isset($result['title']));
        $this->assertTrue(isset($result['author']));
        $this->assertEquals($title, $result['title']);
        $this->assertEquals($author, $result['author']);
    }
    
}
