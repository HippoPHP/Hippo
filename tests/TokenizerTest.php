<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\Tokenizer;
	use \HippoPHP\Hippo\File;

	class TokenizerTest extends \PHPUnit_Framework_TestCase {
		protected $tokenizer;

		public function setUp() {
			$this->filename = 'test.php';

			$this->tokenizer = new Tokenizer;
		}

		public function testTokenizeWithDefaultNamespace() {
$source = <<<ESRC
<?php 
	namespace \HippoPHP\Hippo;
	if (0 > 1) {
		echo 'What? Zero can never be higher.';
	} else {
		echo "Cool.";
	}
	echo 'Hello'; 
?>
ESRC;
			$this->assertInstanceOf('\HippoPHP\Hippo\TokenList', $this->tokenizer->tokenize(new File($this->filename, $source)));
		}

		public function testTokenizeWithIdentNamespace() {
$source = <<<ESRC
<?php 
	namespace \HippoPHP\Hippo {
		function foo() {
			return "bar";
		}
	}
?>
ESRC;
			$this->assertInstanceOf('\HippoPHP\Hippo\TokenList', $this->tokenizer->tokenize(new File($this->filename, $source)));
		}
	}
