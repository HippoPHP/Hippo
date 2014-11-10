<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\CheckContext;
	use \HippoPHP\Tokenizer\Token;
	use \PhpParser\Parser;

	class CheckContextTest extends \PHPUnit_Framework_TestCase {
		private $_file;
		private $_checkContext;

		public function setUp() {
			$this->_file = new File('tokio.php', '<?php');
			$this->_checkContext = new CheckContext($this->_file);
		}

		public function testGetFile() {
			$this->assertNotNull($this->_checkContext->getFile());
			$this->assertEquals('tokio.php', $this->_checkContext->getFile()->getFilename());
		}

		public function testGetTokenList() {
			$tokenList = $this->_checkContext->getTokenList();
			$this->assertNotNull($tokenList);
			$this->assertTrue(is_array($tokenList));
			$this->assertEquals(1, count($tokenList));
			$this->assertInstanceOf('\HippoPHP\Tokenizer\Token', $tokenList[0]);
		}

		public function testGetSyntaxTree() {
			$syntaxTree = $this->_checkContext->getSyntaxTree();
			$this->assertNotNull($syntaxTree);
			$this->assertTrue(is_array($syntaxTree));
			$this->assertInstanceOf('\PhpParser\Node\Stmt\InlineHTML', $syntaxTree[0]);
		}
	}
