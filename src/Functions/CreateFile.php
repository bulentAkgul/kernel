<?php

namespace Bakgul\Kernel\Functions;

use Bakgul\FileContent\Functions\MakeFile;
use Bakgul\FileContent\Tasks\CompleteFolders;
use Bakgul\Kernel\Helpers\Prevented;

class CreateFile
{
    public static function _($request)
    {
        if (Prevented::file($request['attr'])) return;
        
        CompleteFolders::_($request['attr']['path']);

        MakeFile::_($request);
    }
}