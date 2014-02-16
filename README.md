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

1) Speed: Easily Store All Models In A Database And Also In Memcache

Easily configure Octopus to store a copy of all of your models in both a database and also Memcache. You can then configure your read operations to first attempt pulling from Memcache and to only subsequently use the database as a fallback data access strategy.

2) Robustness: Keep System Up Even If Database Goes Down

During a database outage, Octopus can keep your system up. Configure Octopus to first write to Database (with a special backup strategy of journaled log) and then Memcache and the 2nd strategy. During a database outage, models are still written to Memcache and your journaled change log. This means the read requests are able to find their data during the database outage. Later, when the database recovers, you can replay the changes in the journaled log file which causes your database to advance to the correct current state.

3) Easy Migration Between Databases

With most websites, migrating between different databases like MySQL, Postgres, Microsoft SQL Server, MongoDB and others would be nearly impossible because of the tight coupling between your codebase the required data access patterns of the database vendor you're using. With Octopus, you would only need to change your data access strategy setting to use to your new backend. No additional code level changes would be necessary throughout the rest of your codebase. (You'd still need to manually do a one time data migration between your databases).
