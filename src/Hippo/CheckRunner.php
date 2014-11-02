<?php

	namespace Hippo;

	use Hippo\File;

	class CheckRunner {
		/**
		 * @var CheckRepository
		 */
		protected $checkRepository;

		/**
		 * @param CheckRepository
		 */
		public function __construct(CheckRepository $checkRepository) {
			$this->checkRepository = $checkRepository;
		}

		/**
		 * @param File $file
		 * @return CheckResult[]
		 */
		public function checkFile(File $file) {
			//TODO: prepare AST and token context here
			$results = [];
			foreach ($this->checkRepository->getChecks() as $check) {
				//TODO: inject context to Check here
				$results[] = $check->checkFile($file);
			}
			return $results;
		}
	}
