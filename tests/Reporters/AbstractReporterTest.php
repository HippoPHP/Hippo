<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <james@alt-three.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Tests\Reporters;

use HippoPHP\Hippo\CheckResult;
use HippoPHP\Hippo\File;
use HippoPHP\Hippo\Violation;
use PHPUnit_Framework_TestCase;

abstract class AbstractReporterTest extends PHPUnit_Framework_TestCase
{
    protected $fileSystemMock;
    private $_savedContent;

    public function setUp()
    {
        $this->fileSystemMock = $this
                ->getMockBuilder('\HippoPHP\Hippo\FileSystem')
                ->disableOriginalConstructor()
                ->getMock();

        $this->_savedContent = '';

        $this->fileSystemMock
                ->method('putContent')
                ->will($this->returnCallback(
                    function ($file, $content) {
                        $this->_savedContent .= $content;
                    }));

        parent::setUp();
    }

    protected function getSavedContent()
    {
        return $this->_savedContent;
    }

    protected function getFile($filename)
    {
        return new File($filename);
    }

    protected function getEmptyCheckResult(File $file)
    {
        $checkResult = new CheckResult();
        $checkResult->setFile($file);

        return $checkResult;
    }

    protected function getBasicCheckResult(File $file)
    {
        $checkResult = $this->getEmptyCheckResult($file);
        $file = $checkResult->getFile();

        $info = new Violation($file, 1, 4, Violation::SEVERITY_INFO, 'first message');
        $warning = new Violation($file, 2, 5, Violation::SEVERITY_WARNING, 'second message');
        $error = new Violation($file, 3, 6, Violation::SEVERITY_ERROR, 'third message');

        $checkResult->addViolation($info);
        $checkResult->addViolation($error);
        $checkResult->addViolation($warning);

        return $checkResult;
    }
}
