<?php

namespace HippoPHP\Hippo;

/**
 * File class.
 */
class File
{
    /**
     * Filename.
     *
     * @var string
     */
    protected $filename;

    /**
     * Source code within the file.
     *
     * @var string
     */
    protected $source;

    /**
     * Encoding of the file.
     *
     * @var string
     */
    protected $encoding;

    /**
     * Lines of the file.
     *
     * @var array
     */
    protected $lines = [];

    /**
     * Create a new file representation.
     *
     * @param string $filename
     * @param string $source
     * @param string $encoding
     *
     * @return File
     */
    public function __construct($filename = null, $source = null, $encoding = 'UTF-8')
    {
        $this->filename = $filename;
        $this->source = $source;
        $this->encoding = $encoding;

        $this->lines = $this->_buildLinesFromSource($source);

        return $this;
    }

    /**
     * Return the filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Return the source of the file.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Return the individual array of lines.
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Return the file encoding.
     *
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

    /**
     * @param string $source
     *
     * @return array
     */
    private function _buildLinesFromSource($source)
    {
        $eols = ["\r", "\n", "\r\n"];

        $lines = [];
        $index = 1;
        while ($source !== '') {
            $line = $this->_extractNextLine($source, $eols, $eolUsed);
            $lines[$index ++] = $line;
            $source = strval(substr($source, strlen($line)));
            if ($eolUsed !== null && $source === '') {
                $lines[$index ++] = '';
                break;
            }
        }

        return $lines;
    }

    /**
     * @param string $source
     * @param string[] $eols
     * @param string $eolUsed
     *
     * @return string
     */
    private function _extractNextLine($source, array $eols, &$eolUsed)
    {
        $minIndex = false;
        $eolUsed = null;
        foreach ($eols as $eol) {
            $index = strpos($source, $eol);
            if ($index === false) {
                continue;
            }
            $isLonger = strlen($eol) > strlen($eolUsed);
            if ($minIndex === false || $index < $minIndex || ($index === $minIndex && $isLonger)) {
                $eolUsed = $eol;
                $minIndex = $index;
            }
        }

        return $eolUsed !== null
            ? strval(substr($source, 0, $minIndex + strlen($eolUsed)))
            : $source;
    }
}
