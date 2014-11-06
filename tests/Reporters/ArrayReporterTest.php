<?php

	namespace HippoPHP\Hippo\Tests\Reporters;

	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Reporters\ArrayReporter;
	use \HippoPHP\Hippo\Tests\Reporters\AbstractReporterTest;

	class ArrayReporterTest extends AbstractReporterTest {
		public function testEmptyReport() {
			$reporter = new ArrayReporter();
			$reporter->start();
			$reporter->finish();
			$this->assertEquals([], $reporter->getReport());
		}

		public function testReportWithNoViolations() {
			$reporter = new ArrayReporter();
			$reporter->start();
			$reporter->addCheckResult($this->getEmptyCheckResult('whatever.php'));
			$reporter->finish();
			$this->assertEquals([], $reporter->getReport());
		}

		public function testBasicReport() {
			$reporter = new ArrayReporter();
			$reporter->start();
			$reporter->addCheckResult($this->getBasicCheckResult('whatever.php'));
			$reporter->finish();

			$expectedLines = [
				'whatever.php:1' => [
					0 => [
						'file' => 'whatever.php',
						'line' => 1,
						'column' => 4,
						'severity' => 1,
						'message' => 'first message',
						'source' => '<?php'
					]
				],
				'whatever.php:2' => [
					0 => [
						'file' => 'whatever.php',
						'line' => 2,
						'column' => 5,
						'severity' => 2,
						'message' => 'second message',
						'source' => '<?php'
					]
				],
				'whatever.php:3' => [
					0 => [
						'file' => 'whatever.php',
						'line' => 3,
						'column' => 6,
						'severity' => 3,
						'message' => 'third message',
						'source' => '<?php'
					]
				]
			];
			$this->assertEquals($expectedLines, $reporter->getReport());
		}
	}
