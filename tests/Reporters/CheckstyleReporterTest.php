<?php

	namespace HippoPHP\Hippo\Tests\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Reporters\CheckstyleReporter;
	use \HippoPHP\Hippo\Tests\Reporters\AbstractReporterTest;

	class CheckstyleReporterTest extends AbstractReporterTest {
		public function testEmptyReport() {
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

		public function testReportWithNoViolations() {
			$reporter = new CheckstyleReporter($this->fileSystemMock);
			$reporter->setFilename('checkstyle.xml');
			$reporter->start();
			$reporter->addCheckResult($this->getEmptyCheckResult('whatever.php'));
			$reporter->finish();
			$expectedLines = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="5.5">
    <file name="whatever.php"/>
</checkstyle>

EXML;
			$this->assertEquals($expectedLines, $this->getSavedContent());
		}

		public function testBasicReport() {
			$reporter = new CheckstyleReporter($this->fileSystemMock);
			$reporter->setFilename('checkstyle.xml');
			$reporter->start();
			$reporter->addCheckResult($this->getBasicCheckResult('whatever.php'));
			$reporter->finish();

			$expectedLines = <<<EXML
<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="5.5">
    <file name="whatever.php">
        <error line="1" column="4" severity="1" message="first message" source="&lt;?php"/>
        <error line="2" column="5" severity="2" message="second message" source="&lt;?php"/>
        <error line="3" column="6" severity="3" message="third message" source="&lt;?php"/>
    </file>
</checkstyle>

EXML;

			$this->assertEquals($expectedLines, $this->getSavedContent());
		}
	}