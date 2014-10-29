<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle\Tokenizer;

	class TokenizerTest extends \PHPUnit_Framework_TestCase {
		public function setUp() {
			$this->filename = 'test.php';
			$this->source = '<?php echo 1; ?>';
		}

		public function testTrue() {
			return $this->assertTrue(TRUE);
		}
	}
