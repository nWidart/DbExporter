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
     * @var DbSeeding
     */
    protected $seeder;

    /**
     * Inject the DbMigrations class
     * @param DbMigrations $DbMigrations
     * @param DbSeeding $DbSeeding
     */
    function __construct(DbMigrations $DbMigrations, DbSeeding $DbSeeding)
    {
        $this->migrator = $DbMigrations;
        $this->seeder = $DbSeeding;
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
        $this->seeder->convert($database)->write();

    }
}