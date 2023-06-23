
# Backend - Case Study

This is a backend application implemented using PHP and Symfony. It provides a REST API for searching books based on different criteria.

## Requirements

- PHP
- Composer

## Installation

### Docker Installation
1. Make sure you have Docker installed on your system.
2. Clone the repository to your local machine.
3. Open a terminal or command prompt and navigate to the project directory.
4. Build the Docker image and start the container by running the following command:
```
docker-compose up --build
```
5. The application will be running inside the Docker container. You can access the APIs at `http://localhost:8000/book/search`.


### Normal Installation
1. Make sure you have PHP and Composer installed on your system.
2. Clone the repository to your local machine.
3. Open a terminal or command prompt and navigate to the project directory.
4. Install the project dependencies by running the following command:
   ```
   composer install
   ```

## Configuration

1. Copy the `.env` file to `.env.local`:
   ```
   cp .env .env.local
   ```

2. Modify the `.env.local` file to configure the environment variables according to your needs. You may need to set the database connection, API URLs, and other parameters.

## Usage

Start the Symfony development server by running the following command:
   ```
   symfony server:start
   ```

## API Documentation

The application exposes the following API endpoint:

- `GET /book/search`: Retrieves one or more book records based on the passed search criteria.

Query Parameters:
- `title`: Filter books by title.
- `isbn`: Filter books by isbn.
- `price`: Filter books by price.
- `category`: Filter books by category.
- `date`: Filter books by publication date.

Examples:
- `/book/search?price=40`: Returns books with a price of 40.
- `/book/search?category=Java`: Returns books in the Java category.
- `/book/search?date=2009-04-01&price=40`: Returns books published on April 1, 2009, with a price of 40.


## Testing

1. To run the unit tests, open a terminal or command prompt and navigate to the project directory.

2. Run the following command:
   ```
   ./bin/phpunit
   ```

   This will execute the unit tests and display the test results.

## License

This project is licensed under the [MIT License](LICENSE).
```

Feel free to customize the README file further based on your project's specific requirements and additional information you want to include.