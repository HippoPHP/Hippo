<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <jbrooksuk@me.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
