<?php

namespace Src\Integration;

/**
 * Interface DataProviderInterface
 * @package Src\Integration
 */
interface DataProviderInterface
{
    /**
     * @param array $request
     *
     * @return array
     */
    function get(array $request);
}
