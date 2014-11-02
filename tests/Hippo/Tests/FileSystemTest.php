<?php

	namespace Hippo\Tests;

	use Hippo\FileSystem;
	use Hippo\Exception\FileNotFoundException;

	class FileSystemTest extends \PHPUnit_Framework_TestCase {
		protected $fileSystem;

		public function setUp() {
			$this->fileSystem = new FileSystem;
		}

		public function testGetContent() {
			$path = $this->_getTemporaryFilePath();
			$this->_runWithCleanup(function() use ($path) {
				$this->fileSystem->putContent($path, 'whatever');
				$this->assertEquals('whatever', $this->fileSystem->getContent($path));
			}, function() use ($path) {
				$this->_cleanup($path);
			});
		}

		public function testGetNonExistingContent() {
			$this->setExpectedException('Hippo\Exception\FileNotFoundException');
			$this->fileSystem->getContent('nope');
		}

		public function testOverwriteExistingContent() {
			$path = $this->_getTemporaryFilePath();
			$this->_runWithCleanup(function() use ($path) {
				touch($path);
				$this->fileSystem->putContent($path, 'whatever');
				$this->assertEquals('whatever', $this->fileSystem->getContent($path));
			}, function() use ($path) {
				$this->_cleanup($path);
			});
		}

		public function testReadFolder() {
			$this->setExpectedException('Hippo\Exception\FileNotReadableException');
			$path = $this->_getTemporaryFilePath();
			$this->_runWithCleanup(function() use ($path) {
				mkdir($path);
				$this->fileSystem->getContent($path, 'whatever');
			}, function() use ($path) {
				$this->_cleanup($path);
			});
		}

		public function testOverwriteFolder() {
			$this->setExpectedException('Hippo\Exception\FileNotWritableException');
			$path = $this->_getTemporaryFilePath();
			$this->_runWithCleanup(function() use ($path) {
				mkdir($path);
				$this->fileSystem->putContent($path, 'whatever');
			}, function() use ($path) {
				$this->_cleanup($path);
			});
		}

		public function testSavingToNotWritableTarget() {
			$this->setExpectedException('Hippo\Exception\FileNotWritableException');
			$path = $this->_getTemporaryFilePath();
			$subPath = $path . DIRECTORY_SEPARATOR . 'file.txt';
			$this->_runWithCleanup(function() use ($subPath) {
				$this->fileSystem->putContent($subPath, 'whatever');
			}, function() use ($path, $subPath) {
				$this->_cleanup($subPath);
				$this->_cleanup($path);
			});
		}

		private function _getTemporaryFilePath() {
			return sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'test_' . uniqid() . '_' . microtime(true);
		}

		private function _cleanup($file) {
			if (!file_exists($file)) {
				return;
			}
			if (is_dir($file)) {
				rmdir($file);
			} else {
				unlink($file);
			}
		}

		private function _runWithCleanUp($mainAction, $cleanupAction) {
			try {
				$mainAction();
			} catch (\Exception $e) {
				$cleanupAction();
				throw $e;
			}
			$cleanupAction();
		}
	}
