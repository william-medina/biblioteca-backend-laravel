<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // php artisan db:seed --class=UsersTableSeeder
    public function run(): void
    {
        $csvFile = storage_path('app/users.csv'); 

        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Leer y omitir encabezados si los hay
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                User::updateOrCreate(
                    ['email' => $data[1]], 
                    [
                        'password' => $data[2],
                    ]
                );
            }

            fclose($handle);
        }
    }
}
