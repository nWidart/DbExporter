<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 2/01/14
 * @Time 14:21
 *
 */

namespace Nwidart\DbExporter;

use Illuminate\Support\Facades\DB;

class DbMigrations
{
    protected $database;

    // TODO Check the correct migrations table name
    protected $ignore = array('migrations');

    protected $selects = array(
        'column_name as Field',
        'column_type as Type',
        'is_nullable as Null',
        'column_key as Key',
        'column_default as Default',
        'extra as Extra',
        'data_type as Data_Type'
    );
    protected $schema;
    protected $up;
    protected $down;

    function __construct($database)
    {
        $this->database = $database['database'];
    }

    public function write()
    {
        $schema = $this->compileSchema();
        $filename = date('Y_m_d_His') . "_create_" . $this->database . "_database.php";
        file_put_contents("./app/database/migrations/{$filename}", $schema);
    }

    public function convert()
    {
        $table_headers = array('Field', 'Type', 'Null', 'Key', 'Default', 'Extra');
        $tables = $this->getTables();

        // Loop over the tables
        foreach ($tables as $key => $value) {
            // Do not export the ignored tables
            if (in_array($value['table_name'], $this->ignore)) {
                continue;
            }

            $down = "Schema::drop('{$value['table_name']}');";
            $up = "Schema::create('{$value['table_name']}', function($" . "table) {\n";
            $tableDescribes = $this->getTableDescribes($value['table_name']);

            // Loop over the tables fields
            foreach ($tableDescribes as $values) {
                $method = "";
                $para = strpos($values->Type, '(');
                $type = $para > -1 ? substr($values->Type, 0, $para) : $values->Type;
                $numbers = "";
                $nullable = $values->Null == "NO" ? "" : "->nullable()";
                $default = empty($values->Default) ? "" : "->default(\"{$values->Default}\")";
                $unsigned = strpos($values->Type, "unsigned") === false ? '' : '->unsigned()';

                switch ($type) {
                    case 'int' :
                        $method = 'integer';
                        break;

                    case 'char' :
                    case 'varchar' :
                        $para = strpos($values->Type, '(');
                        $numbers = ", " . substr($values->Type, $para + 1, -1);
                        $method = 'string';
                        break;

                    case 'float' :
                        $method = 'float';
                        break;

                    case 'decimal' :
                        $para = strpos($values->Type, '(');
                        $numbers = ", " . substr($values->Type, $para + 1, -1);
                        $method = 'decimal';
                        break;

                    case 'tinyint' :
                        $method = 'boolean';
                        break;

                    case 'timestamp' :
                    case 'datetime' :
                        $method = 'date';
                        break;

                    case 'mediumtext' :
                        $method = 'mediumtext';
                        break;

                    case 'text' :
                        $method = 'text';
                        break;
                }

                if ($values->Key == 'PRI') {
                    $method = 'increments';
                }

                $up .= "            $" . "table->{$method}('{$values->Field}'{$numbers}){$nullable}{$default}{$unsigned};\n";
            }
            $up .= "        });\n\n";

            $this->schema[$value['table_name']] = array(
                'up'   => $up,
                'down' => $down
            );
        }

        return $this;
    }

    protected function getTables()
    {
        $pdo = DB::connection()->getPdo();
        return $pdo->query('SELECT table_name FROM information_schema.tables WHERE table_schema="' . $this->database . '"');
    }

    protected function getTableDescribes($table)
    {
        return \DB::table('information_schema.columns')
            ->where('table_schema', '=', $this->database)
            ->where('table_name', '=', $table)
            ->get($this->selects);
    }

    private function compileSchema()
    {
        $upSchema = "";
        $downSchema = "";
        $newSchema = "";

        foreach ($this->schema as $name => $values) {
            // check again for ignored tables
            if (in_array($name, $this->ignore)) {
                continue;
            }
            $upSchema .= "
//
// NOTE -- {$name}
// --------------------------------------------------
{$values['up']}";

            $downSchema .= "
{$values['down']}";
        }

        $schema = "<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

//
// NOTE Migration Created: " . date("Y-m-d H:i:s") . "
// --------------------------------------------------
class Create_" . \Str::title($this->database) . "_Database extends Migration {

    //
    // NOTE - Make changes to the database.
    // --------------------------------------------------
    public function up()
    {
        " . $upSchema . "
        " . $this->up . "
    }
    //
    // NOTE - Revert the changes to the database.
    // --------------------------------------------------
    public function down()
    {
        " . $downSchema . "
        " . $this->down . "
    }
}";
        return $schema;
    }
}