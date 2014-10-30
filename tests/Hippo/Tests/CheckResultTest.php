<?php 

	namespace Hippo\Tests;

	use Hippo;
	use Hippo\File;
	use Hippo\Violation;
	use Hippo\CheckResult;

	class CheckResultTest extends \PHPUnit_Framework_TestCase {
		protected $instance;
		protected $file;

		public function setUp() {
			$this->instance = new CheckResult;
		}

		public function testConstructor() {
			$this->assertInstanceOf('Hippo\CheckResult', $this->instance);
		}

		public function testGetFileAtStartup() {
			$this->assertNull($this->instance->getFile());
		}

		public function testEmptyByDefault() {
			$this->assertEmpty($this->instance->getViolations());
		}

		public function testCount() {
			$this->assertEquals(0, $this->instance->count());
		}

		public function testSetFile() {
			$this->instance->setFile(new File('test.php', '<?php echo 1 ?>'));

			$this->assertInstanceOf('Hippo\File', $this->instance->getFile());
		}

		public function testAddViolation() {
			$file = new File('test.php', '<? echo 1; ?>');
			$violation = new Violation($file, 1, 1, Violation::SEVERITY_ERROR, 'Do not use short opening tags.', '<?');
			$this->instance->addViolation($violation);

			$this->assertTrue($this->instance->hasFailed());
			$this->assertFalse($this->instance->hasSucceeded());
		}

		/**
		 * @dataProvider violationOrderProvider
		 */
		public function testViolationOrder(array $inputViolations, array $expectedViolations) {
			foreach ($inputViolations as $violation) {
				$this->instance->addViolation($violation);
			}

			$actualViolations = $this->instance->getViolations();
			$this->assertEquals($expectedViolations, $actualViolations);
		}

		public function violationOrderProvider() {
			$file = new File('test.php', '<? echo 1; ?>');
			$violationLine1Col1 = new Violation($file, 1, 1, Violation::SEVERITY_ERROR, null, null);
			$violationLine2Col1 = new Violation($file, 2, 1, Violation::SEVERITY_ERROR, null, null);
			$violationLine3Col1 = new Violation($file, 3, 1, Violation::SEVERITY_ERROR, null, null);
			$violationLine1Col2 = new Violation($file, 1, 2, Violation::SEVERITY_ERROR, null, null);
			$violationLine2Col2 = new Violation($file, 2, 2, Violation::SEVERITY_ERROR, null, null);
			return array(
				array(array($violationLine1Col1, $violationLine2Col1), array($violationLine1Col1, $violationLine2Col1)),
				array(array($violationLine2Col1, $violationLine1Col1), array($violationLine1Col1, $violationLine2Col1)),
				array(array($violationLine2Col1, $violationLine3Col1), array($violationLine2Col1, $violationLine3Col1)),
				array(
					array($violationLine2Col1, $violationLine1Col2, $violationLine3Col1, $violationLine2Col2),
					array($violationLine1Col2, $violationLine2Col1, $violationLine2Col2, $violationLine3Col1)),
			);
		}
	}
