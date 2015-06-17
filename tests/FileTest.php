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

namespace HippoPHP\tests;

use HippoPHP\Hippo\File;

class FileTest extends \PHPUnit_Framework_TestCase
{
    protected $file;

    public function setUp()
    {
        $this->file = new File('test.php', '<?php echo 1; ?>');
    }

    public function testNewFile()
    {
        $this->assertInstanceOf('\HippoPHP\Hippo\File', $this->file);
    }

    public function testGetFilename()
    {
        $this->assertEquals('test.php', $this->file->getFilename());
    }

    public function testGetSource()
    {
        $this->assertEquals('<?php echo 1; ?>', $this->file->getSource());
    }

    public function testGetOneLine()
    {
        $file = new File('test.php', '<?php echo 1;');
        $this->assertEquals([
                1 => '<?php echo 1;', ],
                $file->getLines());
    }

    public function testGetLines()
    {
        $file = new File('test.php', "<?php echo 1;\necho 2 ?>");
        $this->assertEquals([
                1 => "<?php echo 1;\n",
                2 => 'echo 2 ?>', ],
                $file->getLines());
    }

    public function testGetLinesBlankAtEnd()
    {
        $file = new File('test.php', "<?php echo 1;\n");
        $this->assertEquals([
                1 => "<?php echo 1;\n",
                2 => '', ],
                $file->getLines());
    }

    public function testGetLinesMulitpleBlanksAtEnd()
    {
        $file = new File('test.php', "<?php echo 1;\n\n");
        $this->assertEquals([
                1 => "<?php echo 1;\n",
                2 => "\n",
                3 => '', ],
                $file->getLines());
    }

    public function testGetLinesVaryingEol()
    {
        $file = new File('test.php', "<?php echo 1;\r\necho 2;\recho 3;\necho 4;\recho 5;\r\necho 6;\n");
        $this->assertEquals([
                1 => "<?php echo 1;\r\n",
                2 => "echo 2;\r",
                3 => "echo 3;\n",
                4 => "echo 4;\r",
                5 => "echo 5;\r\n",
                6 => "echo 6;\n",
                7 => '', ],
                $file->getLines());
    }

    public function testGetEncoding()
    {
        $this->assertEquals('UTF-8', $this->file->getEncoding());
    }
}
