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
        if (preg_match('~<a[^>]*>([^<]+)<\/a>~', $html)) {
            return $html;
        }

        //Selects all urls starting with ://
        $html =  preg_replace(
            '~[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/=!\?,]~',
            '<a href="\\0" target="_blank" rel="noopener noreferrer">\\0</a>',
            $html
        );

        //Selects all urls starting with www. but do not start with ://
        $html =  preg_replace(
            '~(?<!://)www.[^<>[:space:]]+[[:alnum:]/=!\?,]~',
            '<a href="http://\\0" target="_blank" rel="noopener noreferrer">\\0</a>',
            $html
        );

        return $html;
    }
}
