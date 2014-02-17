Master: [![Build Status](https://secure.travis-ci.org/blockjon/octopus.png?branch=master)](http://travis-ci.org/blockjon/octopus)

Octopus
=======
With one API, you can Create, Read, Update and Delete models. Octopus lets you decide where and how your models are stored. It supports any data storage format PHP can interact with such as databases, files, caches, queues, REST and more. 

Octopus lets you customize different strategies for reading data and writing. Each of your models can have different strategies. For example, you can easily customize Octopus to save your "User" models into both MySQL and Memcache during a write, but during a read, to first try to read from Memcache and then use the database as fallback.

When writing data, Octopus can be set to automatically use a different strategy if an important one fails. For example, if your database throws a deadlock error when you try to insert an order in your website, the order infrormation can instead be set to write to a queue.

Note: This is under active development. Will not be ready for production use until June, 2014.

The Octopus API Is Simple
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

Example Use Cases
------------------

1) Speed: Easily Store All Models In A Database And Also In Memcache

Easily configure Octopus to store a copy of all of your models in both a database and also Memcache. You can then configure your read operations to first attempt pulling from Memcache and to only subsequently use the database as a fallback data access strategy.

2) Robustness: Keep System Up Even If Database Goes Down

During a database outage, Octopus can keep your system up. Configure Octopus to use your database as the first strategy (with a journaled log strategy that is utilized if that fails) and then Memcache as the 2nd strategy. During a database outage, models are still written to Memcache and also to the journaled change log. When reads happen, data is served from Memcache. This masks the otherwise crippiling effects of a database outage. Later, when the database recovers, you can replay the changes in the journaled log file which causes your database to advance to the correct current state.

3) Easy Migration Between Databases

With most websites, migrating between different databases like MySQL, Postgres, Microsoft SQL Server, MongoDB and others would be nearly impossible because of the tight coupling between your codebase the required data access patterns of the database vendor you're using. Using Octopus, you only need to change your data access strategy setting to use to your new backend. No additional code level changes are necessary throughout the rest of your codebase. (You'd still need to manually do a one time data migration between your databases).

Data Strategies Currently Supported
-----------------------------------
Memcache, APC, PdoSqlite, PdoMysql, Json log.

Note: You can easily implement custom strategy adapters. More adapters be added to Octopus as development continues. Some of the next adapters we'd like to add include MongoDb, REST, and SQS.


