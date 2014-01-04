<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 4/01/14
 * @Time 12:33
 *
 */

namespace Nwidart\DbExporter\Commands;

use Illuminate\Console\Command;
use Config;

class GeneratorCommand extends Command
{
    /**
     * Get the database name from the app/config/database.php file
     * @return String
     */
    protected function getDatabaseName()
    {
        $connType = Config::get('database.default');
        $database = Config::get('database.connections.' .$connType );

        return $database['database'];
    }
}