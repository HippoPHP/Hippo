<?php

namespace HippoPHP\Hippo;

/**
 * A factory of ArgContainer.
 */
class ArgParser
{
    /**
     * @var bool
     */
    private $_stopParsing;

    /**
     * @var ArgContainer
     */
    private $_argContainer;

    /**
     * @var ArgParserOptions
     */
    private $_argParserOptions;

    /**
     * @param string[]         $argv
     * @param ArgParserOptions $argParserOptions
     *
     * @return ArgContainer
     */
    public static function parse(array $argv, ArgParserOptions $argParserOptions = null)
    {
        $parser = new self($argParserOptions);

        return $parser->_parse($argv);
    }

    /**
     * @param ArgParserOptions $argParserOptions
     */
    private function __construct(ArgParserOptions $argParserOptions = null)
    {
        $this->_argParserOptions = $argParserOptions === null
            ? new ArgParserOptions()
            : $argParserOptions;
    }

    /**
     * @param string[] $argv
     *
     * @return ArgContainer
     */
    private function _parse(array $argv)
    {
        $this->_stopParsing = false;
        $this->_argContainer = new ArgContainer();

        $argCount = count($argv);

        for ($i = 0; $i < $argCount; $i++) {
            $arg = $argv[$i];
            $nextArg = isset($argv[$i + 1]) ? $argv[$i + 1] : null;
            $hasUsedNextArg = $this->_processArg($arg, $nextArg);
            if ($hasUsedNextArg) {
                $i++;
            }
        }

        return $this->_argContainer;
    }

    /**
     * @param string $arg
     * @param string $nextArg
     *
     * @return bool whether the next arg was used
     */
    private function _processArg($arg, $nextArg)
    {
        if ($arg === '--') {
            $this->_stopParsing = true;

            return false;
        }

        if (!$this->_stopParsing) {
            if ($this->_isLongArgument($arg)) {
                $this->_argContainer->setLongOption(
                    $this->_normalizeArg($arg),
                    $this->_extractArgValue($arg, $nextArg, $hasUsedNextArg));

                return $hasUsedNextArg;
            }

            if ($this->_isShortArgument($arg)) {
                $this->_argContainer->setShortOption(
                    $this->_normalizeArg($arg),
                    $this->_extractArgValue($arg, $nextArg, $hasUsedNextArg));

                return $hasUsedNextArg;
            }
        }

        $this->_argContainer->addStrayArgument($arg);

        return false;
    }

    /**
     * @param string $arg
     * @param string $nextArg
     * @param bool   $hasUsedNextArg
     *
     * @return mixed
     */
    private function _extractArgValue($arg, $nextArg, &$hasUsedNextArg)
    {
        $hasUsedNextArg = false;
        $normalizedArg = $this->_normalizeArg($arg);

        $index = strpos($arg, '=');
        if ($index !== false) {
            return $this->_processStringValue($normalizedArg, substr($arg, $index + 1));
        } elseif ($this->_argParserOptions->isFlag($normalizedArg)) {
            if ($this->_isBool($nextArg)) {
                $hasUsedNextArg = true;

                return $this->_processStringValue($normalizedArg, $nextArg);
            }

            return true;
        } elseif ($nextArg !== null && !$this->_isArgument($nextArg)) {
            $hasUsedNextArg = true;

            return $this->_processStringValue($normalizedArg, $nextArg);
        }

        return;
    }

    /**
     * @param string $normalizedArg
     * @param string $value
     *
     * @return mixed
     */
    private function _processStringValue($normalizedArg, $value)
    {
        if ($this->_argParserOptions->isFlag($normalizedArg)) {
            return $this->_toBool($value);
        } elseif ($this->_argParserOptions->isArray($normalizedArg)) {
            return preg_split('/[\s,;]+/', $value);
        }

        return $value;
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function _isLongArgument($arg)
    {
        return substr($arg, 0, 2) === '--';
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function _isShortArgument($arg)
    {
        return !$this->_isLongArgument($arg) && $arg{0}
        === '-';
    }

    /**
     * Normalizes an argument key.
     *
     * @param string $arg
     *
     * @return string
     */
    private function _normalizeArg($arg)
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
    private function _isArgument($arg)
    {
        return $this->_isLongArgument($arg) || $this->_isShortArgument($arg);
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function _isBool($arg)
    {
        return $this->_toBool($arg) !== null;
    }

    /**
     * @param string $arg
     *
     * @return bool
     */
    private function _toBool($arg)
    {
        if ($arg === '0') {
            return false;
        } elseif ($arg === '1') {
            return true;
        }

        return;
    }
}
