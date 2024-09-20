<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    // php artisan db:seed --class=BooksTableSeeder
    public function run(): void
    {
         // Ruta al archivo CSV en storage
         $csvFile = storage_path('app/books.csv');

         // Abre el archivo en modo lectura
         if (($handle = fopen($csvFile, 'r')) !== false) {
             // Omitir la primera fila (encabezados)
             fgetcsv($handle);
 
             // Leer cada línea del archivo CSV
             while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                 Book::create([
                     'isbn' => $data[1],          
                     'title' => $data[2],         
                     'author' => $data[3],        
                     'publisher' => $data[4],     
                     'publication_year' => $data[5], 
                     'location' => $data[6],      
                 ]);
             }
 
             fclose($handle);
         }
    }
}
