<?php

	namespace Hippo\Tests;

	use Hippo\ArgParser;

	class ArgParserTest extends \PHPUnit_Framework_TestCase {
		public function testLongArgumentEqual() {
			$argOptions = ArgParser::parse(['--long=option']);
			$this->assertEquals('option', $argOptions->getLongOption('long'));
		}

		public function testLongArgumentAlternative() {
			$argOptions = ArgParser::parse(['--long', 'option']);
			$this->assertEquals('option', $argOptions->getLongOption('long'));
		}

		public function testLongFlagArguments() {
			$argOptions = ArgParser::parse(['--long']);
			$this->assertTrue($argOptions->getLongOption('long'));
		}

		public function testLongConsecutiveFlagArguments() {
			$argOptions = ArgParser::parse(['--long', '--long2']);
			$this->assertTrue($argOptions->getLongOption('long'));
			$this->assertTrue($argOptions->getLongOption('long2'));
		}

		public function testShortArgumentEqual() {
			$argOptions = ArgParser::parse(['-short=option']);
			$this->assertEquals('option', $argOptions->getShortOption('short'));
		}

		public function testShortArgumentAlternative() {
			$argOptions = ArgParser::parse(['-short', 'option']);
			$this->assertEquals('option', $argOptions->getShortOption('short'));
		}

		public function testShortFlagArguments() {
			$argOptions = ArgParser::parse(['-short']);
			$this->assertTrue($argOptions->getShortOption('short'));
		}

		public function testShortConsecutiveFlagArguments() {
			$argOptions = ArgParser::parse(['-short', '-short2']);
			$this->assertTrue($argOptions->getShortOption('short'));
			$this->assertTrue($argOptions->getShortOption('short2'));
		}

		public function testStrayArguments() {
			$argOptions = ArgParser::parse(['stray1', 'stray2']);
			$this->assertEquals(['stray1', 'stray2'], $argOptions->getStrayArguments());
		}

		public function testMixedLongAndShortFlags() {
			$argOptions = ArgParser::parse(['--flag', '-flag']);
			$this->assertTrue($argOptions->getLongOption('flag'));
			$this->assertTrue($argOptions->getShortOption('flag'));
		}

		public function testStrayArgumentsMixedWithOptions() {
			$argOptions = ArgParser::parse(['--long', 'value', 'stray1', 'stray2']);
			$this->assertEquals('value', $argOptions->getLongOption('long'));
			$this->assertEquals(['stray1', 'stray2'], $argOptions->getStrayArguments());
		}

		public function testStrayArgumentsMixedWithOptionsManualStop() {
			$argOptions = ArgParser::parse(['--long', '--', 'stray1', 'stray2', 'stray3']);
			$this->assertTrue($argOptions->getLongOption('long'));
			$this->assertEquals(['stray1', 'stray2', 'stray3'], $argOptions->getStrayArguments());
		}
	}
