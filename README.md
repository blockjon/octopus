Octopus
=======
Octopus lets you to Create, Update, Read and Delete models using any backend as your persistence/storage system (databases, files, caches, queues etc.). Rather than programing against a specific vendor libraries such as MySQL and Mongo, consider using Octopus... the last data access API you may ever need.

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

Each "DAO" is a data access object configured with strategies for reading from and writing to your backends. You can implement new backend strategies for Octopus that we haven't created. Octopus ships with adapters for SQLite, APC, and text files. More on-board adapters are on the way.

One of the key features of Octopus is the ability to set strategy rules for your data access. The most common one is that models are written to database and cache, whereas reads should first attempt a read from Memcache and finally from MySQL if memcache did not have the desired object. Octopus can do this for you with very basic configuration.
