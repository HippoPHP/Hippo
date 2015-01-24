<?php

namespace HippoPHP\Hippo\Config;

interface ConfigReaderInterface
{
        /**
         * @param string $filename
         *
         * @return Config
         */
        public function loadFromFile($filename);

        /**
         * @param string $string
         *
         * @return Config
         */
        public function loadFromString($string);
}
