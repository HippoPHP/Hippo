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

namespace HippoPHP\tests;

use HippoPHP\Hippo\FileSystem;
use HippoPHP\Tests\Helpers\FileSystemTestHelper;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    private $_fileSystem;
    private $_fileSystemTestHelper;

    public function setUp()
    {
        $this->_fileSystem = new FileSystem();
        $this->_fileSystemTestHelper = new FileSystemTestHelper();
    }

    public function testGetContent()
    {
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        $this->_fileSystem->putContent($path, 'whatever');
        $this->assertEquals('whatever', $this->_fileSystem->getContent($path));
    }

    public function testGetNonExistingContent()
    {
        $this->setExpectedException('\HippoPHP\Hippo\Exception\FileNotFoundException');
        $this->_fileSystem->getContent('nope');
    }

    public function testOverwriteExistingContent()
    {
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        touch($path);
        $this->_fileSystem->putContent($path, 'whatever');
        $this->assertEquals('whatever', $this->_fileSystem->getContent($path));
    }

    public function testReadFolder()
    {
        $this->setExpectedException('\HippoPHP\Hippo\Exception\FileNotReadableException');
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        mkdir($path);
        $this->_fileSystem->getContent($path, 'whatever');
    }

    public function testOverwriteFolder()
    {
        $this->setExpectedException('\HippoPHP\Hippo\Exception\FileNotWritableException');
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        mkdir($path);
        $this->_fileSystem->putContent($path, 'whatever');
    }

    public function testSavingToNotWritableTarget()
    {
        $this->setExpectedException('\HippoPHP\Hippo\Exception\FileNotWritableException');
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        $subPath = $path.DIRECTORY_SEPARATOR.'file.txt';
        $this->_fileSystem->putContent($subPath, 'whatever');
    }

    public function testGettingAllFilesWithoutNesting()
    {
        $folder = $this->_fileSystemTestHelper->createTemporaryFolder();
        touch($folder.DIRECTORY_SEPARATOR.'file1.txt');
        touch($folder.DIRECTORY_SEPARATOR.'file2.txt');
        $result = $this->_fileSystem->getAllFiles($folder);
        $this->assertNotNull($result);
        $this->assertEquals(2, count($result));
        $this->assertEquals('file1.txt', basename($result[0]));
        $this->assertEquals('file2.txt', basename($result[1]));
    }

    public function testGettingAllFilesWithNesting()
    {
        $folder = $this->_fileSystemTestHelper->createTemporaryFolder();
        touch($folder.DIRECTORY_SEPARATOR.'file1.txt');
        mkdir($folder.DIRECTORY_SEPARATOR.'subfolder');
        touch($folder.DIRECTORY_SEPARATOR.'subfolder'.DIRECTORY_SEPARATOR.'file2.txt');
        $result = $this->_fileSystem->getAllFiles($folder);
        $this->assertEquals(2, count($result));
        $this->assertEquals('file1.txt', basename($result[0]));
        $this->assertEquals('file2.txt', basename($result[1]));
        $this->assertContains('subfolder', $result[1]);
    }

    public function testGettingAllFilesWithRegex()
    {
        $folder = $this->_fileSystemTestHelper->createTemporaryFolder();
        touch($folder.DIRECTORY_SEPARATOR.'file.txt');
        touch($folder.DIRECTORY_SEPARATOR.'file.dat');
        $result = $this->_fileSystem->getAllFiles($folder, '/\.DAT/i');
        $this->assertEquals(1, count($result));
        $this->assertEquals('file.dat', basename($result[0]));
    }
}
