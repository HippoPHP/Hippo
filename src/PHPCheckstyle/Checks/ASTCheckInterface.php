<?php 

	namespace PHPCheckstyle\Checks;

	use PHPCheckstyle\File;
	use PhpParser\Lexer;
	use PhpParser\Parser;

	/**
	 * Abstract Tree Interface.
	 * Checks implementing this interface will recieve a full AST of the file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface ASTCheckInterface extends CheckInterface {
	}
