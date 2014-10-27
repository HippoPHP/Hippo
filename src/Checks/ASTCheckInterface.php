<?php 

	namespace PHPCheckstyle\PHPCheckstyle\Checks;

	use PHPCheckstyle\PHPCheckstyle\File;
	use PhpParser\Lexer;
	use PhpParser\Parser;

	/**
	 * Abstract Tree Interface.
	 * Checks implementing this interface will recieve a full AST of the file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface ASTCheckInterface {
		public function parseFile(File $file);
	}