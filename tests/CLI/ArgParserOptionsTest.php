<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <james@alt-three.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Tests\CLI;

use HippoPHP\Hippo\CLI\ArgParserOptions;
use PHPUnit_Framework_TestCase;

class ArgParserOptionsTest extends PHPUnit_Framework_TestCase
{
    protected $argParserOptions;

    public function setUp()
    {
        $this->argParserOptions = new ArgParserOptions();
    }

    public function testIsNotFlag()
    {
        $this->assertFalse($this->argParserOptions->isFlag('nope'));
    }

    public function testIsFlag()
    {
        $this->argParserOptions->markFlag('nope');
        $this->assertTrue($this->argParserOptions->isFlag('nope'));
    }

    public function testIsNotArray()
    {
        $this->assertFalse($this->argParserOptions->isArray('nope'));
    }

    public function testIsArray()
    {
        $this->argParserOptions->markArray('nope');
        $this->assertTrue($this->argParserOptions->isArray('nope'));
    }
}
