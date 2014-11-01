<?php

	namespace Hippo;

	use Hippo\Exception;
	use Hippo\ArgParser;
	use Hippo\ArgOptions;
	use Hippo\CheckRunner;
	use Hippo\Reporters\CLIReporter;

	class HippoTextUI {
		const LONG_OPTION_HELP = 'help';
		const SHORT_OPTION_HELP = 'h';

		/**
		 * @var ReportInterface[]
		 */
		protected $reporters;

		/**
		 * @var CheckRunner
		 */
		protected $checkRunner;

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
			$this->checkRunner = new CheckRunner;
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

			// TODO:
			// make this work with a family of --report options, that controls which reporter to use
			// make this work with --quiet and --verbose also
			$this->reporters[] = new CLIReporter;

			foreach ($this->argOptions->getStrayArguments() as $strayArgument) {
				$this->executeCheckRunner($strayArgument);
			}
		}

		/**
		 * @return void
		 */
		protected function showHelp() {
			throw new \BadMethodCallException('Not implemented');
		}

		/**
		 * @param string $path
		 * @return void
		 */
		protected function executeCheckRunner($path) {
			if (!file_exists($path)) {
				throw new Exception('File does not exist: ' . $path);
			}

			if (is_dir($path)) {
				$this->executeCheckRunnerForDir($path);
			} else {
				$this->executeCheckRunnerForFile($path);
			}
		}

		/**
		 * @param string $path
		 * @return void
		 */
		protected function executeCheckRunnerForDir($path) {
			foreach (glob($path . '/**/*.php') as $subPath) {
				$this->executeCheckRunnerForFile($subPath);
			}
		}

		/**
		 * @param string $path
		 * @return void
		 */
		protected function executeCheckRunnerForFile($path) {
			if (!is_readable($path)) {
				throw new Exception('Supplied file is not readable: ' . $path);
			}
			if (!file_exists($path)) {
				throw new Exception('Supplied file is not readable: ' . $path);
			}

			$file = new File($path, file_get_contents($path));
			$checkResults = $this->checkRunner->checkFile($file);
			$this->reportCheckResults($file, $checkResults);
		}

		/**
		 * @param CheckResult[]
		 * @return void
		 */
		protected function reportCheckResults(File $file, array $checkResults) {
			echo 'Checking ' . $file->getFilename() . PHP_EOL;

			foreach ($this->reporters as $reporter) {
				$reporter->start();
				foreach ($checkResults as $checkResult) {
					$reporter->addCheckResult($checkResult);
				}
				$reporter->finish();
			}
		}
	}
