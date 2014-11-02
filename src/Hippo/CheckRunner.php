<?php

	namespace Hippo;

	use Hippo\Config\Config;
	use Hippo\File;
	use \Exception;

	class CheckRunner {
		/**
		 * @var CheckRepository
		 */
		private $_checkRepository;

		/**
		 * @var Config
		 */
		private $_config;

		/**
		 * @param CheckRepository
		 */
		public function __construct(CheckRepository $checkRepository, Config $config) {
			$this->_checkRepository = $checkRepository;
			$this->_config = $config;
		}

		/**
		 * @param File $file
		 * @return CheckResult[]
		 */
		public function checkFile(File $file) {
			//TODO: prepare AST and token context here
			$results = [];
			foreach ($this->_checkRepository->getChecks() as $check) {
				//TODO: inject context to Check here
				$branch = $this->_config->get($check->getConfigRoot());
				if ($branch->get('enabled') === true) {
					$results[] = $check->checkFile($file, $branch);
				}
			}
			return $results;
		}
	}
