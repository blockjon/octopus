Octopus
=======
Octopus was created to help keep your website up even if the database goes down.

It works with MySQL, MongoDB, Postgres, Redis, and almost any other backend
including files, queues, and caches. You can write your own data adapter for 
any backend we haven't yet implemented.

The Octopus framework talks to your backend adapters for you. You choose the rules 
for writing to each backend. One common use of Octopus is to automatically store 
your model in Memcache in addition to MySQL. During a read operation, Octopus can 
automatically pull the record from Memcache thereby bypassing your database
entirely.

In the example code below, the calling context has no knowledge of how each
model is stored or loaded. Was it pulled from MySQL? Was it pulled from Memcache?
Did it journal to a log file when I called save? 

The calling context doesn't care.

Code samples:
-------------

// Create a new object
$book = new Book();
$book->setTitle('A tale of two cities');
$dao->create($book);

// Retrieve an object
$book = $dao->read($id);

// Update an object
$book->setTitle('A Tale of Two Cities');
$dao->update($book);

// Delete an object
$dao->delete($book);
