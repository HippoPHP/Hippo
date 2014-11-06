<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\ArgOptions;
	use \HippoPHP\Hippo\ArgParser;
	use \HippoPHP\Hippo\CheckRepository;
	use \HippoPHP\Hippo\CheckRunner;
	use \HippoPHP\Hippo\Config\ConfigReaderInterface;
	use \HippoPHP\Hippo\Config\YAMLConfigReader;
	use \HippoPHP\Hippo\Exception;
	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\HippoTextUIContext;

	class HippoTextUI {
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
			$context = new HippoTextUIContext($this->fileSystem, $this->argOptions);

			switch ($context->getAction()) {
				case HippoTextUIContext::ACTION_HELP:
					$this->showHelp();
					$this->environment->setExitCode(0);
					$this->environment->shutdown();
					break;

				case HippoTextUIContext::ACTION_VERSION:
					$this->showVersion();
					$this->environment->setExitCode(0);
					$this->environment->shutdown();
					break;

				case HippoTextUIContext::ACTION_CHECK:
					$this->runChecks($context);
					break;

				default:
					throw new Exception('Unrecognized action');
			}
		}

		/**
		 * @param HippoTextUIContext $context
		 * @return void
		 */
		protected function runChecks(HippoTextUIContext $context) {
			// TODO:
			// make this work with --standard
			$standardName = 'base';
			$baseConfig = $this->configReader->loadFromFile($this->_getStandardPath($standardName));

			$success = true;
			$checkRunner = new CheckRunner($this->fileSystem, $this->checkRepository, $baseConfig);

			array_map(array($this, '_startReporter'), $context->getReporters());
			$checkRunner->setObserver(
				function(File $file, array $checkResults) use ($context, &$success) {
					$minimumSeverityToFail = $context->hasStrictModeEnabled()
						? Violation::SEVERITY_IGNORE
						: Violation::SEVERITY_ERROR;

					$this->reportCheckResults($context->getReporters(), $file, $checkResults);
					foreach ($checkResults as $checkResult) {
						if ($checkResult->count() > 0) {
							$success &= $checkResult->getMaximumViolationSeverity() < $minimumSeverityToFail;
						}
					}
				});

			foreach ($context->getPathsToCheck() as $path) {
				$checkRunner->checkPath($path);
			}

			array_map(array($this, '_finishReporter'), $context->getReporters());

			$this->environment->setExitCode($success ? 0 : 1);
			$this->environment->shutdown();
		}

		/**
		 * Shows the help information.
		 * @return void
		 */
		protected function showHelp() {
			echo "Usage: hippo [switches] <directory>\n"
					. "  -h, --help                Prints this usage information\n"
					. "  -v, --version             Print version information\n"
					. "  -l, --log LOGLEVELS       Sets which severity levels should be logged\n"
					. "                            (default: \"info,warning,error\")\n"
					. "  -s, --strict 1|0          Enables or disables strict mode (default: 0)\n"
					. "                            Strict mode will exit with code 1 on any violation.\n";
		}

		/**
		 * Shows the version information.
		 * @return void
		 */
		protected function showVersion() {
			echo "Hippo " . $this->_getPackageVersion() . "\n\n";
		}

		/**
		 * @param ReporterInterface[] $reporters
		 * @param File $file
		 * @param CheckResult[] $checkResults
		 * @return void
		 */
		protected function reportCheckResults(array $reporters, File $file, array $checkResults) {
			echo 'Checking ' . $file->getFilename() . PHP_EOL;

			foreach ($reporters as $reporter) {
				foreach ($checkResults as $checkResult) {
					$reporter->addCheckResult($checkResult);
				}
			}
		}

		/**
		 * Returns the absolute path for a standards file.
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

		/**
		 * Returns the package version number for Hippo via composer.json
		 * @return string
		 */
		private function _getPackageVersion() {
			$content = file_get_contents($this->_getComposerPath());
			$package = json_decode($content);

			return $package->version;
		}

		/**
		 * Returns the absolute path for composer.json
		 * @return string
		 */
		private function _getComposerPath() {
			return __DIR__ . DIRECTORY_SEPARATOR
				. '..' . DIRECTORY_SEPARATOR
				. 'composer.json';
		}
	}
