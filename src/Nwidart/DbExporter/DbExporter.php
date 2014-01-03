<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 3/01/14
 * @Time 20:44
 *
 */

namespace Nwidart\DbExporter;

use DB;

abstract class DbExporter
{
    protected static $ignore = array('migrations');

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
            ->get($this->selects);
    }
}