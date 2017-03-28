<?php

namespace Intracto\SecretSantaBundle\Twig;

class LinkifyExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('linkify', [$this, 'linkifyFilter']),
        ];
    }

    public function linkifyFilter($html)
    {
        // if it contains html links, the user entered HTML. Don't touch it
        if (strpos($html, '<a href="')) {
            return $html;
        }

        return preg_replace(
            '~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]~',
            '<a href="\\0" target="_blank" rel="noopener noreferrer">\\0</a>',
            $html
        );
    }
}
