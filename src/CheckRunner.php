<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Exception\FileNotFoundException;

class CheckRunner
{
    /**
     * @var FileSystem
     */
    private $_fileSystem;

    /**
     * @var CheckRepository
     */
    private $_checkRepository;

    /**
     * @var Config
     */
    private $_config;

    /**
     * @var callable
     */
    private $_observer;

    /**
     * @param CheckRepository
     */
    public function __construct(
        FileSystem $fileSystem,
        CheckRepository $checkRepository,
        Config $config
    ) {
        $this->_fileSystem = $fileSystem;
        $this->_checkRepository = $checkRepository;
        $this->_config = $config;
        $this->_observer = null;
    }

    /**
     * @param callable $observer
     *
     * @return void
     */
    public function setObserver(callable $observer)
    {
        $this->_observer = $observer;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return boolean if there were no errors
     */
    public function checkPath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        return is_dir($path)
            ? $this->_checkPathDirectory($path)
            : $this->_checkPathFile($path);
    }

    /**
     * @param File $file
     *
     * @return CheckResult[]
     */
    public function checkFile(File $file)
    {
        $checkContext = new CheckContext($file);
        $results = [];
        foreach ($this->_checkRepository->getChecks() as $check) {
            $branch = $this->_config->get($check->getConfigRoot());
            if ($branch->get('enabled') === true) {
                $results[] = $check->checkFile($checkContext, $branch);
            }
        }

        return $results;
    }

    /**
     * @param string $path
     *
     * @return CheckResult[]
     */
    private function _checkPathDirectory($path)
    {
        $iterator = $this->_fileSystem->getAllFiles($path, '/^.+\.php$/i');
        $results = [];
        foreach ($iterator as $subPath) {
            $results = array_merge($results, $this->_checkPathFile($subPath));
        }

        return $results;
    }

    /**
     * @param string $path
     *
     * @return CheckResult[]
     */
    protected function _checkPathFile($path)
    {
        $file = new File($path, $this->_fileSystem->getContent($path));
        $checkResults = $this->checkFile($file);
        if ($this->_observer !== null) {
            call_user_func($this->_observer, $file, $checkResults);
        }

        return $checkResults;
    }
}
