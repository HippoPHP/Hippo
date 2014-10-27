<?php 

	namespace PHPCheckstyle\Check\Whitespace;

	use PHPCheckstyle\PHPCheckstyle\File;
	use PHPCheckstyle\Check\AbstractCheck;

	class Indentation extends AbstractCheck {
		/**
		 * Style of indent.
		 * Either 'tab' or 'space'.
		 * @var string
		 */
		protected $indentStyle = 'space';

		/**
		 * Number of indentation characters per-level.
		 * @var integer
		 */
		protected $indentCount = 4;

		/**
		 * Sets the indentation style.
		 * @param string $style
		 * @return Indentation
		 */
		public function setIndentStyle($style) {
			$style = strtolower($style);

			switch ($style) {
				case 'space':
				case 'tab':
					$this->indentStyle = $style;
					break;
			}

			return $this;
		}

		/**
		 * Sets the indentation count.
		 * @param int $count
		 * @return Indentation
		 */
		public function setIndentCount($count) {
			$this->indentCount = max(1, (int), $count);
			return $this;
		}
	}