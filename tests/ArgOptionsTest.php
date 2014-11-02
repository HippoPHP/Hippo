<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\ArgOptions;

	class ArgOptionsText extends \PHPUnit_Framework_TestCase {
		protected $argOptions;

		public function setUp() {
			$this->argOptions = new ArgOptions();
		}

		public function testSetShortOption() {
			$this->argOptions->setShortOption('option', 'value');
			$this->assertEquals('value', $this->argOptions->getShortOption('option'));
		}

		public function testSetLongOption() {
			$this->argOptions->setLongOption('option', 'value');
			$this->assertEquals('value', $this->argOptions->getLongOption('option'));
		}

		public function testNullShortOption() {
			$this->assertEquals(null, $this->argOptions->getShortOption('option'));
		}

		public function testNullLongOption() {
			$this->assertEquals(null, $this->argOptions->getLongOption('option'));
		}

		public function testStrayArguments() {
			$this->argOptions->addStrayArgument('arg1');
			$this->argOptions->addStrayArgument('arg2');
			$this->assertEquals(['arg1', 'arg2'], $this->argOptions->getStrayArguments());
		}

		public function testGetShortOptions() {
			$this->argOptions->setShortOption('option', 'value');
			$this->assertEquals(['option' => 'value'], $this->argOptions->getShortOptions());
		}

		public function testGetLongOptions() {
			$this->argOptions->setLongOption('option', 'value');
			$this->assertEquals(['option' => 'value'], $this->argOptions->getLongOptions());
		}
	}
