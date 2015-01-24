<?php

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Exception\BadConfigKeyException;
use HippoPHP\Hippo\Violation;

class QuoteStyleCheck extends AbstractCheck implements CheckInterface
{
    /**
     * Quote style to use.
     *
     * @var string
     */
    protected $style = 'single';

    /**
     * Look up of quote styles.
     *
     * @var array
     */
    private $_styles = [
        'single' => '\'',
        'double' => '"',
    ];

    public function setQuoteStyle($style)
    {
        if (isset($this->_styles[$style])) {
            $this->style = $style;
        } else {
            throw new BadConfigKeyException(sprintf(
                    'Unknown quote style \"%s\"',
                    $style
                ));
        }
    }

    /**
     * @return string
     */
    public function getConfigRoot()
    {
        return 'style.quotes';
    }

    /**
     * checkFileInternal(): defined by AbstractCheck.
     *
     * @see AbstractCheck::checkFileInternal()
     *
     * @param CheckContext $checkContext
     * @param Config $config
     *
     * @return void
     */
    protected function checkFileInternal(CheckContext $checkContext, Config $config)
    {
        $file = $checkContext->getFile();
        $tokens = $checkContext->getTokenList();

        $this->setQuoteStyle($config->get('style', $this->style));

        try {
            do {
                // Jump us to the next token we want to check.
                $tokens->seekToType(T_CONSTANT_ENCAPSED_STRING);

                $token = $tokens->current();
                if ($this->_isBadStyle($token)) {
                    // Work out what style we shouldn't be using.
                    $styles = $this->_styles;
                    unset($styles[$this->style]);
                    $badStyle = array_keys($styles)[0];

                    $this->addViolation(
                        $file,
                        $token->getLine(),
                        $token->getColumn(),
                        sprintf(
                            'Prefer %s quotes to %s',
                            addslashes($this->style),
                            $badStyle
                        ),
                        Violation::SEVERITY_INFO
                    );
                }
            } while ($tokens->valid());
        } catch (\HippoPHP\Tokenizer\Exception\OutOfBoundsException $e) {
            // Ignore the exception, we're at the end of the file.
        }
    }

    private function _isBadStyle($token)
    {
        $tokenQuote = $this->_getQuoteFromToken($token);

        return $tokenQuote !== $this->_styles[$this->style];
    }

    private function _getQuoteFromToken($token)
    {
        return $token->getContent()[0];
    }
}
