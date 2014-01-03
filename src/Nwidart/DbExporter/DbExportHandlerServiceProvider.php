<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 3/01/14
 * @Time 13:19
 *
 */

namespace Nwidart\DbExporter;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;


class DbExportHandlerServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->package('nwidart/db-exporter');
    }

    public function register()
    {

        $this->app['DbExportHandler'] = $this->app->share(function($app)
        {
            $connType = Config::get('database.default');
            $database = Config::get('database.connections.' .$connType );

            // Instatiate a new DbMigrations class to send to the handler
            $migrator = new DbMigrations($database['database']);

            // Return the ExportHandler
            return new DbExportHandler($migrator);
        });

        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('DbExportHandler', 'Nwidart\DbExporter\Facades\DbExportHandler');
        });
    }

    public function provides()
    {
        return array('DbExportHandler');
    }
}