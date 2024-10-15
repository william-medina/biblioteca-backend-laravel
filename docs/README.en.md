# BIBLIOTECA Backend
![Framework](https://img.shields.io/badge/Framework-Laravel%2011-red)
![Version](https://img.shields.io/badge/version-1.0.0-green)
![Database](https://img.shields.io/badge/Database-MySQL-orange)
![Language](https://img.shields.io/badge/Language-PHP%208-blue)
![API Documentation](https://img.shields.io/badge/API%20Docs-Swagger-green)


The backend of **BIBLIOTECA** is a RESTful API management system designed for a personal library. It provides key functionalities for user authentication and book management, including secure login, collection management, and cover image uploads. Built with Laravel and MySQL, this system facilitates the organization and control of books in a private library.

## Features

The backend offers the following key functionalities:

### 1. **User Authentication**
   - **Login:** Handles user login, verifying credentials and creating user sessions.
   - **Retrieve User Data:** Provides access to the logged-in user's details.

### 2. **Book Management**
   - **Add New Books:** Allows the addition of new books to the library's collection.
   - **Update Book Information:** Enables updates to details of existing books.
   - **Delete Books:** Supports the removal of books from the library's collection.
   - **Get All Books:** Retrieves a list of all books in the library, optionally sorted by a specified field (e.g., title, author, publisher).
   - **Get Total Book Count:** Provides the total number of books in the collection.
   - **Get Random Books:** Retrieves a random selection of books based on a specified count.
   - **Get Books by Location:** Retrieves books organized by their location within the library.
   - **Search Books:** Searches for books using keywords in titles, authors, publishers, publication year, and ISBN.
   - **Get Book by ISBN:** Retrieves detailed information for a specific book using its ISBN.

### 3. **Book Cover Upload**
   - **File Upload:** The application supports file uploads for book covers using Laravel's file system. This allows users to upload images with the following constraints:
     - **File Type:** Only `.jpg` images are allowed.
     - **File Size Limit:** Files must be 5MB or smaller.
   - **How It Works:**
     - **Configuration:** The file system is configured to use public storage for permanent file handling.
     - **File Validation:** Only `.jpg` files with the MIME type `image/jpeg` are accepted.
     - **Error Handling:** If an uploaded file exceeds the size limit or has an invalid type, the application responds with a specific error message.
     - **Field Name:** The field name for the uploaded cover image in the form must be `cover`.

## Technologies

The **BIBLIOTECA** backend is built using the following modern technologies:

- **Laravel**: A powerful PHP framework for building web applications, providing an elegant syntax and robust features.
- **MySQL**: A relational database used for storing and managing book and user data.
- **Eloquent ORM**: Laravel's built-in Object-Relational Mapping (ORM) tool for database interactions.
- **bcrypt**: A library for password hashing, ensuring secure user authentication.
- **Laravel Sanctum**: Provides API authentication using personal access tokens and simple session-based authentication.
- **CORS**: Middleware to enable Cross-Origin Resource Sharing (CORS) for communication between frontend and backend.
- **dotenv**: Loads environment variables from a `.env` file to manage configuration settings effectively.
- **L5-Swagger**: A package for automatically generating interactive API documentation using Swagger in Laravel.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- [PHP](https://www.php.net/downloads.php) (Recommended: Latest version compatible with Laravel)
- [Composer](https://getcomposer.org/download/) (PHP package manager)
- [MySQL](https://dev.mysql.com/downloads/installer/) (Installed and running locally or using a cloud instance)

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/william-medina/biblioteca-backend-laravel.git
    ```

2. Navigate to the project directory:

    ```bash
    cd biblioteca-backend-laravel
    ```

3. **Install dependencies:**

    ```bash
    composer install
    ```

4. **Create a `.env` file:**

    Create a `.env` file in the root of the project directory and fill it with the necessary environment variables. Here is a template you can use:

    ```dotenv
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

    ALLOWED_ORIGINS=http://localhost:5173
    ALLOW_ALL_ORIGINS=true

    ```
    ### Explanation of Additional Variables:

    - **ALLOWED_ORIGINS**: This variable specifies which origins are allowed to interact with the backend of your application. For example, in this case, `http://localhost:5173` is allowed to make requests to the backend. You can add multiple origins by separating them with commas if you want to enable access from several different domains.

    - **ALLOW_ALL_ORIGINS**: Setting this variable to `true` allows all origins to access the backend, essentially permitting requests from any domain. This is useful during development but should be handled carefully in production to prevent security issues.

    Replace the placeholder values with your actual configuration details.

5. **Run database migrations:**

    ```bash
    php artisan migrate
    ```

6. **Start the Laravel server:**

    ```bash
    php artisan serve
    ```

## File Upload Configuration

To upload book cover images:

1. Ensure your storage is linked to the public directory:
   
    ```bash
    php artisan storage:link
    ```

2. Images will be stored in `storage/app/public/covers`, and you can access them using the following routes:

    - `http://localhost:8000/api/covers/cover.jpg`


## Architecture

The backend for **BIBLIOTECA** follows the **Model-View-Controller (MVC)** architecture:

### 1. **Model**

- **Location:** `app/Models`
- **Responsibilities:** Defines the data structure for the application (e.g., Books, Users), handles database interactions using Eloquent ORM, and implements business logic related to data.

### 2. **View**

- **Location:** Not directly applicable; Laravel APIs typically return JSON responses, which serve as the "view."
- **Responsibilities:** Provides formatted JSON responses for API requests, which are consumed by the frontend or other services.

### 3. **Controller**

- **Location:** `app/Http/Controllers`
- **Responsibilities:** Processes incoming requests, communicates with models to handle data, and returns responses to the client (usually in JSON format).

## API Documentation

The Swagger API documentation for **BIBLIOTECA** is available at [Swagger UI](http://localhost:8000/api/docs).

**Important:** To access the Swagger documentation, the environment variable `ALLOW_ALL_ORIGINS=true` must be set. This allows unrestricted access to the backend during development, making it possible to view the documentation without CORS issues.

### Custom Domain/Port:

If your server is running on a different domain or port than `localhost:8000`, you will need to update the Swagger UI URL accordingly. For example:

- If your server is running on `http://192.168.1.100:8000`, you should access the documentation at `http://192.168.1.100:8000/api/docs`.
- If your server is running on `http://mydomain.com:5000`, the documentation will be accessible at `http://mydomain.com:5000/api/docs`.

### How to Access:

1. Ensure the environment variable `ALLOW_ALL_ORIGINS=true` is set in your `.env` file.
2. Determine the domain and port where your Laravel server is running.
3. Replace `localhost:8000` in the Swagger UI URL with your server's domain and port.
4. Open your browser and navigate to the updated URL to view the API documentation.

Be cautious when using `ALLOW_ALL_ORIGINS=true` in production environments to avoid potential security risks.

## API Endpoints

### Book Routes

| **Endpoint**                   | **Method** | **Description**                                                  |
|--------------------------------|------------|------------------------------------------------------------------|
| `/api/books/count`            | `GET`      | Retrieves the total count of books in the library.              |
| `/api/books/random/{count}`   | `GET`      | Retrieves a random set of books based on the specified count.    |
| `/api/books/location`          | `GET`      | Retrieves books organized by their location in the library.      |
| `/api/books/{sortBy}`         | `GET`      | Retrieves all books sorted by a specific field (e.g., title, author, publisher). |
| `/api/books/search/{keyword}`  | `GET`      | Searches for books by a keyword in titles, authors, publishers, publication year, and ISBN. |
| `/api/books/isbn/{isbn}`      | `GET`      | Retrieves detailed information for a specific book by its ISBN.  |
| `/api/books`                  | `POST`     | Adds a new book to the library.                                  |
| `/api/books/{isbn}`           | `PUT`      | Updates the information of a specific book by its ISBN.         |
| `/api/books/{isbn}`           | `DELETE`   | Deletes a specific book from the library.                       |

### Auth Routes

| **Endpoint**                   | **Method** | **Description**                                                  |
|--------------------------------|------------|------------------------------------------------------------------|
| `/api/auth/login`             | `POST`     | Authenticates a user and returns a JWT token.                   |
| `/api/auth/me`                | `GET`      | Retrieves the details of the currently authenticated user.       |


### Middleware

**Laravel Sanctum** provides a simple token-based authentication system for APIs. Ensure that you include Sanctum middleware in your routes or controllers that require authentication to secure access to your API.

The **CorsMiddleware** is responsible for handling Cross-Origin Resource Sharing (CORS) requests. It reads the configuration from the environment variables:

- **ALLOW_ALL_ORIGINS**: Set this to `true` to permit requests from any origin during development.
- **ALLOWED_ORIGINS**: This variable should contain a comma-separated list of allowed origins that can make requests to your API.




## Author

This backend application for **BIBLIOTECA** is developed and maintained by:

**William Medina**

Thank you for checking out **BIBLIOTECA**!
