<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 3/01/14
 * @Time 12:55
 *
 */

namespace Nwidart\DbExporter;

class DbExportHandler
{
    /**
     * @var DbMigrations
     */
    protected $migrator;

    /**
     * Inject the DbMigrations class
     * @param DbMigrations $DbMigrations
     */
    function __construct(DbMigrations $DbMigrations)
    {
        $this->migrator = $DbMigrations;
    }

    /**
     * Create migrations from the given DB
     * @param String null $database
     */
    public function migrate($database = null)
    {
        $this->migrator->convert($database)->write();
    }

    /**
     * @param null $database
     */
    public function seed($database = null)
    {
        // Todo add seeding stuff
    }
}