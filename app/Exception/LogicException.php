<?php
namespace App\Exception;

use Hyperf\Server\Exception\ServerException;

class LogicException extends ServerException
{
    public function __construct($message, $code = 500){
        parent::__construct($message, $code);
    }
}
