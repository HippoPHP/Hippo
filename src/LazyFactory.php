<?php

namespace HippoPHP\Hippo;

class LazyFactory
{
        /**
         * @var array<*,*>
         */
        private $_cache = [];

        /**
         * @return File
         */
        public function resetCache()
        {
            $this->_cache = [];
        }

        /**
         * @param mixed $cacheKey
         * @param callable $factory
         *
         * @return mixed
         */
        public function retrieve($cacheKey, callable $factory)
        {
            if (!isset($this->_cache[$cacheKey])) {
                $this->_cache[$cacheKey] = $factory();
            }

            return $this->_cache[$cacheKey];
        }
}
