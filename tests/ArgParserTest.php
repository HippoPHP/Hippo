<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\ArgParser;
	use \HippoPHP\Hippo\ArgParserOptions;

	class ArgParserTest extends \PHPUnit_Framework_TestCase {
		public function testLongArgumentEqual() {
			$argContainer = ArgParser::parse(['--long=option']);
			$this->assertEquals('option', $argContainer->getLongOption('long'));
		}

		public function testLongArgumentAlternative() {
			$argContainer = ArgParser::parse(['--long', 'option']);
			$this->assertEquals('option', $argContainer->getLongOption('long'));
		}

		public function testLongFlagArguments() {
			$argContainer = ArgParser::parse(['--long']);
			$this->assertTrue($argContainer->getLongOption('long'));
		}

		public function testLongConsecutiveFlagArguments() {
			$argContainer = ArgParser::parse(['--long', '--long2']);
			$this->assertTrue($argContainer->getLongOption('long'));
			$this->assertTrue($argContainer->getLongOption('long2'));
		}

		public function testShortArgumentEqual() {
			$argContainer = ArgParser::parse(['-short=option']);
			$this->assertEquals('option', $argContainer->getShortOption('short'));
		}

		public function testShortArgumentAlternative() {
			$argContainer = ArgParser::parse(['-short', 'option']);
			$this->assertEquals('option', $argContainer->getShortOption('short'));
		}

		public function testShortFlagArguments() {
			$argContainer = ArgParser::parse(['-short']);
			$this->assertTrue($argContainer->getShortOption('short'));
		}

		public function testShortFlagArgumentWithBooleanInlineArgument() {
			$argParserOptions = new ArgParserOptions();
			$argParserOptions->markFlag('short');
			$argContainer = ArgParser::parse(['-short=1'], $argParserOptions);
			$this->assertTrue($argContainer->getShortOption('short'));
		}

		public function testShortConsecutiveFlagArguments() {
			$argContainer = ArgParser::parse(['-short', '-short2']);
			$this->assertTrue($argContainer->getShortOption('short'));
			$this->assertTrue($argContainer->getShortOption('short2'));
		}

		public function testFlagsWithNonBooleanStrayArgument() {
			$argParserOptions = new ArgParserOptions();
			$argParserOptions->markFlag('flag');
			$argContainer = ArgParser::parse(['--flag', 'stray'], $argParserOptions);
			$this->assertTrue($argContainer->getLongOption('flag'));
			$this->assertEquals(['stray'], $argContainer->getStrayArguments());
		}

		public function testFlagsWithBooleanStrayArgument() {
			$argParserOptions = new ArgParserOptions();
			$argParserOptions->markFlag('flag');
			$argContainer = ArgParser::parse(['--flag', '0', 'stray'], $argParserOptions);
			$this->assertFalse($argContainer->getLongOption('flag'));
			$this->assertEquals(['stray'], $argContainer->getStrayArguments());
		}

		public function testArrayArgument() {
			$argParserOptions = new ArgParserOptions();
			$argParserOptions->markArray('arg');
			$argContainer = ArgParser::parse(['--arg', '1,2,3;4 5', 'stray'], $argParserOptions);
			$this->assertEquals(['1', '2', '3', '4', '5'], $argContainer->getLongOption('arg'));
			$this->assertEquals(['stray'], $argContainer->getStrayArguments());
		}

		public function testStrayArguments() {
			$argContainer = ArgParser::parse(['stray1', 'stray2']);
			$this->assertEquals(['stray1', 'stray2'], $argContainer->getStrayArguments());
		}

		public function testMixedLongAndShortFlags() {
			$argContainer = ArgParser::parse(['--flag', '-flag']);
			$this->assertTrue($argContainer->getLongOption('flag'));
			$this->assertTrue($argContainer->getShortOption('flag'));
		}

		public function testStrayArgumentsMixedWithOptions() {
			$argContainer = ArgParser::parse(['--long', 'value', 'stray1', 'stray2']);
			$this->assertEquals('value', $argContainer->getLongOption('long'));
			$this->assertEquals(['stray1', 'stray2'], $argContainer->getStrayArguments());
		}

		public function testStrayArgumentsMixedWithOptionsManualStop() {
			$argContainer = ArgParser::parse(['--long', '--', 'stray1', 'stray2', 'stray3']);
			$this->assertTrue($argContainer->getLongOption('long'));
			$this->assertEquals(['stray1', 'stray2', 'stray3'], $argContainer->getStrayArguments());
		}

		public function testEmptyStrayArguments() {
			$argContainer = ArgParser::parse([]);
			$this->assertEquals([], $argContainer->getStrayArguments());
		}
	}
