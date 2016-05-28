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

namespace HippoPHP\tests;

use HippoPHP\Hippo\LazyFactory;
use PHPUnit_Framework_TestCase;

class LazyFactoryTest extends PHPUnit_Framework_TestCase
{
    private $_lazyFactory;

    public function setUp()
    {
        $this->_lazyFactory = new LazyFactory();
    }

    public function testReference()
    {
        $value1 = $this->_lazyFactory->get('key', function () {
            return 'value';
        });
        $value2 = $this->_lazyFactory->get('key', function () {
            return 'value';
        });
        $this->assertSame($value1, $value2);
    }

    public function testCallbackLaziness()
    {
        $value1 = $this->_lazyFactory->get('key', function () {
            return 'persistent value';
        });
        $value2 = $this->_lazyFactory->get('key', function () {
            return 'nope, this key is already cached';
        });
        $this->assertEquals('persistent value', $value1);
        $this->assertEquals('persistent value', $value2);
    }

    public function testCacheReset()
    {
        $value1 = $this->_lazyFactory->get('key', function () {
            return 'obedient value';
        });
        $this->_lazyFactory->resetCache();
        $value2 = $this->_lazyFactory->get('key', function () {
            return 'new value';
        });
        $this->assertEquals('obedient value', $value1);
        $this->assertEquals('new value', $value2);
    }
}
