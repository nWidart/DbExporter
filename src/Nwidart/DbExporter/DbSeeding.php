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

use DB, Str;

class DbSeeding
{
    protected $database;

    protected $ignore = array('migrations');

    protected $seedingStub;
    protected $customDb;

    /**
     * Set the database name
     * @param String $database
     */
    function __construct($database)
    {
        $this->database = $database;
    }

    public function write()
    {
        // Check if convert method was called before
        // If not, call it on default DB
        if (!$this->customDb) {
            $this->convert();
        }

        $seed = $this->compile();

        $filename = \Str::camel($this->database) . "TableSeeder";

        file_put_contents(app_path() . "/database/seeds/{$filename}.php", $seed);
    }

    public function convert($database = null)
    {
        if (!is_null($database)) {
            $this->database = $database;
            $this->customDb = true;
        }

        $tables = $this->getTables();

        $stub = "";
        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], $this->ignore)) {
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

    protected function getTableData($table)
    {
        return DB::table($table)->get();
    }
    /**
     * Get all the tables
     * @return mixed
     */
    protected function getTables()
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query('SELECT table_name FROM information_schema.tables WHERE table_schema="' . $this->database . '"');
    }

    /**
     * Get all the columns for a given table
     * @param $table
     * @return mixed
     */
    protected function getTableDescribes($table)
    {
        return DB::table('information_schema.columns')
            ->where('table_schema', '=', $this->database)
            ->where('table_name', '=', $table)
            ->get();
    }

    protected function compile()
    {
        // Grab the template
        $template = \File::get(__DIR__ . '/templates/seed.txt');
        // Replace the classname
        $template = str_replace('{{className}}', \Str::camel($this->database) . "TableSeeder", $template);
        $template = str_replace('{{run}}', $this->seedingStub, $template);

        return $template;
    }
}