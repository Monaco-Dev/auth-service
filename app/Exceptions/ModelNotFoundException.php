<?php

namespace App\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public static function abort()
    {
        abort(404, 'Page not found');
    }
}
