<?php

	namespace Hippo\Tests;

	use Hippo\Config\YAMLConfigReader;
	use Hippo\FileSystem;

	class YAMLConfigReaderTest extends \PHPUnit_Framework_TestCase {
		private $_reader;
		private $_fileSystemMock;

		public function setUp() {
			$this->_fileSystemMock = $this->getMockBuilder('Hippo\FileSystem')->disableOriginalConstructor()->getMock();
			$this->_reader = new YAMLConfigReader($this->_fileSystemMock);
		}

		public function testLoadFromFile() {
			$yamlConfig = <<<YML
standards: "PSR-1"
YML;

			$this->_fileSystemMock
				->expects($this->once())
				->method('getContent')
				->willReturn($yamlConfig);

			$config = $this->_reader->loadFromFile('test.txt');
			$this->assertNotNull($config);
			$this->assertEquals('PSR-1', $config['standards']);
		}

		public function testLoadFromFileExtended() {
			$baseYamlConfig = <<<YML
bracesOnNewLine: true
parent: 1
YML;

			$yamlConfig = <<<YML
extends: "PSR-1"

bracesOnNewLine: false
child: 2
YML;

			$this->_fileSystemMock
				->expects($this->exactly(2))
				->method('getContent')
				->withConsecutive(['initial.txt'], ['.' . DIRECTORY_SEPARATOR . 'PSR-1.yml'])
				->will($this->onConsecutiveCalls($yamlConfig, $baseYamlConfig));

			$config = $this->_reader->loadFromFile('initial.txt');
			$this->assertFalse($config['bracesOnNewLine']);
			$this->assertEquals('1', $config['parent']);
			$this->assertEquals('2', $config['child']);
		}

		public function testLoadFromFileExtendedTwice() {
			$baseYamlConfig = <<<YML
bracesOnNewLine: true
parent: 1
YML;

			$middleYamlConfig = <<<YML
extends: "base"
bracesOnNewLine: true
middle: 2
YML;

			$yamlConfig = <<<YML
extends: "middle"

bracesOnNewLine: false
child: 3
YML;

			$this->_fileSystemMock
				->expects($this->exactly(3))
				->method('getContent')
				->withConsecutive(
					['initial.txt'],
					['.' . DIRECTORY_SEPARATOR . 'middle.yml'],
					['.' . DIRECTORY_SEPARATOR . 'base.yml'])
				->will($this->onConsecutiveCalls($yamlConfig, $middleYamlConfig, $baseYamlConfig));

			$config = $this->_reader->loadFromFile('initial.txt');
			$this->assertFalse($config['bracesOnNewLine']);
			$this->assertEquals('1', $config['parent']);
			$this->assertEquals('2', $config['middle']);
			$this->assertEquals('3', $config['child']);
		}

		public function testLoadFromFileExtendedCycle() {
			$baseYamlConfig = <<<YML
extends: "initial"
bracesOnNewLine: true
parent: 1
YML;

			$childYamlConfig = <<<YML
extends: "base"
bracesOnNewLine: false
child: 2
YML;

			$this->_fileSystemMock
				->expects($this->exactly(2))
				->method('getContent')
				->withConsecutive(
					['initial.yml'],
					['.' . DIRECTORY_SEPARATOR . 'base.yml'])
				->will($this->onConsecutiveCalls($childYamlConfig, $baseYamlConfig));

			$config = $this->_reader->loadFromFile('initial.yml');
			$this->assertFalse($config['bracesOnNewLine']);
			$this->assertEquals('1', $config['parent']);
			$this->assertEquals('2', $config['child']);
		}
	}
