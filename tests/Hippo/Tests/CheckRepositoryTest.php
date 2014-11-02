<?php

	namespace Hippo\Tests;

	use Hippo\CheckRepository;
	use Hippo\Config\Config;
	use Hippo\File;
	use Hippo\FileSystem;
	use Hippo\Tests\Helpers\FileSystemTestHelper;

	/**
	 * Class that tests discovery of Check implementations.
	 * It's important to run each test in separate process - include() calls invoked by CheckRepository pollute global
	 * namespace, which makes tests dependent on each other. This is undesired and obscures the tests.
	 *
	 * @runTestsInSeparateProcesses
	 * @preserveGlobalState disabled
	 */
	class CheckRepositoryTest extends \PHPUnit_Framework_TestCase {
		private $_checkRepository;

		public function setUp() {
			$this->_fileSystemMock = $this->getMockBuilder('Hippo\FileSystem')->disableOriginalConstructor()->getMock();
			$this->_fileSystemTestHelper = new FileSystemTestHelper;
			$this->_checkRepository = new CheckRepository($this->_fileSystemMock);
		}

		public function testSimpleClass() {
			$path = $this->_fileSystemTestHelper->getTemporaryFilePath();
			$objectName = $this->_getObjectName();
			file_put_contents($path, <<<ESRC
<?php
class $objectName implements Hippo\Checks\CheckInterface {
	public function checkFile(Hippo\File \$file, Hippo\Config\Config \$config) {
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

		public function testAbstractClass() {
			$path = $this->_fileSystemTestHelper->getTemporaryFilePath();
			$objectName = $this->_getObjectName();
			file_put_contents($path, <<<ESRC
<?php
abstract class $objectName implements Hippo\Checks\CheckInterface {
}
ESRC
);
			$this->_fileSystemMock->expects($this->once())->method('getAllFiles')->willReturn([$path]);
			$checks = $this->_checkRepository->getChecks();
			$this->assertEquals(0, count($checks));
		}

		public function testInterface() {
			$path = $this->_fileSystemTestHelper->getTemporaryFilePath();
			$objectName = $this->_getObjectName();
			file_put_contents($path, <<<ESRC
<?php
interface $objectName extends Hippo\Checks\CheckInterface {
}
ESRC
);
			$this->_fileSystemMock->expects($this->once())->method('getAllFiles')->willReturn([$path]);
			$checks = $this->_checkRepository->getChecks();
			$this->assertEquals(0, count($checks));
		}

		private function _getObjectName() {
			return 'TestClass' . uniqid();
		}
	}