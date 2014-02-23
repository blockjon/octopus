Master: [![Build Status](https://secure.travis-ci.org/blockjon/octopus.png?branch=master)](http://travis-ci.org/blockjon/octopus)

Octopus
=======
Octopus allows you to customize where and how your models are stored. It supports virtually any data storage format that PHP can interact with such as databases, files, caches, queues, REST and more. 

Octopus lets you customize different strategies for reading data and writing data. Each of your models can have different strategies. For example, you can easily customize Octopus to save your "User" models into both MySQL and Memcache during a write, but during a read, to first try to read from Memcache and then use the database as fallback.

When writing data, Octopus can be set to automatically use a different strategy if an important one fails. For example, if your database throws a deadlock exception while executing an insert statement, Octopus can be set to internally respond by automatically retrying the insert with a different write strategy such as a queue resulting in no data loss.

Note: This is under active development. Will not be ready for production use until June, 2014.

The Octopus API Is Simple
-------------
```
// Get the dao manager.
$daoManager = new DaoManager;

// Create a new model.
$book = new Book;

// Get the book dao.
$dao = $daoManager->getDao($book);

// Set the properties of your model.
$book->setTitle('A tale of two cities');
$dao->create($book);

// Retrieve a model.
$book = $dao->read($id);

// Update a model.
$book->setTitle('A Tale of Two Cities');
$dao->update($book);

// Delete a model.
$dao->delete($book);
```

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


