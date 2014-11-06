<?php

	namespace HippoPHP\Hippo;

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
		 * @var HippoTextUIContext
		 */
		protected $context;

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
		 * @param HippoTextUIContext $context
		 * @return void
		 */
		public function __construct(
			Environment $environment,
			FileSystem $fileSystem,
			CheckRepository $checkRepository,
			ConfigReaderInterface $configReader,
			$pathToSelf,
			HippoTextUIContext $context
		) {
			$this->environment = $environment;
			$this->fileSystem = $fileSystem;
			$this->checkRepository = $checkRepository;
			$this->configReader = $configReader;
			$this->pathToSelf = $pathToSelf;
			$this->context = $context;
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

			$pathToSelf = array_shift($args);
			$context = new HippoTextUIContext($fileSystem, $args);

			$hippoTextUi = new self(
				$environment,
				$fileSystem,
				$checkRepository,
				$configReader,
				$pathToSelf,
				$context);

			$hippoTextUi->run();
		}

		/**
		 * @return void
		 */
		protected function run() {
			switch ($this->context->getAction()) {
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
					$this->runChecks();
					break;

				default:
					throw new Exception('Unrecognized action');
			}
		}

		/**
		 * @return void
		 */
		protected function runChecks() {
			// TODO:
			// make this work with --standard
			$standardName = 'base';
			$baseConfig = $this->configReader->loadFromFile($this->_getStandardPath($standardName));

			$success = true;
			$checkRunner = new CheckRunner($this->fileSystem, $this->checkRepository, $baseConfig);

			array_map(array($this, '_startReporter'), $this->context->getReporters());
			$checkRunner->setObserver(
				function(File $file, array $checkResults) use (&$success) {
					$minimumSeverityToFail = $this->context->hasStrictModeEnabled()
						? Violation::SEVERITY_IGNORE
						: Violation::SEVERITY_ERROR;

					$this->reportCheckResults($this->context->getReporters(), $file, $checkResults);
					foreach ($checkResults as $checkResult) {
						if ($checkResult->count() > 0) {
							$success &= $checkResult->getMaximumViolationSeverity() < $minimumSeverityToFail;
						}
					}
				});

			foreach ($this->context->getPathsToCheck() as $path) {
				$checkRunner->checkPath($path);
			}

			array_map(array($this, '_finishReporter'), $this->context->getReporters());

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
				. "                            Strict mode will exit with code 1 on any violation.\n"
				. "  -v, --verbose 1|0         Same as --log \"info,warning,error\"\n"
				. "  -q, --quiet 1|0           Same as --log \"\"\n";
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
