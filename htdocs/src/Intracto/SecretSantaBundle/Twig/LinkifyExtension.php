<?php
namespace Intracto\SecretSantaBundle\Twig;

class LinkifyExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('linkify', array($this, 'linkifyFilter')),
        );
    }

    public function linkifyFilter($html)
    {
        return preg_replace(
            '@(https?://([-\w\.]+)+(:\d+)?(/([-\w/_\.]*(\?\S+)?)?)?)@',
            '<a href="$1" target="_blank">$1</a>',
            $html
        );
    }

    public function getName()
    {
        return 'linkify_extension';
    }
}
