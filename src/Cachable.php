<?php

namespace Src;

/**
 * Interface Cachable
 * @package Src
 */
interface Cachable
{
    /**
     * Get cache key according to input data
     *
     * @param array $input
     * @return string
     */
    function getCacheKey(array $input);
}
