<?php 

	namespace PHPCheckstyle\Check;

	use PHPCheckstyle\File;

	interface TokenRuleInterface {
		public function getListenerTokens();

		public function visitToken(File $file);
	}