<?php

	namespace Hippo;

	use Hippo\Exception;
	use Hippo\ArgParser;
	use Hippo\ArgOptions;
	use Hippo\CheckRunner;
	use Hippo\FileSystem;
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
		 * @var FileSystem
		 */
		protected $fileSystem;

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
			$this->fileSystem = new FileSystem;
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

			$success = true;
			foreach ($this->argOptions->getStrayArguments() as $strayArgument) {
				$success &= $this->executeCheckRunner($strayArgument);
			}

			exit($success ? 0 : 1);
		}

		/**
		 * @return void
		 */
		protected function showHelp() {
			throw new \BadMethodCallException('Not implemented');
		}

		/**
		 * @param string $path
		 * @return boolean if there were no errors
		 */
		protected function executeCheckRunner($path) {
			if (!file_exists($path)) {
				throw new Exception('File does not exist: ' . $path);
			}

			return is_dir($path)
				? $this->executeCheckRunnerForDir($path)
				: $this->executeCheckRunnerForFile($path);
		}

		/**
		 * @param string $path
		 * @return boolean if there were no errors
		 */
		protected function executeCheckRunnerForDir($path) {
			$success = true;
			foreach (glob($path . '/**/*.php') as $subPath) {
				$success &= $this->executeCheckRunnerForFile($subPath);
			}
			return $success;
		}

		/**
		 * @param string $path
		 * @return boolean if there were no errors
		 */
		protected function executeCheckRunnerForFile($path) {
			$file = new File($path, $this->fileSystem->getContent($path));
			$checkResults = $this->checkRunner->checkFile($file);
			$this->reportCheckResults($file, $checkResults);

			foreach ($checkResults as $checkResult) {
				if ($checkResult->hasFailed()) {
					return false;
				}
			}
			return true;
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
