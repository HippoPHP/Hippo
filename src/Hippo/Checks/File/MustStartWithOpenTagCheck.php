<?php

	namespace Hippo\Checks\Line;

	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\CheckInterface;
	use Hippo\Config\Config;
	use Hippo\File;

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
