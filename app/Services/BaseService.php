<?php

namespace App\Services;

abstract class BaseService
{
    /**
     * Create new service instance
     *
     * @return $this
     */
    public static function getInstance()
    {
        return app(static::class);
    }
    
}