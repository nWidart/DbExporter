<?php
/**
 * DbExporter.
 *
 * @User nicolaswidart
 * @Date 3/01/14
 * @Time 13:21
 *
 */

namespace Nwidart\DbExporter\Facades;

use Illuminate\Support\Facades\Facade;

class DbExportHandler extends Facade
{
    protected static function getFacadeAccessor() { return 'DbExportHandler'; }
}