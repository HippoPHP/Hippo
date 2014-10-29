<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle;
	use PHPCheckstyle\File;
	use PHPCheckstyle\Violation;
	use PHPCheckstyle\CheckResult;

	class CheckResultTest extends \PHPUnit_Framework_TestCase {
		protected $instance;

		public function setUp() {
			$this->instance = new CheckResult;
		}

		public function testConstructor() {
			$this->assertInstanceOf('PHPCheckstyle\CheckResult', $this->instance);
		}

		public function testEmptyByDefault() {
			$this->assertEmpty($this->instance->getViolations());
		}

		public function testCount() {
			$this->assertEquals(0, $this->instance->count());
		}

		public function testAddViolation() {
			$file = new File('test.php', '<? echo 1; ?>');
			$violation = new Violation($file, 1, 1, Violation::SEVERITY_ERROR, 'Do not use short opening tags.', '<?');
			$this->instance->addViolation($violation);

			$this->assertTrue($this->instance->hasFailed());
			$this->assertFalse($this->instance->hasSucceeded());
		}

	}
