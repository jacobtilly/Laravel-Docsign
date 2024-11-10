<?php

namespace JacobTilly\LaravelDocsign\Facades;

use Illuminate\Support\Facades\Facade;

class Docsign extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'JacobTilly\LaravelDocsign\LaravelDocsign';
    }
}
