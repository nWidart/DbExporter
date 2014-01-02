# Database Exporter

Export your database as a Laravel Migration

## Installation

Add `"nwidart/db-exporter"` as a requirement to `composer.json`:

```
{
    ...
    "require": {
        ...
		"nwidart/db-exporter": "dev-master"
    },
}

```

Update composer:

```
$ php composer.phar update
```

Add the service provider to `app/config/app.php`:

```
'Nwidart\DbExporter\DbMigrationsServiceProvider'
```



## Usage

**This requires your database config file to be updated.** The class will export the database name from your `app/config/database.php` file, based on your 'default' option.


Make a export route on your development environment

```
Route::get('export', function()
{
    DbMigrations::convert()->write();
});
```



## TODO
* Export data too.



## License (MIT)

Copyright (c) 2013 [Nicolas Widart](http://www.nicolaswidart.com) , n.widart@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.