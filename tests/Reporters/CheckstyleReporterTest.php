<?php

namespace HippoPHP\Hippo\tests\Reporters;

use HippoPHP\Hippo\Reporters\CheckstyleReporter;
use HippoPHP\Hippo\Tests\Reporters\AbstractReporterTest;

class CheckstyleReporterTest extends AbstractReporterTest
{
    public function testEmptyReport()
    {
        $reporter = new CheckstyleReporter($this->fileSystemMock);
        $reporter->setFilename('checkstyle.xml');
        $reporter->start();
        $reporter->finish();
        $expectedLines = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="5.5"/>

EXML;
        $this->assertEquals($expectedLines, $this->getSavedContent());
    }

    public function testReportWithNoViolations()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CheckstyleReporter($this->fileSystemMock);
        $reporter->setFilename('checkstyle.xml');
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getEmptyCheckResult($file)]);
        $reporter->finish();
        $expectedLines = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="5.5">
    <file name="whatever.php"/>
</checkstyle>

EXML;
        $this->assertEquals($expectedLines, $this->getSavedContent());
    }

    public function testBasicReport()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new CheckstyleReporter($this->fileSystemMock);
        $reporter->setFilename('checkstyle.xml');
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getBasicCheckResult($file)]);
        $reporter->finish();

        $expectedLines = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="5.5">
    <file name="whatever.php">
        <error line="1" column="4" severity="1" message="first message"/>
        <error line="2" column="5" severity="2" message="second message"/>
        <error line="3" column="6" severity="3" message="third message"/>
    </file>
</checkstyle>

EXML;

        $this->assertEquals($expectedLines, $this->getSavedContent());
    }
}
