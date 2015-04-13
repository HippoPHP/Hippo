<?php

namespace HippoPHP\Hippo;

use HippoPHP\Hippo\CLI\ArgContainer;
use HippoPHP\Hippo\CLI\ArgParser;
use HippoPHP\Hippo\CLI\ArgParserOptions;
use HippoPHP\Hippo\Exception\UnrecognizedOptionException;
use HippoPHP\Hippo\Reporters\CheckstyleReporter;
use HippoPHP\Hippo\Reporters\CLIReporter;

/**
 * Helper class for HippoTextUI, that acts as an object factory
 * and encapsulates all the program options by reading ArgContainer.
 *
 * @see HippoTextUI
 */
class HippoTextUIContext
{
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
     * @var bool
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
     * @param string[]   $args
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
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * @return ReporterInterface[]
     */
    public function getReporters()
    {
        return $this->_reporters;
    }

    /**
     * @return string[]
     */
    public function getPathsToCheck()
    {
        return $this->_pathsToCheck;
    }

    /**
     * @return bool
     */
    public function hasStrictModeEnabled()
    {
        return $this->_strictModeEnabled;
    }

    /**
     * @return string
     */
    public function getConfigName()
    {
        return $this->_configName;
    }

    /**
     * @param callable $action
     * @param string[] $arguments
     */
    private function _createArgMapping(callable $action, array $arguments)
    {
        return ['action' => $action, 'arguments' => $arguments];
    }

    /**
     * @return array
     */
    private function _buildArgMappings()
    {
        return [
            $this->_createArgMapping([$this, '_handleHelpArgument'], ['h', 'help']),
            $this->_createArgMapping([$this, '_handleVersionArgument'], ['v', 'version']),
            $this->_createArgMapping([$this, '_handleStrictArgument'], ['s', 'strict']),
            $this->_createArgMapping([$this, '_handleLogArgument'], ['l', 'log']),
            $this->_createArgMapping([$this, '_handleVerboseArgument'], ['verbose']),
            $this->_createArgMapping([$this, '_handleQuietArgument'], ['q', 'quiet']),
            $this->_createArgMapping([$this, '_handleXmlReportArgument'], ['report-xml']),
            $this->_createArgMapping([$this, '_handleConfigArgument'], ['c', 'config']),
        ];
    }

    /**
     * @param ArgContainer $argContainer
     *
     * @return void
     */
    private function _processArgContainer(ArgContainer $argContainer)
    {
        $mappings = $this->_buildArgMappings();

        foreach ($argContainer->getAllOptions() as $argName => $argValue) {
            $handled = false;

            foreach ($mappings as $mapping) {
                if (in_array($argName, $mapping['arguments'])) {
                    $mapping['action']($argValue);
                    $handled = true;
                    break;
                }
            }

            if (!$handled) {
                throw new UnrecognizedOptionException('Unrecognized option: '.$argName);
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

    private function _handleHelpArgument()
    {
        $this->_action = self::ACTION_HELP;
    }

    private function _handleVersionArgument()
    {
        $this->_action = self::ACTION_VERSION;
    }

    private function _handleStrictArgument($argValue)
    {
        $this->_strictModeEnabled = $argValue;
    }

    private function _handleLogArgument($argValue)
    {
        $severities = [];
        foreach ($argValue as $value) {
            $severity = Violation::getSeverityFromString($value);
            if ($severity === null) {
                throw new UnrecognizedOptionException('Unrecognized severity: '.$value);
            }
            $severities [] = $severity;
        }
        $this->_loggedSeverities = array_unique($severities);
    }

    private function _handleVerboseArgument($argValue)
    {
        $this->_loggedSeverities = $argValue ? Violation::getSeverities() : [];
    }

    private function _handleQuietArgument($argValue)
    {
        $this->_loggedSeverities = $argValue ? [] : Violation::getSeverities();
    }

    private function _handleXmlReportArgument($argValue)
    {
        $targetFilename = $argValue === null ? 'checkstyle.xml' : $argValue;
        $checkstyleReporter = new CheckstyleReporter($this->_fileSystem);
        $checkstyleReporter->setFilename($targetFilename);
        $this->_reporters[] = $checkstyleReporter;
    }

    private function _handleConfigArgument($argValue)
    {
        if (!$argValue) {
            throw new UnrecognizedOptionException('Must specify config path');
        }
        $this->_configName = $argValue;
    }
}
