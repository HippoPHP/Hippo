<?php

	namespace HippoPHP\Hippo\Tests\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\Reporters\CLIReporter;
	use \HippoPHP\Hippo\Tests\Reporters\AbstractReporterTest;

	class CLIReporterTest extends AbstractReporterTest {
		public function testEmptyReport() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->start();
			$reporter->finish();
			$this->assertEquals('', $this->getSavedContent());
		}

		public function testReportWithNoViolations() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->start();
			$reporter->addCheckResult($this->getEmptyCheckResult('whatever.php'));
			$reporter->finish();
			$this->assertEquals('Checking whatever.php' . PHP_EOL, $this->getSavedContent());
		}

		public function testSilentReport() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->setLoggedSeverities([]);
			$reporter->start();
			$reporter->addCheckResult($this->getEmptyCheckResult('whatever.php'));
			$reporter->finish();
			$this->assertEquals('', $this->getSavedContent());
		}

		public function testOmittingWarnings() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->setLoggedSeverities([Violation::SEVERITY_INFO, Violation::SEVERITY_ERROR]);
			$reporter->start();
			$reporter->addCheckResult($this->getBasicCheckResult('whatever.php'));
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

		public function testBasicReport() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->start();
			$reporter->addCheckResult($this->getBasicCheckResult('whatever.php'));
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

		public function testReportWithTwoFiles() {
			$reporter = new CLIReporter($this->fileSystemMock);
			$reporter->start();
			$reporter->addCheckResult($this->getBasicCheckResult('whatever.php'));
			$reporter->addCheckResult($this->getBasicCheckResult('anotherfile.php'));
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
