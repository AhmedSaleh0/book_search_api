<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\BookApiService;

class BookSearchController extends AbstractController
{
    private $bookApiService;

    public function __construct(BookApiService $bookApiService)
    {
        $this->bookApiService = $bookApiService;
    }


    /**
     * @Route("/book/search", name="book_search", methods={"GET"})
     */
    public function search(Request $request): JsonResponse
    {
        // Retrieve JSON data from the API
        $booksData = $this->bookApiService->fetchBooksData();


        // Filter books based on the search criteria
        $filteredBooks = $this->filterBooks($booksData, $request->query->all());

        return $this->json($filteredBooks);
    }

    /**
     * Filters books based on the given criteria.
     *
     * @param array $booksData
     * @param array $criteria
     * @return array
     */
    protected function filterBooks(array $booksData, array $criteria): array
    {
        return array_filter($booksData, function ($bookData) use ($criteria) {
            return $this->matchesCriteria($bookData, $criteria);
        });
    }

    /**
     * Checks if a book matches the given criteria.
     *
     * @param array $bookData
     * @param array $criteria
     * @return bool
     */
    protected function matchesCriteria(array $bookData, array $criteria): bool
    {
        foreach ($criteria as $field => $value) {
            switch ($field) {
                case 'date':
                    // Extract the date value from the book data
                    $bookValue = $bookData['published']['$date'];

                    // Split the date and time, considering only the date part
                    $bookDate = explode('T', $bookValue);

                    // Count the number of dashes in the value to determine the format
                    $countDate = substr_count($value, '-');

                    // Choose the format based on the number of dashes
                    $format = ($countDate > 0) ? 'Y-m-d' : 'Y';

                    // Format the book date to match the criteria format
                    $bookDateFormatted = (new \DateTime($bookDate[0]))->format($format);

                    // Check if the book date matches the criteria value
                    if ($bookDateFormatted !== $value) {
                        return false;
                    }
                    break;
                case 'price':
                    // Retrieve the book's price value
                    $bookValue = (int)$bookData['published'][$field];

                    // Convert the criteria value to integer
                    $value = (int)$value;

                    // Check if the book price matches the criteria value
                    if ($bookValue !== $value) {
                        return false;
                    }
                    break;
                case 'currency':
                    // Retrieve the book's currency value
                    $bookValue = $bookData['published'][$field];

                    // Check if the book currency matches the criteria value
                    if ($bookValue !== $value) {
                        return false;
                    }
                    break;
                case 'category':
                    // Retrieve the book's categories
                    $bookValue = $bookData['categories'] ?? [];

                    // Check if the book has the specified category
                    if (!in_array($value, $bookValue)) {
                        return false;
                    }
                    break;
                default:
                    // For other fields, check if the book data matches the criteria value
                    if (!isset($bookData[$field]) || $bookData[$field] !== $value) {
                        return false;
                    }
                    break;
            }
        }

        return true;
    }
}
