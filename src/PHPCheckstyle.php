<?php 

	namespace PHPCheckstyle\PHPCheckstyle;

	use PHPCheckstyle\Exception;
	use PHPCheckstyle\PHPCheckstyle\File;

	class PHPCheckstyle {
		protected $checks = array();
		protected $listenerTokens = array();

		public function check(File $file) {
			foreach ($this->checks as $check) {
				if ($check instanceof FileRuleInterface) {
					$rule->visitFile($file);
				}
			}

			while ($file->valid()) {
				$position = $file->key();
				$tokenType = $file->current()->getType();

				if (isset($this->listenerTokens[$tokenType])) {
					foreach ($this->listenerTokens[$tokenType] as $checkName) {
						$this->checks[$checkName]->check($file);
						$file->seek($position);
					}
				}

				$file->next();
			}
		}
	}