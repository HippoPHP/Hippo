<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle\PHPCheckstyle\Violation;
	use PHPCheckstyle\PHPCheckstyle\Exception\OutOfBoundsException;

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

		public function testGetSeverityFromStringNull() {
			$this->assertNull($this->violation->getSeverityFromString('foobar'));
		}

		public function testGetLine() {
			$this->assertEquals($this->violation->getLine(), 1);
		}

		public function testGetColumn() {
			$this->assertEquals($this->violation->getColumn(), 0);
		}

		public function testGetSeverity() {
			$this->assertEquals($this->violation->getSeverity(), 0);
		}

		public function testGetSeverityNameIgnore() {
			$mock = new Violation(1, 0, 0, "Test", "Ignore");
			$this->assertEquals($mock->getSeverityName(), 'ignore');
		}

		public function testGetSeverityNameInfo() {
			$mock = new Violation(1, 0, 1, "Test", "Info");
			$this->assertEquals($mock->getSeverityName(), 'info');
		}

		public function testGetSeverityNameWarning() {
			$mock = new Violation(1, 0, 2, "Test", "Warning");
			$this->assertEquals($mock->getSeverityName(), 'warning');
		}

		public function testGetSeverityNameError() {
			$mock = new Violation(1, 0, 3, "Test", "Error");
			$this->assertEquals($mock->getSeverityName(), 'error');
		}

		/**
		 * getSeverityName should return error by default.
		 */
		public function testGetSeverityNameException() {
			$mock = new Violation(1, 0, 100, "Test", "Exception");
			$this->assertEquals($mock->getSeverityName(), 'error');
		}

		public function testGetMessage() {
			$this->assertEquals($this->violation->getMessage(), 'Test');
		}

		public function testGetSource() {
			$this->assertEquals($this->violation->getSource(), 'Test');
		}
	}
