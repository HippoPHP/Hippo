<?php 

	namespace PHPCheckstyle\Checks;

	use PHPCheckstyle\File;

	interface FileRuleInterface {
		public function checkFile(File $file);
	}