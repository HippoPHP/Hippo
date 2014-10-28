<?php 

	namespace PHPCheckstyle\Tests;

	use PHPCheckstyle\PHPCheckstyle\File;

	class FileTest extends \PHPUnit_Framework_TestCase {
		protected $file;

		public function __construct() {
			$this->file = new File('test.php', '<?php echo 1; ?>');
		}

		public function testNewFile() {
			$this->assertInstanceOf('PHPCheckstyle\PHPCheckstyle\File', $this->file);
		}

		public function testGetFilename() {
			$this->assertEquals('test.php', $this->file->getFilename());
		}

		public function testGetSource() {
			$this->assertEquals('<?php echo 1; ?>', $this->file->getSource());
		}

		/*public function testGetLines() {
			$this->assertEquals(array('<?php echo 1; ?>'), $this->file->getLines());
		}*/

		public function testGetEncoding() {
			$this->assertEquals('UTF-8', $this->file->getEncoding());
		}
	}
