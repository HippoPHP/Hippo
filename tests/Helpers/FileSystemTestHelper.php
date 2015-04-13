<?php

namespace HippoPHP\Tests\Helpers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileSystemTestHelper
{
    private $_foldersToCleanup = [];

    public function __destruct()
    {
        $this->cleanup();
    }

    public function cleanup()
    {
        foreach ($this->_foldersToCleanup as $folder) {
            $this->_remove($folder);
        }
    }

    public function createTemporaryFolder()
    {
        $tempPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'test_'.uniqid().'_'.microtime(true);
        $this->_foldersToCleanup[] = $tempPath;
        mkdir($tempPath);

        return $tempPath;
    }

    public function getTemporaryFilePath()
    {
        return $this->createTemporaryFolder().DIRECTORY_SEPARATOR.microtime(true);
    }

    private function _remove($path)
    {
        if (!file_exists($path)) {
            return;
        }
        if (is_dir($path)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($iterator as $i) {
                $todo = ($i->isDir() ? 'rmdir' : 'unlink');
                $todo($i->getRealPath());
            }

            rmdir($path);
        } else {
            unlink($path);
        }
    }
}
