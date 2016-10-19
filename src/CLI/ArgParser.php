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

namespace HippoPHP\Hippo\CLI;

/**
 * A factory of ArgContainer.
 */
class ArgParser
{
    /**
     * @var bool
     */
    private $stopParsing;

    /**
     * @var ArgContainer
     */
    private $argContainer;

    /**
     * @var ArgParserOptions
     */
    private $argParserOptions;

    /**
     * @param string[]                             $argv
     * @param \HippoPHP\Hippo\CLI\ArgParserOptions $argParserOptions
     *
     * @return \HippoPHP\Hippo\CLI\ArgContainer
     */
    public static function parse(array $argv, ArgParserOptions $argParserOptions = null)
    {
        $parser = new self($argParserOptions);

        return $parser->parseArgs($argv);
    }

    /**
     * @param \HippoPHP\Hippo\CLI\ArgParserOptions $argParserOptions
     */
    private function __construct(ArgParserOptions $argParserOptions = null)
    {
        $this->argParserOptions = $argParserOptions === null
            ? new ArgParserOptions()
            : $argParserOptions;
    }

    /**
     * @param string[] $argv
     *
     * @return \HippoPHP\Hippo\CLI\ArgContainer
     */
    private function parseArgs(array $argv)
    {
        $this->stopParsing = false;
        $this->argContainer = new ArgContainer();

        $argCount = count($argv);

        for ($i = 0; $i < $argCount; $i++) {
            $arg = $argv[$i];
            $nextArg = isset($argv[$i + 1]) ? $argv[$i + 1] : null;
            $hasUsedNextArg = $this->processArg($arg, $nextArg);
            if ($hasUsedNextArg) {
                $i++;
            }
        }

        return $this->argContainer;
    }

    /**
     * @param string $arg
     * @param string $nextArg
     *
     * @return bool whether the next arg was used
     */
    private function processArg($arg, $nextArg)
    {
        if ($arg === '--') {
            $this->stopParsing = true;

            return false;
        }

        if (!$this->stopParsing) {
            if ($this->isLongArgument($arg)) {
                $this->argContainer->setLongOption(
                    $this->normalizeArg($arg),
                    $this->extractArgValue($arg, $nextArg, $hasUsedNextArg));

                return $hasUsedNextArg;
            }

            if ($this->isShortArgument($arg)) {
                $this->argContainer->setShortOption(
                    $this->normalizeArg($arg),
                    $this->extractArgValue($arg, $nextArg, $hasUsedNextArg));

                return $hasUsedNextArg;
            }
        }

        $this->argContainer->addStrayArgument($arg);

        return false;
    }

    /**
     * @param string $arg
     * @param string $nextArg
     * @param bool   $hasUsedNextArg
     *
     * @return mixed
     */
    private function extractArgValue($arg, $nextArg, &$hasUsedNextArg)
    {
        $hasUsedNextArg = false;
        $normalizedArg = $this->normalizeArg($arg);

        $index = strpos($arg, '=');
        if ($index !== false) {
            return $this->processStringValue($normalizedArg, substr($arg, $index + 1));
        } elseif ($this->argParserOptions->isFlag($normalizedArg)) {
            if ($this->isBool($nextArg)) {
                $hasUsedNextArg = true;

                return $this->processStringValue($normalizedArg, $nextArg);
            }

            return true;
        } elseif ($nextArg !== null && !$this->isArgument($nextArg)) {
            $hasUsedNextArg = true;

            return $this->processStringValue($normalizedArg, $nextArg);
        }
    }

    /**
     * @param string $normalizedArg
     * @param string $value
     *
     * @return mixed
     */
    private function processStringValue($normalizedArg, $value)
    {
        if ($this->argParserOptions->isFlag($normalizedArg)) {
            return $this->toBool($value);
        } elseif ($this->argParserOptions->isArray($normalizedArg)) {
            return preg_split('/[\s,;]+/', $value);
        }

        return $value;
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function isLongArgument($arg)
    {
        return substr($arg, 0, 2) === '--';
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function isShortArgument($arg)
    {
        return !$this->isLongArgument($arg) && $arg[0]
        === '-';
    }

    /**
     * Normalizes an argument key.
     *
     * @param string $arg
     *
     * @return string
     */
    private function normalizeArg($arg)
    {
        if (strpos($arg, '=') !== false) {
            $arg = substr($arg, 0, strpos($arg, '='));
        }

        return ltrim($arg, '-');
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function isArgument($arg)
    {
        return $this->isLongArgument($arg) || $this->isShortArgument($arg);
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function isBool($arg)
    {
        return $this->toBool($arg) !== null;
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function toBool($arg)
    {
        if ($arg === '0') {
            return false;
        } elseif ($arg === '1') {
            return true;
        }
    }
}
