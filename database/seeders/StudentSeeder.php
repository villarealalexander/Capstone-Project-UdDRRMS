<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\UploadedFile;
use Faker\Factory as Faker;

class StudentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            $student = Student::create([
                'name' => $faker->name,
                'batchyear' => $faker->numberBetween(2010, 2020),
                'type_of_student' => $faker->randomElement(['Post Graduate', 'Masteral']),
            ]);

            // Simulate uploading files for each student
            for ($j = 0; $j < 9; $j++) {
                $fileName = $faker->unique()->word . '.pdf';
                $uploadedFile = new UploadedFile([
                    'file' => $fileName,
                ]);
                $student->uploadedFiles()->save($uploadedFile);
            }
        }
    }
}
