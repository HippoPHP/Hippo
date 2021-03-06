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

namespace HippoPHP\tests;

use HippoPHP\Hippo\CheckRepository;
use HippoPHP\Hippo\CheckRunner;
use HippoPHP\Hippo\Config\YAMLConfigReader;
use HippoPHP\Hippo\FileSystem;
use PHPUnit_Framework_TestCase;

class CheckRunnerTest extends PHPUnit_Framework_TestCase
{
    private $_fileSystemMock;

    protected $instance;

    public function setUp()
    {
        $this->_fileSystemMock = $this->getMockBuilder('HippoPHP\Hippo\FileSystem')->disableOriginalConstructor()->getMock();

        $fileSystem = new FileSystem();
        $checkRepository = new CheckRepository($fileSystem);
        $configReader = new YAMLConfigReader($this->_fileSystemMock);

        $yamlConfig = <<<'YML'
standards: "PSR-1"
YML;

        $this->_fileSystemMock
                ->expects($this->once())
                ->method('getContent')
                ->willReturn($yamlConfig);

        $config = $configReader->loadFromFile('test.txt');

        $this->instance = new CheckRunner($fileSystem, $checkRepository, $config);
    }

    public function testSetObserver()
    {
        $callable = function () { /**/
        };

        $this->assertInstanceOf(
            '\HippoPHP\Hippo\CheckRunner',
            $this->instance->setObserver($callable)
        );
    }

    public function testCheckPath()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testCheckFile()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
