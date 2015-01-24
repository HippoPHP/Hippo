<?php

namespace HippoPHP\Hippo\Checks\Line;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;

/**
 * Checks the open tag.
 */
class MustStartWithOpenTagCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'file.open_tag';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param CheckContext $checkContext
     * @param Config       $config
     *
     * @return void
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $tokens = $checkContext->getTokenList();
        $firstToken = $tokens->rewind()->current();
        if (!$firstToken->isType(T_OPEN_TAG)) {
            $this->addViolation($file, 1, 1, 'Files must begin with the PHP open tag.');
        }
    }
}
