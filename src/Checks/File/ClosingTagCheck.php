<?php

namespace HippoPHP\Hippo\Checks\Line;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;

/**
 * Checks the open tag.
 */
class ClosingTagCheck extends AbstractCheck implements CheckInterface
{
    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'file.end_tag';
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
        $endToken = $tokens->end()->current();

        if (count($file) > 0) {
            if ($config->get('endwith') && !$endToken->isType(T_CLOSE_TAG)) {
                $this->addViolation($file, $endToken->getLine(), 0, 'Files must end with a closing tag.');
            } elseif (!$config->get('endwith') && $endToken->isType(T_CLOSE_TAG)) {
                $this->addViolation($file, $endToken->getLine(), 0, 'Files must not end with a closing tag.');
            }
        }
    }
}