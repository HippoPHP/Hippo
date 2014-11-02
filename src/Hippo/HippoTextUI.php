<?php

	namespace Hippo;

	use Hippo\ArgOptions;
	use Hippo\ArgParser;
	use Hippo\CheckRunner;
	use Hippo\Exception;
	use Hippo\FileSystem;
	use Hippo\Reporters\CLIReporter;

	use \RecursiveDirectoryIterator;
	use \RecursiveIteratorIterator;
	use \RegexIterator;

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
		 * @param Environment $environment
		 * @param FileSystem $fileSystem
		 * @param CheckRunner $checkRunner
		 * @param string $pathToSelf
		 * @param ArgOptions $argOptions
		 * @return void
		 */
		public function __construct(
			Environment $environment,
			FileSystem $fileSystem,
			CheckRunner $checkRunner,
			$pathToSelf,
			ArgOptions $argOptions
		) {
			$this->environment = $environment;
			$this->fileSystem = $fileSystem;
			$this->checkRunner = $checkRunner;
			$this->pathToSelf = $pathToSelf;
			$this->argOptions = $argOptions;
		}

		/**
		 * @return void
		 */
		public static function main() {
			if (!isset($_SERVER['argv'])) {
				throw new Exception('Hippo must be run from command line interface.');
			}
			$argv = $_SERVER['argv'];
			$environment = new Environment;
			$fileSystem = new FileSystem;
			$checkRunner = new CheckRunner;

			$hippoTextUi = new self(
				$environment,
				$fileSystem,
				$checkRunner,
				array_shift($argv),
				ArgParser::parse($argv));

			$hippoTextUi->run();
		}

		/**
		 * @return void
		 */
		protected function run() {
			if ($this->argOptions->getLongOption(self::LONG_OPTION_HELP) === true ||
				$this->argOptions->getShortOption(self::SHORT_OPTION_HELP) === true) {
				$this->showHelp();
				$this->environment->setExitCode(0);
				$this->environment->shutdown();
			}

			// TODO:
			// make this work with a family of --report options, that controls which reporter to use
			// make this work with --quiet and --verbose also
			$this->reporters[] = new CLIReporter;

			$success = true;
			foreach ($this->argOptions->getStrayArguments() as $strayArgument) {
				$success &= $this->executeCheckRunner($strayArgument);
			}

			$this->environment->setExitCode($success ? 0 : 1);
			$this->environment->shutdown();
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
			$directory = new RecursiveDirectoryIterator($path);
			$flattened = new RecursiveIteratorIterator($directory);
			$iterator = new RegexIterator($flattened, '/^.+\.php$/i');

			$success = true;
			foreach ($iterator as $subPath) {
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
