<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle\Tokenizer;

	class TokenizerTest extends \PHPUnit_Framework_TestCase {
		protected $tokenizer;

		public function setUp() {
			$this->filename = 'test.php';

			$this->tokenizer = new Tokenizer;
		}

		public function testTokenizeWithDefaultNamespace() {
$source = <<<ESRC
<?php 
	namespace PHPCheckstyle;
	if (0 > 1) {
		echo 'What? Zero can never be higher.';
	} else {
		echo "Cool.";
	}
	echo 'Hello'; 
?>
ESRC;
			$this->assertInstanceOf('PHPCheckstyle\File', $this->tokenizer->tokenize($this->filename, $source));
		}

		public function testTokenizeWithIdentNamespace() {
$source = <<<ESRC
<?php 
	namespace PHPCheckstyle {
		function foo() {
			return "bar";
		}
	}
	if (0 > 1) {
		echo 'What? Zero can never be higher.';
	} else {
		echo "Cool.";
	}
	echo 'Hello'; 
?>
ESRC;
			$this->assertInstanceOf('PHPCheckstyle\File', $this->tokenizer->tokenize($this->filename, $source));
		}
	}
