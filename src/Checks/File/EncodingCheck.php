<?php

namespace HippoPHP\Hippo\Checks\Naming;

use HippoPHP\Hippo\CheckContext;
use HippoPHP\Hippo\Checks\AbstractCheck;
use HippoPHP\Hippo\Checks\CheckInterface;
use HippoPHP\Hippo\Config\Config;
use HippoPHP\Hippo\Violation;

class EncodingCheck extends AbstractCheck implements CheckInterface
{
        /**
         * Set BOM encoding string.
         */
        const BOM = '\xEF\xBB\xBF';

        /**
         * File encoding to check for.
         *
         * @var string
         */
        protected $encoding = 'UTF-8';

        /**
         * Are we checking for a BOM too?
         *
         * @var bool
         */
        protected $bom = true;

        /**
         * Sets the file encoding type to check for.
         *
         * @param string $encoding
         */
        public function setEncodingType($encoding)
        {
            $this->encoding = $encoding;
        }

        /**
         * Do we want to use BOM?
         *
         * @param bool $bom
         */
        public function setWithBOM($bom)
        {
            $this->bom = $bom;
        }

        /**
         * @return string
         */
        public function getConfigRoot()
        {
            return 'file.encoding';
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

            if (!function_exists('mb_detect_encoding')) {
                $this->addViolation(
                    $file,
                    0,
                    0,
                    'PHP MB extension is disabled. Cannot detect file encoding.',
                    Violation::SEVERITY_WARNING
                );

                return;
            }

            $this->setEncodingType($config->get('encoding', $this->encoding));
            $this->setWithBOM($config->get('bom', $this->bom));

            $encoding = mb_detect_encoding($file->getSource(), $this->encoding, true);

            if ($encoding !== $this->encoding) {
                $this->addViolation(
                    $file,
                    0,
                    0,
                    sprintf(
                        'File encoding should be %s. Currently using %s',
                        $this->encoding,
                        $encoding

                    ),
                    Violation::SEVERITY_INFO
                );

                // Are we checking for BOM too?
                if ($this->bom) {
                    if (false === strpos($file->getSource, self::BOM)) {
                        $this->addViolation(
                            $file,
                            0,
                            0,
                            'Files should be saved with BOM.',
                            Violation::SEVERITY_INFO
                        );
                    }
                }
            }
        }
}
