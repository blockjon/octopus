Octopus
=======
Octopus lets you to Create, Update, Read and Delete models from any backend using 
any persistence/storage system (database, file, cache, queues etc.).

The Octopus API Is Simple:
-------------
```
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
```

Each "DAO" is a data access object configured with the strategy for reading from
and writing to your backends. You can implement new backend strategies for Octopus
that we haven't created. Octopus ships with adapters for SqLite, APC, and text files. 
More on-board adapters are on the way.

One of the key features of Octopus is the ability to set strategy rules for your data
access. The most common one is that models are written to database and cache, whereas
reads should first attempt a read from Memcache and finally from MySQL if memcache
did not have the desired object. Octopus can do this for you with very basic configuration.
