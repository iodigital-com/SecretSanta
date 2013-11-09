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
        return ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a href=\"\\0\">\\0</a>", $html);
    }

    public function getName()
    {
        return 'linkify_extension';
    }
}
