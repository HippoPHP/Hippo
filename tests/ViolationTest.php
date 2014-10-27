<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle\Violation;

	class ViolationTest extends \PHPUnit_Framework_TestCase {
		public function testGetSeverityFromString() {
			$violation = new Violation(1, 0, 0, "Test", "Test");
			$this->asset($violation->getSeverityFromString('info'), 1);
		}
	}