<?php

namespace App\Service;

class UrlTransformerService
{
    /*
     * Known hostformats.
     *  key is regex to match host portion of url (parse_url)
     *  value are <type>[,parameters...] where type denotes the affiliate program later used for the transformation logic
     *  value can contain backreferences \1, \2, ... to the matches in the regular expression
     */
    private array $hostFormats = [
        '/^(?:www\.)?amazon\.(com|ae|ca|cn|de|eg|es|fr|ie|in|it|nl|pl|sa|se|sg)$/' => 'amazon_\1',
        '/^(?:www\.)?amazon\.co\.(jp|uk|za)$/' => 'amazon_co_\1',
        '/^(?:www\.)?amazon\.com\.(au|be|br|mx|tr)$/' => 'amazon_com_\1',
        '/^(?:www\.)?bol\.com$/' => 'bol',
        '/^(?:www\.)?coolblue\.be$/' => 'awin,85165',
    ];

    private $partnerIds = [];

    public function __construct()
    {
        // Get all partner ids from ENV.
        // Multiple ids are supported by space seperating them.
        foreach ($_ENV as $key => $value) {
            if (0 === strpos($key, 'PARTNER_')) {
                $this->partnerIds[strtolower(substr($key, 8))] = array_filter(explode(' ', $value));
            }
        }
    }

    public function extractUrls(string $text): array
    {
        $pattern = '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#';
        if (preg_match_all($pattern, $text, $matches)) {
            // remove duplicates
            $urls = array_unique($matches[0]);

            return $urls;
        }

        return [];
    }

    /**
     * Replace all urls in input array (as key) with its replacements (values).
     *
     * Custom function because str_replace could replace occurrences from previous replacements
     *   or urls that are child/parent urls of other urls that need to be replaced
     */
    public function replaceUrls(string $text, array $urls): string
    {
        // urls contain the original urls as key, and replacements as value
        $urlsOnly = array_keys($urls);

        // sort by length longest to shortest
        usort($urlsOnly, function ($a, $b) {
            return strlen($b) - strlen($a);
        });

        // create index of all positions of urls, where a position can only be taken by the longest url (child/parent)
        $byUrl = [];
        $byPosition = [];
        foreach ($urlsOnly as $url) {
            $byUrl[$url] = [];
            // get first match
            $position = strpos($text, $url, 0);
            while (false !== $position) {
                if (!isset($byPosition[$position])) {
                    // position not already matched with longer url
                    $byUrl[$url][] = $position;
                    $byPosition[$position] = $url;
                }
                // find next occurrence
                $position = strpos($text, $url, $position + strlen($url));
            }
        }

        // start replacements back to front to not mess up earlier positions
        krsort($byPosition, SORT_NUMERIC);
        foreach ($byPosition as $position => $url) {
            $text = substr($text, 0, $position).
                    $urls[$url].
                    substr($text, $position + strlen($url))
            ;
        }

        return $text;
    }

    public function transformUrl(string $url): string
    {
        // parse URL into parts
        $urlParts = parse_url($url);

        // find matching hostpattern
        $matchedFormat = '';
        foreach ($this->hostFormats as $hostFormat => $key) {
            if (preg_match($hostFormat, $urlParts['host'])) {
                $matchedFormat = preg_replace($hostFormat, $key, $urlParts['host']);
                break;
            }
        }

        if ($matchedFormat) {
            // split by comma. Shift first element off and use as key to identify type of link.
            $params = explode(',', $matchedFormat);
            $key = array_shift($params);
            // have we configured a partner id for this program?
            if (isset($this->partnerIds[$key][0]) && $this->partnerIds[$key][0]) {
                if (count($this->partnerIds[$key]) > 1) {
                    // select random id from array
                    $partnerId = $this->partnerIds[$key][array_rand($this->partnerIds[$key])];
                } else {
                    $partnerId = $this->partnerIds[$key][0];
                }

                switch (true) {
                    case 'amazon_' == substr($key, 0, 7):
                        // append id as tag parameter
                        if (isset($urlParts['query'])) {
                            $url .= '&tag='.$partnerId;
                        } else {
                            $url .= '?tag='.$partnerId;
                        }
                        break;

                    case 'bol' == $key:
                        // generate text link to partner program and append original URL encoded
                        $url = 'https://partner.bol.com/click/click?p=1&t=url&s='.$partnerId.'&f=TXL&url='.urlencode($url);
                        break;

                    case 'tradetracker' == $key:
                        // params[0] should contain campaignid, append original URL encoded
                        $url = 'https://tc.tradetracker.net/?c='.$params[0].'&m=12&a='.$partnerId.'&r=&u='.urlencode($urlParts['path']);
                        if (isset($urlParts['query'])) {
                            $url .= urlencode('?'.$urlParts['query']);
                        }
                        break;

                    case 'awin' == $key:
                        // params[0] should contain merchantid, append original URL encoded
                        $url = 'https://www.awin1.com/cread.php?awinmid='.$params[0].'&awinaffid='.$this->partnerIds[$key].'&ued='.urlencode($url);
                        break;

                    default:
                        // No matching format
                }
            }
        }

        return $url;
    }
}
