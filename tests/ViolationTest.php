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

use HippoPHP\Hippo\File;
use HippoPHP\Hippo\Violation;

class ViolationTest extends \PHPUnit_Framework_TestCase
{
    protected $violation;
    protected $file;

    public function setUp()
    {
        $this->file = new File();
        $this->violation = new Violation($this->file, 1, 0, 0, 'Test');
    }

    public function testGetSeveritiies()
    {
        $this->assertEquals([
            Violation::SEVERITY_ERROR,
            Violation::SEVERITY_WARNING,
            Violation::SEVERITY_INFO,
            Violation::SEVERITY_IGNORE,
        ], Violation::getSeverities());
    }

    public function testSeverityIgnoreValue()
    {
        $this->assertEquals(0, Violation::SEVERITY_IGNORE);
    }

    public function testSeverityInfoValue()
    {
        $this->assertEquals(1, Violation::SEVERITY_INFO);
    }

    public function testSeverityWarningValue()
    {
        $this->assertEquals(2, Violation::SEVERITY_WARNING);
    }

    public function testSeverityErrorValue()
    {
        $this->assertEquals(3, Violation::SEVERITY_ERROR);
    }

    public function testGetSeverityFromStringIgnore()
    {
        $this->assertEquals(Violation::SEVERITY_IGNORE, Violation::getSeverityFromString('ignore'));
    }

    public function testGetSeverityFromStringInfo()
    {
        $this->assertEquals(Violation::SEVERITY_INFO, Violation::getSeverityFromString('info'));
    }

    public function testGetSeverityFromStringWarning()
    {
        $this->assertEquals(Violation::SEVERITY_WARNING, Violation::getSeverityFromString('warning'));
    }

    public function testGetSeverityFromStringError()
    {
        $this->assertEquals(Violation::SEVERITY_ERROR, Violation::getSeverityFromString('error'));
    }

    public function testGetSeverityFromStringNull()
    {
        $this->assertNull(Violation::getSeverityFromString('foobar'));
    }

    public function testGetFile()
    {
        $this->assertEquals($this->file, $this->violation->getFile());
    }

    public function testGetLine()
    {
        $this->assertEquals(1, $this->violation->getLine());
    }

    public function testGetColumn()
    {
        $this->assertEquals(0, $this->violation->getColumn());
    }

    public function testGetSeverity()
    {
        $this->assertEquals(0, $this->violation->getSeverity());
    }

    public function testGetSeverityNameIgnore()
    {
        $mock = new Violation($this->file, 1, 0, 0, 'Test', 'Ignore');
        $this->assertEquals('ignore', $mock->getSeverityName());
    }

    public function testGetSeverityNameInfo()
    {
        $mock = new Violation($this->file, 1, 0, 1, 'Test', 'Info');
        $this->assertEquals('info', $mock->getSeverityName());
    }

    public function testGetSeverityNameWarning()
    {
        $mock = new Violation($this->file, 1, 0, 2, 'Test', 'Warning');
        $this->assertEquals('warning', $mock->getSeverityName());
    }

    public function testGetSeverityNameError()
    {
        $mock = new Violation($this->file, 1, 0, 3, 'Test', 'Error');
        $this->assertEquals('error', $mock->getSeverityName());
    }

    /**
     * getSeverityName should return error by default.
     */
    public function testGetSeverityNameException()
    {
        $mock = new Violation($this->file, 1, 0, 100, 'Test', 'Exception');
        $this->assertEquals('error', $mock->getSeverityName());
    }

    public function testGetMessage()
    {
        $this->assertEquals('Test', $this->violation->getMessage());
    }
}
