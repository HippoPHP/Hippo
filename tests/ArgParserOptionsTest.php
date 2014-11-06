<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\ArgParserOptions;

	class ArgParserOptionsTest extends \PHPUnit_Framework_TestCase {
		protected $argParserOptions;

		public function setUp() {
			$this->argParserOptions = new ArgParserOptions();
		}

		public function testIsNotFlag() {
			$this->assertFalse($this->argParserOptions->isFlag('nope'));
		}

		public function testIsFlag() {
			$this->argParserOptions->markFlag('nope');
			$this->assertTrue($this->argParserOptions->isFlag('nope'));
		}
	}
