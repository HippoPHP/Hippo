<?php

namespace HippoPHP\Hippo\Tests;

use HippoPHP\Hippo\FileSystem;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlValidationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider yamlPathProvider
     */
    public function testSymfonyParsing($yamlPath)
    {
        $parser = new YamlParser();
        $parsed = $parser->parse(file_get_contents($yamlPath), true);
        $this->assertTrue(is_array($parsed));
    }

    /**
     * @dataProvider yamlPathProvider
     */
    public function testTwoSpaceIndentation($yamlPath)
    {
        $lines = explode("\n", file_get_contents($yamlPath));
        $leadingWhitespace = null;

        foreach ($lines as $line) {
            $priorLeadingWhitespace = $leadingWhitespace;
            preg_match('/^\s*/', $line, $matches);
            $leadingWhitespace = $matches[0];

            // indentation is always nothing but spaces
            $nonSpaces = preg_replace('/[^\32]/', '', $leadingWhitespace);
            $this->assertEquals(0, strlen($nonSpaces));

            // indentation is always a multiple of 2
            $this->assertEquals(0, strlen($leadingWhitespace) % 2);

            // increased indentation = previous line + 2 spaces
            if ($priorLeadingWhitespace !== null && strlen($leadingWhitespace) > strlen($priorLeadingWhitespace)) {
                $this->assertEquals(strlen($priorLeadingWhitespace) + 2, strlen($leadingWhitespace));
            }
        }
    }

    public function yamlPathProvider()
    {
        $paths = $this->_getAllYamlFiles();

        return array_map(function ($path) { return [$path]; }, $paths);
    }

    private function _getAllYamlFiles()
    {
        $fileSystem = new FileSystem();
        $rootDir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src';

        return $fileSystem->getAllFiles($rootDir, '/\.yml$/i');
    }
}
