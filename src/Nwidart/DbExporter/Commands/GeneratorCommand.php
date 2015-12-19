<?php namespace Nwidart\DbExporter\Commands;

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
        $connType = config('database.default');
        $database = config('database.connections.' .$connType );

        return $database['database'];
    }

    protected function blockMessage($title, $message, $style = 'info')
    {
        // Symfony style block messages
        $formatter = $this->getHelperSet()->get('formatter');
        $errorMessages = array($title, $message);
        $formattedBlock = $formatter->formatBlock($errorMessages, $style, true);
        $this->line($formattedBlock);
    }

    protected function sectionMessage($title, $message)
    {
        $formatter = $this->getHelperSet()->get('formatter');
        $formattedLine = $formatter->formatSection(
            $title,
            $message
        );
        $this->line($formattedLine);
    }
}