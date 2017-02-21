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

use HippoPHP\Hippo\CLI\ArgContainer;
use PHPUnit_Framework_TestCase;

class ArgContainerTest extends PHPUnit_Framework_TestCase
{
    protected $argContainer;

    public function setUp()
    {
        $this->argContainer = new ArgContainer();
    }

    public function testSetShortOption()
    {
        $this->argContainer->setShortOption('option', 'value');
        $this->assertEquals('value', $this->argContainer->getShortOption('option'));
    }

    public function testSetLongOption()
    {
        $this->argContainer->setLongOption('option', 'value');
        $this->assertEquals('value', $this->argContainer->getLongOption('option'));
    }

    public function testNullShortOption()
    {
        $this->assertEquals(null, $this->argContainer->getShortOption('option'));
    }

    public function testNullLongOption()
    {
        $this->assertEquals(null, $this->argContainer->getLongOption('option'));
    }

    public function testStrayArguments()
    {
        $this->argContainer->addStrayArgument('arg1');
        $this->argContainer->addStrayArgument('arg2');
        $this->assertEquals(['arg1', 'arg2'], $this->argContainer->getStrayArguments());
    }

    public function testGetShortContainer()
    {
        $this->argContainer->setShortOption('option', 'value');
        $this->assertEquals(['option' => 'value'], $this->argContainer->getShortOptions());
    }

    public function testGetLongContainer()
    {
        $this->argContainer->setLongOption('option', 'value');
        $this->assertEquals(['option' => 'value'], $this->argContainer->getLongOptions());
    }

    public function testGetAllContainer()
    {
        $this->argContainer->setShortOption('o', 'value');
        $this->argContainer->setLongOption('option', 'value');
        $this->assertEquals(['o' => 'value', 'option' => 'value'], $this->argContainer->getAllOptions());
    }
}
