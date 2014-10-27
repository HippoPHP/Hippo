<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Checks;

	use PHPCheckstyle\PHPCheckstyle\File;
	use PhpParser\Lexer;
	use PhpParser\Parser;

	/**
	 * Abstract Tree Interface.
	 * Rules implementing this interface will recieve a full AST of the file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface ASTRuleInterface {
		public function parseFile(File $file);
	}