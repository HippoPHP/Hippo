<?php

	namespace HippoPHP\Hippo;

	use \HippoPHP\Hippo\ArgOptions;
	use \HippoPHP\Hippo\FileSystem;
	use \HippoPHP\Hippo\Violation;
	use \HippoPHP\Hippo\Exception\UnrecognizedOptionException;
	use \HippoPHP\Hippo\Reporters\CLIReporter;
	use \HippoPHP\Hippo\Reporters\CheckstyleReporter;

	/**
	 * Helper class for HippoTextUI, that acts as an object factory
	 * and encapsulates all the program options by reading ArgOptions.
	 * @see HippoTextUI
	 */
	class HippoTextUIContext {
		const ACTION_UNKNOWN = 0;
		const ACTION_CHECK = 1;
		const ACTION_HELP = 2;
		const ACTION_VERSION = 3;

		const LONG_OPTION_HELP = 'help';
		const SHORT_OPTION_HELP = 'h';
		const LONG_OPTION_VERSION = 'version';
		const SHORT_OPTION_VERSION = 'v';
		const LONG_OPTION_LOG_SEVERITIES = 'log';
		const SHORT_OPTION_LOG_SEVERITIES = 'l';
		const LONG_OPTION_STRICT_MODE = 'strict';
		const SHORT_OPTION_STRICT_MODE = 's';
		const LONG_OPTION_VERBOSE = 'verbose';
		const SHORT_OPTION_VERBOSE = 'v';
		const LONG_OPTION_QUIET = 'quiet';
		const SHORT_OPTION_QUIET = 'q';

		/**
		 * @var int
		 */
		private $_action = self::ACTION_UNKNOWN;

		/**
		 * @var boolean
		 */
		private $_strictModeEnabled = false;

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
		 * @param ArgOptions $argOptions
		 */
		public function __construct(
			FileSystem $fileSystem,
			ArgOptions $argOptions
		) {
			$this->_loggedSeverities = Violation::getSeverities();

			$this->_parseArgOptions($argOptions);

			$cliReporter = new CLIReporter($fileSystem);
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
		 * @param string $arg
		 * @return int[]
		 */
		private function _getSeveritiesFromArgument($arg) {
			$values = $this->_splitUserArgument($arg);
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
		 * @param ArgOptions $argOptions
		 * @return void
		 */
		private function _parseArgOptions(ArgOptions $argOptions) {
			foreach ($argOptions->getAllOptions() as $key => $value) {
				switch ($key) {
					case self::SHORT_OPTION_HELP:
					case self::LONG_OPTION_HELP:
						$this->_action = self::ACTION_HELP;
						break;

					case self::SHORT_OPTION_VERSION:
					case self::LONG_OPTION_VERSION:
						$this->_action = self::ACTION_VERSION;
						break;

					case self::SHORT_OPTION_LOG_SEVERITIES:
					case self::LONG_OPTION_LOG_SEVERITIES:
						$this->_loggedSeverities = $this->_getSeveritiesFromArgument($value);
						break;

					case self::SHORT_OPTION_STRICT_MODE:
					case self::LONG_OPTION_STRICT_MODE:
						$this->_strictModeEnabled = $this->_getFlagValue($value);
						break;

					case self::SHORT_OPTION_VERBOSE:
					case self::LONG_OPTION_VERBOSE:
						$this->_loggedSeverities = $this->_getFlagValue($value) ? Violation::getSeverities() : [];
						break;

					case self::SHORT_OPTION_QUIET:
					case self::LONG_OPTION_QUIET:
						$this->_loggedSeverities = $this->_getFlagValue($value) ? [] : Violation::getSeverities();
						break;

					// TODO:
					// --report-xml PATH

					default:
						throw new UnrecognizedOptionException('Unrecognized option: ' . $key);
				}
			}

			foreach ($argOptions->getStrayArguments() as $strayArgument) {
				$this->_pathsToCheck[] = $strayArgument;
			}

			if ($this->_action == self::ACTION_UNKNOWN) {
				$this->_action = empty($this->_pathsToCheck)
					? self::ACTION_HELP
					: self::ACTION_CHECK;
			}
		}

		/**
		 * @param string $arg
		 * @return string[]
		 */
		private function _splitUserArgument($arg) {
			return preg_split('/[\s,;]+/', $arg);
		}

		/**
		 * @param mixed $arg
		 * @return boolean
		 */
		private function _getFlagValue($arg) {
			return $arg === null ? true : boolval($arg);
		}
	}
