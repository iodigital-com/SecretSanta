<?php

declare(strict_types=1);

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * This is just a demo test.
 */
final class FirstApiTest extends KernelTestCase
{
    private HttpClientInterface $httpClient;
    private string $host;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->httpClient = self::getContainer()->get(HttpClientInterface::class);
        $this->host = $_ENV['HOST'];
    }

    /** @test */
    public function itReturns404ForNonExistingEndpoint(): void
    {
        $this->markTestIncomplete('Test fails because host will not resolve.');

        $response = $this->httpClient->request(
            'GET',
            "https://{$this->host}/api/non-existing-endpoint",
            ['verify_peer' => false]
        );
        $this->assertEquals(404, $response->getStatusCode());
    }
}
