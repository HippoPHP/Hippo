<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Checks;

	use PHPCheckstyle\PHPCheckstyle\File;

	interface FileRuleInterface {
		public function checkFile(File $file);
	}