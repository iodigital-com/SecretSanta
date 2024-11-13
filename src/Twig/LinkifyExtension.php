<?php

namespace App\Twig;

use App\Service\UrlTransformerService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class LinkifyExtension extends AbstractExtension
{
    public function __construct(private UrlTransformerService $urlTransformerService)
    {
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('linkify', [$this, 'linkifyFilter']),
        ];
    }

    public function linkifyFilter($html)
    {
        // if it contains html links, the user entered HTML. Don't touch it
        if (preg_match('~<a[^>]*>([^<]+)<\/a>~', $html)) {
            return $html;
        }

        // extract, transform, create links and replace
        $urls = $this->urlTransformerService->extractUrls($html);
        $replacements = [];
        foreach ($urls as $url) {
            $replacement = $this->urlTransformerService->transformUrl($url);
            $replacements[$url] = '<a href="'.$replacement.'" target="_blank" rel="noopener noreferrer">'.$url.'</a>';
        }
        $html = $this->urlTransformerService->replaceUrls($html, $replacements);

        return $html;
    }
}
