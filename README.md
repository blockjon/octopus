Octopus
=======
Octopus is a vendor neutral data access API. You can Create, Read, Update and Delete models using any backend as your persistence & storage system including (databases, files, caches, queues etc.). Rather than programing against a specific vendor library such as MySQL or Mongo, consider using Octopus instead.

Disclaimer: This is under active development. Will not be ready for production use until June, 2014.

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

Octopus works by allowing you to customize each "DAO" (data access object) with strategies for reading from and writing to your backends. You can register multiple strategies for reading and writing your models.

This strategy pattern allows Octopus projects to more easily scale and also be tolerant to backend outages. 

The most common two examples of where Octopus really shines are as follows:

1) You can set your models to automatically read from Memcache. If a model isn't found, Octopus can automatically load the model from a database query instead.

2) If MySQL goes down, you can instead write to Memcache and also to a journaled log file. Subsequent reads will find your models if they are set to first read from Memcache. Later, when the database comes back online, you can replay the journaled database changes such that the missing records flow back into the database.
