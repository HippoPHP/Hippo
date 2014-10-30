<?php 

	namespace PHPCheckstyle\Checks;

	use PHPCheckstyle\File;

	/**
	 * Abstract Tree Interface.
	 * Checks implementing this interface will recieve a full AST of the file.
	 * @package PHPCheckstyle
	 * @author James Brooks <jbrooksuk@me.com>
	 */
	interface ASTCheckInterface extends CheckInterface {
		/**
		 * Returns the node tree for a given File instance.
		 * @param  File   $file
		 * @return PhpParser
		 */
		public function getNodeTree(File $file);	
	}
