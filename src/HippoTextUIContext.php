<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <jbrooksuk@me.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @var \HippoPHP\Hippo\FileSystem
     */
    private $fileSystem;

    /**
     * @var int
     */
    private $action = self::ACTION_UNKNOWN;

    /**
     * @var bool
     */
    private $strictModeEnabled = false;

    /**
     * @var string
     */
    private $configName = 'base';

    /**
     * @var int[]
     */
    private $loggedSeverities = [];

    /**
     * @var \HippoPHP\Hippo\Reporters\ReportInterface[]
     */
    private $reporters = [];

    /**
     * @var string[]
     */
    private $pathsToCheck = [];

    /**
     * @param \HippoPHP\Hippo\FileSystem $fileSystem
     * @param string[]                   $args
     */
    public function __construct(
        FileSystem $fileSystem,
        array $args
    ) {
        $this->fileSystem = $fileSystem;

        $argParserOptions = new ArgParserOptions();
        $argParserOptions->markArray('l');
        $argParserOptions->markArray('log');
        $argParserOptions->markFlag('q');
        $argParserOptions->markFlag('s');
        $argParserOptions->markFlag('quiet');
        $argParserOptions->markFlag('verbose');
        $argParserOptions->markFlag('strict');
        $argContainer = ArgParser::parse($args, $argParserOptions);

        $this->loggedSeverities = Violation::getSeverities();

        $this->processArgContainer($argContainer);

        $cliReporter = new CLIReporter($this->fileSystem);
        $cliReporter->setLoggedSeverities($this->loggedSeverities);
        $this->reporters[] = $cliReporter;
    }

    /**
     * @return int
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return \HippoPHP\Hippo\Reporters\ReporterInterface[]
     */
    public function getReporters()
    {
        return $this->reporters;
    }

    /**
     * @return string[]
     */
    public function getPathsToCheck()
    {
        return $this->pathsToCheck;
    }

    /**
     * @return bool
     */
    public function hasStrictModeEnabled()
    {
        return $this->strictModeEnabled;
    }

    /**
     * @return string
     */
    public function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @param callable $action
     * @param string[] $arguments
     */
    private function createArgMapping(callable $action, array $arguments)
    {
        return ['action' => $action, 'arguments' => $arguments];
    }

    /**
     * @return array
     */
    private function buildArgMappings()
    {
        return [
            $this->createArgMapping([$this, 'handleHelpArgument'], ['h', 'help']),
            $this->createArgMapping([$this, 'handleVersionArgument'], ['v', 'version']),
            $this->createArgMapping([$this, 'handleStrictArgument'], ['s', 'strict']),
            $this->createArgMapping([$this, 'handleLogArgument'], ['l', 'log']),
            $this->createArgMapping([$this, 'handleVerboseArgument'], ['verbose']),
            $this->createArgMapping([$this, 'handleQuietArgument'], ['q', 'quiet']),
            $this->createArgMapping([$this, 'handleXmlReportArgument'], ['report-xml']),
            $this->createArgMapping([$this, 'handleConfigArgument'], ['c', 'config']),
        ];
    }

    /**
     * @param ArgContainer $argContainer
     */
    private function processArgContainer(ArgContainer $argContainer)
    {
        $mappings = $this->buildArgMappings();

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
            $this->pathsToCheck[] = $strayArgument;
        }

        if ($this->action == self::ACTION_UNKNOWN) {
            $this->action = empty($this->pathsToCheck)
                ? self::ACTION_HELP
                : self::ACTION_CHECK;
        }
    }

    private function handleHelpArgument()
    {
        $this->action = self::ACTION_HELP;
    }

    private function handleVersionArgument()
    {
        $this->action = self::ACTION_VERSION;
    }

    private function handleStrictArgument($argValue)
    {
        $this->strictModeEnabled = $argValue;
    }

    private function handleLogArgument($argValue)
    {
        $severities = [];
        foreach ($argValue as $value) {
            $severity = Violation::getSeverityFromString($value);
            if ($severity === null) {
                throw new UnrecognizedOptionException('Unrecognized severity: '.$value);
            }
            $severities [] = $severity;
        }
        $this->loggedSeverities = array_unique($severities);
    }

    private function handleVerboseArgument($argValue)
    {
        $this->loggedSeverities = $argValue ? Violation::getSeverities() : [];
    }

    private function handleQuietArgument($argValue)
    {
        $this->loggedSeverities = $argValue ? [] : Violation::getSeverities();
    }

    private function handleXmlReportArgument($argValue)
    {
        $targetFilename = $argValue === null ? 'checkstyle.xml' : $argValue;
        $checkstyleReporter = new CheckstyleReporter($this->fileSystem);
        $checkstyleReporter->setFilename($targetFilename);
        $this->reporters[] = $checkstyleReporter;
    }

    private function handleConfigArgument($argValue)
    {
        if (!$argValue) {
            throw new UnrecognizedOptionException('Must specify config path');
        }
        $this->configName = $argValue;
    }
}
