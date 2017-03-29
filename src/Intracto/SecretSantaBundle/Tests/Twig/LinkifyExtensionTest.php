<?php

namespace Intracto\SecretSantaBundle\Tests\Twig;

use Intracto\SecretSantaBundle\Twig\LinkifyExtension;
use PHPUnit\Framework\TestCase;

class LinkifyExtensionTest extends TestCase
{
    /**
     * @test
     * @dataProvider htmlProvider
     */
    public function it_should_wrap_an_html_link_in_an_anchor_tag($rawHtml, $anchorHtml)
    {
        $filter = new LinkifyExtension();

        $this->assertEquals($anchorHtml, $filter->linkifyFilter($rawHtml));
    }

    public function htmlProvider()
    {
        return [
            [
                'test',
                'test',
            ],
            [
                'test.com',
                'test.com',
            ],
            [
                'ww.test.com',
                'ww.test.com',
            ],
            [
                'www.test.com',
                '<a href="http://www.test.com" target="_blank" rel="noopener noreferrer">www.test.com</a>',
            ],
            [
                'http://test',
                '<a href="http://test" target="_blank" rel="noopener noreferrer">http://test</a>',
            ],
            [
                'test http://test test',
                'test <a href="http://test" target="_blank" rel="noopener noreferrer">http://test</a> test',
            ],
            [
                'test http://www.google.com/foo test http://google.com/bar',
                'test <a href="http://www.google.com/foo" target="_blank" rel="noopener noreferrer">http://www.google.com/foo</a> test <a href="http://google.com/bar" target="_blank" rel="noopener noreferrer">http://google.com/bar</a>',
            ],
            [
                'a http://www.costumecraze.com/XMAS166.html b',
                'a <a href="http://www.costumecraze.com/XMAS166.html" target="_blank" rel="noopener noreferrer">http://www.costumecraze.com/XMAS166.html</a> b',
            ],
            [
                'https://www.amazon.com/Amazon-1-US-Email-eGift-Card/keywords=gift+card.',
                '<a href="https://www.amazon.com/Amazon-1-US-Email-eGift-Card/keywords=gift+card" target="_blank" rel="noopener noreferrer">https://www.amazon.com/Amazon-1-US-Email-eGift-Card/keywords=gift+card</a>.'
            ],
            [
                'https://www.amazon.com/Amazon-1-US-Email-eGift-Card/dp/B004LLIKVU/ref=sr_1_1?s=gift-cards&ie=UTF8&qid=1490789563&?=!',
                '<a href="https://www.amazon.com/Amazon-1-US-Email-eGift-Card/dp/B004LLIKVU/ref=sr_1_1?s=gift-cards&ie=UTF8&qid=1490789563&?=!" target="_blank" rel="noopener noreferrer">https://www.amazon.com/Amazon-1-US-Email-eGift-Card/dp/B004LLIKVU/ref=sr_1_1?s=gift-cards&ie=UTF8&qid=1490789563&?=!</a>'
            ],
            [
                'a http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound b',
                'a <a href="http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound" target="_blank" rel="noopener noreferrer">http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound</a> b',
            ],
            [
                '<p><a href="http://www.paddypallin.com.au/osprey-quantum-daypack.html">http://www.paddypallin.com.au/osprey-quantum-daypack.html</a></p>
<p><a href="http://www.wildearth.com.au/buy/black-wolf-meridian-30l-adventure-daypack-chilli">http://www.wildearth.com.au/buy/black-wolf-meridian-30l-adventure-daypack-chilli</a></p>
<p><a href="http://www.drjays.com/shop/P1604911/the-north-face/recon-backpack.html">http://www.drjays.com/shop/P1604911/the-north-face/recon-backpack.html</a></p>
<p><a href="http://www.drjays.com/shop/P1604613/the-north-face/borealis-backpack.html">http://www.drjays.com/shop/P1604613/the-north-face/borealis-backpack.html</a></p>
<p>* These are in no order....but I would like a travel daypack/short trip backpac...with&nbsp;3 compartments...water bottle carrier....not too large...&nbsp;</p>
<p>&nbsp;</p>',
                '<p><a href="http://www.paddypallin.com.au/osprey-quantum-daypack.html">http://www.paddypallin.com.au/osprey-quantum-daypack.html</a></p>
<p><a href="http://www.wildearth.com.au/buy/black-wolf-meridian-30l-adventure-daypack-chilli">http://www.wildearth.com.au/buy/black-wolf-meridian-30l-adventure-daypack-chilli</a></p>
<p><a href="http://www.drjays.com/shop/P1604911/the-north-face/recon-backpack.html">http://www.drjays.com/shop/P1604911/the-north-face/recon-backpack.html</a></p>
<p><a href="http://www.drjays.com/shop/P1604613/the-north-face/borealis-backpack.html">http://www.drjays.com/shop/P1604613/the-north-face/borealis-backpack.html</a></p>
<p>* These are in no order....but I would like a travel daypack/short trip backpac...with&nbsp;3 compartments...water bottle carrier....not too large...&nbsp;</p>
<p>&nbsp;</p>',
            ],
        ];
    }
}
