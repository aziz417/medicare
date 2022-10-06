<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    public function toArray()
    {
        return [
            'status' => false,
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
        ];
    }
}
