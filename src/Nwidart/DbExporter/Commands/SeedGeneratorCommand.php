<?php namespace Nwidart\DbExporter\Commands;


use Nwidart\DbExporter\DbExportHandler, Str, Config;

class SeedGeneratorCommand extends GeneratorCommand
{
    protected $name = 'dbe:seeds';

    protected $description = 'Export your database table data to a seed class.';

    /**
     * @var \Nwidart\DbExporter\DbExportHandler
     */
    protected $handler;

    public function __construct(DbExportHandler $handler)
    {
        parent::__construct();

        $this->handler = $handler;
    }

    public function fire()
    {
        $this->comment("Preparing the seeder class for database {$this->getDatabaseName()}");

        $this->handler->seed();

        // Symfony style block messages
        $formatter = $this->getHelperSet()->get('formatter');

        $filename = Str::camel($this->getDatabaseName()) . "TableSeeder";
        $filename = Config::get('db-exporter::export_path.seeds')."{$filename}.php";

        $errorMessages = array('Success!', "Database seed class generated in: {$filename}");
        $formattedBlock = $formatter->formatBlock($errorMessages, 'info', true);
        $this->line($formattedBlock);
    }
}
