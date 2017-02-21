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

namespace HippoPHP\Hippo\Checks\File;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;

/**
 * This is the tag types check class.
 *
 * @author James Brooks <james@alt-three.com>
 */
class TagTypesCheck extends AbstractCheck implements CheckInterface
{
    /**
     * The accepted open tags.
     *
     * @var string[]
     */
    const ACCEPTED_OPEN_TAGS = [
        '<?php',
        '<?=',
    ];

    /**
     * Returns the configuration root.
     *
     * @return string
     */
    public function getConfigRoot()
    {
        return 'file.tag_types';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param CheckContext $checkContext
     * @param Config       $config
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $tokens = $checkContext->getTokenList();
        $firstToken = $tokens->rewind()->current();

        // Make sure we open with a valid open tag, but then check what it is.
        if ($firstToken->isType(T_OPEN_TAG) || $firstToken->isType(T_OPEN_TAG_WITH_ECHO)) {
            $openTag = trim($firstToken->getContent());

            if (!in_array($openTag, self::ACCEPTED_OPEN_TAGS)) {
                $this->addViolation($file, 1, 1, 'Files must use the long tags or short-echo tags.');
            }
        }
    }
}
