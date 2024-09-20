<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookCollection;


class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new BookCollection(Book::orderBy('title')->get());
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(AddBookRequest $request)
    {
        try{

            $data = $request->only(['title', 'author', 'publisher', 'publication_year', 'isbn', 'location']);

            // Aplicar trim a los campos
            $data = array_map('trim', $data);

            // Reemplazar valores vacíos con predeterminados
            $data['author'] = !empty($data['author']) ? $data['author'] : 'S.A';
            $data['publisher'] = !empty($data['publisher']) ? $data['publisher'] : 'S.E';
            $data['publication_year'] = !empty($data['publication_year']) ? $data['publication_year'] : 'S.F';
            $data['location'] = !empty($data['location']) ? $data['location'] : '---';

            // Convertir todo a mayúsculas
            $data['title'] = mb_strtoupper($data['title'], 'UTF-8');
            $data['author'] = mb_strtoupper($data['author'], 'UTF-8');
            $data['publisher'] = mb_strtoupper($data['publisher'], 'UTF-8');
            $data['publication_year'] = mb_strtoupper($data['publication_year'], 'UTF-8');
            $data['location'] = mb_strtoupper($data['location'], 'UTF-8');

            $book = Book::create($data);

            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $file->storeAs('covers', "{$book->isbn}.{$file->extension()}", 'public');
            }
            
            return 'Libro almacenado correctamente';
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add book'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, $isbn)
    {
        try{
            $data = $request->only(['title', 'author', 'publisher', 'publication_year', 'isbn', 'location']);
            
            // Aplicar trim a los campos
            $data = array_map('trim', $data);

            // Buscar el libro por su ISBN
            $book = Book::where('isbn', $isbn)->first();


            if (!$book) {
                return response()->json(['error' => 'Libro no encontrado'], 404);
            }

            // Reemplazar valores vacíos con predeterminados
            $data['author'] = !empty($data['author']) ? $data['author'] : 'S.A';
            $data['publisher'] = !empty($data['publisher']) ? $data['publisher'] : 'S.E';
            $data['publication_year'] = !empty($data['publication_year']) ? $data['publication_year'] : 'S.F';
            $data['location'] = !empty($data['location']) ? $data['location'] : '---';

            // Convertir todo a mayúsculas
            $data['title'] = mb_strtoupper($data['title'], 'UTF-8');
            $data['author'] = mb_strtoupper($data['author'], 'UTF-8');
            $data['publisher'] = mb_strtoupper($data['publisher'], 'UTF-8');
            $data['publication_year'] = mb_strtoupper($data['publication_year'], 'UTF-8');
            $data['location'] = mb_strtoupper($data['location'], 'UTF-8');

            // Actualizar el libro con los nuevos datos
            $book->update($data);

            // Actualizar la portada si se proporciona una nueva
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $file->storeAs('covers', "{$book->isbn}.{$file->extension()}", 'public');
            }

            return 'Libro actualizado correctamente';
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update book'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($isbn)
    {
        try {
            // Buscar el libro por su ISBN
            $book = Book::where('isbn', $isbn)->first();

            if (!$book) {
                return response()->json(['error' => 'Libro no encontrado'], 404);
            }

            // Eliminar la imagen de la portada si existe
            $coverPath = storage_path("app/public/covers/{$isbn}.jpg");
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }

            // Eliminar el libro de la base de datos
            $book->delete();

            return response()->json('Libro eliminado correctamente', 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete book'], 500);
        }
    }

    public function getBookCount()
    {
        try {
            $count = Book::count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve book count.'], 500);
        }
    }

    /**
     * Obtener una cantidad específica de libros de forma aleatoria.
     */
    public function getRandomBooks($count)
    {
        try {
            $numberOfBooks = intval($count);

            if ($numberOfBooks <= 0) {
                return response()->json(['error' => 'El valor de count debe ser un número positivo.'], 400);
            }

            // Obtener el total de libros
            $totalBooks = Book::count();

            if ($totalBooks === 0) {
                return response()->json(['error' => 'No hay libros disponibles.'], 404);
            }

            // Obtener los libros aleatorios
            $randomBooks = Book::inRandomOrder()->limit($numberOfBooks)->get();

            return response()->json($randomBooks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve books.'], 500);
        }
    }

    /**
     * Obtener todos los libros, opcionalmente ordenados por un campo específico.
     */
    public function getAllBooks($sortBy = 'title')
    {
        
        try {
            // Definir campos de ordenación válidos
            $validSortFields = ['title', 'author', 'publisher', 'publication_year', 'id'];
            $orderField = in_array($sortBy, $validSortFields) ? $sortBy : 'title';
            
             
            // Iniciar la consulta
            $query = Book::query();

            // Definir el orden basado en el campo
            if ($orderField === 'author') {
                // Ordenar por autor primero, luego por título
                $query->orderByRaw('CASE WHEN author LIKE "%S.A" THEN 1 ELSE 0 END')
                    ->orderBy('author', 'ASC')
                    ->orderBy('title', 'ASC');
            } elseif ($orderField === 'publisher') {
                // Ordenar por editor primero, luego por título
                $query->orderByRaw('CASE WHEN publisher LIKE "%S.E" THEN 1 ELSE 0 END')
                    ->orderBy('publisher', 'ASC')
                    ->orderBy('title', 'ASC');
            } elseif ($orderField === 'publication_year') {
                // Ordenar por año de publicación primero, luego por título
                $query->orderByRaw('CASE WHEN publication_year LIKE "%S.F" THEN 1 ELSE 0 END')
                    ->orderBy('publication_year', 'DESC')
                    ->orderBy('title', 'ASC');
            } elseif ($orderField === 'id') {
                // Ordenar solo por id
                $query->orderBy('id', 'DESC');
            } else {
                // Ordenar por el campo predeterminado 'title'
                $query->orderBy('title', 'ASC');
            }

            // Obtener los resultados
            $books = $query->get();

            return response()->json($books);
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }

    /**
     * Buscar libros por una palabra clave en el título, autor o editorial.
     */
    public function getBooksByKeyword($keyword)
    {
        try {
            $keyword = trim($keyword);

            $books = $keyword === ''
                ? Book::orderBy('title', 'asc')->get()
                : Book::where(function ($query) use ($keyword) {
                    $query->where('isbn', $keyword)
                          ->orWhere('title', 'like', "%{$keyword}%")
                          ->orWhere('author', 'like', "%{$keyword}%")
                          ->orWhere('publisher', 'like', "%{$keyword}%")
                          ->orWhere('publication_year', $keyword)
                          ->orWhere('location', 'like', "%{$keyword}%");
                })
                ->orderBy('title', 'asc')
                ->get();

            return response()->json($books);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to search books.'], 500);
        }
    }

    /**
     * Obtener un libro por ISBN.
     */
    public function getBookByISBN($isbn)
    {
        try {
            if (!$isbn) {
                return response()->json(['error' => 'ISBN parameter is required.'], 400);
            }

            $book = Book::where('isbn', $isbn)->first();

            if (!$book) {
                return response()->json(['error' => 'Libro no encontrado.'], 404);
            }

            return response()->json($book);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve book.'], 500);
        }
    }

    public function getLocationBooks()
    {
        try {
            // Obtener todos los libros
            $books = Book::all();

            // Inicializar el array resultante para las estanterías
            $locationBooks = [];

            foreach ($books as $book) {
                // Dividir la ubicación en estantería y posición
                if (strpos($book->location, '-') === false) {
                    continue; // Ignorar si no hay un guion en la ubicación
                }

                [$shelf, $position] = explode('-', $book->location);
                $section = substr($position, 0, 1);
                $number = (int)substr($position, 1);

                // Si no hay estantería o posición, ignorar
                if (empty($shelf) || empty($section) || $number === 0) {
                    continue;
                }

                // Si la estantería no existe, la creamos
                if (!isset($locationBooks[$shelf])) {
                    $locationBooks[$shelf] = ['shelf' => $shelf, 'sections' => []];
                }

                // Si la sección no existe dentro de la estantería, la creamos
                if (!isset($locationBooks[$shelf]['sections'][$section])) {
                    $locationBooks[$shelf]['sections'][$section] = ['section' => $section, 'books' => []];
                }

                // Añadir el libro con el número de posición a la sección correspondiente
                $locationBooks[$shelf]['sections'][$section]['books'][] = [
                    'id' => $book->id,
                    'isbn' => $book->isbn,
                    'title' => $book->title,
                    'author' => $book->author,
                    'publisher' => $book->publisher,
                    'publication_year' => $book->publication_year,
                    'location' => $book->location,
                    'number' => $number, // Añadir el número de posición
                ];
            }

            // Ordenar estanterías
            ksort($locationBooks);

            foreach ($locationBooks as &$shelfGroup) {
                // Ordenar las secciones dentro de cada estantería
                ksort($shelfGroup['sections']);

                foreach ($shelfGroup['sections'] as &$sectionGroup) {
                    // Ordenar los libros dentro de cada sección por número de posición
                    usort($sectionGroup['books'], function ($a, $b) {
                        return $a['number'] - $b['number'];
                    });
                }

                // Reindexar secciones para mantener un array numérico
                $shelfGroup['sections'] = array_values($shelfGroup['sections']);
            }

            // Reindexar estanterías para mantener un array numérico
            $locationBooks = array_values($locationBooks);

            return response()->json($locationBooks);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve location books.'], 500);
        }
    }

    
}
