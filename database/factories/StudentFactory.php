<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Student::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ];
        
        return [
            'name' => $this->faker->name,
            'batchyear' => $this->faker->numberBetween(2010, 2023),
            'type_of_student' => $this->faker->randomElement(['Regular', 'Exchange', 'Transfer']),
            'course' => $this->faker->randomElement(['Computer Science', 'Engineering', 'Business', 'Arts']),
            'major' => $this->faker->randomElement(['Mathematics', 'Physics', 'Economics', 'Literature']),
            'month_uploaded' => $this->faker->randomElement($months),
        ];
    }
}
