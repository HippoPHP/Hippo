<?php

	namespace Hippo;

	use Hippo\Exception;
	use Hippo\ArgParser;
	use Hippo\ArgOptions;

	class HippoTextUI {
		const LONG_OPTION_HELP = 'help';
		const SHORT_OPTION_HELP = 'h';

		/**
		 * @var ArgOptions
		 */
		protected $argOptions;

		/**
		 * @var string
		 */
		protected $pathToSelf;

		/**
		 * @return void
		 */
		public static function main() {
			if (!isset($_SERVER['argv'])) {
				throw new Exception('Hippo must be run from command line interface.');
			}
			$argv = $_SERVER['argv'];
			$hippoTextUi = new self(array_shift($argv), ArgParser::parse($argv));
			$hippoTextUi->run();
		}

		/**
		 * @param string $pathToSelf
		 * @param ArgOptions $argOptions
		 * @return void
		 */
		protected function __construct($pathToSelf, ArgOptions $argOptions) {
			$this->argOptions = $argOptions;
			$this->pathToSelf = $pathToSelf;
		}

		/**
		 * @return void
		 */
		protected function run() {
			if ($this->argOptions->getLongOption(self::LONG_OPTION_HELP) === true ||
				$this->argOptions->getShortOption(self::SHORT_OPTION_HELP) === true) {
				$this->showHelp();
				exit(0);
			}
		}

		/**
		 * @return void
		 */
		protected function showHelp() {
			throw new \BadMethodCallException('Not implemented');
		}
	}
