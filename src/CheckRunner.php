<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\CheckRepository;
use HippoPHP\Hippo\FileSystem;
use HippoPHP\Hippo\Exception\FileNotFoundException;

class CheckRunner
{
    /**
     * @var \HippoPHP\Hippo\FileSystem
     */
    private $fileSystem;

    /**
     * @var \HippoPHP\Hippo\CheckRepository
     */
    private $checkRepository;

    /**
     * @var \HippoPHP\Hippo\Config\Config
     */
    private $config;

    /**
     * @var callable
     */
    private $observer;

    /**
     * @param CheckRepository
     */
    public function __construct(
        FileSystem $fileSystem,
        CheckRepository $checkRepository,
        Config $config
    ) {
        $this->fileSystem = $fileSystem;
        $this->checkRepository = $checkRepository;
        $this->config = $config;
        $this->observer = null;
    }

    /**
     * @param callable $observer
     *
     * @return void
     */
    public function setObserver(callable $observer)
    {
        $this->observer = $observer;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return bool if there were no errors
     */
    public function checkPath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        return is_dir($path)
            ? $this->checkPathDirectory($path)
            : $this->checkPathFile($path);
    }

    /**
     * @param \HippoPHP\Hippo\File $file
     *
     * @return \HippoPHP\Hippo\CheckResult[]
     */
    public function checkFile(File $file)
    {
        $checkContext = new CheckContext($file);
        $results = [];

        foreach ($this->checkRepository->getChecks() as $check) {
            $branch = $this->config->get($check->getConfigRoot());
            if ($branch->get('enabled') === true) {
                $results[] = $check->checkFile($checkContext, $branch);
            }
        }

        return $results;
    }

    /**
     * @param string $path
     *
     * @return \HippoPHP\Hippo\CheckResult[]
     */
    private function checkPathDirectory($path)
    {
        $iterator = $this->fileSystem->getAllFiles($path, '/^.+\.php$/i');
        $results = [];
        foreach ($iterator as $subPath) {
            $results = array_merge($results, $this->checkPathFile($subPath));
        }

        return $results;
    }

    /**
     * @param string $path
     *
     * @return \HippoPHP\Hippo\CheckResult[]
     */
    protected function checkPathFile($path)
    {
        $file = new File($path, $this->fileSystem->getContent($path));
        $checkResults = $this->checkFile($file);
        if ($this->observer !== null) {
            call_user_func($this->observer, $file, $checkResults);
        }

        return $checkResults;
    }
}
