<?php

namespace App\Tests\Service;

use App\Service\BookApiService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BookApiServiceTest extends KernelTestCase
{
    public function testFetchBooksData()
    {
        $booksData = [
            [
                'id' => 1,
                'title' => 'Book 1',
                'author' => 'Author 1',
                'price' => 40,
                'published' => [
                    '$date' => '2022-01-01T00:00:00.000+00:00',
                    'currency' => 'USD',
                ],
                'categories' => ['Category 1', 'Category 2'],
            ],
            [
                'id' => 2,
                'title' => 'Book 2',
                'author' => 'Author 2',
                'price' => 30,
                'published' => [
                    '$date' => '2021-01-01T00:00:00.000+00:00',
                    'currency' => 'USD',
                ],
                'categories' => ['Category 2', 'Category 3'],
            ],
        ];

        // Create a MockHttpClient with a mock response containing the mocked books data
        $httpClient = new MockHttpClient([
            new MockResponse(json_encode($booksData), ['http_code' => 200]),
        ]);

        $apiUrl = 'https://run.mocky.io/v3/d7f02fdc-5591-4080-a163-95a08ce6895e';

        // Create an instance of BookApiService with the mock HTTP client and API URL
        $bookApiService = new BookApiService($httpClient, $apiUrl);

        // Fetch the books data using the service
        $fetchedData = $bookApiService->fetchBooksData();

        // Assert that the fetched data matches the expected mocked books data
        $this->assertSame($booksData, $fetchedData);
    }

    public function testFetchBooksDataWithHttpError()
    {
        // Create a MockHttpClient with a mock response containing an HTTP error (404)
        $httpClient = new MockHttpClient([
            new MockResponse('', ['http_code' => 404]),
        ]);

        $apiUrl = 'https://run.mocky.io/v3/d7f02fdc-5591-4080-a163-95a08ce6895e';

        $bookApiService = new BookApiService($httpClient, $apiUrl);

        // Expect an exception of type \Exception to be thrown when fetching the books data
        $this->expectException(\Exception::class);
        $bookApiService->fetchBooksData();
    }

    public function testFetchBooksDataWithEmptyResponse()
    {
        // Create a MockHttpClient with a mock response containing an empty array as the response body
        $httpClient = new MockHttpClient([
            new MockResponse('[]', ['http_code' => 200]),
        ]);

        $apiUrl = 'https://run.mocky.io/v3/d7f02fdc-5591-4080-a163-95a08ce6895e';

        $bookApiService = new BookApiService($httpClient, $apiUrl);

        // Fetch the books data using the service
        $fetchedData = $bookApiService->fetchBooksData();

        // Assert that the fetched data is an empty array
        $this->assertSame([], $fetchedData);
    }

    public function testFetchBooksDataWithInvalidJsonResponse()
    {
        // Create a MockHttpClient with a mock response containing an invalid JSON response body
        $httpClient = new MockHttpClient([
            new MockResponse('invalid_json', ['http_code' => 200]),
        ]);

        $apiUrl = 'https://run.mocky.io/v3/d7f02fdc-5591-4080-a163-95a08ce6895e';

        $bookApiService = new BookApiService($httpClient, $apiUrl);

        // Expect an exception of type \Exception to be thrown when fetching the books data
        $this->expectException(\Exception::class);
        $bookApiService->fetchBooksData();
    }

    public function testFetchBooksDataWithNetworkError()
    {
        // Create a mock HttpClientInterface using createMock and configure it to throw an exception
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('request')->willThrowException(new \Exception('Network error'));

        $apiUrl = 'https://run.mocky.io/v3/d7f02fdc-5591-4080-a163-95a08ce6895e';

        $bookApiService = new BookApiService($httpClient, $apiUrl);

        // Expect an exception of type \Exception to be thrown when fetching the books data
        $this->expectException(\Exception::class);
        $bookApiService->fetchBooksData();
    }
}
