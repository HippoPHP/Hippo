<?php

	namespace Hippo\Tests;

	use Hippo;
	use Hippo\File;

	class FileTest extends \PHPUnit_Framework_TestCase {
		protected $file;

		public function setUp() {
			$this->file = new File('test.php', '<?php echo 1; ?>');
		}

		public function testNewFile() {
			$this->assertInstanceOf('Hippo\File', $this->file);
		}

		public function testGetFilename() {
			$this->assertEquals('test.php', $this->file->getFilename());
		}

		public function testGetSource() {
			$this->assertEquals('<?php echo 1; ?>', $this->file->getSource());
		}

		public function testGetOneLine() {
			$file = new File('test.php', "<?php echo 1;");
			$this->assertEquals(array(
				"<?php echo 1;"),
				$file->getLines());
		}

		public function testGetLines() {
			$file = new File('test.php', "<?php echo 1;\necho 2 ?>");
			$this->assertEquals(array(
				"<?php echo 1;\n",
				"echo 2 ?>"),
				$file->getLines());
		}

		public function testGetLinesBlankAtEnd() {
			$file = new File('test.php', "<?php echo 1;\n");
			$this->assertEquals(array(
				"<?php echo 1;\n",
				''),
				$file->getLines());
		}

		public function testGetLinesMulitpleBlanksAtEnd() {
			$file = new File('test.php', "<?php echo 1;\n\n");
			$this->assertEquals(array(
				"<?php echo 1;\n",
				"\n",
				''),
				$file->getLines());
		}

		public function testGetLinesVaryingEol() {
			$file = new File('test.php', "<?php echo 1;\r\necho 2;\recho 3;\necho 4;\recho 5;\r\necho 6;\n");
			$this->assertEquals(array(
				"<?php echo 1;\r\n",
				"echo 2;\r",
				"echo 3;\n",
				"echo 4;\r",
				"echo 5;\r\n",
				"echo 6;\n",
				''),
				$file->getLines());
		}

		public function testGetEncoding() {
			$this->assertEquals('UTF-8', $this->file->getEncoding());
		}
	}
