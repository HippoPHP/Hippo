<?php

	namespace Hippo\Tests;

	use Hippo\Token;

	class TokenTest extends \PHPUnit_Framework_TestCase {
		protected $token;

		public function setUp() {
			$this->token = new Token(T_OPEN_TAG, '<?php', 1, 1);
		}

		public function testGetType() {
			$this->assertEquals(T_OPEN_TAG, $this->token->getType());
		}

		public function testGetLexeme() {
			$this->assertEquals('<?php', $this->token->getLexeme());
		}

		public function testGetLine() {
			$this->assertEquals(1, $this->token->getLine());
		}

		public function testGetColumn() {
			$this->assertEquals(1, $this->token->getColumn());
		}

		public function testSetLevel() {
			$token = $this->token->setLevel(1);
			$this->assertInstanceOf('Hippo\Token', $token);
		}

		public function testGetLevel() {
			$this->assertEquals(0, $this->token->getLevel());
		}

		public function testSetNamespace() {
			$this->assertInstanceOf('Hippo\Token', $this->token->setNamespace('Hippo'));
		}

		public function testGetNamespace() {
			$this->assertNull($this->token->getNamespace());
		}

		public function testHasNewLineNoNewLine() {
			$this->assertFalse($this->token->hasNewLine());
		}

		public function testHasNewLineWithNewLine() {
			$token = new Token(T_OPEN_TAG, "<?php\n", 1, 1);
			$this->assertTrue($token->hasNewLine());
		}

		public function testGetNewlineCount() {
			$this->assertEquals(0, $this->token->getNewlineCount());
		}

		public function testGetTrailingLineLength() {
			$this->assertEquals(0, $this->token->getTrailingLineLength());
		}

		public function testGetLength() {
			$this->assertEquals(5, $this->token->getLength());
		}
	}
