<?php

	namespace Hippo\Checks\Line;

	use Hippo\File;
	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\FileCheckInterface;

	class MustStartWithOpenTag extends AbstractCheck implements FileCheckInterface {
		/**
		 * visitFile(): defined by FileCheckInterface.
		 * @see FileCheckInterface::visitFile()
		 * @param File $file
		 * @return void
		 */
		public function visitFile(File $file) {
			if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
				$this->addViolation($file, 1, 1, 'Files must begin with the PHP open tag.');
			}
		}
	}
