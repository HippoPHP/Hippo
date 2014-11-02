<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\File;
	use \HippoPHP\Hippo\Checks\CheckInterface;

	use \ReflectionClass;

	class CheckRepository {
		/**
		 * @var boolean
		 */
		private $_hasBeenBuilt = false;

		/**
		 * @var Check[]
		 */
		private $_checks = [];

		/**
		 * @var FileSystem $fileSystem
		 */
		private $_fileSystem;

		public function __construct(FileSystem $fileSystem) {
			$this->_fileSystem = $fileSystem;
		}

		/**
		 * @return Check[]
		 */
		public function getChecks() {
			$this->_buildIfNecessary();
			return $this->_checks;
		}

		private function _buildIfNecessary() {
			if (!$this->_hasBeenBuilt) {
				$this->_build();
				$this->_hasBeenBuilt = true;
			}
		}

		private function _build() {
			foreach ($this->_fileSystem->getAllFiles($this->_getRootDirectory(), '/^.*\.php$/') as $filePath) {
				require_once($filePath);
			}

			$this->_checks = [];
			foreach (get_declared_classes() as $class) {
				$reflectionClass = new ReflectionClass($class);
				if ($this->_canInstantiate($reflectionClass)) {
					//TODO: incorporate config here
					$this->_checks[] = $reflectionClass->newInstance();
				}
			}
		}

		private function _getRootDirectory() {
			return __DIR__ . DIRECTORY_SEPARATOR . 'Checks';
		}

		private function _canInstantiate(ReflectionClass $reflectionClass) {
			return $reflectionClass->implementsInterface('Hippo\Checks\CheckInterface')
				&& !$reflectionClass->isInterface()
				&& !$reflectionClass->isAbstract();
		}
	}

