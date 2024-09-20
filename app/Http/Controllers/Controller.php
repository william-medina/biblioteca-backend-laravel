<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="BIBLIOTECA API",
 *     version="1.0.0",
 *     description="API documentation for the Biblioteca APP"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000/api",
 *     description="Development server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     description="Please provide your token in the following format: Bearer <token>."
 * )
 * 
 * @OA\Tag(
 *     name="Auth",
 *     description="Endpoints for user authentication"
 * )
 * 
 * @OA\Post(
 *     path="/auth/login",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="william@example.com"),
 *             @OA\Property(property="password", type="string", example="Password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Token returned",
 *         @OA\MediaType(
 *             mediaType="text/plain",
 *             @OA\Schema(
 *                 type="string",
 *                 example="tokenHere"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="El Email o password son incorrectos")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/auth/me",
 *     summary="Get the current user's details",
 *     tags={"Auth"},
 *     security={{"BearerAuth": {}}},
 *     @OA\Response(
 *         response=200,
 *         description="Returns user details",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", description="The user ID"),
 *             @OA\Property(property="email", type="string", description="The email address of the user")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized, invalid credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="unauthorized.")
 *         )
 *     )
 * )
 * 
 * @OA\Tag(
 *     name="Books",
 *     description="Endpoints for managing books in the library."
 * )
 * 
 * @OA\Get(
 *     path="/books/count",
 *     summary="Get the total count of books",
 *     tags={"Books"},
 *     @OA\Response(
 *         response=200,
 *         description="Returns the total count of books",
 *         @OA\JsonContent(
 *             @OA\Property(property="count", type="integer", example=150)
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to retrieve book count",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to retrieve book count")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/books/random/{count}",
 *     summary="Get a random set of books",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="count",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a random set of books",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to retrieve books",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to retrieve books")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/books/location",
 *     summary="Get books by location",
 *     tags={"Books"},
 *     @OA\Response(
 *         response=200,
 *         description="Returns books by location",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Location"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to retrieve location books",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to retrieve location books")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/books/{sortBy}",
 *     summary="Get all books sorted by a specific field",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="sortBy",
 *         in="path",
 *         required=true,
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns all books sorted by the specified field",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to retrieve books",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to retrieve books")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/books/search/{keyword}",
 *     summary="Search books by keyword",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="keyword",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns a list of books that match the keyword",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to search books",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to search books")
 *         )
 *     )
 * )
 * 
 * @OA\Get(
 *     path="/books/isbn/{isbn}",
 *     summary="Get a book by ISBN",
 *     tags={"Books"},
 *     @OA\Parameter(
 *         name="isbn",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the book details",
 *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Book"))
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Libro no encontrado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to retrieve book",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to retrieve book.")
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/books",
 *     summary="Add a new book",
 *     tags={"Books"},
 *     security={{"BearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"isbn", "title"},
 *                 @OA\Property(property="isbn", type="string", example="9780307474728"),
 *                 @OA\Property(property="title", type="string", example="Cien años de soledad"),
 *                 @OA\Property(property="author", type="string", example="Gabriel García Márquez"),
 *                 @OA\Property(property="publisher", type="string", example="Editorial Sudamericana"),
 *                 @OA\Property(property="publication_year", type="string", example="1967"),
 *                 @OA\Property(property="location", type="string", example="A-V10"),
 *                 @OA\Property(
 *                     property="cover",
 *                     type="string",
 *                     format="binary",
 *                     description="Cover image file"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Book added successfully",
 *         @OA\MediaType(
 *             mediaType="text/plain",
 *             @OA\Schema(
 *                 type="string",
 *                 example="Libro almacenado correctamente"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid input or duplicate data",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="El libro con ese ISBN ya existe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to add book",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to add book")
 *         )
 *     )
 * )
 * 
 * @OA\Post(
 *     path="/books/{isbn}",
 *     summary="[ PUT ] - Update a book by ISBN using POST (PUT override)",
 *     description="This endpoint allows updating a book using a POST request with a PUT method override. This is useful in environments where PUT requests are not allowed.",
 *     tags={"Books"},
 *     security={{"BearerAuth": {}}},
 *     @OA\Parameter(
 *         name="isbn",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"isbn", "title"},
*                  @OA\Property(
*                    property="_method",
*                    type="string",
*                    description="Method override for PUT request",
*                    enum={"PUT"},
*                    example="PUT"
*                  ),
 *                 @OA\Property(property="isbn", type="string", example="9780307474728"),
 *                 @OA\Property(property="title", type="string", example="Cien años de soledad"),
 *                 @OA\Property(property="author", type="string", example="Gabriel García Márquez"),
 *                 @OA\Property(property="publisher", type="string", example="Editorial Sudamericana"),
 *                 @OA\Property(property="publication_year", type="string", example="1967"),
 *                 @OA\Property(property="location", type="string", example="A-V10"),
 *                 @OA\Property(
 *                     property="cover",
 *                     type="string",
 *                     format="binary",
 *                     description="Cover image file"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Book updated successfully",
 *         @OA\MediaType(
 *             mediaType="text/plain",
 *             @OA\Schema(
 *                 type="string",
 *                 example="Libro actualizado correctamente"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Libro no encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid input or duplicate data",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="El libro con ese ISBN ya existe")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to update book",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to update book")
 *         )
 *     )
 * )
 * 
 * @OA\Delete(
 *     path="/books/{isbn}",
 *     summary="Delete a book by ISBN",
 *     tags={"Books"},
 *     security={{"BearerAuth": {}}},
 *     @OA\Parameter(
 *         name="isbn",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Book deleted successfully",
 *         @OA\MediaType(
 *             mediaType="text/plain",
 *             @OA\Schema(
 *                 type="string",
 *                 example="Libro eliminado correctamente"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Book not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Libro no encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Failed to delete book",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Failed to delete book")
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *    schema="Book",
 *    type="object",
 *    description="Book schema",
 *    @OA\Property(property="id", type="integer", example=207, description="The book ID"),
 *    @OA\Property(property="isbn", type="integer", example=9505043651, description="The book ISBN"),
 *    @OA\Property(property="title", type="string", example="APICULTURA PRÁCTICA", description="The title of the book"),
 *    @OA\Property(property="author", type="string", example="ALDO L. PERSANO", description="The author of the book"),
 *    @OA\Property(property="publisher", type="string", example="HEMISFERIO SUR", description="The publisher of the book"),
 *    @OA\Property(property="publication_year", type="string", example="1992", description="The year the book was published"),
 *    @OA\Property(property="location", type="string", example="E-G06", description="The location of the book in the library")
 * )
 * 
 * @OA\Schema(
 *   schema="Location",
 *   type="object",
 *   description="Shelf and book location schema",
 *   @OA\Property(
 *     property="shelf",
 *     type="string",
 *     example="E",
 *     description="Shelf identifier"
 *   ),
 *   @OA\Property(
 *     property="sections",
 *     type="array",
 *     @OA\Items(
 *       type="object",
 *       @OA\Property(
 *         property="section",
 *         type="string",
 *         example="G",
 *         description="Section identifier"
 *       ),
 *       @OA\Property(
 *         property="books",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Book")
 *       )
 *     )
 *   )
 * )
 * 
 */


abstract class Controller
{
    //
}
