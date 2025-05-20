<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $categories = [
            // Tech
            "Python Programming",
            "JavaScript Fundamentals",
            "React Native Development",
            "Machine Learning Basics",
            "Cloud Computing",
            "Blockchain Technology",
            "Web Development",
            "Mobile Development",
            "Data Science",
            "Artificial Intelligence",
            "Cybersecurity",
            "Ethical Hacking",
            "DevOps Practices",

            // Business
            "Social Media Marketing",
            "Financial Planning",
            "Agile Methodologies",
            "Business Analytics",
            "Project Management",
            "Finance & Accounting",

            // Creative
            "UI/UX Design",
            "Adobe Photoshop",
            "Cinematography",
            "Graphic Design",

            // Personal Development
            "Leadership",
            "Communication Skills",
            "Time Management",
            "Career Development",

            // Health & Fitness
            "Yoga",
            "Nutrition",
            "Mental Health",
            "Personal Training",

        ];

        $name = fake()->unique()->randomElement($categories);

        return [
            'name' => $name,
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'updated_at' =>fake()->dateTimeBetween('-1 year', 'now')
        ];
    }
}
