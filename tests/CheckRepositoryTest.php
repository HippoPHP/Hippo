<?php

namespace HippoPHP\Hippo\tests;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\CheckRepository;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\FileSystem;
use HippoPHP\Hippo\Tests\Helpers\FileSystemTestHelper;

/**
 * Class that tests discovery of Check implementations.
 * It's important to run each test in separate process - include() calls invoked by CheckRepository pollute global
 * namespace, which makes tests dependent on each other. This is undesired and obscures the tests.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class CheckRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $_checkRepository;

    public function setUp()
    {
        $this->_fileSystemMock = $this->getMockBuilder('\HippoPHP\Hippo\FileSystem')->disableOriginalConstructor()->getMock();
        $this->_fileSystemTestHelper = new FileSystemTestHelper();
        $this->_checkRepository = new CheckRepository($this->_fileSystemMock);
    }

    public function testSimpleClass()
    {
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        $objectName = $this->_getObjectName();
        file_put_contents($path, <<<ESRC
<?php
class $objectName implements \HippoPHP\Hippo\Checks\CheckInterface {
public function checkFile(\HippoPHP\Hippo\CheckContext \$checkContext, \HippoPHP\Hippo\Config\Config \$config) {
}

public function getConfigRoot() {
}
}
ESRC
);
        $this->_fileSystemMock->expects($this->once())->method('getAllFiles')->willReturn([$path]);
        $checks = $this->_checkRepository->getChecks();
        $this->assertEquals(1, count($checks));
        $this->assertInstanceOf($objectName, $checks[0]);
    }

    public function testAbstractClass()
    {
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        $objectName = $this->_getObjectName();
        file_put_contents($path, <<<ESRC
<?php
abstract class $objectName implements \HippoPHP\Hippo\Checks\CheckInterface {
}
ESRC
);
        $this->_fileSystemMock->expects($this->once())->method('getAllFiles')->willReturn([$path]);
        $checks = $this->_checkRepository->getChecks();
        $this->assertEquals(0, count($checks));
    }

    public function testInterface()
    {
        $path = $this->_fileSystemTestHelper->getTemporaryFilePath();
        $objectName = $this->_getObjectName();
        file_put_contents($path, <<<ESRC
<?php
interface $objectName extends \HippoPHP\Hippo\Checks\CheckInterface {
}
ESRC
);
        $this->_fileSystemMock->expects($this->once())->method('getAllFiles')->willReturn([$path]);
        $checks = $this->_checkRepository->getChecks();
        $this->assertEquals(0, count($checks));
    }

    private function _getObjectName()
    {
        return 'TestClass'.uniqid();
    }
}
