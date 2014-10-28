<?php 

	namespace PHPCheckstyle\Check\Whitespace;

	use PHPCheckstyle\PHPCheckstyle\File;
	use PHPCheckstyle\Check\AbstractCheck;

	class Indentation extends AbstractCheck {
		const INDENT_STYLE_SPACE = 'space';
		const INDENT_STYLE_TAB = 'tab';

		/**
		 * Style of indent.
		 * Either 'tab' or 'space'.
		 * @var string
		 */
		protected $indentStyle = self::INDENT_STYLE_SPACE;

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
				case self::INDENT_STYLE_SPACE:
				case self::INDENT_STYLE_TAB:
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
			$this->indentCount = max(1, (int) $count);
			return $this;
		}

		public function visitFile(File $file) {
			$indentation = $this->_getIndentChar();

			$file->rewind();

			while (TRUE) {
				$token = $file->current();
				$level = $token->getLevel();

				$file->next();
				if ($file->current()->getType() === '}' || $file->current()->getType() === ')') {
					$level--;
				}
				$file->prev();

				$expectedIndentation = str_repeat($indentation, $level);
				$actualIndentation = $token->getTrailingWhitespace();

				if ($expectedIndentation !== $actualIndentation) {
					$this->addViolation(
						$file, 
						$token, 
						$column, 
						sprintf("Unexpected indentation found at level %d", $level),
						Violation::SEVERITY_WARNING
					);
				}

				if (!$file->seekNextLine()) {
					return;
				}
			}
		}

		private function _getIndentChar() {
			$char = '';
			if ($this->indentStyle === self::INDENT_STYLE_SPACE) {
				$char = ' ';
			} elseif ($this->indentStyle === self::INDENT_STYLE_TAB) {
				$char = "\t";
			}

			return str_repeat($char, $this->indentCount);
		}
	}
