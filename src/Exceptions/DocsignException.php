<?php

namespace JacobTilly\LaravelDocsign\Exceptions;

use Exception;

class DocsignException extends Exception
{
    protected $errorData;

    public function __construct($message = "", $code = 0, Exception $previous = null, $errorData = [])
    {
        parent::__construct($message, $code, $previous);
        $this->errorData = $errorData;
    }

    public function getErrorData()
    {
        return $this->errorData;
    }
}
