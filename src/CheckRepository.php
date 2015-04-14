<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\Checks\CheckInterface;
use ReflectionClass;

class CheckRepository
{
    /**
     * @var bool
     */
    private $hasBeenBuilt = false;

    /**
     * @var \HippoPHP\Hippo\Check[]
     */
    private $checks = [];

    /**
     * @var \HippoPHP\Hippo\FileSystem
     */
    private $fileSystem;

    public function __construct(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return \HippoPHP\Hippo\Check[]
     */
    public function getChecks()
    {
        $this->buildIfNecessary();

        return $this->checks;
    }

    /**
     * If the checks haven't been ran, then we need to run them.
     *
     * @return void
     */
    private function buildIfNecessary()
    {
        if (!$this->hasBeenBuilt) {
            $this->build();
            $this->hasBeenBuilt = true;
        }
    }

    /**
     * Builds a list of checks to run.
     *
     * @return void
     */
    private function build()
    {
        foreach ($this->fileSystem->getAllFiles($this->getRootDirectory(), '/^.*\.php$/') as $filePath) {
            require_once $filePath;
        }

        $this->checks = [];
        foreach (get_declared_classes() as $class) {
            $reflectionClass = new ReflectionClass($class);
            if ($this->canInstantiate($reflectionClass)) {
                $this->checks[] = $reflectionClass->newInstance();
            }
        }
    }

    /**
     * Returns the root checks directory.
     *
     * @return string
     */
    private function getRootDirectory()
    {
        return __DIR__.DIRECTORY_SEPARATOR.'Checks';
    }

    /**
     * Determines whether a "check" does indeed implement CheckInterface.
     *
     * @param \ReflectionClass $reflectionClass
     *
     * @return bool
     */
    private function canInstantiate(ReflectionClass $reflectionClass)
    {
        return $reflectionClass->implementsInterface('\HippoPHP\Hippo\Checks\CheckInterface')
            && !$reflectionClass->isInterface()
            && !$reflectionClass->isAbstract();
    }
}
