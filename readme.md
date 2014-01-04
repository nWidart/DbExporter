# Database Exporter

Export your database quickly and easely as a Laravel Migration and all the data as a Seeder class. This can be done via artisan commands or a controller action.

## Installation

Add `"nwidart/db-exporter"`* as a requirement to `composer.json`:

```
{
    ...
    "require": {
        ...
		"nwidart/db-exporter": "v0.3"
    },
}

```

Update composer:

```
$ php composer.phar update
```

Add the service provider to `app/config/app.php`:

```
'Nwidart\DbExporter\DbExportHandlerServiceProvider'
```

**Use `dev-master` as version requirement to be on the cutting edge*


## Documentation

### From the commandline

#### Export database to migration

**Basic usage**

```
php artisan dbe:migrations
```

**Specify a database**

```
php artisan dbe:migrations otherDatabaseName
```

**Ignoring tables**

You can ignore multiple tables by seperating them with a comma.

```
php artisan dbe:migrations --ignore="table1,table2"
```

#### Export database table data to seed class
This command will export all your database table data into a seed class.

```
php artisan dbe:seeds
```
*Important: This **requires your database config file to be updated in `app/config/database.php`**.*


***

### From a controller / route

#### Database to migration

##### Export current database

**This requires your database config file to be updated.** The class will export the database name from your `app/config/database.php` file, based on your 'default' option.


Make a export route on your development environment

```
<?php

Route::get('export', function()
{
    DbExportHandler::migrate();
});
```

##### Export a custom database

```
<?php 

Route::get('export', function()
{
    DbExportHandler::migrate('otherDatabaseName');
});
```

#### Database to seed

**This is still in testing phase. Use with care.**

This will write a seeder class with all the data of the current database.

```
<?php 

Route::get('exportSeed', function()
{
    DbExportHandler::seed();
});
```
Next all you have to do is add the call method on the base seed class:
```
$this->call('nameOfYourSeedClass');
```

Now you can run from the commmand line:

* `php artisan db:seed`,
* or, without having to add the call method: `php artisan db:seed --class=nameOfYourSeedClass`

#### Chaining
You can also combine the generation of the migrations & the seed:

```
DbExportHandler::migrate()->seed();
```
Or with:

```
DbExportHandler::migrateAndSeed();
```
**!! Important :** Please note you cannot set a external seed database. 
If you know of a way to connect to a external DB with laravel without writing in the app/database.php file [let me know](http://www.twitter.com/nicolaswidart).


#### Ignoring tables
By default the migrations table is ignored. You can add tabled to ignore with the following syntax:

```
DbExportHandler::ignore('tableToIgnore')->migrate();
DbExportHandler::ignore('tableToIgnore')->seed();
```



## TODO
* ~~Export data too. It would be cool if it could also generate a seed file based of the data in the tables. This would be more usefull to run on the production server to get the seed on the development server.~~ **Done.**
* Deploy the migration directly to the production server ready to be migrated. (as an option)
* ~~Make commands to do the same thing (export db to migration)~~
* Make commands to do the same thing (export db to seed)




## Credits
Credits to **@michaeljcalkins** for the [original class](http://paste.laravel.com/1jdw#4) on paste.laravel.com. Sadly I couldn't get it working as-is, so I debugged it and decided to make a package out of it.

## License (MIT)

Copyright (c) 2013 [Nicolas Widart](http://www.nicolaswidart.com) , n.widart@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.