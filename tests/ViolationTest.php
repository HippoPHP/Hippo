<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Tests;

	use PHPCheckstyle\PHPCheckstyle\Violation;

	class ViolationTest extends \PHPUnit_Framework_TestCase {
		protected $violation;

		public function setUp() {
			$this->violation = new Violation(1, 0, 0, "Test", "Test");
		}

		public function testGetSeverityFromStringIgnore() {
			$this->assertEquals($this->violation->getSeverityFromString('ignore'), 0);
		}

		public function testGetSeverityFromStringInfo() {
			$this->assertEquals($this->violation->getSeverityFromString('info'), 1);
		}

		public function testGetSeverityFromStringWarning() {
			$this->assertEquals($this->violation->getSeverityFromString('warning'), 2);
		}

		public function testGetSeverityFromStringError() {
			$this->assertEquals($this->violation->getSeverityFromString('error'), 3);
		}

		public function testGetLine() {
			$this->assertEquals($this->violation->getLine(), 1);
		}

		public function testGetColumn() {
			$this->assertEquals($this->violation->getColumn(), 0);
		}

		public function testGetSeverityName() {
			$this->assertEquals($this->violation->getSeverityName(), 'ignore');
		}

		public function testGetMessage() {
			$this->assertEquals($this->violation->getMessage(), 'Test');
		}

		public function testGetSource() {
			$this->assertEquals($this->violation->getSource(), 'Test');
		}
	}