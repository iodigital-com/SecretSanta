<?php

namespace App\Tests\Unit\Service;

use App\Service\UrlTransformerService;
use PHPUnit\Framework\TestCase;

class UrlTransformerTest extends TestCase
{
    // Simple party with simple excludes
    public function testUrlExtraction()
    {
        $urlTransformer = new UrlTransformerService();

        $urls = $urlTransformer->extractUrls('https://www.test.com');
        $this->assertEquals(1, count($urls));
        $this->assertEquals('https://www.test.com', $urls[0]);
    }

    public function testAmazonUrlExtraction()
    {
        $_ENV['PARTNER_AMAZON'] = 'abc-123';
        $urlTransformer = new UrlTransformerService();

        $url = $urlTransformer->transformUrl('https://www.amazon.com/Zmart-Funny-Christmas-Coworkers-Secret/dp/B0CC1S12S3');
        $this->assertEquals('https://www.amazon.com/Zmart-Funny-Christmas-Coworkers-Secret/dp/B0CC1S12S3?tag=abc-123', $url);

        $url = $urlTransformer->transformUrl('https://www.amazon.com/Zmart-Funny-Christmas-Coworkers-Secret/dp/B0CC1S12S3?crid=123456789');
        $this->assertEquals('https://www.amazon.com/Zmart-Funny-Christmas-Coworkers-Secret/dp/B0CC1S12S3?crid=123456789&tag=abc-123', $url);
    }

    public function testurlReplacement()
    {
        $urlTransformer = new UrlTransformerService();

        $html = 'Multiple links to https://www.tom.be, again https://www.tom.be and a child https://www.tom.be/zeb and a child https://www.tom.be/arne';
        $replacements = [
            'https://www.tom.be' => 'https://www.tom.be/zeb',
            'https://www.tom.be/zeb' => 'https://www.tom.be/Zeb',
            'https://www.tom.be/arne' => 'https://www.tom.be/Arne',
        ];
        // It should not replace the /zeb instances that are the result of the replacements of https://www.tom.be.
        // It should also not replace https://www.tom.be in any of the other child urls, but instead replace them with the capital names.
        $expectedHtml = 'Multiple links to https://www.tom.be/zeb, again https://www.tom.be/zeb and a child https://www.tom.be/Zeb and a child https://www.tom.be/Arne';

        $actual = $urlTransformer->replaceUrls($html, $replacements);
        $this->assertEquals($expectedHtml, $actual);
    }
}
