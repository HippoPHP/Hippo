<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\ArgOptions;
	use \HippoPHP\Hippo\ArgParser;
	use \HippoPHP\Hippo\CheckRepository;
	use \HippoPHP\Hippo\CheckRunner;
	use \HippoPHP\Hippo\Exception;
	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Config\ConfigReaderInterface;
	use \HippoPHP\Hippo\Config\YAMLConfigReader;
	use \HippoPHP\Hippo\Reporters\CLIReporter;
	use \HippoPHP\Hippo\Reporters\CheckstyleReporter;

	class HippoTextUI {
		const VERSION = '0.1.0';

		const LONG_OPTION_HELP = 'help';
		const SHORT_OPTION_HELP = 'h';
		const LONG_OPTION_VERSION = 'version';
		const SHORT_OPTION_VERSION = 'v';

		/**
		 * @var ReportInterface[]
		 */
		protected $reporters;

		/**
		 * @var CheckRepository
		 */
		protected $checkRepository;

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
		 * @param CheckRepository $checkRepository
		 * @param string $pathToSelf
		 * @param ArgOptions $argOptions
		 * @return void
		 */
		public function __construct(
			Environment $environment,
			FileSystem $fileSystem,
			CheckRepository $checkRepository,
			ConfigReaderInterface $configReader,
			$pathToSelf,
			ArgOptions $argOptions
		) {
			$this->environment = $environment;
			$this->fileSystem = $fileSystem;
			$this->checkRepository = $checkRepository;
			$this->configReader = $configReader;
			$this->pathToSelf = $pathToSelf;
			$this->argOptions = $argOptions;
		}

		/**
		 * @return void
		 */
		public static function main($args) {
			if (!$args) {
				throw new Exception('Hippo must be run from command line interface.');
			}
			$environment = new Environment;
			$fileSystem = new FileSystem;
			$configReader = new YAMLConfigReader($fileSystem);
			$checkRepository = new CheckRepository($fileSystem);

			$hippoTextUi = new self(
				$environment,
				$fileSystem,
				$checkRepository,
				$configReader,
				array_shift($args),
				ArgParser::parse($args));

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
			} elseif ($this->argOptions->getLongOption(self::LONG_OPTION_VERSION) === true ||
				$this->argOptions->getShortOption(self::SHORT_OPTION_VERSION) === true) {
				$this->showVersion();
				$this->environment->setExitCode(0);
				$this->environment->shutdown();
			}

			// TODO:
			// make this work with a family of --report options, that controls which reporter to use
			// make this work with --quiet and --verbose also
			$this->reporters[] = new CLIReporter;

			// TODO:
			// make this work with --standard
			$standardName = 'base';
			$baseConfig = $this->configReader->loadFromFile($this->_getStandardPath($standardName));

			$success = true;
			$checkRunner = new CheckRunner($this->fileSystem, $this->checkRepository, $baseConfig);
			
			array_map(array($this, '_startReporter'), $this->reporters);
			$checkRunner->setObserver(function(File $file, array $checkResults) use (&$success) {
				$this->reportCheckResults($file, $checkResults);
				foreach ($checkResults as $checkResult) {
					if ($checkResult->hasFailed()) {
						$success = false;
					}
				}
			});

			foreach ($this->argOptions->getStrayArguments() as $strayArgument) {
				$checkRunner->checkPath($strayArgument);
			}
			
			array_map(array($this, '_finishReporter'), $this->reporters);

			$this->environment->setExitCode($success ? 0 : 1);
			$this->environment->shutdown();
		}

		/**
		 * @return void
		 */
		protected function showHelp() {
			echo "Usage: hippo [switches] <directory>\n"
					. "  -h, --help                Prints this usage information\n"
					. "  -v, --version             Print version information\n";
		}

		protected function showVersion() {
			echo "Hippo " . self::VERSION . "\n\n";
		}

		/**
		 * @param CheckResult[]
		 * @return void
		 */
		protected function reportCheckResults(File $file, array $checkResults) {
			echo 'Checking ' . $file->getFilename() . PHP_EOL;

			foreach ($this->reporters as $reporter) {
				foreach ($checkResults as $checkResult) {
					$reporter->addCheckResult($checkResult);
				}
			}
		}

		/**
		 * @param string
		 * @return string
		 */
		private function _getStandardPath($standardName) {
			return __DIR__ . DIRECTORY_SEPARATOR
				. 'Standards' . DIRECTORY_SEPARATOR
				. $standardName . '.yml';
		}

		/**
		 * Starts the reporter. Can be used for setup.
		 * @param  ReporterInterface $reporter
		 * @return mixed
		 */
		private function _startReporter(&$reporter) {
			return $reporter->start();
		}

		/**
		 * Finishes the reporter. Usually used for cleanups.
		 * @param  ReporterInterface $reporter
		 * @return mixed
		 */
		private function _finishReporter(&$reporter) {
			return $reporter->finish();
		}
	}
