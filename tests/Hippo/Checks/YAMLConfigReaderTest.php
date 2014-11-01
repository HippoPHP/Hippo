<?php 

	namespace Hippo\Tests;

	use Hippo\Config\YAMLConfigReader;

	class YAMLConfigReaderTest extends \PHPUnit_Framework_TestCase {
		private $_reader;

		public function setUp() {
			$this->_reader = new YAMLConfigReader;
		}

		public function testDeserializeMethod() {
			$yamlConfig = <<<YML
standards: "PSR-1"
YML;

			$config = $this->_reader->deserialize($yamlConfig);
			$this->assertNotNull($config);
		}

		public function testDeserializeExtendedMethod() {
			$yamlConfig = <<<YML
extends: "PSR-1"

bracesOnNewLine: false
YML;

			$config = $this->_reader->deserialize($yamlConfig);
			$this->assertFalse($config['bracesOnNewLine']);
		}
	}