<?php

namespace Src;

/**
 * Interface Responsable
 * @package Src
 */
interface Responsable
{
    /**
     * Get response according to input data
     *
     * @param array $input
     * @return array|mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    function getResponse(array $input);
}
