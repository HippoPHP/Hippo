<?php

namespace HippoPHP\tests;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\File;

class CheckContextTest extends \PHPUnit_Framework_TestCase
{
    private $_file;
    private $_checkContext;

    public function setUp()
    {
        $this->_file = new File('tokio.php', '<?php echo "1" + "1";');
        $this->_checkContext = new CheckContext($this->_file);
    }

    public function testGetFile()
    {
        $this->assertNotNull($this->_checkContext->getFile());
        $this->assertEquals('tokio.php', $this->_checkContext->getFile()->getFilename());
    }

    public function testGetTokenList()
    {
        $tokenList = $this->_checkContext->getTokenList();
        $this->assertNotNull($tokenList);
        $this->assertTrue(is_array($tokenList->getTokens()));
        $this->assertEquals(9, count($tokenList));
        $this->assertInstanceOf('\HippoPHP\Tokenizer\Token', $tokenList->current());
    }

    public function testTokenListPositionReset()
    {
        $tokenList = $this->_checkContext->getTokenList();
        $tokenList->rewind();
        $tokenList->seek(1);
        $this->assertEquals(1, $tokenList->key());
        $tokenList = $this->_checkContext->getTokenList();
        $this->assertEquals(0, $tokenList->key());
    }

    public function testGetSyntaxTree()
    {
        $syntaxTree = $this->_checkContext->getSyntaxTree();
        $this->assertNotNull($syntaxTree);
        $this->assertTrue(is_array($syntaxTree));
        $this->assertInstanceOf('\PhpParser\Node\Stmt\Echo_', $syntaxTree[0]);
    }
}
