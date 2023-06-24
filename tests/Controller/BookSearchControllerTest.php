<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Response;

class BookSearchControllerTest extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Load environment variables
        $dotenv = new Dotenv();
        $dotenv->loadEnv('.env');
    }

    public function testSearchByPrice(): void
    {
        $client = static::createClient();

        // Send a GET request to '/book/search' with the 'price' query parameter set to '40'
        $client->request('GET', '/book/search', ['price' => '40']);
        $response = $client->getResponse();

        // Assert that the response status code is HTTP_OK (200)
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        // Decode the response content to an array
        $data = json_decode($response->getContent(), true);

        // Assert that the response content is an array
        $this->assertIsArray($data);

        // Assert that the response content array has a count of 17
        $this->assertCount(17, $data);
    }

    public function testSearchByCategory(): void
    {
        $client = static::createClient();

        $client->request('GET', '/book/search', ['category' => 'Java']);
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(29, $data);
    }

    public function testSearchByDate(): void
    {
        $client = static::createClient();

        $client->request('GET', '/book/search', ['date' => '2009-04-01']);
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(2, $data);
    }

    public function testSearchByMultipleCriteria(): void
    {
        $client = static::createClient();

        $client->request('GET', '/book/search', ['date' => '2009-04-01', 'price' => '40']);
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(2, $data);
    }

    public function testSearchWithEmptyResult(): void
    {
        $client = static::createClient();

        $client->request('GET', '/book/search', ['category' => 'Nonexistent']);
        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());

        $data = json_decode($response->getContent(), true);
        $this->assertIsArray($data);
        $this->assertCount(0, $data);
    }
}
