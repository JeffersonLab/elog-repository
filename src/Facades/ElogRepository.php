<?php

namespace Jlab\ElogRepository\Facades;

use Illuminate\Support\Facades\Facade;

class ElogRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'elog-repository';
    }
}
