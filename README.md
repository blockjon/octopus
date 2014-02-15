Octopus
=======
Octopus is a vendor neutral data access API for PHP. You can Create, Read, Update and Delete models using any database or other type of data storage fromat such as files, caches, and queues. Instead of tightly coupling your project to one specific vendor library such as MySQL or Mongo, consider using Octopus instead, a flexible alternative.

Note: This is under active development. Will not be ready for production use until June, 2014.

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

Example Use Cases:
------------------

1) Speed: Elegantly Store All Data In Memcache
Easily configure Octopus to store a copy of all of your models in Memcache. You can then configure your Reads to try to first load a model from Memcache and to only then use the database as your fallback data access strategy.

2) Robustness: Keep System Up Even If Database Goes Down
If your database goes down, Octopus can keep your system up. Configure your Octopus settings to first write to Memcache, then to a journaled log file, and finally to the database. During a database outage, models are still written to your journaled change log and Memcache. This means the read requests are able to find their data. Later, when the database recovers, you can replay the changes in the journaled log file which causes the database to advance to the correct current state.

3) Easy Migration Between Databases
With most websites, migrating from MySQL to Postgres to Microsoft SQL Server and then to MongoDB would be nearly impmossible because of the tight coupling between the codebase and the vendor specfic features of your data backend. With Octopus, you would only need to change your data access stratgy setting to talk to your new backend. No additional code level changes would be necessary throughout the rest of your codebase. (You'd still need to manually do a one time data migration between your databases).
