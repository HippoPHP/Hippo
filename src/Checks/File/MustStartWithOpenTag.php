<?php 

	namespace PHPCheckstyle\Check\Line;

	use PHPCheckstyle\PHPCheckstyle\File;
	use PHPCheckstyle\Check\AbstractCheck;
	use PHPCheckstyle\Check\FileCheckInterface;

	class MustStartWithOpenTag extends AbstractCheck implements FileCheckInterface {
		/**
		 * visitFile(): defined by FileCheckInterface.
		 * @see FileCheckInterface::visitFile()
		 * @param File $file
		 * @return void
		 */
		public function visitFile(File $file) {
			if (count($file) > 0 && $file->bottom()->getType() !== T_OPEN_TAG) {
				$this->addViolateion($file, 1, 1, 'Files must begin with the PHP open tag.');
			}
		}
	}
