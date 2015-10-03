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

use HippoPHP\Hippo\CheckResult;
use HippoPHP\Hippo\File;
use HippoPHP\Hippo\Violation;
use PHPUnit_Framework_TestCase;

class CheckResultTest extends PHPUnit_Framework_TestCase
{
    protected $instance;
    protected $file;

    public function setUp()
    {
        $this->instance = new CheckResult();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf('\HippoPHP\Hippo\CheckResult', $this->instance);
    }

    public function testGetFileAtStartup()
    {
        $this->assertNull($this->instance->getFile());
    }

    public function testEmptyByDefault()
    {
        $this->assertEmpty($this->instance->getViolations());
    }

    public function testCount()
    {
        $this->assertEquals(0, $this->instance->count());
    }

    public function testSetFile()
    {
        $this->instance->setFile(new File('test.php', '<?php echo 1 ?>'));

        $this->assertInstanceOf('\HippoPHP\Hippo\File', $this->instance->getFile());
    }

    public function testAddViolation()
    {
        $file = new File('test.php', '<? echo 1; ?>');
        $violation = new Violation($file, 1, 1, Violation::SEVERITY_ERROR, 'Do not use short opening tags.', '<?');
        $this->instance->addViolation($violation);

        $this->assertTrue($this->instance->hasFailed());
        $this->assertFalse($this->instance->hasSucceeded());
    }

    public function testEmptyMaximumViolationSeverity()
    {
        $this->assertNull($this->instance->getMaximumViolationSeverity());
    }

    public function testMaximumViolationSeverity()
    {
        $file = new File('test.php', '<? echo 1; ?>');
        $violation1 = new Violation($file, 1, 1, Violation::SEVERITY_INFO, 'Do not use short opening tags.', '<?');
        $this->instance->addViolation($violation1);

        $violation2 = new Violation($file, 1, 1, Violation::SEVERITY_WARNING, 'Do not use short opening tags.', '<?');
        $this->instance->addViolation($violation2);

        $this->assertEquals(Violation::SEVERITY_WARNING, $this->instance->getMaximumViolationSeverity());
    }

    /**
     * @dataProvider violationOrderProvider
     */
    public function testViolationOrder(array $inputViolations, array $expectedViolations)
    {
        foreach ($inputViolations as $violation) {
            $this->instance->addViolation($violation);
        }

        $actualViolations = $this->instance->getViolations();
        $this->assertEquals($expectedViolations, $actualViolations);
    }

    public function violationOrderProvider()
    {
        $file = new File('test.php', '<? echo 1; ?>');
        $violationLine1Col1 = new Violation($file, 1, 1, Violation::SEVERITY_ERROR, null, null);
        $violationLine2Col1 = new Violation($file, 2, 1, Violation::SEVERITY_ERROR, null, null);
        $violationLine3Col1 = new Violation($file, 3, 1, Violation::SEVERITY_ERROR, null, null);
        $violationLine1Col2 = new Violation($file, 1, 2, Violation::SEVERITY_ERROR, null, null);
        $violationLine2Col2 = new Violation($file, 2, 2, Violation::SEVERITY_ERROR, null, null);

        return [
            [[$violationLine1Col1, $violationLine1Col1], [$violationLine1Col1, $violationLine1Col1]],
            [[$violationLine1Col1, $violationLine2Col1], [$violationLine1Col1, $violationLine2Col1]],
            [[$violationLine2Col1, $violationLine1Col1], [$violationLine1Col1, $violationLine2Col1]],
            [[$violationLine2Col1, $violationLine3Col1], [$violationLine2Col1, $violationLine3Col1]],
            [
                [$violationLine2Col1, $violationLine1Col2, $violationLine3Col1, $violationLine2Col2],
                [$violationLine1Col2, $violationLine2Col1, $violationLine2Col2, $violationLine3Col1],
            ],
        ];
    }
}
