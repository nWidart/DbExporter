<?php namespace Nwidart\DbExporter\Commands;


use Nwidart\DbExporter\DbExporter;
use Nwidart\DbExporter\DbExportHandler;
use Symfony\Component\Console\Input\InputOption;
use Config, Str;

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

        // Grab the options
        $ignore = $this->option('ignore');

        if (empty($ignore)) {
            $this->handler->seed();
        } else {
            $tables = explode(',', str_replace(' ', '', $ignore));
            $this->handler->ignore($tables)->seed();
            foreach (DbExporter::$ignore as $table) {
                $this->comment("Ignoring the {$table} table");
            }
        }

        // Symfony style block messages
        $formatter = $this->getHelperSet()->get('formatter');
        $filename = $this->getFilename();

        $errorMessages = array('Success!', "Database seed class generated in: {$filename}");

        $formattedBlock = $formatter->formatBlock($errorMessages, 'info', true);
        $this->line($formattedBlock);
    }

    private function getFilename()
    {
        $filename = Str::camel($this->getDatabaseName()) . "TableSeeder";
        return config('db-exporter.export_path.seeds')."{$filename}.php";
    }

    protected function getOptions()
    {
        return array(
            array('ignore', 'ign', InputOption::VALUE_REQUIRED, 'Ignore tables to export, separated by a comma', null)
        );
    }
}