<?php

use App\Controller\BookSearchController;
use App\Exception\ApiCommunicationException;
use App\Service\BookApiService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class BookSearchControllerTest extends TestCase
{
    private $bookApiService;
    private $parameterBag;
    private $bookSearchController;

    protected function setUp(): void
    {
        $this->bookApiService = $this->createMock(BookApiService::class);
        $this->parameterBag = $this->createMock(ParameterBagInterface::class);
        $this->bookSearchController = new BookSearchController($this->bookApiService, $this->parameterBag);
    }

    public function testSearch(): void
    {
        $booksData = [
            // Mocked books data
        ];

        // Mock the BookApiService response
        $this->bookApiService->expects($this->once())
            ->method('fetchBooksData')
            ->willReturn($booksData);

        // Mock the parameter bag to return the mocked API URL
        $this->parameterBag->expects($this->once())
            ->method('get')
            ->with('api_url')
            ->willReturn('mocked_api_url');

        // Mock the request object
        $request = $this->createMock(Request::class);
        $request->query = new ParameterBag([
            'price' => 40,
        ]);

        // Set up the expected filtered books
        $expectedFilteredBooks = [
            // Expected filtered books based on the provided query parameters
        ];

        // Call the search method
        $response = $this->bookSearchController->search($request);

        // Assert that the response is a JSON response
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response content matches the expected filtered books
        $this->assertEquals($expectedFilteredBooks, json_decode($response->getContent(), true));
    }

    public function testSearchWithApiCommunicationException(): void
    {
        $this->expectException(ApiCommunicationException::class);

        // Mock the BookApiService to throw an ApiCommunicationException
        $this->bookApiService->expects($this->once())
            ->method('fetchBooksData')
            ->willThrowException(new ApiCommunicationException('API communication error'));

        // Mock the parameter bag to return the mocked API URL
        $this->parameterBag->expects($this->once())
            ->method('get')
            ->with('api_url')
            ->willReturn('mocked_api_url');

        // Mock the request object
        $request = $this->createMock(Request::class);

        // Call the search method (which should throw an ApiCommunicationException)
        $this->bookSearchController->search($request);
    }
}
