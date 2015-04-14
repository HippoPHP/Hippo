<?php

namespace HippoPHP\Hippo;

class LazyFactory
{
    /**
     * @var array<*,*>
     */
    private $cache = [];

    /**
     * @return File
     */
    public function resetCache()
    {
        $this->cache = [];
    }

    /**
     * @param mixed    $cacheKey
     * @param callable $factory
     *
     * @return mixed
     */
    public function get($cacheKey, callable $factory)
    {
        if (!isset($this->cache[$cacheKey])) {
            $this->cache[$cacheKey] = $factory();
        }

        return $this->cache[$cacheKey];
    }
}
