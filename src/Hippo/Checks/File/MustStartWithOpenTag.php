<?php

	namespace Hippo\Checks\Line;

	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\CheckInterface;
	use Hippo\File;

	class MustStartWithOpenTag extends AbstractCheck implements CheckInterface {
		/**
		 * checkFile(): defined by CheckInterface.
		 * @see CheckInterface::checkFile()
		 * @param File $file
		 * @return void
		 */
		public function checkFile(File $file) {
			if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
				$this->addViolation($file, 1, 1, 'Files must begin with the PHP open tag.');
			}
		}
	}
