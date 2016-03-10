<?php

namespace Intracto\SecretSantaBundle\Query;

use Google_Client;
use Google_Service_Analytics;

class GoogleAnalyticsQuery
{
    private $viewId;
    private $clientSecret;

    public function __construct($viewId, $clientSecret)
    {
        $this->viewId = $viewId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @param null $year
     * @return array
     * @throws \Google_Exception
     */
    public function getAnalyticsReport($year = null)
    {
        $season = new Season($year);

        $client = new Google_Client();
        $credentials = $client->loadServiceAccountJson($this->clientSecret, "https://www.googleapis.com/auth/analytics.readonly");
        $client->setAssertionCredentials($credentials);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion();
        }

        $analytics = new Google_Service_Analytics($client);
        $analyticsViewId = $this->viewId;
        $startDate = $season->getStart()->format('Y-m-d');
        $endDate = $season->getEnd()->format('Y-m-d');
        $metrics = 'ga:sessions';

        $gaParameters = new GaParameters($analytics, $analyticsViewId, $startDate, $endDate, $metrics);

        return [
            'countries' => $this->getTopCountries($gaParameters)->rows,
            'language' => $this->getTopLanguages($gaParameters),
            'deviceCategory' => $this->getDeviceCategory($gaParameters)->rows,
            'browser' => $this->getBrowsers($gaParameters)->rows,
        ];
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getTopCountries(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:country',
                'sort' => '-ga:sessions, -ga:country'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getTopLanguages(GaParameters $gaParameters)
    {
        $query = $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:language',
                'sort' => '-ga:sessions, -ga:language'
            ]
        );

        $languages = [];

        $english = 0;
        $spanish = 0;
        $dutch = 0;
        $french = 0;
        $russian = 0;
        $portuguese = 0;
        $german = 0;
        $chinese = 0;
        $korean = 0;
        $romanian = 0;
        $italian = 0;
        $ukrainian = 0;
        $swedish = 0;
        $polish = 0;
        $japanese = 0;
        $greek = 0;
        $turkish = 0;
        $bulgarian = 0;
        $vietnamese = 0;
        $norwegian = 0;
        $danish = 0;
        $finnish = 0;
        $hungarian = 0;
        $estonian = 0;
        $lithuanian = 0;
        $latvian = 0;
        $serbian = 0;
        $slovene = 0;
        $slovak = 0;
        $croatian = 0;
        $czech = 0;
        $arabic = 0;
        $maltese = 0;
        $thai = 0;
        $hebrew = 0;
        $bosnian = 0;
        $kazakh = 0;
        $indonesian = 0;
        $afrikaans = 0;
        $malay = 0;
        $tonga = 0;
        $georgian = 0;
        $tagalog = 0;
        $icelandic = 0;
        $mongolian = 0;
        $bihari = 0;
        $akan = 0;
        $persian = 0;
        $maori = 0;
        $belarusian = 0;
        $gujarati = 0;
        $hindi = 0;
        $luxembourgish = 0;
        $tamil = 0;
        $uzbek = 0;
        $swahili = 0;
        $panjabi = 0;

        foreach ($query->rows as $lang) {
            if (strpos($lang[0], 'en') !== false ||
                strpos($lang[0], 'ga') !== false ||
                strpos($lang[0], 'cy') !== false
            ) {
                $english += $lang[1];
            } else if (strpos($lang[0], 'es') !== false ||
                       strpos($lang[0], 'ca') !== false ||
                       strpos($lang[0], 'gl') !== false ||
                       strpos($lang[0], 'eu') !== false
            ) {
                $spanish += $lang[1];
            } else if (strpos($lang[0], 'nl') !== false) {
                $dutch += $lang[1];
            } else if (strpos($lang[0], 'fr') !== false ||
                       strpos($lang[0], 'br') !== false
            ) {
                $french += $lang[1];
            } else if (strpos($lang[0], 'ru') !== false) {
                $russian += $lang[1];
            } else if (strpos($lang[0], 'pt') !== false) {
                $portuguese += $lang[1];
            } else if (strpos($lang[0], 'de') !== false) {
                $german += $lang[1];
            } else if (strpos($lang[0], 'zh') !== false) {
                $chinese += $lang[1];
            } else if (strpos($lang[0], 'ko') !== false) {
                $korean += $lang[1];
            } else if (strpos($lang[0], 'ro') !== false) {
                $romanian += $lang[1];
            } else if (strpos($lang[0], 'it') !== false) {
                $italian += $lang[1];
            } else if (strpos($lang[0], 'uk') !== false) {
                $ukrainian += $lang[1];
            } else if (strpos($lang[0], 'sv') !== false) {
                $swedish += $lang[1];
            } else if (strpos($lang[0], 'pl') !== false) {
                $polish += $lang[1];
            } else if (strpos($lang[0], 'ja') !== false) {
                $japanese += $lang[1];
            } else if (strpos($lang[0], 'el') !== false) {
                $greek += $lang[1];
            } else if (strpos($lang[0], 'tr') !== false) {
                $turkish += $lang[1];
            } else if (strpos($lang[0], 'bg') !== false) {
                $bulgarian += $lang[1];
            } else if (strpos($lang[0], 'vi') !== false) {
                $vietnamese += $lang[1];
            } else if (strpos($lang[0], 'nb') !== false ||
                       strpos($lang[0], 'no') !== false
            ) {
                $norwegian += $lang[1];
            } else if (strpos($lang[0], 'da') !== false) {
                $danish += $lang[1];
            } else if (strpos($lang[0], 'fi') !== false) {
                $finnish += $lang[1];
            } else if (strpos($lang[0], 'hu') !== false) {
                $hungarian += $lang[1];
            } else if (strpos($lang[0], 'et') !== false) {
                $estonian += $lang[1];
            } else if (strpos($lang[0], 'lt') !== false) {
                $lithuanian += $lang[1];
            } else if (strpos($lang[0], 'lv') !== false) {
                $latvian += $lang[1];
            } else if (strpos($lang[0], 'sr') !== false) {
                $serbian += $lang[1];
            } else if (strpos($lang[0], 'sl') !== false) {
                $slovene += $lang[1];
            } else if (strpos($lang[0], 'sk') !== false) {
                $slovak += $lang[1];
            } else if (strpos($lang[0], 'hr') !== false) {
                $croatian += $lang[1];
            } else if (strpos($lang[0], 'cs') !== false) {
                $czech += $lang[1];
            } else if (strpos($lang[0], 'ar') !== false) {
                $arabic += $lang[1];
            } else if (strpos($lang[0], 'mt') !== false) {
                $maltese += $lang[1];
            } else if (strpos($lang[0], 'th') !== false) {
                $thai += $lang[1];
            } else if (strpos($lang[0], 'he') !== false) {
                $hebrew += $lang[1];
            } else if (strpos($lang[0], 'bs') !== false) {
                $bosnian += $lang[1];
            } else if (strpos($lang[0], 'kk') !== false) {
                $kazakh += $lang[1];
            } else if (strpos($lang[0], 'id') !== false) {
                $indonesian += $lang[1];
            } else if (strpos($lang[0], 'af') !== false) {
                $afrikaans += $lang[1];
            } else if (strpos($lang[0], 'ms') !== false) {
                $malay += $lang[1];
            } else if (strpos($lang[0], 'to') !== false) {
                $tonga += $lang[1];
            } else if (strpos($lang[0], 'ka') !== false) {
                $georgian += $lang[1];
            } else if (strpos($lang[0], 'tl') !== false) {
                $tagalog += $lang[1];
            } else if (strpos($lang[0], 'is') !== false) {
                $icelandic += $lang[1];
            } else if (strpos($lang[0], 'mn') !== false) {
                $mongolian += $lang[1];
            } else if (strpos($lang[0], 'bh') !== false) {
                $bihari += $lang[1];
            } else if (strpos($lang[0], 'ak') !== false) {
                $akan += $lang[1];
            } else if (strpos($lang[0], 'fa') !== false) {
                $persian += $lang[1];
            } else if (strpos($lang[0], 'mi') !== false) {
                $maori += $lang[1];
            } else if (strpos($lang[0], 'be') !== false) {
                $belarusian += $lang[1];
            } else if (strpos($lang[0], 'gu') !== false) {
                $gujarati += $lang[1];
            } else if (strpos($lang[0], 'hi') !== false) {
                $hindi += $lang[1];
            } else if (strpos($lang[0], 'lb') !== false) {
                $luxembourgish += $lang[1];
            } else if (strpos($lang[0], 'ta') !== false) {
                $tamil += $lang[1];
            } else if (strpos($lang[0], 'uz') !== false) {
                $uzbek += $lang[1];
            } else if (strpos($lang[0], 'sw') !== false) {
                $swahili += $lang[1];
            } else if (strpos($lang[0], 'pa') !== false) {
                $panjabi += $lang[1];
            } else {
                array_push($languages, [$lang[0], (int) $lang[1]]);
            }
        }

        array_push($languages, ['English', $english]);
        array_push($languages, ['Spanish', $spanish]);
        array_push($languages, ['Dutch', $dutch]);
        array_push($languages, ['French', $french]);
        array_push($languages, ['Russian', $russian]);
        array_push($languages, ['Portuguese', $portuguese]);
        array_push($languages, ['German', $german]);
        array_push($languages, ['Chinese', $chinese]);
        array_push($languages, ['Korean', $korean]);
        array_push($languages, ['Romanian', $romanian]);
        array_push($languages, ['Italian', $italian]);
        array_push($languages, ['Ukrainian', $ukrainian]);
        array_push($languages, ['Swedish', $swedish]);
        array_push($languages, ['Polish', $polish]);
        array_push($languages, ['Japanese', $japanese]);
        array_push($languages, ['Greek', $greek]);
        array_push($languages, ['Turkish', $turkish]);
        array_push($languages, ['Bulgarian', $bulgarian]);
        array_push($languages, ['Vietnamese', $vietnamese]);
        array_push($languages, ['Norwegian', $norwegian]);
        array_push($languages, ['Danish', $danish]);
        array_push($languages, ['Finnish', $finnish]);
        array_push($languages, ['Hungarian', $hungarian]);
        array_push($languages, ['Estonian', $estonian]);
        array_push($languages, ['Lithuanian', $lithuanian]);
        array_push($languages, ['Latvian', $latvian]);
        array_push($languages, ['Serbian', $serbian]);
        array_push($languages, ['Slovene', $slovene]);
        array_push($languages, ['Slovak', $slovak]);
        array_push($languages, ['Croatian', $croatian]);
        array_push($languages, ['Czech', $czech]);
        array_push($languages, ['Arabic', $arabic]);
        array_push($languages, ['Maltese', $maltese]);
        array_push($languages, ['Thai', $thai]);
        array_push($languages, ['Hebrew', $hebrew]);
        array_push($languages, ['Bosnian', $bosnian]);
        array_push($languages, ['Kazach', $kazakh]);
        array_push($languages, ['Indonesian', $indonesian]);
        array_push($languages, ['Afrikaans', $afrikaans]);
        array_push($languages, ['Malay', $malay]);
        array_push($languages, ['Tonga', $tonga]);
        array_push($languages, ['Georgian', $georgian]);
        array_push($languages, ['Tagalog', $tagalog]);
        array_push($languages, ['Icelandic', $icelandic]);
        array_push($languages, ['Mongolian', $mongolian]);
        array_push($languages, ['Bihari', $bihari]);
        array_push($languages, ['Akan', $akan]);
        array_push($languages, ['Persian', $persian]);
        array_push($languages, ['Maori', $maori]);
        array_push($languages, ['Belarusian', $belarusian]);
        array_push($languages, ['Gujarati', $gujarati]);
        array_push($languages, ['Hindi', $hindi]);
        array_push($languages, ['Luxembourgish', $luxembourgish]);
        array_push($languages, ['Tamil', $tamil]);
        array_push($languages, ['Uzbek', $uzbek]);
        array_push($languages, ['Swahili', $swahili]);
        array_push($languages, ['Panjabi', $panjabi]);

        return $languages;
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getDeviceCategory(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:deviceCategory'
            ]
        );
    }

    /**
     * @param GaParameters $gaParameters
     * @return mixed
     */
    public function getBrowsers(GaParameters $gaParameters)
    {
        return $gaParameters->getAnalytics()->data_ga->get(
            $gaParameters->getViewId(),
            $gaParameters->getStart(),
            $gaParameters->getEnd(),
            $gaParameters->getMetrics(),
            [
                'dimensions' => 'ga:browser',
                'sort' => '-ga:sessions, -ga:browser',
                'max-results' => 5
            ]
        );
    }
}