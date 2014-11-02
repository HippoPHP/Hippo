<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\Environment;
	use \HippoPHP\Hippo\Exception\ShutdownException;

	class EnvironmentTest extends \PHPUnit_Framework_TestCase {
		private $_environment;

		public function setUp() {
			$this->_environment = new Environment;
		}

		public function testDefaultExitCode() {
			$this->assertEquals(0, $this->_environment->getExitCode());
		}

		public function testSettingExitCode() {
			$this->_environment->setExitCode(1);
			$this->assertEquals(1, $this->_environment->getExitCode());
		}

		public function testDefaultExit() {
			try {
				$this->_environment->shutdown();
			} catch (ShutdownException $ex) {
				$this->assertEquals(0, $ex->getExitCode());
				return;
			}
			$this->fail('Shutdown exception not thrown');
		}

		public function testExitWithExitCode() {
			try {
				$this->_environment->setExitCode(1);
				$this->_environment->shutdown();
			} catch (ShutdownException $ex) {
				$this->assertEquals(1, $ex->getExitCode());
				return;
			}
			$this->fail('Shutdown exception not thrown');
		}
	}
