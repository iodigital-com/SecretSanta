<?php

namespace Intracto\SecretSantaBundle\Tests\Twig;

use Intracto\SecretSantaBundle\Twig\LinkifyExtension;

class LinkifyExtensionTest extends \PHPUnit_Framework_TestCase
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
        return array(
            array(
                'test',
                'test',
            ),
            array(
                'http://test',
                '<a href="http://test" target="_blank" rel="noopener noreferrer">http://test</a>',
            ),
            array(
                'test http://test test',
                'test <a href="http://test" target="_blank" rel="noopener noreferrer">http://test</a> test',
            ),
            array(
                'test http://www.google.com/foo test http://google.com/bar',
                'test <a href="http://www.google.com/foo" target="_blank" rel="noopener noreferrer">http://www.google.com/foo</a> test <a href="http://google.com/bar" target="_blank" rel="noopener noreferrer">http://google.com/bar</a>',
            ),
            array(
                'a http://www.costumecraze.com/XMAS166.html b',
                'a <a href="http://www.costumecraze.com/XMAS166.html" target="_blank" rel="noopener noreferrer">http://www.costumecraze.com/XMAS166.html</a> b',
            ),
            array(
                'a http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound b',
                'a <a href="http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound" target="_blank" rel="noopener noreferrer">http://www.amazon.co.uk/Love-Curses-VINYL-Reigning-Sound/dp/B002D6EXRK/ref=sr_1_9?s=music&ie=UTF8&qid=1384346660&sr=1-9&keywords=reigning+sound</a> b',
            ),
            array(
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
            ),
        );
    }
}
