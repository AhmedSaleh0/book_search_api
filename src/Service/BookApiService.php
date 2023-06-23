<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Exception\ApiCommunicationException;
use App\Exception\Throwable;

class BookApiService
{
    private $httpClient;
    private $apiUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiUrl = $apiUrl;
    }

    public function fetchBooksData(): array
    {
        try {
            $response = $this->httpClient->request('GET', $this->apiUrl);
            if ($response->getStatusCode() !== 200) {
                throw new ApiCommunicationException('Failed to fetch books data from the API.');
            }

            return $response->toArray();
        } catch (Throwable $e) {
            throw new ApiCommunicationException('An error occurred during API communication.', 0, $e);
        }
    }
}
