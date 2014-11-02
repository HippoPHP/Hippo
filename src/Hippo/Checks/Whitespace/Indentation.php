<?php

	namespace Hippo\Checks\Whitespace;

	use Hippo\Checks\AbstractCheck;
	use Hippo\Checks\CheckInterface;
	use Hippo\Config\Config;
	use Hippo\File;

	class Indentation extends AbstractCheck implements CheckInterface {
		//TODO: add "auto", which checks only for consistency
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

		/**
		 * checkFile(): defined by CheckInterface.
		 * @see CheckInterface::checkFile()
		 * @param File $file
		 * @param Config $config
		 * @return void
		 */
		public function checkFile(File $file, Config $config) {
			$this->setIndentStyle($config->get('style', $this->indentStyle));
			$this->setIndentCount($config->get('count', $this->indentCount));

			$indentation = $this->_getIndentChar();

			$file->rewind();

			while (true) {
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

		/**
		 * @return string
		 */
		public function getConfigRoot() {
			return 'file.indentation';
		}
	}
