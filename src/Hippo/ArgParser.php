<?php

	namespace Hippo;

	/**
	 * A factory of ArgOptions.
	 * @package Hippo
	 */
	class ArgParser {
		/**
		 * @var boolean
		 */
		private $_stopParsing;

		/**
		 * @var ArgOptions
		 */
		private $_argOptions;

		/**
		 * @param string[] $argv
		 * @return ArgOptions
		 */
		public static function parse(array $argv) {
			$parser = new self;
			return $parser->_parse($argv);
		}

		/**
		 * @param string[] $argv
		 * @return ArgOptions
		 */
		private function _parse(array $argv) {
			$this->_stopParsing = false;
			$this->_argOptions = new ArgOptions();

			for ($i = 0; $i < count($argv); $i ++) {
				$arg = $argv[$i];
				$nextArg = isset($argv[$i + 1]) ? $argv[$i + 1] : null;
				$hasUsedNextArg = $this->_processArg($arg, $nextArg);
				if ($hasUsedNextArg) {
					++ $i;
				}
			}

			return $this->_argOptions;
		}

		/**
		 * @param string $arg
		 * @param string $nextArg
		 * @return boolean whether the next arg was used
		 */
		private function _processArg($arg, $nextArg) {
			if ($arg === '--') {
				$this->_stopParsing = true;
				return false;
			}

			if (!$this->_stopParsing) {
				if ($this->_isLongArgument($arg)) {
					$this->_argOptions->setLongOption(
						$this->_normalizeArg($arg),
						$this->_extractArgValue($arg, $nextArg, $hasUsedNextArg));
					return $hasUsedNextArg;
				}

				if ($this->_isShortArgument($arg)) {
					$this->_argOptions->setShortOption(
						$this->_normalizeArg($arg),
						$this->_extractArgValue($arg, $nextArg, $hasUsedNextArg));
					return $hasUsedNextArg;
				}
			}

			$this->_argOptions->addStrayArgument($arg);
			return false;
		}

		/**
		 * @param string $arg
		 * @param string $nextArg
		 * @param boolean $hasUsedNextArg
		 * @return mixed
		 */
		private function _extractArgValue($arg, $nextArg, &$hasUsedNextArg) {
			$hasUsedNextArg = false;

			$index = strpos($arg, '=');
			if ($index !== false) {
				return substr($arg, $index + 1);
			} elseif ($nextArg !== null && !$this->_isArgument($nextArg)) {
				$hasUsedNextArg = true;
				return $nextArg;
			}

			return true;
		}

		/**
		 * @param string $arg
		 * @return boolean
		 */
		private function _isLongArgument($arg) {
			return substr($arg, 0, 2) === '--';
		}

		/**
		 * @param string $arg
		 * @return boolean
		 */
		private function _isShortArgument($arg) {
			return !$this->_isLongArgument($arg) && $arg{0} === '-';
		}

		private function _normalizeArg($arg) {
			if (strpos($arg, '=') !== false)
				$arg = substr($arg, 0, strpos($arg, '='));
			return ltrim($arg, '-');
		}

		/**
		 * @param string $arg
		 * @return boolean
		 */
		private function _isArgument($arg) {
			return $this->_isLongArgument($arg) || $this->_isShortArgument($arg);
		}
	}
