<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\ArgContainer;
	use \HippoPHP\Hippo\ArgParser;
	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\Exception\UnrecognizedOptionException;
	use \HippoPHP\Hippo\Reporters\CLIReporter;
	use \HippoPHP\Hippo\Reporters\CheckstyleReporter;

	/**
	 * Helper class for HippoTextUI, that acts as an object factory
	 * and encapsulates all the program options by reading ArgContainer.
	 * @see HippoTextUI
	 */
	class HippoTextUIContext {
		const ACTION_UNKNOWN = 0;
		const ACTION_CHECK = 1;
		const ACTION_HELP = 2;
		const ACTION_VERSION = 3;

		/**
		 * @var FileSystem
		 */
		private $_fileSystem;

		/**
		 * @var int
		 */
		private $_action = self::ACTION_UNKNOWN;

		/**
		 * @var boolean
		 */
		private $_strictModeEnabled = false;

		/**
		 * @var string
		 */
		private $_configName = 'base';

		/**
		 * @var int[]
		 */
		private $_loggedSeverities = [];

		/**
		 * @var ReportInterface[]
		 */
		private $_reporters = [];

		/**
		 * @var string[]
		 */
		private $_pathsToCheck = [];

		/**
		 * @param FileSystem $fileSystem
		 * @param string[] $args
		 */
		public function __construct(
			FileSystem $fileSystem,
			array $args
		) {
			$this->_fileSystem = $fileSystem;

			$argParserOptions = new ArgParserOptions();
			$argParserOptions->markArray('l');
			$argParserOptions->markArray('log');
			$argParserOptions->markFlag('q');
			$argParserOptions->markFlag('v');
			$argParserOptions->markFlag('s');
			$argParserOptions->markFlag('quiet');
			$argParserOptions->markFlag('verbose');
			$argParserOptions->markFlag('strict');
			$argContainer = ArgParser::parse($args, $argParserOptions);

			$this->_loggedSeverities = Violation::getSeverities();

			$this->_processArgContainer($argContainer);

			$cliReporter = new CLIReporter($this->_fileSystem);
			$cliReporter->setLoggedSeverities($this->_loggedSeverities);
			$this->_reporters[] = $cliReporter;
		}

		/**
		 * @return int
		 */
		public function getAction() {
			return $this->_action;
		}

		/**
		 * @return ReporterInterface[]
		 */
		public function getReporters() {
			return $this->_reporters;
		}

		/**
		 * @return string[]
		 */
		public function getPathsToCheck() {
			return $this->_pathsToCheck;
		}

		/**
		 * @return boolean
		 */
		public function hasStrictModeEnabled() {
			return $this->_strictModeEnabled;
		}

		/**
		 * @return string
		 */
		public function getConfigName() {
			return $this->_configName;
		}

		/**
		 * @param string[] $arg
		 * @return int[]
		 */
		private function _getSeveritiesFromArgument(array $values) {
			$severities = [];
			foreach ($values as $value) {
				$severity = Violation::getSeverityFromString($value);
				if ($severity === null) {
					throw new UnrecognizedOptionException('Unrecognized severity: ' . $value);
				}
				$severities []= $severity;
			}
			return array_unique($severities);
		}

		/**
		 * @param ArgContainer $argContainer
		 * @return void
		 */
		private function _processArgContainer(ArgContainer $argContainer) {
			foreach ($argContainer->getAllOptions() as $key => $value) {
				switch ($key) {
					case 'help':
					case 'h':
						$this->_action = self::ACTION_HELP;
						break;

					case 'version':
					case 'v':
						$this->_action = self::ACTION_VERSION;
						break;

					case 'log':
					case 'l':
						$this->_loggedSeverities = $this->_getSeveritiesFromArgument($value);
						break;

					case 'strict':
					case 's':
						$this->_strictModeEnabled = $value;
						break;

					case 'verbose':
					case 'v':
						$this->_loggedSeverities = $value ? Violation::getSeverities() : [];
						break;

					case 'quiet':
					case 'q':
						$this->_loggedSeverities = $value ? [] : Violation::getSeverities();
						break;

					case 'config':
					case 'c':
						if (!$value) {
							throw new UnrecognizedOptionException('Must specify config path');
						}
						$this->_configName = $value;
						break;

					case 'report-xml':
						$targetFilename = $value === null ? 'checkstyle.xml' : $value;
						$checkstyleReporter = new CheckstyleReporter($this->_fileSystem);
						$checkstyleReporter->setFilename($targetFilename);
						$this->_reporters[] = $checkstyleReporter;
						break;

					default:
						throw new UnrecognizedOptionException('Unrecognized option: ' . $key);
				}
			}

			foreach ($argContainer->getStrayArguments() as $strayArgument) {
				$this->_pathsToCheck[] = $strayArgument;
			}

			if ($this->_action == self::ACTION_UNKNOWN) {
				$this->_action = empty($this->_pathsToCheck)
					? self::ACTION_HELP
					: self::ACTION_CHECK;
			}
		}
	}
