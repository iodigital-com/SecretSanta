<?php

namespace App\Service;

use Symfony\Contracts\Translation\LocaleAwareInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Responsible for replacing the SITE_DOMAIN placeholder in the translation files
 */
class SiteDomainDecoratingTranslator implements TranslatorInterface, TranslatorBagInterface, LocaleAwareInterface
{
    public function __construct(
        private TranslatorInterface $translator,
        private string $siteDomain,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        $trans = $this->translator->trans($id, $parameters, $domain, $locale);

        return str_replace('SITE_DOMAIN', $this->siteDomain, $trans);
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale)
    {
        // @todo: refactor to intersection type in php 8.1
        if (!($this->translator instanceof LocaleAwareInterface)) {
            throw new \LogicException('Translator should implement LocaleAwareInterface');
        }

        $this->translator->setLocale($locale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale()
    {
        // @todo: refactor to intersection type in php 8.1
        if (!($this->translator instanceof LocaleAwareInterface)) {
            throw new \LogicException('Translator should implement LocaleAwareInterface');
        }

        return $this->translator->getLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getCatalogue($locale = null)
    {
        // @todo: refactor to intersection type in php 8.1
        if (!($this->translator instanceof TranslatorBagInterface)) {
            throw new \LogicException('Translator should implement TranslatorBagInterface');
        }

        return $this->translator->getCatalogue($locale);
    }
}
