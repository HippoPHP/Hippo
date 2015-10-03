<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <jbrooksuk@me.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Tests\CLI;

use HippoPHP\Hippo\CLI\ArgParser;
use HippoPHP\Hippo\CLI\ArgParserOptions;
use PHPUnit_Framework_TestCase;

class ArgParserTest extends PHPUnit_Framework_TestCase
{
    public function testLongArgumentEqual()
    {
        $argContainer = ArgParser::parse(['--long=option']);
        $this->assertEquals('option', $argContainer->getLongOption('long'));
    }

    public function testLongArgumentAlternative()
    {
        $argContainer = ArgParser::parse(['--long', 'option']);
        $this->assertEquals('option', $argContainer->getLongOption('long'));
    }

    public function testLongArgumentsWithoutValue()
    {
        $argContainer = ArgParser::parse(['--long']);
        $this->assertNull($argContainer->getLongOption('long'));
    }

    public function testLongConsecutiveArgumentsWithoutValue()
    {
        $argContainer = ArgParser::parse(['--long', '--long2']);
        $this->assertNull($argContainer->getLongOption('long'));
        $this->assertNull($argContainer->getLongOption('long2'));
    }

    public function testShortArgumentWithoutValue()
    {
        $argContainer = ArgParser::parse(['-short']);
        $this->assertNull($argContainer->getShortOption('short'));
    }

    public function testShortConsecutiveArgumentsWithoutValue()
    {
        $argContainer = ArgParser::parse(['-short', '-short2']);
        $this->assertNull($argContainer->getShortOption('short'));
        $this->assertNull($argContainer->getShortOption('short2'));
    }

    public function testShortArgumentWithValue()
    {
        $argContainer = ArgParser::parse(['-short=option']);
        $this->assertEquals('option', $argContainer->getShortOption('short'));
    }

    public function testShortArgumentWithValueInNextArgument()
    {
        $argContainer = ArgParser::parse(['-short', 'option']);
        $this->assertEquals('option', $argContainer->getShortOption('short'));
    }

    public function testShortFlagWithValue()
    {
        $argParserOptions = new ArgParserOptions();
        $argParserOptions->markFlag('short');
        $argContainer = ArgParser::parse(['-short=1'], $argParserOptions);
        $this->assertTrue($argContainer->getShortOption('short'));
    }

    public function testFlagsWithNonBooleanStrayArgument()
    {
        $argParserOptions = new ArgParserOptions();
        $argParserOptions->markFlag('flag');
        $argContainer = ArgParser::parse(['--flag', 'stray'], $argParserOptions);
        $this->assertTrue($argContainer->getLongOption('flag'));
        $this->assertEquals(['stray'], $argContainer->getStrayArguments());
    }

    public function testFlagsWithBooleanStrayArgument()
    {
        $argParserOptions = new ArgParserOptions();
        $argParserOptions->markFlag('flag');
        $argContainer = ArgParser::parse(['--flag', '0', 'stray'], $argParserOptions);
        $this->assertFalse($argContainer->getLongOption('flag'));
        $this->assertEquals(['stray'], $argContainer->getStrayArguments());
    }

    public function testArrayArgument()
    {
        $argParserOptions = new ArgParserOptions();
        $argParserOptions->markArray('arg');
        $argContainer = ArgParser::parse(['--arg', '1,2,3;4 5', 'stray'], $argParserOptions);
        $this->assertEquals(['1', '2', '3', '4', '5'], $argContainer->getLongOption('arg'));
        $this->assertEquals(['stray'], $argContainer->getStrayArguments());
    }

    public function testStrayArguments()
    {
        $argContainer = ArgParser::parse(['stray1', 'stray2']);
        $this->assertEquals(['stray1', 'stray2'], $argContainer->getStrayArguments());
    }

    public function testMixedLongAndShortFlags()
    {
        $argContainer = ArgParser::parse(['--flag', '-flag']);
        $this->assertNull($argContainer->getLongOption('flag'));
        $this->assertNull($argContainer->getShortOption('flag'));
    }

    public function testStrayArgumentsMixedWithOptions()
    {
        $argContainer = ArgParser::parse(['--long', 'value', 'stray1', 'stray2']);
        $this->assertEquals('value', $argContainer->getLongOption('long'));
        $this->assertEquals(['stray1', 'stray2'], $argContainer->getStrayArguments());
    }

    public function testStrayArgumentsMixedWithOptionsManualStop()
    {
        $argContainer = ArgParser::parse(['--long', '--', 'stray1', 'stray2', 'stray3']);
        $this->assertNull($argContainer->getLongOption('long'));
        $this->assertEquals(['stray1', 'stray2', 'stray3'], $argContainer->getStrayArguments());
    }

    public function testEmptyStrayArguments()
    {
        $argContainer = ArgParser::parse([]);
        $this->assertEquals([], $argContainer->getStrayArguments());
    }
}
