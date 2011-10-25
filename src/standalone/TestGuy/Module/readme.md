# TestGuy Modules

This repository is a part of TestGuy Standalone Pack. It contains all standard modules for TestGuy and will be populated with new ones in future.
This modules can be used in custom TestGuy packs as they are not bound to any TestGuy configuration.
Every module has it's methods and it's configuration, and it should be listed here

Each test suite can have several modules.
Use 'modules' option to include modules into suite.

## Web

A web-browser testing engine, based on Mink (https://github.com/Behat/Mink) with Goutte Driver.
This is a headless web browser, i.e it doesn't emulate web browser full. It just runs http requests and gets response which can be parsed.

### Configuration
  
  Web:
    start: http://localhost/livestreet/
    log: tests/log

Configuration options are:

* start: URL of site you want to test, for example: http://localhost/mysite
* log: path were logs and snapshots will be put. Use: test/log


## DbPopulator
Important module for anyone who uses database. This module cleans up database after each test.
Create a database with current schema and testing data. Consider using fixtures from third-party orms (like Doctrine2) to populate testing database.
Dump database into file and use ir for DbPopulator

### Setup and Configuration
 
  DbPopulator:
    dump: tests/dump/dump.sql
    dsn: mysql:host=localhost;dbname=yourdatabase
    user: root
    password: 

Configuration options are:

* dump: path to your sql dump file. Can be relative.
* dsn: DSN of your test database. Please, make sure project you test will use the same database.
* user: user for connection
* password: password for connection


## Cli
Very simple module that can only execute shell commands and parse the result.
No configuration needed.

## Filesystem
Simple module to test changes in your file system. 
No configuration needed