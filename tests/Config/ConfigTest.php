<?php

	namespace HippoPHP\Hippo\Tests;

	use \HippoPHP\Hippo\Config\Config;
	use \HippoPHP\Hippo\Exception\BadConfigKeyException;

	class ConfigTest extends \PHPUnit_Framework_TestCase {
		public function testGetNonExistingValue() {
			$config = new Config();
			$this->setExpectedException('HippoPHP\Hippo\Exception\BadConfigKeyException');
			$config->get('nope');
		}

		public function testGetNonExistingValueWithDefault() {
			$config = new Config();
			$this->assertEquals('whatever', $config->get('nope', 'whatever'));
			$this->assertNull($config->get('nope', null));
		}

		public function testBasicSet() {
			$config = new Config();
			$config->set('hippo', 1);
			$config->set('elephant', 2);
			$this->assertEquals(1, $config->get('hippo'));
			$this->assertEquals(2, $config->get('elephant'));
		}

		public function testKeyNormalization() {
			$config = new Config();
			$config->set('wild_case', 1);
			$this->assertEquals(1, $config->get('wildCase'));
		}

		public function testBasicNesting() {
			$config = new Config();
			$config->set('parent.child', 1);
			$this->assertEquals(1, $config->get('parent.child'));
		}

		public function testGettingBranches() {
			$config = new Config();
			$config->set('parent.child', 1);
			$this->assertInstanceOf('HippoPHP\Hippo\Config\Config', $config->get('parent'));
			$this->assertEquals(1, $config->get('parent')->get('child'));
		}

		public function testTraversingNonArrayNodes() {
			$config = new Config();
			$config->set('parent', 'whatever');
			$this->setExpectedException('HippoPHP\Hippo\Exception\BadConfigKeyException');
			$config->get('parent.child');
		}

		public function testOverwritingNonArrayNodes() {
			$config = new Config();
			$config->set('parent', 'whatever');
			$config->set('parent.child', 1);
			$this->assertEquals(1, $config->get('parent.child'));
			$this->assertInstanceOf('HippoPHP\Hippo\Config\Config', $config->get('parent'));
		}

		public function testSettingArrays() {
			$config = new Config();
			$config->set('parent', ['child' => ['grandchild' => 1], 'sibling' => 2]);
			$this->assertEquals(1, $config->get('parent.child.grandchild'));
			$this->assertEquals(2, $config->get('parent.sibling'));
		}

		public function testRemovingBranch() {
			$config = new Config();
			$config->set('parent', ['child' => ['grandchild' => 1], 'sibling' => 2]);
			$config->remove('parent.child');
			$this->assertEquals(2, $config->get('parent.sibling'));
			$this->setExpectedException('HippoPHP\Hippo\Exception\BadConfigKeyException');
			$config->get('parent.child');
		}

		public function testRemovingNonexistingBranch() {
			$config = new Config();
			$config->remove('node');
			$this->assertNull($config->get('node', null));
		}
	}
