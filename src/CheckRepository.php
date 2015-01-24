<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\Checks\CheckInterface;
    use ReflectionClass;

    class CheckRepository
    {
        /**
         * @var boolean
         */
        private $_hasBeenBuilt = false;

        /**
         * @var Check[]
         */
        private $_checks = [];

        /**
         * @var FileSystem
         */
        private $_fileSystem;

        public function __construct(FileSystem $fileSystem)
        {
            $this->_fileSystem = $fileSystem;
        }

        /**
         * @return Check[]
         */
        public function getChecks()
        {
            $this->_buildIfNecessary();

            return $this->_checks;
        }

        /**
         * If the checks haven't been ran, then we need to run them.
         *
         * @return void
         */
        private function _buildIfNecessary()
        {
            if (!$this->_hasBeenBuilt) {
                $this->_build();
                $this->_hasBeenBuilt = true;
            }
        }

        /**
         * Builds a list of checks to run.
         *
         * @return void
         */
        private function _build()
        {
            foreach ($this->_fileSystem->getAllFiles($this->_getRootDirectory(), '/^.*\.php$/') as $filePath) {
                require_once $filePath;
            }

            $this->_checks = [];
            foreach (get_declared_classes() as $class) {
                $reflectionClass = new ReflectionClass($class);
                if ($this->_canInstantiate($reflectionClass)) {
                    $this->_checks[] = $reflectionClass->newInstance();
                }
            }
        }

        /**
         * Returns the root checks directory.
         *
         * @return string
         */
        private function _getRootDirectory()
        {
            return __DIR__.DIRECTORY_SEPARATOR.'Checks';
        }

        /**
         * Determines whether a "check" does indeed implement CheckInterface.
         *
         * @param  ReflectionClass $reflectionClass
         *
         * @return bool
         */
        private function _canInstantiate(ReflectionClass $reflectionClass)
        {
            return $reflectionClass->implementsInterface('\HippoPHP\Hippo\Checks\CheckInterface')
                && !$reflectionClass->isInterface()
                && !$reflectionClass->isAbstract();
        }
    }
