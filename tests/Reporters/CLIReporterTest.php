<?php

namespace HippoPHP\Hippo\tests\Reporters;

use HippoPHP\Hippo\Reporters\CLIReporter;
use HippoPHP\Hippo\Tests\Reporters\AbstractReporterTest;
use HippoPHP\Hippo\Violation;

class CLIReporterTest extends AbstractReporterTest
{
    public function testEmptyReport()
    {
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->start();
        $reporter->finish();
        $this->assertEquals('', $this->getSavedContent());
    }

    public function testReportWithNoViolations()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getEmptyCheckResult($file)]);
        $reporter->finish();
        $this->assertEquals('Checking whatever.php'.PHP_EOL, $this->getSavedContent());
    }

    public function testSilentReport()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->setLoggedSeverities([]);
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getEmptyCheckResult($file)]);
        $reporter->finish();
        $this->assertEquals('', $this->getSavedContent());
    }

    public function testOmittingWarnings()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->setLoggedSeverities([Violation::SEVERITY_INFO, Violation::SEVERITY_ERROR]);
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getBasicCheckResult($file)]);
        $reporter->finish();

        $expectedLines = [
            'Checking whatever.php',
            'whatever.php:',
            '--------------------------------------------------------------------------------',
            'Line 1:4 (info) : first message',
            'Line 3:6 (error) : third message',
            '',
            '',
        ];
        $fullText = implode(PHP_EOL, $expectedLines);
        $this->assertEquals($fullText, $this->getSavedContent());
    }

    public function testBasicReport()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getBasicCheckResult($file)]);
        $reporter->finish();

        $expectedLines = [
            'Checking whatever.php',
            'whatever.php:',
            '--------------------------------------------------------------------------------',
            'Line 1:4 (info) : first message',
            'Line 2:5 (warning) : second message',
            'Line 3:6 (error) : third message',
            '',
            '',
        ];
        $fullText = implode(PHP_EOL, $expectedLines);
        $this->assertEquals($fullText, $this->getSavedContent());
    }

    public function testReportWithTwoFiles()
    {
        $file1 = $this->getFile('whatever.php');
        $file2 = $this->getFile('anotherfile.php');
        $reporter = new CLIReporter($this->fileSystemMock);
        $reporter->start();
        $reporter->addCheckResults($file1, [$this->getBasicCheckResult($file1)]);
        $reporter->addCheckResults($file2, [$this->getBasicCheckResult($file2)]);
        $reporter->finish();

        $expectedLines = [
            'Checking whatever.php',
            'whatever.php:',
            '--------------------------------------------------------------------------------',
            'Line 1:4 (info) : first message',
            'Line 2:5 (warning) : second message',
            'Line 3:6 (error) : third message',
            '',
            '',
            'Checking anotherfile.php',
            'anotherfile.php:',
            '--------------------------------------------------------------------------------',
            'Line 1:4 (info) : first message',
            'Line 2:5 (warning) : second message',
            'Line 3:6 (error) : third message',
            '',
            '',
        ];

        $fullText = implode(PHP_EOL, $expectedLines);
        $this->assertEquals($fullText, $this->getSavedContent());
    }
}
