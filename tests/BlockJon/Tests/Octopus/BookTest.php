<?php

namespace BlockJon\Tests\Octopus;

use Models\Book;

class BookTest extends \BlockJon\Tests\OctopusTestCase
{
    
    public function testBook()
    {
        $book = new Book;
        $this->assertEquals('Models\Book', get_class($book));
    }
    
}
