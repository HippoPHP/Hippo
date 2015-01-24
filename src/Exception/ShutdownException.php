<?php

namespace HippoPHP\Hippo\Exception;

/**
     */
    class ShutdownException extends \Exception implements ExceptionInterface
    {
        /**
         * @var int
         */
        private $_exitCode;

        /**
         * @param int $exitCode
         */
        public function __construct($exitCode)
        {
            $this->_exitCode = $exitCode;
        }

        /**
         * @return int
         */
        public function getExitCode()
        {
            return $this->_exitCode;
        }
    }
