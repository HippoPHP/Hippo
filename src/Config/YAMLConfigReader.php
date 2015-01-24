<?php

namespace HippoPHP\Hippo\Config;

use HippoPHP\Hippo\FileSystem;
use Symfony\Component\Yaml\Parser as YamlParser;

class YAMLConfigReader implements ConfigReaderInterface
{
    protected $parser;
    protected $fileSystem;

    /**
     * @param FileSystem $fileSystem
     */
    public function __construct(FileSystem $fileSystem)
    {
        $this->parser = new YamlParser();
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param string $filename
     *
     * @return Config
     */
    public function loadFromFile($filename)
    {
        $config = $this->_parseFile($filename);

        return $this->_load($config, $filename);
    }

    /**
     * @param string $string
     *
     * @return Config
     */
    public function loadFromString($string)
    {
        $config = $this->_parseString($string);

        return $this->_load($config);
    }

    /**
     * @param array $config
     * @param mixed $filename
     *
     * @return Config
     */
    private function _load($config, $filename = false)
    {
        if ($filename) {
            $included = [$this->_normalizeConfigName($filename)];
        } else {
            $included = [];
        }

        while (isset($config['extends'])) {
            $baseConfigName = $config['extends'];
            $baseConfigDir = ($filename ? dirname($filename) : '.');
            $baseConfigPath = $baseConfigDir.DIRECTORY_SEPARATOR.$baseConfigName.'.yml';
            $baseConfig = $this->_parseFile($baseConfigPath);
            unset($config['extends']);

            $config = $this->_mergeRecursive($baseConfig, $config);

            if (isset($config['extends'])) {
                if (in_array($this->_normalizeConfigName($config['extends']), $included)) {
                    // Avoid circular dependencies
                    unset($config['extends']);
                } else {
                    $included[] = $this->_normalizeConfigName($config['extends']);
                }
            }
        }

        return new Config($config);
    }

    /**
     * @param string $filePath
     *
     * @return array<*,*>
     */
    private function _parseFile($filePath)
    {
        return $this->_parseString($this->fileSystem->getContent($filePath));
    }

    /**
     * @param string $string
     *
     * @return array<*,*>
     */
    private function _parseString($string)
    {
        $result = $this->parser->parse($string);
        if (is_string($result)) {
            throw new \Exception('Config must be an array');
        }

        return $result;
    }

    /**
     * Normalizes a configuration filename.
     *
     * @param string $name
     *
     * @return string
     */
    private function _normalizeConfigName($name)
    {
        return trim(basename(strtolower($name), '.yml'));
    }

    /**
     * @param array<*,*> $array1
     * @param array<*,*> $array2
     *
     * @return array<*,*>
     */
    private function _mergeRecursive($array1, $array2)
    {
        $result = [];
        foreach (array_merge(array_keys($array1), array_keys($array2)) as $key) {
            if (!isset($array1[$key])) {
                $result[$key] = $array2[$key];
            } elseif (!isset($array2[$key])) {
                $result[$key] = $array1[$key];
            } elseif (is_array($array1[$key]) || is_array($array2[$key])) {
                if (!is_array($array1[$key]) || !is_array($array2[$key])) {
                    throw new \Exception('Cannot merge a scalar with an array');
                }
                $result[$key] = $this->_mergeRecursive($array1[$key], $array2[$key]);
            } else {
                $result[$key] = $array2[$key];
            }
        }

        return $result;
    }
}
