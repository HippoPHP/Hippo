<?php

namespace HippoPHP\Hippo\Exception;

/**
     */
    class FileNotReadableException extends \Exception implements ExceptionInterface
    {
        /**
         * @param string $path
         */
        public function __construct($path)
        {
            parent::__construct('File not readable: '.$path);
        }
    }
