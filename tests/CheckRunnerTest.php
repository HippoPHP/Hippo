<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\Config;
	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\CheckRunner;
	use \HippoPHP\Hippo\CheckRepository;
	use \HippoPHP\Hippo\Config\YAMLConfigReader;

	class CheckRunnerTest extends \PHPUnit_Framework_TestCase {
		protected $instance;

		public function setUp() {
			$fileSystem = new FileSystem;
			$checkRepository = new CheckRepository($fileSystem);
			$configReader = new YAMLConfigReader($fileSystem);
			// $config = $configReader->loadFromFile

			// $this->instance = new CheckRunner($fileSystem, $checkRepository, $configReader);
		}

		public function testSetObserver() {
			$this->markTestIncomplete(
				'This test has not been implemented yet.'
			);
		}

		public function testCheckPath() {
			$this->markTestIncomplete(
				'This test has not been implemented yet.'
			);
		}

		public function testCheckFile() {
			$this->markTestIncomplete(
				'This test has not been implemented yet.'
			);
		}
	}
