<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 3/01/14
 * @Time 12:50
 *
 */

namespace Nwidart\DbExporter;

use DB, Str, File;

class DbSeeding extends DbExporter
{
    /**
     * @var String
     */
    protected $database;

    /**
     * @var String
     */
    protected $seedingStub;

    /**
     * @var bool
     */
    protected $customDb = false;

    /**
     * Set the database name
     * @param String $database
     */
    function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * Write the seed file
     */
    public function write()
    {
        // Check if convert method was called before
        // If not, call it on default DB
        if (!$this->customDb) {
            $this->convert();
        }

        $seed = $this->compile();

        $filename = Str::camel($this->database) . "TableSeeder";

        file_put_contents(app_path() . "/database/seeds/{$filename}.php", $seed);
    }

    /**
     * Convert the database tables to something usefull
     * @param null $database
     * @return $this
     */
    public function convert($database = null)
    {
        if (!is_null($database)) {
            $this->database = $database;
            $this->customDb = true;
        }

        // Get the tables for the database
        $tables = $this->getTables();

        $stub = "";
        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], self::$ignore)) {
                continue;
            }
            $tableName = $value['table_name'];
            $tableData = $this->getTableData($value['table_name']);

            $insertStub = "";

            foreach ($tableData as $obj) {
                $insertStub .= "
            array(\n";
                foreach ($obj as $prop => $value) {
                    if (is_numeric($value)) {
                        $insertStub .= "                '{$prop}' => {$value},\n";
                    } else {
                        $insertStub .= "                '{$prop}' => '{$value}',\n";
                    }
                }

                if (count($tableData) > 1) {
                    $insertStub .= "            ),\n";
                } else {
                    $insertStub .= "            )\n";
                }
            }

            if (count($tableData) > 1) {
                $stub .= "
        DB::table('" . $tableName . "')->insert(array(
                    ".$insertStub."
        ));";
            } else {
                $stub .= "DB::table('" . $tableName . "')->insert(
                    ".$insertStub."
        );";
            }
        }

        $this->seedingStub = $stub;

        return $this;
    }

    /**
     * Compile the current seedingStub with the seed template
     * @return mixed
     */
    protected function compile()
    {
        // Grab the template
        $template = File::get(__DIR__ . '/templates/seed.txt');

        // Replace the classname
        $template = str_replace('{{className}}', \Str::camel($this->database) . "TableSeeder", $template);
        $template = str_replace('{{run}}', $this->seedingStub, $template);

        return $template;
    }
}