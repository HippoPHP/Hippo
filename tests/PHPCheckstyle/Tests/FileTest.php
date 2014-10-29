<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle;
	use PHPCheckstyle\File;

	class FileTest extends \PHPUnit_Framework_TestCase {
		protected $file;

		public function __construct() {
			$this->file = new File('test.php', '<?php echo 1; ?>');
		}

		public function testNewFile() {
			$this->assertInstanceOf('PHPCheckstyle\File', $this->file);
		}

		public function testGetFilename() {
			$this->assertEquals('test.php', $this->file->getFilename());
		}

		public function testGetSource() {
			$this->assertEquals('<?php echo 1; ?>', $this->file->getSource());
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
			$file = new File('test.php', "<?php echo 1;\r\necho 2\recho 3\n");
			$this->assertEquals(array(
				"<?php echo 1;\r\n",
				"echo 2\r",
				"echo 3\n",
				''),
				$file->getLines());
		}

		public function testGetEncoding() {
			$this->assertEquals('UTF-8', $this->file->getEncoding());
		}

		public function testSeekTokenTypeMethod() {
			$this->markTestIncomplete('This test has not been implemented yet.');
		}

		public function testSeekNextLineMethod() {
			$this->markTestIncomplete('This test has not been implemented yet.');
		}

	}
