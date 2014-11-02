<?php

	namespace HippoPHP\Hippo\Checks\Line;

	use HippoPHP\Hippo\Checks\AbstractCheck;
	use HippoPHP\Hippo\Checks\CheckInterface;
	use HippoPHP\Hippo\Config\Config;
	use HippoPHP\Hippo\File;

	class MustStartWithOpenTagCheck extends AbstractCheck implements CheckInterface {
		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.open_tag';
		}

		/**
		 * checkFileInternal(): defined by AbstractCheck.
		 * @see AbstractCheck::checkFileInternal()
		 * @param File $file
		 * @param Config $config
		 * @return void
		 */
		protected function checkFileInternal(File $file, Config $config) {
			if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
				$this->addViolation($file, 1, 1, 'Files must begin with the PHP open tag.');
			}
		}

	}
