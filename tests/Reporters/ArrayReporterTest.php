<?php

/*
 * This file is part of Hippo.
 *
 * (c) James Brooks <james@alt-three.com>
 * (c) Marcin Kurczewski <rr-@sakuya.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HippoPHP\Tests\Reporters;

use HippoPHP\Hippo\Reporters\ArrayReporter;

class ArrayReporterTest extends AbstractReporterTest
{
    public function testEmptyReport()
    {
        $reporter = new ArrayReporter();
        $reporter->start();
        $reporter->finish();
        $this->assertEquals([], $reporter->getReport());
    }

    public function testReportWithNoViolations()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new ArrayReporter();
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getEmptyCheckResult($file)]);
        $reporter->finish();
        $this->assertEquals([], $reporter->getReport());
    }

    public function testBasicReport()
    {
        $file = $this->getFile('whatever.php');
        $reporter = new ArrayReporter();
        $reporter->start();
        $reporter->addCheckResults($file, [$this->getBasicCheckResult($file)]);
        $reporter->finish();

        $expectedLines = [
            'whatever.php:1' => [
                0 => [
                    'file'     => 'whatever.php',
                    'line'     => 1,
                    'column'   => 4,
                    'severity' => 1,
                    'message'  => 'first message',
                ],
            ],
            'whatever.php:2' => [
                0 => [
                    'file'     => 'whatever.php',
                    'line'     => 2,
                    'column'   => 5,
                    'severity' => 2,
                    'message'  => 'second message',
                ],
            ],
            'whatever.php:3' => [
                0 => [
                    'file'     => 'whatever.php',
                    'line'     => 3,
                    'column'   => 6,
                    'severity' => 3,
                    'message'  => 'third message',
                ],
            ],
        ];

        $this->assertEquals($expectedLines, $reporter->getReport());
    }
}
